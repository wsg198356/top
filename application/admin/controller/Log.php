<?php
namespace app\admin\controller;
use app\admin\model\LogModel;
use com\IpLocation;
use think\Db;

class Log extends Base
{
    /**
     * 操作日志
     */
    public function oprate_log()
    {
        $key = input('key');
        $map = [];
        if ($key && $key !== '') {
            $map['admin_id'] = $key;
        }
        $arr = Db::name('admin')->column('id,username');
        $Nowpage = input('get.page') ? input('get.page') : 1;
        $limits = config('list_rows');
        $count = Db::name('log')->where($map)->count();
        $allpage = intval(ceil($count / $limits));
        $lists = Db::name('log')->where($map)->page($Nowpage, $limits)->order('add_time desc')->select();
        $Ip = new IpLocation("UTFWry.dat");
        foreach ($lists as $k => $v) {
            $lists[$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            $lists[$k]['ipaddr'] = $Ip->getlocation($lists[$k]['ip']);
        }
        $this->assign([
            'Nowpage' => $Nowpage,
            'allpage' => $allpage,
            'count' => $count,
            'search_user' => $arr,
            'val' => $key
        ]);
        if (input('get.page')) {
            return json($lists);
        }
        return $this->fetch();
    }
    /**
     * 删除日志
     */
    public function del_log()
    {
        $id = input('param.id/d');
        $log = new LogModel();
        $flag = $log->delLog($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}