<?php
namespace app\admin\validate;
use think\Validate;

class CateValidate extends Validate
{
    /**
     * @var array
     * 分类验证
     */
    protected $rule = [
        'title|分类名' => 'require|min:2'
    ];
}