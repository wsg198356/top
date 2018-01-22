<?php
namespace app\admin\model;
use think\exception\PDOException;
use think\Model;

class MemberGroupModel extends Model
{
    protected $name = 'member_group';
    protected $autoWriteTimestamp = true;
    /**
     * 根据条件获取用户组数据
     */
    public function getAll($map, $Nowpage, $limits)
    {
        return $this->where($map)->page($Nowpage, $limits)->order('id desc')->select();
    }
    /**
     * 根据条件获取用户数量
     */
    public function getAllCount($map)
    {
        return $this->where($map)->count();
    }
    /**
     * 获取所有会员组信息
     */
    public function getGroup()
    {
        return $this->select();
    }
    /**
     * 添加用户组
     */
    public function insertGroup($param)
    {
        try {
            $res = $this->validate('MemberGroupValidate')->save($param);
            if (false !== $res) {
                return ['code' => 1, 'data' => '', 'msg' => '用户组添加成功'];
            } else {
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 编辑用户组信息
     */
    public function editGroup($param)
    {
        try {
            $res = $this->validate('MemberGroupValidate')->save($param, ['id' => $param['id']]);
            if (false !== $res) {
                return ['code' => 1, 'data' => '', 'msg' => '用户组编辑成功'];
            } else {
                return ['code' => 0, 'data' => '', 'msg' => getError()];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 根据ID获取一条用户信息
     */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }
    /**
     * 删除用户组
     */
    public function delGroup($id)
    {
        try {
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '用户组删除成功'];
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}
