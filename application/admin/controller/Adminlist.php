<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use app\admin\model\Admin as AdminModel;
use lib\Validata;
class Adminlist extends Basic
{
	
	
    public function index(Request $request)
    {	
    	$where = '';
		$list = AdminModel::getList($where);
		// 获取分页显示
		$page = $list->render();
		// 模板变量赋值
		$this->assign('list', $list);
		$this->assign('page', $page);
		$this->assign('page_title','管理员列表');
       	return $this->fetch();
    }

    
	
	public function add(){
		
		$rolelist =  Db::name('auth_group')->select();
		$validata = new Validata();
		//防重复提交
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('rolelist',$rolelist);
		$this->assign('page_title','添加管理员 ');
       	return $this->fetch();
	}
	
	
	public function add_save(Request $request){
		$_param = $request->param();
		$_param['create_time'] = time();
		$group_id = $_param['group'];
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_param['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_param['token']);
		unset($_param['group']);
		
		//密码加密
		$salt = set_salts(20);
		$_param['pwd'] = set_passwords($_param['pwd'],$salt);
		$_param['salt'] = $salt;
		$res = AdminModel::saveData($_param);
		
		if($res){
			$res_group = Db::name('auth_group_access')->insert(['uid'=>$res,'group_id'=>$group_id]);
			if($res_group){
				// 提交事务
				Db::commit();
				$this->success('添加管理员成功', 'Adminlist/index');
				exit();
			}
			
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
	}
	
	public function edit(Request $request){
		$id = $request->param('id');
		if($id >  0){
			$res_admin = Db::name('admin')->where('id',$id)->find();
			$res_group = Db::name('auth_group_access')->where('uid',$id)->find();
		}
		$rolelist =  Db::name('auth_group')->select();
		$validata = new Validata();
		//防重复提交
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('res_admin',$res_admin);
		$this->assign('rolelist',$rolelist);
		$this->assign('select_id',$res_group['group_id']);
		$this->assign('page_title','编辑管理员 ');
       	return $this->fetch();
	}
	
	
	public function edit_save(Request $request){
		$_param = $request->param();
		$group_id = $_param['group'];
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_param['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_param['token']);
		unset($_param['group']);
		
		//密码加密
		if(strlen($_param['pwd']) > 5){
			$salt = set_salts(20);
			$_param['pwd'] = set_passwords($_param['pwd'],$salt);
			$_param['salt'] = $salt;
		}else{
			unset($_param['pwd']);
		}
		$res = AdminModel::editsaveData($_param);
		
		if($res){
			//echo $_param['id'];
			
			//print_r($res_group);

			Db::name('auth_group_access')->where('uid', $_param['id'])->update(['group_id'=>$group_id]);
			Db::commit();
			$this->success('编辑管理员成功', 'Adminlist/index');
			exit();
			
			
		
			
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
	}
	
    
}