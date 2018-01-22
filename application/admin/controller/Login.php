<?php
namespace app\admin\controller;
use think\Controller;

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
}