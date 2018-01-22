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
     * [insertMenu  添加菜单]
     * @autor [王生功][1064860088@qq.com]
     */
    public function insertMenu($param)
    {
        try{
            $result = $this->save($param);
            if(false === $result){
                writelog(session('uid'),session('username'),'用户【'.session('username').'】添加菜单失败',2);
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                writelog(session('uid'),session('username'),'用户【'.session('username').'】添加菜单成功',1);
                return ['code' => 1, 'data' => '', 'msg' => '添加菜单成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 编辑菜单
     */
    public function editMenu($param)
    {
        try {
            $res = $this->save($param, ['id' => $param['id']]);
            if (false !== $res) {
                writelog(session('uid'), session('username'), '用户【' . session('username') . '】编辑菜单成功', 1);
                return ['code' => 1, 'data' => '', 'msg' => '编辑菜单成功'];
            } else {
                writelog(session('uid'), session('username'), '用户【' . session('username') . '】编辑菜单失败', 2);
                return ['code' => 0, 'data' => '', 'msg' => '编辑菜单成功'];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 根据菜单获取一条信息
     */
    public function getOneMenu($id)
    {
        return $this->where('id', $id)->find();
    }
    /**
     * 删除菜单
     */
    public function delMenu($id)
    {
        try {
            $this->where('id', $id)->delete();
            writelog(session('uid'), session('username'), '用户【' . session('username') . '】删除菜单成功', 1);
            return json(['code' => 1, 'data' => '', 'msg' => '删除菜单成功']);
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}