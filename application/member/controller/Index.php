<?php

namespace app\member\controller;
use app\member\model\MemberModel;
use app\member\validate\LoginValidate;
use think\Controller;

class Index extends Controller {

    public function index() {
        return $this -> error('非法访问','/');
    }

}