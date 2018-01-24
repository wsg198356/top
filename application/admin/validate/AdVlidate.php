<?php

namespace app\admin\validate;

use think\Validate;

class AdVlidate extends Validate
{
    protected $rule = [
        'title|标题'   => 'require|min:3',
        'orderby|排序' => 'require',
        'ad_position_id|广告位' => 'require',
        'link_url|链接地址' => 'require|url',
        'images|图片' => 'require',
        'start_date|开始日期' => 'require',
        'end_date|结束日期' => 'require'
    ];
}