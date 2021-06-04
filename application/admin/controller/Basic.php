<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use lib\Captchas;
use lib\Datarules;
use think\Session;
use think\auth\Auth;
class Basic extends \think\Controller
{
    public function _initialize(){
		if(!Session::get('sess_admin')){
	  		return $this->error('请先登陆',url('login/login'));
		}else{
			#检查权限
			$auth=new Auth();
			$request = Request::instance();
			$module=$request->module();
			$control=$request->controller();
			$action=$request->action();
			$rule_name = $module.'/'.$control.'/'.$action;
			
			$admin_info = Session::get('sess_admin');
			/*print_r($rule_name);
			echo "<br/>";
			print_r($admin_info['id']);
			exit();*/
			if(in_array($rule_name,Session::get('sess_rule'))){
				$auth_result=$auth->check($rule_name,$admin_info['id']);
				if(!$auth_result){
					$this->success('该模块没有权限', 'Index/index');
				}
			}
			$this->assign('data_admin', Session::get('sess_admin'));
		}
	}		
		
	
    
    
}