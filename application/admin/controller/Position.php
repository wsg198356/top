<?php
namespace app\admin\controller;
use app\admin\model\AdPositionModel;
use app\admin\validate\AdPositionValidate;
use think\Db;

class Position extends Base
{
    /**
     * @return mixed
     * [index_position  获取广告位列表]
     * @autor [王生功][1064860088@qq.com]
     */
    public function index()
    {
        $key = input('key');
        $map = [];
        if ($key && $key !== '') {
            $map = [
                ['name', 'like', "%" . $key . "%"],
            ];
        }
        $ad = new AdPositionModel();
        $nowpage = input('get.page')?input('get.page'):1;
        $limits = 10;
        $count = Db::name('ad_position')->count();
        $allpage = intval(ceil($count / $limits));
        $list = $ad->getAll($map,$nowpage, $limits);
        $this->assign([
            'nowpage' => $nowpage,
            'allpage' => $allpage,
            'list' => $list,
        ]);
        return $this->fetch();
    }
    /**
     * 添加广告位
     */
    public function add_position()
    {
        if (request()->isAjax()) {
            $param = input('post.');
            //数据验证
            $v = new AdPositionValidate();
            $res = $v->check($param);
            if (!$res){
                return json(['code' => -1, 'data' => '', 'msg' => $v->getError()]);
            }else{
                $ad = new AdPositionModel();
                $flag = $ad->insertAdPoition($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
        }
        return $this->fetch();
    }
    /**
     * 编辑广告位
     */
    public function edit_position()
    {
        $ad = new AdPositionModel();
        if (request()->isAjax()) {
            $param = input('post.');
            //数据验证
            $v = new AdPositionValidate();
            $res = $v->check($param);
            if (!$res) {
                return json(['code' => -1, 'data' => '', 'msg' => $v->getError()]);
            } else {
                $flag = $ad->editAdPosition($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
        }
        $id = input('param.id');
        $this->assign('ad', $ad->getOne($id));
        return $this->fetch();
    }
    /**
     * 删除广告位
     */
    public function del_position()
    {
        $id = input('param.id');
        $ad = new AdPositionModel();
        $flag = $ad->delAdPostition($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
    /**
     * 编辑广告位状态
     */
    public function position_status()
    {
        $id = input('param.id');
        $status = Db::name('ad_position')->where(array('id' => $id))->value('status');
        if ($status == 1) {
            $flag = Db::name('ad_position')->where(array('id' => $id))->setField(['status' => 0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        } else {
            $flag = Db::name('ad_position')->where(array('id' => $id))->setField(['status' => 1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }
}