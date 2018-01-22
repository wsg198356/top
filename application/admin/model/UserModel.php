<?php
namespace app\admin\model;
use think\Db;
use think\exception\PDOException;
use think\Model;

class UserModel extends Model
{
    protected $name = 'admin';
    //根据条件获取用户信息列表
    public function getUserByWhere($map, $Nowpage, $limits)
    {
        return $this->alias(a)->field('a.*,title')->join('think_auth_group b', 'a.group_id=b.id')->where($map)->page($Nowpage, $limits)->order('id desc')->select();
    }
    //根据条件获取所有用户数量
    public function getAllUsers($where)
    {
        return $this->where($where)->count();
    }
    //插入用户信息
    public function insertUser($param)
    {
        try {
            $result = $this->validate('UserValidate')->allowField(true)->save($param);
            if (false !== $result) {
                writelog(session('uid'), session('username'), '用户【' . $param['username'] . '】添加成功', 1);
                return ['code' => 1, 'data' => '', 'msg' => '用户添加成功'];
            } else {
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    //编辑用户信息
    public function editUser($param)
    {
        try {
            $result = $this->validate('UserValidate')->allowField(true)->save($param, ['id' => $param['id']]);
            if (false !== $result) {
                writelog(session('uid'), session('username'), '用户【' . $param['username'] . '】编辑成功', 1);
                return ['code' => 1, 'data' => '', 'msg' => '用户编辑成功'];
            } else {
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    //根据管理员的id获取角色信息
    public function getOneUser($id)
    {
        return $this->where('id', $id)->find();
    }
    //删除管理员
    public function delUser($id)
    {
        try {
            $this->where('id', $id)->delete();
            Db::name('auth_group_access')->where('uid', $id)->delete();
            writelog(session('uid'), session('username'), '用户【' . session('username') . '】管理员删除成功（ID=' . $id . ')', 1);
            return ['code' => 1, 'data' => '', 'msg' => '删除用户成功'];
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}