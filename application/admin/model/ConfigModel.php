<?php
namespace app\admin\model;
use think\exception\PDOException;
use think\Model;

class ConfigModel extends Model
{
    protected $name = 'config';
    /**
     * [getAllConfig  获取网站配置]
     * @autor [王生功][1064860088@qq.com]
     */
    public function getAllConfig()
    {
        return $this->select();
    }
    //保存网站信息
    public function SaveConfig($map, $value)
    {
        try {
            $result = $this->allowField(true)->where($map)->setField('value', $value);
            if (true === $result) {
                return ['code' => 1, 'data' => '', 'msg' => '保存成功'];
            } else {
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $exception) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

}
