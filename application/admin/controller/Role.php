<?php
namespace app\admin\controller;
use app\admin\model\Node;
use app\admin\model\UserType;
use think\Db;

class Role extends Base
{
    /**
     * 角色列表
     */
    public function index()
    {
        $key = input('key');
        $map = [];
        if ($key && $key !== '') {
            $map['title'] = ['like', "%" . $key . "%"];
        }
        $user = new UserType();
        $Nowpage = input('get.page') ? input('get.page') : 1;
        $limits = config('list_rows');
        $count = $user->getAllRole($map);
        $allpage = intval(ceil($count / $limits));
        $lists = $user->getRoleByWhere($map, $Nowpage, $limits);
        $this->assign([
            'Nowpage' => $Nowpage,
            'allpage' => $allpage,
            'val' => $key
        ]);
        if (input('get.page')) {
            return json($lists);
        }
        return $this->fetch();
    }
    /**
     * 添加角色
     */
    public function roleAdd()
    {
        if (request()->isAjax()) {
            $param = input('post.');
            $role = new UserType();
            $flag = $role->insertRole($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }
    /**
     * 编辑角色
     */
    public function roleEdit()
    {
        $role = new UserType();
        if (request()->isAjax()) {
            $param = input('post.');
            $flag = $role->editRole($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $this->assign([
            'role' => $role->getOneRole($id)
        ]);
        return $this->fetch();
    }
    /**
     * 删除角色
     */
    public function roleDel()
    {
        $id = input('param.id');
        $role = new UserType();
        $flag = $role->delRole($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
    /**
     * 角色状态
     */
    public function role_status()
    {
        $id = input('param.id');
        $status = Db::name('suth_group')->where('id', $id)->value('status');
        if ($status == 1) {
            $flag = Db::name('auth_group')->where('id', $id)->setField(['status' => 0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        } else {
            $flag = Db::name('auth_group')->where('id', $id)->setField(['status' => 1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }
    /**
     * 分配权限
     */
    public function giveAccess()
    {
        $param = input('param.');
        $node = new Node();
        if ('get' == $param['type']) {
            $nodeStr = $node->getNodeInfo($param['id']);
            return json(['code' => 1, 'data' => $nodeStr, 'msg' => 'success']);
        }
        //分配权限
        if ('give' == $param['type']) {
            $doparam = [
                'id' => $param['id'],
                'rules' => $param['rule']
            ];
            $user = new UserType();
            $flag = $user->editAccess($doparam);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}