<?php
namespace app\admin\model;

use think\exception\PDOException;
use think\Model;

class ArticleCateModel extends Model
{
    protected $name = 'article_cate';
    protected $autoWriteTimestamp = true;
    /**
     * 获取全部分类
     */
    public function getAllCate()
    {
        return $this->order('id desc')->select();
    }
    /**
     * 添加分类
     */
    public function insertCate($param)
    {
        try {
            $res = $this->allowField(true)->save($param);
            if (false !== $res) {
                return ['code' => 1, 'data' => '', 'msg' => '添加分类成功'];
            } else {
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 编辑分类
     */
    public function editCate($param)
    {
        try {
            $res = $this->allowField(true)->save($param, ['id' => $param['id']]);
            if (false !== $res) {
                return ['code' => 1, 'data' => '', 'msg' => '分类编辑成功'];
            } else {
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 根据ID获取分类
     */
    public function getOneCate($id)
    {
        return $this->where('id', $id)->find();
    }
    /**
     * 删除分类
     */
    public function delCate($id)
    {
        try {
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '分类删除成功'];
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}