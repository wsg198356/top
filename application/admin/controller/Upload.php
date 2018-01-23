<?php
namespace app\admin\controller;
use think\Env;

class Upload extends Base
{
    /**
     * 图片上传
     */
    public function upload()
    {
        $file = request()->file('file');
        $info = $file->move(Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'uploads/images');
        if ($info) {
            echo $info->getSaveName();
        } else {
            echo $file->getError();
        }
    }

    /**
     * 会员头像上传
     */
    public function uploadFace()
    {
        $file = request()->file('file');
        $info = $file->move(Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . '/uploads/face');
        if ($info) {
            echo $info->getSaveName();
        } else {
            echo $info->getError();
        }
    }
}