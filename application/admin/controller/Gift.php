<?php
namespace app\admin\controller;
use think\Db;
use think\Config;
use think\Request;

class Gift extends Basic
{
    public function index(Request $request)
    {	

      $search_field = $request->param('keyword');
      if($search_field!=''){
        $sql = " name like '%".$search_field."%'";
      }else{
        $sql = '';
      }
      $list = Db::name('product')->where($sql)->order('sid desc')->paginate(10);
      // 获取分页显示
      $page = $list->render();
      // 模板变量赋值
      $this->assign('list', $list);
      $this->assign('page', $page);
      $this->assign('keyword', $search_field);
    	$this->assign('page_title','商品列表');
      return $this->fetch();
    }


    public function comment(Request $request)
    {	
        $search_field = $request->param('keyword');
        if($search_field!=''){
          $sql = " comment like '%".$search_field."%'";
        }else{
          $sql = '';
        }
        $list = Db::name('comment')->where($sql)->order('id desc')->paginate(10);
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('keyword', $search_field);
        $this->assign('page_title','评论列表');
        return $this->fetch();
    }



    public function del(Request $request){
      $sid = $request->param('sid'); 
      $res = Db::name('product')->where('sid',$sid)->delete();
      if($res){
        $this->success('删除成功', 'Gift/index');
      }else{
        $this->error('删除失败');  
      }
      
    }


    public function commentdel(Request $request){
      $id = $request->param('id'); 
      $res = Db::name('comment')->where('id',$id)->delete();
      if($res){
        $this->success('删除成功', 'Gift/comment');
      }else{
        $this->error('删除失败');  
      }
      
    }

    public function addproduct(){
        $this->assign('page_title','添加产品');
        return $this->fetch();
    }


    public function editproduct(Request $request){
      $sid = $request->param('sid');
      if($sid >  0){
        $res = Db::name('product')->where('sid',$sid)->find();
      }

      $this->assign('prodata', $res);
      $this->assign('page_title','商品编辑');
      return $this->fetch();
    }

    public function saveproduct(Request $request){
        $_arr = $request->param();
        $_arr['addtime'] = time();
        if(isset($_arr['status'])){
          if($_arr['status']=='on'){
            $_arr['status'] = 1;
          }else{
            $_arr['status'] = 2;
          }
        }else{
            $_arr['status'] = 2;
        }
        
        if($_arr['name']!=''){
          $res = Db::name('product')->insert($_arr,true);
          $pid = Db::name('product')->getLastInsID(); 
        }
        
        if($pid  > 0){
          $this->success('添加成功', 'Gift/index');
        }else{
          $this->error('系统繁忙,请稍候再试!');  
        }
    }


    public function editsave(Request $request){
      $param = $request->param();
      $productdata = $param ;
      unset($productdata['sid']);
      if(isset($productdata['status'])){
          if($productdata['status']=='on'){
            $productdata['status'] = 1;
          }else{
            $productdata['status'] = 2;
          }
      }else{
          $productdata['status'] = 2;
      }
      $res = Db::name('product')->where('sid', $param['sid'])->update($productdata);
      if($res){
        $this->success('修改成功', 'Gift/index');
      }else{
        $this->error('系统繁忙,请稍候再试!');  
      }
    }



    public function delmul(Request $request){
      $d = $request->param('id');
      $res = Db::name('product')->where("sid in (".$d.")")->delete();
      if($res){
        echo json_encode(1);
        exit();
      }else{
        echo json_encode(2);
        exit(); 
      }
    }


}