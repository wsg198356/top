<?php
namespace app\admin\validate;
use think\Validate;

class RoleValidate extends Validate
{
    protected $rule = [
        'title|角色名称'=>'unique:auth_auth_group'
    ];
}