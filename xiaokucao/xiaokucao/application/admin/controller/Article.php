<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24
 * Time: 23:55
 */
namespace app\admin\controller;

class Article extends Base{

    public function lists(){
        $articles=db('article')->order('date','desc')->paginate(15);
        $this->assign('articles',$articles);
        return $this->fetch();
    }

    public function add(){
        if (request()->isPost()){


            $data=input('post.');
            $data['cate_id']=request()->param('cate_id');
            $file=request()->file('thumb');
            if($file){
                $info=$file->move(ROOT_PATH.'public'.DS.'uploads');
                if($info){
                    $data['thumb']='/uploads/'.$info->getSaveName();
                }else{
                    $back['status']=0;
                    $back['msg']="上传遇到错误，请稍后再试";
                     $this->error('添加文章失败');
                }
            }


            $data['date']=time();
            $data['cate_name']=db('cates')->where('cate_id',$data['cate_id'])->find()['cate_name'];
            $res=db('article')->insert($data);


            $data_Id = db('article')->getLastInsID();
            db('article')->where('article_id',$data_Id)->update(['link'=>"http://www.xiaokucao.com/index/article/article_details/article_id/".$data_Id.".html"]);
            if ($res){
               $this->success('添加文章成功','lists');
            }else{
               $this->error('添加文章失败');
            }

        }else{
            $cates=db('cates')->select();
            $this->assign('cates',$cates);
            return $this->fetch();
        }
    }

    public function article_status(){
        $article_id=input('article_id');
        $status=input('status');

        $res=db('article')->where('article_id',$article_id)->update(['status'=>$status]);
        if ($res){
            $this->success('更新成功');

        }else{
            $this->error('更新失败');
        }
    }

    public function read(){
        $article_id=input('article_id');
        $article_info=db('article')->where('article_id',$article_id)->find();
        $this->assign('article',$article_info);
        return $this->fetch();
    }
}