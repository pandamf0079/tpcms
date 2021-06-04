<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use app\admin\model\News as NewsModel;
use lib\Validata;
class Hnews extends Basic
{
	
	
    public function index(Request $request)
    {	
    	$search_field = $request->param('keyword');
		if($search_field!=''){
			$sql = " title like '%".$search_field."%'";
		}else{
			$sql = '';
		}
		$list = NewsModel::getList($sql);
		// 获取分页显示
	
		$page = $list->render();
		// 模板变量赋值
		$this->assign('list', $list);
		$this->assign('page', $page);
		$this->assign('keyword', $search_field);
		$this->assign('page_title','新闻列表');
       	return $this->fetch();
    }

    public function add_news(){
		$validata = new Validata();
		//防重复提交
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
        $this->assign('page_title','添加新闻');
        return $this->fetch();
    }
	
	
	public function upload(Request $request){
		//print_r($_FILES);
		//exit();
		$file = request()->file('file');
		//print_r($file);
		$info = $file->validate(['size'=>10000000,'ext'=>'jpeg,jpg,png,gif'])->move('upfiles/');   //上传到服务器
		
		if($info) {
			$params['temp_img']=$info->getSaveName();
			 //echo $params['temp_img'];
			$img_place = str_replace('/','\\','/upfiles/'.$params['temp_img']);
			$redata = array(
				"code"=>0,
				"msg"=>"suc",
				"data"=>array('src'=>$img_place,'title'=>'')	
			);
			echo json_encode($redata);
			exit();
		} else {
			echo json_encode('fail');
			exit();
			//this->error($file->getError(),'addTemp');
		}

	}
	
	public function save_news(Request $request){
		
        $_param = $request->param();
		$_param['addtime'] = time();
		
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_param['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_param['token']);
		unset($_param['file']);
		//
		$res = NewsModel::saveData($_param);
		if($res){
			$this->success('添加成功', 'Hnews/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
    }
	
	
	
	public function  del_news(Request $request){
		$request_id = $request->param('id'); 
		$res = Db::name('news')->delete($request_id);
		if($res){
			$this->success('删除成功', 'Hnews/index');
		}else{
			$this->error('删除失败');  
		}
		
				
	}
	
	
	public function  edit_news(Request $request){
		
	
		$id = $request->param('id');
		if($id >  0){
			$res = Db::name('news')->where('id',$id)->find();
		}
		//防重复提交
		$validata = new Validata();
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('news_data', $res);
		$this->assign('page_title','文章编辑');
		return $this->fetch();
	}
	
	public function saveedit_news(Request $request){
		
		
		$_params = $request->param();
		//echo $_param['token'];
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_params['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_params['token']);
		unset($_params['file']);
		//
		$res = NewsModel::editsaveData($_params);
		if($res){
			$this->success('文章修改成功', 'Hnews/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
	}
	
	
    
}