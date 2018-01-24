<?php
namespace app\admin\validate;
use think\Validate;

class ArticleValidate extends Validate
{
    protected $rule = [
        'title|标题' => 'require|min:6|max:40',
        'cate_id|分类名' => 'require',
        'cotent|内容' => 'require|min:10'
    ];
}