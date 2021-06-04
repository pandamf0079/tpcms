<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use lib\Captchas;
use lib\Datarules;
use think\Session;
class Login extends \think\Controller
{
    

	
	public function login(){
		$this->assign('page_title','登陆');
		return $this->fetch();
		
	}
	
	public function getcode(){
	
		//$Captcha= new Captchas();
		Captchas::getCode(5,100,38);
	}
	
	public function loginsubmit(Request $request){
		if($request->isPost()){
			$postData = $request->param();
			if(!Captchas::checkCode($postData['captcha'])){
				$this->error('验证码不正确!');
				exit();
			}else{
				$rule = new Datarules();
				$checkres = $rule->checkData(['name'=>$postData['username'],'password'=>$postData['password']]);
				if($checkres!=1){
					$this->error($checkres);
					exit();
				}else{
					$admin = Db::name('admin')->where(['username'=>$postData['username']])->find();
					if(empty($admin)) {
						$this->error('用户名或密码错误!');
						exit();
					}
					if($admin['status'] != 1) {
						$this->error('管理员状态错误,禁止登陆!');
						exit();
					}
					$tmp = $rule->data_set_password($postData['password'],$admin['salt']);
					if($admin['pwd'] !== $tmp ) {
						$this->error('用户名或密码错误!');
						exit();
					}
					Db::name('admin')->where(['id'=>$admin['id']])->update(['last_login_time'=>time()]);
					Session::set('sess_admin',$admin);
					//查询系统权限
					$rule_list = Db::name('auth_rule')->order('id asc')->select();
					$rules = [];
					foreach($rule_list as $key => $val){
						$rules[] = $val['name'];
					}
					Session::set('sess_rule',$rules);
					$this->success('登陆成功', '/Admin/index/index');
					exit();
				}
				
			}
			
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
	}
    
    
}