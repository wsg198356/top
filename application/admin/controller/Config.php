<?php
namespace app\admin\controller;
use app\admin\model\ConfigModel;

class Config extends Base
{
    /**
     * @return mixed
     * [index  获取参数配置]
     * @autor [王生功][1064860088@qq.com]
     */
    public function index()
    {
        $configModel = new ConfigModel();
        $list = $configModel->getAllConfig();
        $config = [];
        foreach ($list as $k => $value) {
            $config[trim($v['name'])] = $v['value'];
        }
        $this->assign('config', $config);
        return $this->fetch();
    }
    //保存配置
    public function save($config)
    {
        $configModel = new ConfigModel();
        if ($config && is_array(($config))) {
            foreach ($config as $name => $value) {
                $map = array('name' => $name);
                $configModel->SaveConfig($map, $value);
            }
        }
        cache('db_config_data', null);
        $this->success('保存成功');
    }
}