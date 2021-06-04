<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use lib\Validata;
use app\admin\model\Company as CompanyModel;
class Hcompany extends Basic
{
	
	
    public function index(Request $request)
    {	
    	
		$res = Db::name('company')->find();
		//防重复提交
		$validata = new Validata();
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('company_data', $res);
		$this->assign('page_title','公司信息');
		return $this->fetch();
    }

    
	
	public function save_company(Request $request){
		
        $_param = $request->param();
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_param['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_param['token']);
		//
		$res = CompanyModel::saveData($_param);
		if($res){
			$this->success('修改成功', 'Hcompany/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
    }
	
	
	
	
	
	
    
}