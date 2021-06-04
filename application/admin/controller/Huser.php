<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use app\admin\model\User as UserModel;
use lib\Validata;
class Huser extends Basic
{
	
	
    public function index(Request $request)
    {	
    	$search_field = $request->param('keyword');
		if($search_field!=''){
			$sql = " realname like '%".$search_field."%' and type=1 ";
		}else{
			$sql = ' type=1 ';
		}
		$list = UserModel::getList($sql);
		// 获取分页显示
	
		$page = $list->render();
		// 模板变量赋值
		$this->assign('list', $list);
		$this->assign('page', $page);
		$this->assign('keyword', $search_field);
		$this->assign('page_title','客户列表');
       	return $this->fetch();
    }

    
	
	
	public function  edit_user(Request $request){
			
		$id = $request->param('uid');
		if($id >  0){
			$res = Db::name('user')->where('uid',$id)->find();
			if($res['manage_id'] > 0 ){
				$manager = Db::name('user')->where('uid',$res['manage_id'])->find();
				$this->assign('manager_name',$manager['realname'].'('.$manager['nickname'].')');
			}else{
				$this->assign('manager_name','--');
			}
		}
		//防重复提交
		$validata = new Validata();
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('user_data', $res);
		$this->assign('page_title','用户编辑');
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
		unset($_params['ss_phone']);
		unset($_params['ss-select']);
		//
		$res = UserModel::editsaveData($_params);
		if($res){
			$this->success('用户修改成功', 'Huser/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
	}
	
	
	
	public function transfers(Request $request){
		$_params = $request->param();
		$res = Db::name('user')->where('uid', $_params['uid'])->update(['type'=>2]);
		if($res){
			echo json_encode(1);
			exit();
		}else{
			echo json_encode(0);
			exit();
		}
	}
	
	
	public function get_manager(Request $request){
		$_params = $request->param();
		$r = Db::name('user')->where(['type'=>2,'phone'=>$_params['phone']])->select();
		if($r){
			echo json_encode($r);
			exit();
		}
		
	}
	
	
	//清除更新 redis缓存
	public function updatecache(){
		$redis = new \Redis();
		$ping = $redis->connect(Config("cache.host"),Config("cache.port"));
		$res = $redis->keys('userdata*');
		foreach ($res as $key => $value) {
			$redis->delete($value);
		}
		
		echo json_encode(1);
		exit();
		
	}
	
    
}