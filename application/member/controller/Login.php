<?php

namespace app\member\controller;

use app\member\model\MemberModel;
use app\member\validate\LoginValidate;
use app\member\validate\RegisterValidate;
use think\Controller;
use think\Db;

class Login extends Controller {

    /**
     * 网站用户注册首页
     */
    public function index() {
        $config = cache('db_config_data');
        $this -> assign([
            'site_name' => $config['web_site_title'],
            'keywords'  => $config['web_site_keyword'],
            'site_copy' => $config['web_site_copy'],
        ]);

        return $this -> fetch();
    }

    /**
     * 用户注册
     */
    public function login() {
        $param             = input('post.');
        $account           = input('param.account');
        $param['password'] = md5(md5(input('post.password')
            . config('auth_key')));
        $v                 = new LoginValidate();
        if (!$v -> check($param)) {
            return json([
                'code' => -1,
                'data' => '',
                'msg'  => $v -> getError(),
            ]);
        }
        //用户验证
        $validateAccount = Db ::name('member') -> where('account', $account) -> find();
        if (empty($validateAccount)) {
            return json(['code' => 2, 'url' => 'register', 'msg' => '用户不存在，请注册']);
        }
        if (md5(md5(input('param.password') . config('auth_key'))) !== $validateAccount['password']) {
            return json(['code' => 0, 'url' => '', 'msg' => '密码错误！']);
        }
        $member                           = new MemberModel();
        $validateAccount['update_time']   = time();
        $validateAccount['last_login_ip'] = request() -> ip();
        $flag                             = $member -> login($validateAccount);
        //登陆session保存
        session('id', $validateAccount['id']);
        session('account', $validateAccount['account']);
        session('nickname', $validateAccount['nickname']);
        session('head_img', $validateAccount['head_img']);
        session('last_login_ip', $validateAccount['last_login_ip']);
        return json([
            'code' => $flag['code'],
            'data' => $flag['data'],
            'msg'  => $flag['msg'],
        ]);
    }

    /**
     * [register  用户注册]
     * @autor [王生功][1064860088@qq.com]
     */
    public function register() {
        if (request() -> isAjax()) {
            $param                  = input('post.');
            $param['password']      = md5(md5(input('post.password')
                . config('auth_key')));
            $param['last_login_ip'] = request() -> ip();
            $v                      = new RegisterValidate();
            if (!$v -> check($param)) {
                return json([
                    'code' => -1,
                    'data' => '',
                    'msg'  => $v -> getError(),
                ]);
            }
            $member = new MemberModel();
            $flag   = $member -> register($param);

            return json([
                'code' => $flag['code'],
                'url'  => $flag['data'],
                'msg'  => $flag['msg'],
            ]);
        }
        $config = cache('db_config_data');
        $this -> assign([
            'site_name' => $config['web_site_title'],
            'keywords'  => $config['web_site_keyword'],
            'site_copy' => $config['web_site_copy'],
        ]);

        return $this -> fetch('register');
    }
}