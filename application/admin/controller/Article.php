<?php
namespace app\admin\controller;
use app\admin\model\ArticleCateModel;
use app\admin\model\ArticleModel;
use think\Db;

class Article extends Base
{
    /**
     * 文章列表
     */
    public function index()
    {
        $key = input('key');
        $map = [];
        if ($key && $key !== '') {
            $map['title'] = ['like', "%" . $key . "%"];
        }
        $Nowpage = input('get.page') ? input('get.page') : 1;
        $limits = config('list_rows');
        $count = Db::name('article')->where($map)->count();
        $allpage = intval(ceil($count / $limits));
        $article = new  ArticleModel();
        $lists = $article->getArticleByWhere($map, $Nowpage, $limits);
        $this->assign([
            'Nowpage' => $Nowpage,
            'allpage' => $allpage,
            'count' => $count,
            'val' => $key
        ]);
        if (input('get.page')) {
            return json($lists);
        }
        return $this->fetch();
    }
    /**
     * 添加文章
     */
    public function add_article()
    {
        if (request()->isAjax()) {
            $param = input('post.');
            $article = new ArticleModel();
            $flag = $article->insertArticle($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $cate = new ArticleCateModel();
        $this->assign('cate', $cate->getAllCate());
        return $this->fetch();
    }
    /**
     * 编辑文章
     */
    public function edit_article()
    {
        $article = new ArticleModel();
        if (request()->isAjax()) {
            $param = input('post.');
            $flag = $article->editArticle($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $cate = new ArticleCateModel();
        $this->assign([
            'cate' => $cate->getAllCate(),
            'article' => $article->getOneArticle($id)
        ]);
        return $this->fetch();
    }
    /**
     * 删除文章
     */
    public function del_article()
    {
        $id = input('param.id');
        $cate = new ArticleModel();
        $falg = $cate->delArlticle($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
    /**
     * 文章状态更改
     */
    public function article_status()
    {
        $id = input('param.id');
        $status = Db::name('article')->where(array('id' => $id))->setField(['status'=>0]);
        if ($status == 1) {
            $flag = Db::name('article')->where(array('id' => $id))->setField(['status' => 1]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        } else {
            $flag = Db::name('article')->where(array('id' => $id))->setField(['status' => 0]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }
}