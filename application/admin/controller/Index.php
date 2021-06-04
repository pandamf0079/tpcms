<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use lib\Captchas;
use lib\Datarules;
use think\Session;
class Index extends Basic
{
    public function index(Request $request)
    {	
    	$mysqlinfo =Db::name('admin')->query("select VERSION()");
		if(isset($mysqlinfo[0]['VERSION()'])){
			$this->assign('mysqlversion',$mysqlinfo[0]['VERSION()']);
		}
		//print_r($mysqlinfo);
		$this->assign('page_title','后台首页');
		$this->assign('phpversion',PHP_VERSION);
		
       	return $this->fetch();
    }

	public function logout(Request $request){
	
		//销毁session
		Session::set('sess_admin',NULL);
		//跳转页面
		$this->redirect('login/login');
	}
	
    
    
}