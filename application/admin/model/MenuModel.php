<?php
namespace app\admin\model;
use think\exception\PDOException;
use think\Model;

class MenuModel extends Model
{
    protected $name = 'auth_rule';
    protected $autoWriteTimestamp = true;
    //获取全部菜单
    public function getAllMenu()
    {
        return $this->order('id desc')->select();
    }

    /**
     * @param $param
     * [insertMenu  添加菜单]
     * @autor [王生功][1064860088@qq.com]
     */
    public function insertMenu($param)
    {
        try {
            $result = $this->save($param);
            if (false !== $result) {
                writelog(session('uid'), session('username'), '用户【' . session('username') . '】添加菜单成功', 1);
                return ['code' => 1, 'data' => '', 'msg' => '添加菜单成功'];
            } else {
                writelog(session('uid'), session('username'), '用户【' . session('username') . '】添加菜单失败', 2);
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}