<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use app\admin\model\User as UserModel;
use lib\Validata;
class Hmanage extends Basic
{
	
	
    public function index(Request $request)
    {	
    	$search_field = $request->param('keyword');
		if($search_field!=''){
			$sql = " realname like '%".$search_field."%' and type=2 ";
		}else{
			$sql = ' type=2 ';
		}
		$list = UserModel::getList($sql);
		// 获取分页显示
	
		$page = $list->render();
		// 模板变量赋值
		$this->assign('list', $list);
		$this->assign('page', $page);
		$this->assign('keyword', $search_field);
		$this->assign('page_title','客户经理列表');
       	return $this->fetch();
    }

    public function add_man(){
		$validata = new Validata();
		//防重复提交
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
        $this->assign('page_title','添加客户经理');
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
	
	public function save_man(Request $request){
		
        $_param = $request->param();
		$_param['addtime'] = time();
		$_param['nickname'] = $_param['name'];
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_param['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_param['token']);
		//
		$res = UserModel::saveData($_param);
		if($res){
			$this->success('添加成功', 'Hmanage/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
    }
	
	
	
	public function  del_users(Request $request){
		$request_id = $request->param('uid'); 
		$res = Db::name('user')->delete($request_id);
		if($res){
			$this->success('删除成功', 'Hmanage/index');
		}else{
			$this->error('删除失败');  
		}
		
				
	}
	
	
	public function  edit_user(Request $request){
			
		$id = $request->param('uid');
		if($id >  0){
			$res = Db::name('user')->where('uid',$id)->find();
		}
		//防重复提交
		$validata = new Validata();
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('user_data', $res);
		$this->assign('page_title','客户经理编辑');
		return $this->fetch();
	}
	
	
	public function saveedit_user(Request $request){
		
		
		$_params = $request->param();
		//echo $_param['token'];
		$_params['nickname'] = $_params['name'];
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_params['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_params['token']);
		//
		$res = UserModel::editsaveData($_params);
		if($res){
			$this->success('客户经理修改成功', 'Hmanage/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
	}
	
	
    
}