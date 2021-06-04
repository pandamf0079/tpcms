<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use lib\Validata;
use app\admin\model\Sys as SysModel;
class Sys extends Basic
{
	
	
    public function index(Request $request)
    {	
    	
		$res = Db::name('sys')->find();
		//防重复提交
		$validata = new Validata();
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('sys_data', $res);
		$this->assign('page_title','系统信息');
		return $this->fetch();
    }

    
	
	public function save_sys(Request $request){
		
        $_param = $request->param();
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_param['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_param['token']);
		//
		$res = SysModel::save_editData($_param);
		if($res){
			$this->success('修改成功', 'Sys/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
    }
	
	
	
	
	
	
    
}