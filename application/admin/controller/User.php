<?php
namespace app\admin\controller;
use app\admin\model\UserModel;
use app\admin\model\UserType;
use think\Db;

class User extends Base
{
    /**
     * 用户列表
     */
    public function index()
    {
        $key = input('key');
        $map = [];
        if ($key && $key !== '') {
            $map['username'] = ['like', "%" . $key . "%"];
        }
        $Nowpage = input('get.page') ? input('get.page') : 1;
        $limits = config('list_rows');
        $count = Db::name('admin')->where($map)->count();
        $allpage = intval(ceil($count / $limits));
        $user = new UserModel();
        $lists = $user->getUserByWhere($map, $Nowpage, $limits);
        foreach ($lists as $k => $v) {
            $lists[$k]['last_login_time'] = date('Y-m-d H:i:s', $v['last_login_time']);
        }
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
     * 添加用户
     */
    public function userAdd()
    {
        if (request()->isAjax()) {
            $param = input('post.');
            $param['password'] = md5(md5($param['password']) . config('auth_key'));
            $user = new UserModel();
            $flag = $user->insertUser($param);
            $accdata = array(
                'uid' => $user['uid'],
                'group_id' => $param['groupid']
            );
            $group_access = Db::name('auth_group_access')->insert($accdata);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $role = new UserType();
        $this->assign('role', $role->getRole());
        return $this->fetch();
    }
    /**
     * 编辑用户
     */
    public function userEdit()
    {
        $user = new UserModel();
        if (request()->isAjax()) {
            $param = input('post.');
            if (empty($param['password'])) {
                unset($param['password']);
            } else {
                $param['password'] = md5(md5($param['password']) . config('auth_key'));
            }
            $flag = $user->editUser($param);
            $group_access = Db::name('auth_group_access')->where('uid', $user['id'])->update(['group_id' => $param['groupid']]);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $role = new UserType();
        $this->assign([
            'user' => $user->getOneUser(),
            'role' => $role->getRole()
        ]);
        return $this->fetch();
    }
    /**
     * 删除用户
     */
    public function UserDel()
    {
        $id = input('param.id');
        $role = new UserModel();
        $flag = $role->delUser($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    public function user_status()
    {
        $id = input('param.id');
        $status = Db::name('admin')->where('id', $id)->value('status');
        if($status==1)
        {
            $flag = Db::name('admin')->where('id',$id)->setField(['status'=>0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        }
        else
        {
            $flag = Db::name('admin')->where('id',$id)->setField(['status'=>1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }
}