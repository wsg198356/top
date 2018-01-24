<?php
namespace app\admin\model;
use think\exception\PDOException;
use think\Model;

class ArticleModel extends Model
{
    protected $name = 'article';
    protected $autoWriteTimestamp = true;
    /**
     * 根据条件获取文章列表
     */
    public function getArticleByWhere($map, $Nowpage, $limits)
    {
        return $this->alias('a')->field('a.*,name')->join('think_article_cate c', 'a.cate_id=c.id')->where($map)
            ->page($Nowpage, $limits)->order('id desc')->select();
    }
    /**
     * 添加文章
     */
    public function insertArticle($param)
    {
        try {
            $res = $this->allowField(true)->save($param);
            if (false!==$res){
                return ['code' => 1, 'data' => '', 'msg' => '文章添加成功'];
            } else {
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 编辑文章
     */
    public function editArticle($param)
    {
        try {
            $res = $this->allowField(true)->save($param, ['id' => $param['id']]);
            if (false !== $res) {
                return ['code' => 1, 'data' => '', 'msg' => '编辑文章成功'];
            } else {
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * 根据文章id获取文章信息
     */
    public function getOneArticle($id)
    {
        return $this->where('id', $id)->find();
    }
    /**
     * 删除文章
     */
    public function delArlticle($id)
    {
        try {
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除文章成功'];
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}