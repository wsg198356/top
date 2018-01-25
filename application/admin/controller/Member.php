<?php
namespace app\admin\controller;
use app\admin\model\MemberGroupModel;
use app\admin\model\MemberModel;
use app\admin\validate\MemberGroupValidate;
use think\Db;

class Member extends Base
{
    /**
     * 会员组
     */
    public function group()
    {
        $key = input('key');
        $map = [];
        if ($key && $key != '') {
            $map = [
                ['group_name','like', "%" . $key . "%"]
            ];
        }
        $group = new MemberGroupModel();
        $Nowpage = input('get.page') ? input('get.page') : 1;
        $limits = config('list_rows');
        $count = $group->getAllCount($map);
        $allpage = intval(ceil($count / $limits));
        $lists = $group->getAll($map, $Nowpage, $limits);
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
     * 添加会员组
     */
    public function add_group()
    {
        if (request()->isAjax()) {
            $param = input('post.');
            $v = new MemberGroupValidate();
            if (!$v->check($param)) {
                return json(['code' => -1, 'data' => '', 'msg' => $v->getError()]);
            }
            $group = new MemberGroupModel();
            $flag = $group->insertGroup($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }
    /**
     * 编辑会员组
     */
    public function edit_group()
    {
        $group = new MemberGroupModel();
        if (request()->isPost()) {
            $param = input('post.');
            $v = new MemberGroupValidate();
            if (!$v->check($param)) {
                return json(['code' => -1, 'data' => '', 'msg' => $v->getError()]);
            }
            $flag = $group->editGroup($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $this->assign('group', $group->getOne($id));
        return $this->fetch();
    }
    /**
     * 删除会员组
     */
    public function del_group()
    {
        $id = input('param.id');
        $group = new MemberGroupModel();
        $flag = $group->delGroup($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
    /**
     * 会员状态
     */
    public function group_status()
    {
        $id = input('param.id');
        $status = Db::name('member_group')->where(array('id' => $id))->value('status');
        if ($status == 1) {
            $flag = Db::name('member_group')->where(array('id' => $id))->setField(['status' => 0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        } else {
            $flag = Db::name('member_group')->where(array('id' => $id))->setField(['status' => 1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }
    /**
     * 会员列表
     */
    public function index()
    {
        $key = input('key');
        $map['closed'] = 0;
        if ($key && $key != '') {
            $map =[
                ['account|nickname|mobile','like', "%" . $key . "%"]
            ];
        }
        $member = new MemberModel();
        $Nowpage = input('get.page') ? input('get.page') : 1;
        $limits = config('list_rows');
        $count = $member->getAllCount($map);
        $allpage = intval(ceil($count / $limits));
        $lists = $member->getMemberByWhere($map, $Nowpage, $limits);
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
     * 添加会员
     */
    public function add_member()
    {
        if (request()->isAjax()) {
            $param = input('post.');
            $param['password'] = md5(md5($param['password']) . config('auth_key'));
            $member = new MemberModel();
            $flag = $member->insertMember($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $group = new MemberGroupModel();
        $this->assign('group', $group->getGroup());
        return $this->fetch();
    }
    /**
     * 编辑会员
     */
    public function edit_member()
    {
        $member = new MemberModel();
        if (request()->isAjax()) {
            $param = input('post.');
            if (empty($param['password'])) {
                unset($param['password']);
            } else {
                $param['password'] = md5(md5($param['password']) . config('auth_key'));
            }
            $flag = $member->editMember($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $group = new MemberGroupModel();
        $this->assign([
            'member' => $member->getOneMember($id),
            'group' => $group->getGroup()
        ]);
        return $this->fetch();
    }
    /**
     * 删除会员
     */
    public function del_member()
    {
        $id = input('param.id');
        $member = new MemberModel();
        $flag = $member->delMember($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
    /**
     * 会员状态
     */
    public function member_status()
    {
        $id = input('param.id');
        $status = Db::name('member')->where('id', $id)->value('status');
        if ($status == 1) {
            $flag = Db::name('member')->where('id', $id)->setField(['status' => 0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        } else {
            $flag = Db::name('member')->where('id', $id)->setField(['status' => 1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }
}