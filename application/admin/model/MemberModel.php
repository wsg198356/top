<?php
namespace app\admin\model;
use think\Db;
use think\exception\PDOException;
use think\Model;

class MemberModel extends Model
{
    protected $name = 'member';
    protected $autoWriteTimestamp = true;
    /**
     * 根据搜索条件获取用户
     */
    public function getMemberByWhere($map, $Nowpage, $limits)
    {
        return $this->alias('m')->field('m.*,group_name')->join('think_member_group g', 'm.group_id=g.id')
            ->where($map)->page($Nowpage, $limits)->order('id desc')->select();
    }
    /**
     * 根据条件获取所有用户数量
     */
    public function getAllCount($map)
    {
        return $this->where($map)->count();
    }
    /**
     * 插入会员信息
     */
    public function insertMember($param)
    {
        try {
            $res = $this->validate('MemberValidate')->allowField(true)->save($param);
            if (false !== $res) {
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            } else {
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 编辑会员信息
     */
    public function editMember($param)
    {
        try {
            $res = $this->validate('MemberValidate')->allowField(true)->save($param, ['id' => $param['id']]);
            if (false !== $res) {
                return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
            } else {
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 删除管理员
     */
    public function delUser($id)
    {
        try {
            $this->where('id', $id)->delete();
            Db::name('auth_group_access')->where('uid', $id)->delete();
            return ['code' => 0, 'data' => '', 'msg' => '删除管理员成功'];
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 删除会员
     */
    public function delMember($id)
    {
        try {
            $map['closed'] = 1;
            $this->save($map, ['id' => $id]);
            return ['code' => 1, 'data' => '', 'msg' => '删除会员成功'];
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}
