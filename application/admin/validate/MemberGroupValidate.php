<?php
namespace app\admin\validate;
use think\Validate;

class MemberGroupValidate extends Validate
{
    protected $rule = [
        'group_name|会员组' => 'require|unique:member_group'
    ];
}