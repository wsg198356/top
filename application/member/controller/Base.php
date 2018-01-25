<?php

namespace app\member\controller;

use think\Controller;

class Base extends Controller {

    public function initialize() {
        if (!session('id') || !session('account')) {
            $this -> redirect('login/index');
        }
        if (session('id') != '') {
            $this -> error('你还没有登录');
        }
        $config = cache('db_config_data');
        $this -> assign(
            [
                'account'   => session('account'),
                'head_img'  => session('head_img'),
                'nickname'  => session('nickname'),
                'site_name' => $config['web_site_title'],
            ]
        );
        if (!$config) {
            $config = load_config();
            cache('db_config_data', $config);
        }
        config($config);
        if (config('web_site_close') == 0) {
            $this -> error('站点已经关闭，请稍后访问');
        }
        if (config('admin_allow_id')) {
            if (in_array(
                request() -> ip(), explode('#', config('admin_allow_id'))
            )
            ) {
                $this -> error('禁止访问');
            }
        }
    }
}