<?php
namespace app\admin\model;
use think\exception\PDOException;
use think\Model;

class AdPositionModel extends Model
{
    protected $name = 'ad_position';
    protected $autoWriteTimestamp = true;
    /**
     * 获取所有广告位置
     */
    public function getAll($map,$nowpage, $limits)
    {
        return $this->where($map)->page($nowpage, $limits)->order('id asc')->select();
    }
    /**
     * 增加广告位置
     */
    public function insertAdPoition($param)
    {
        try {
            $res = $this->allowField(true)->save($param);
            if (false !== $res) {
                return ['code' => 1, 'data' => '', 'msg' => '添加广告位成功'];
            } else {
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 编辑广告位信息
     */
    public function editAdPosition($param)
    {
        try {
            $res = $this->allowField(true)->save($param, ['id' => $param['id']]);
            if (false !== $res) {
                return ['code' => 1, 'data' => '', 'msg' => '编辑广告位成功'];
            } else {
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 根据ID获取一条信息
     */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }
    /**
     * 获取全部广告位
     */
    public function getAllPosition()
    {
        return $this->order('id asc')->select();
    }
    /**
     * 删除广告位
     */
    public function delAdPostition($id)
    {
        try {
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除广告位成功'];
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}