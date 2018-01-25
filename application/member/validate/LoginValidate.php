<?php

namespace app\member\validate;

use think\Validate;

class LoginValidate extends Validate {

    protected $rule
        = [
            'account|用户名' => 'require',
            'password|密码' => 'require',
            'captcha|验证码' => 'require|captcha',
        ];

    protected $message
        = [
            'password.require' => '用户密码错误',
        ];
}