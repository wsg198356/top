<?php
namespace app\admin\model;
use think\Db;
use think\Model;

class Node extends Model
{
    protected $name = 'auth_rule';

    /**
     * @autor [王生功][1064860088@qq.com]
     */
    public function getNodeInfo($id)
    {
        $result = $this->field('id,title,pid')->select();
        $str ='';
        $role = new UserType();
        $rule = $role->getRuleById($id);
        if (!empty($rule)) {
            $rule = explode(',', $rule);
        }
        foreach ($result as $key => $vo) {
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['title'].'"';

            if(!empty($rule) && in_array($vo['id'], $rule)){
                $str .= ' ,"checked":1';
            }

            $str .= '},';
        }
        return "[" . substr($str, 0, -1) . "]";
    }

    public function getMenu($nodeStr = '')
    {
        $where = empty($nodeStr) ? 'status=1' : 'status=1 and id in(' . $nodeStr . ')';
        $result = Db::name('auth_rule')->where($where)->order('sort')->select();
        $menu = prepareMenu($result);
        return $menu;
    }
}