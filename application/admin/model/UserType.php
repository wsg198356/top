<?php
namespace app\admin\model;
use think\Db;
use think\exception\PDOException;
use think\Model;

class UserType extends Model
{
    protected $name = 'auth_group';
    protected $autoWriteTimetamp = true;

    /**
     * [getRoleByWhere   根据条件获取角色名称]
     * @author [王生功][1064860088@qq.com]
     */
    public function getRoleByWhere($map, $Nowpage, $limits)
    {
        return $this->where($map)->page($Nowpage, $limits)->order('id desc')->select();
    }

    /**
     * [getAllRole   获取角色数量]
     * @author [王生功][1064860088@qq.com]
     */
    public function getAllRole($where)
    {
        return $this->where($where)->count();
    }

    /**
     * [insertRole  插入角色名称]
     * @autor [王生功][1064860088@qq.com]
     */
    public function insertRole($param)
    {
        try {
            $result = $this->validate('RoleValidate')->save($param);
            if (false !== $result) {
                return ['code' => '1', 'data' => '', 'msg' => '添加角色成功'];
            } else {
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * @param $param
     * @return array
     * [editRole  编辑角色]
     * @autor [王生功][1064860088@qq.com]
     */
    public function editRole($param)
    {
        try {
            $result = $this->validate('RoleValidate')->save($param, ['id' => $param['id']]);
            if (false !== $result) {
                return ['code' => 1, 'data' => '', 'msg' => '编辑角色成功'];
            } else {
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * [getOneRole  获取角色信息]
     * @autor [王生功][1064860088@qq.com]
     */
    public function getOneRole($id)
    {
        return $this->where(['id' => $id])->find();
    }

    /**
     * @param $id
     * @return array
     * [delRole  删除角色]
     * @autor [王生功][1064860088@qq.com]
     */
    public function delRole($id)
    {
        try {
            $this->where('id', $id)->delete();
            return ['code'=>1,'data'=>'','msg'=>'删除角色成功'];
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * [getRole  获取所有角色信息]
     * @autor [王生功][1064860088@qq.com]
     */
    public function getRole()
    {
        return $this->where('id', '<>', 1)->select();
    }

    /**
     * [getRuleById  根据ip获取权限]
     * @autor [王生功][1064860088@qq.com]
     */
    public function getRuleById($id)
    {
        $result = $this->field('rules')->where('id', $id)->find();
        return $result['rules'];
    }

    /**
     * [editAccess  编辑权限]
     * @autor [王生功][1064860088@qq.com]
     */
    public function editAccess($param)
    {
        try {
            $this->save($param, ['id' => $param['id']]);
            return ['code' => 1, 'data' => '', 'msg' => '分配权限成功'];
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * [getRoleInfo  名称]
     * @autor [王生功][1064860088@qq.com]
     */
    public function getRoleInfo($id)
    {
        $result = Db::name('auth_group')->where('id', $id)->find();
        if (empty($result['rules'])) {
            $where = '';
        } else {
            $where = 'id in(' . $result['rules'] . ')';
        }
        $res = Db::name('auth_rule')->field('name')->where($where)->select();
        foreach ($res as $key => $vo) {
            if ('#' != $vo['name']) {
                $result['name'][] = $vo['name'];
            }
        }
        return $result;
    }
}