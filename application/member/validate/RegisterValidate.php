<?php
/**
 * 用户注册验证
 */

namespace app\member\validate;

use think\Validate;

class RegisterValidate extends Validate {

    protected $rule
        = [
            'account|用户名'     => 'require|max:10|unique:member|alphaDash',
            'email|邮箱'        => 'email',
            'password|密码'     => 'require|min:6',
            'mobile|手机号码'     => 'mobile',
            'rePassword|重复密码' => 'require|confirm:password',
        ];
    protected $message
        = [
            'password.min'       => '用户密码小于六位！',
            'rePassword.confirm' => '密码不相同',
        ];
}