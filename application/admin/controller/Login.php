<?php
namespace app\admin\controller;
use app\admin\model\UserType;
use org\Verify;
use think\Controller;
use think\Db;

class Login extends Controller
{
    /**
     * 后台登陆
     */
    public function index()
    {
        $this->assign('verify_type', config('verify_type'));
        return $this->fetch('/login');
    }
    /**
     * 登陆操作
     */
    public function doLogin()
    {
        $username = input('param.username');
        $password = input('param.password');
        if (config('verify_type' == 1)) {
            $code = input('param.code');
        }
        $verify = new Verify();
        if (config('verify_type') == 1) {
            if (!$code) {
                return json(['code' => -4, 'url' => '', 'msg' => '请输入验证码']);
            }
            if (!$verify->check($code)) {
                return json(['code' => -4, 'url' => '', 'msg' => '验证码错误']);
            }
        }
        $hasUser = Db::name('admin')->where('username', $username)->find();
        if (empty($hasUser)) {
            return json(['code' => -1, 'url' => '', 'msg' => '管理员不存在']);
        }
        if (md5(md5($password) . config('auth_key')) != $hasUser['password']) {
            writelog($hasUser['id'], $username, '用户【' . $username . '】登陆失败：密码错误', 2);
            return json(['code' => -2, 'url' => '', 'msg' => '账号或密码错误']);
        }
        if (1 != $hasUser['status']) {
            writelog($hasUser['id'], $username, '用户【' . $username . '】登陆失败：该账号被禁用', 2);
            return json(['code' => -6, 'url' => '', 'msg' => '账号被禁用']);
        }
        //获取用户角色信息
        $user = new UserType();
        $info = $user->getRoleInfo($hasUser['groupid']);
        session('uid', $hasUser['id']);
        session('username', $hasUser['username']);
        session('portrait', $hasUser['portrait']);
        session('rolename', $info['title']);
        session('rule', $info['rules']);
        session(['name', $info['name']]);
        //更新管理员状态
        $param = [
            'loginnum' => $hasUser['loginnum'] + 1,
            'last_login_ip' => request()->ip(),
            'last_login_time' => time(),
            'token' => md5($hasUser['username'] . $hasUser['password']),
        ];
    }
}