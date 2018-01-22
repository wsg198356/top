<?php
namespace app\admin\controller;
use app\admin\model\ArticleCateModel;
use think\Db;

class Cate extends Base
{
    /**
     * 分类管理
     */
    public function index_cate()
    {
        $cate = new ArticleCateModel();
        $list = $cate->getAllCate();
        $this->assign('list', $list);
        return $this->fetch();
    }
    /**
     *添加分类
     */
    public function add_cate()
    {
        if (request()->isAjax()) {
            $param = input('post.');
            $cate = new ArticleCateModel();
            $flag = $cate->insertCate($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }
    /**
     * 编辑分类
     */
    public function edit_cate()
    {
        $cate = new ArticleCateModel();
        if (request()->isAjax()) {
            $param = input('post.');
            $flag = $cate->editCate($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $this->assign('cate', $cate->getOneCate($id));
        return $this->fetch();
    }
    /**
     * 删除分类
     */
    public function del_cate()
    {
        $id = input('param.id');
        $cate = new ArticleCateModel();
        $flag = $cate->delCate($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
    /**
     * 分类状态设置
     */
    public function cate_status()
    {
        $id = input('param.id');
        $status = Db::name('article_cate')->where(array('id' => $id))->value('status');
        if ($status == 1) {
            $flag = Db::name('article_cate')->where(array('id' => $id))->setField(['status' => 0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        } else {
            $flag = Db::name('article_cate')->where(array('id' => $id))->setField(['status' => 1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }
}