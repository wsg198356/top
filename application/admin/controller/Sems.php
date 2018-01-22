<?php
namespace app\admin\controller;
class Sems extends Base
{
    /**
     * 发送短信
     */
    public function sms()
    {
        if (request()->isAjax()) {
            $param = input('param.');
            $mobile = $param['mobile'];
            $tplCode = $param['tplCode'];
            $tplParam['code'] = $param['tplCode'];
            $msgStatus = sendMsg($mobile, $tplCode, $tplParam);
            return json(['code' => $msgStatus['code'], 'msg' => $msgStatus['Message']]);
        }
        return $this->fetch();
    }
    /**
     * 生成二维码
     */
    public function qrcode()
    {
        return $this->fetch();
    }
}