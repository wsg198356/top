<?php
namespace app\admin\model;
use think\exception\PDOException;
use think\Model;

class AdModel extends Model
{
    protected $name = 'ad';
    /**
     * 根据条件获取广告
     */
    public function getAdAll($map, $Nowpage, $limits)
    {
        return $this->alias('a')->field('a.*,name')->join('think_ad_position p', 'a.ad_position_id=p.id')->where($map)->page($Nowpage, $limits)->order('orderby desc')->select();
    }
    /**
     * 增加广告
     */
    public function insertAd($param)
    {
        try {
            $res = $this->validate('AdValidate')->allowField(true)->save($param);
            if (false !== $res) {
                return ['code' => 1, 'data' => '', 'msg' => '添加广告成功'];
            } else {
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 编辑广告
     */
    public function editAd($param)
    {
        try {
            $res = $this->validate('AdValidate')->allowField(true)->save($param, ['id' => $param['id']]);
            if (false !== $res) {
                return ['code' => 1, 'data' => '', 'msg' => '编辑广告成功'];
            } else {
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 根据ID获取一条广告
     */
    public function getOneAd($id)
    {
        return $this->where('id', $id)->find();
    }
    /**
     * 删除广告
     */
    public function delAd($id)
    {
        try {
            $map['closed'] = 1;
            $this->save($map, ['id' => $id]);
            return ['code' => 1, 'data' => '', 'msg' => '删除广告成功'];
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}