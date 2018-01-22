<?php
namespace app\admin\controller;
use app\admin\model\AdModel;
use app\admin\model\AdPositionModel;
use think\Db;

class Ad extends Base
{
    /*
     * 广告列表
     */
    public function index()
    {
        $key = input('key');
        $map = [];
        $map['closed'] = 0;
        if ($key && $key !== '') {
            $map['title'] = ['like', '%' . $key . '%'];
        }
        $Nowpage = input('get.page') ? input('get.page') : 1;
        $limits = config('list_rows');
        $count = Db::name('ad')->where($map)->count();
        $allpage = intval(ceil($count / $limits));
        $ad = new AdModel();
        $lists = $ad->getAdAll($map, $Nowpage, $limits);
        $this->assign([
            'Nowpage' => $Nowpage,
            'allpage' =>$allpage,
            'val'=>$key
        ]);
        if (input('get.page')) {
            return json($lists);
        }
        return $this->fetch();
    }
    /**
     * 添加广告
     */
    public function add_ad()
    {
        if (request()->isAjax()) {
            $param = input('post.');
            $param['closed'] = 0;
            $ad = new AdModel();
            $flag = $ad->insertAd($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $position = new AdPositionModel();
        $this->assign('position', $position->getAllPosition());
        return $this->fetch();
    }
    /**
     * 编辑广告
     */
    public function edit_ad()
    {
        $ad = new AdModel();
        if (request()->isPost()) {
            $param = input('post.');
            $flag = $ad->editAd($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $this->assign('ad', $ad->getOneAd($id));
        return $this->fetch();
    }
    /**
     * 删除广告
     */
    public function del_ad()
    {
        $id = input('param.id');
        $ad = new AdModel();
        $flag = $ad->delAd($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
    /**
     * 更改广告状态
     */
    public function ad_status()
    {
        $id = input('param.id');
        $status = Db::name('ad')->where(array('id' => $id))->value('status');//获取当前广告状态
        if ($status == 1) {
            $flag = Db::name('ad')->where(array('id' => $id))->setField(['status' => 0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        } else {
            $flag = Db::name('ad')->where(array('id' => $id))->setField(['status' => 1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }
}