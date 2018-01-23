<?php
namespace app\admin\controller;
use think\Db;
use think\facade\App;

class Index extends Base
{
    public function index()
    {
        return $this->fetch('/index');
    }
    /**
     * 管理后台首页
     */
    public function indexPage()
    {
        //今日新增会员
        $today = strtotime(date('Y-m-d 00:00:00'));
        $member = Db::name('member')->where('create_time','>=',$today)->count();
        $this->assign('member', $member);
        $info = array(
            'web_server' => $_SERVER['SERVER_SOFTWARE'],
            'onload' => ini_get('upload_max_filesize'),
            'think_v' => App::version(),
            'phpversion' => phpversion()
        );
        $this->assign('info', $info);
        return $this->fetch('index');
    }
    /**
     * 修改密码
     */
    public function editPwd()
    {
        if (request()->isAjax()) {
            $param = input('post.');
            $user = Db::name('admin')->where('id=' . session('uid'))->find();
            if (md5(md5($param['old_password']) . config('auth_key')) != $user['password']) {
                return json(['code' => -1, 'url' => '', 'msg' => '旧密码错误']);
            } else {
                $pwd['password'] = md5(md5($param['password']) . config('auth_key'));
                Db::name('admin')->where('id=' . $user['id'])->update($pwd);
                session(null);
                cache('db_config_data', null);//清除网站缓存配置
                return json(['code' => 1, 'url' => 'index/index', 'msg' => '密码修改成功']);
            }
        }
        return $this->fetch();
    }
    /**
     * 清除缓存
     */
    public function clear()
    {
        if (delete_dir_file(CACHE_PATH) && delete_dir_file(TEMP_PATH)) {
            return json(['code' => 1, 'msg' => '清除缓存成功']);
        } else {
            return json(['code' => 0, 'msg' => '清除缓存失败']);
        }
    }
}