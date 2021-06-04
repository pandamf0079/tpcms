<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use app\admin\model\Hetong as HetongModel;
use lib\Validata;
class Hhetong extends Basic
{
	
	
    public function index(Request $request)
    {	
    	$search_field = $request->param('keyword');
		if($search_field!=''){
			$sql = " name like '%".$search_field."%'";
		}else{
			$sql = '';
		}
		$list = HetongModel::getList($sql);
		// 获取分页显示
	
		$page = $list->render();
		// 模板变量赋值
		$this->assign('list', $list);
		$this->assign('page', $page);
		$this->assign('keyword', $search_field);
		$this->assign('page_title','合同列表');
       	return $this->fetch();
    }

    
	
	
	
	public function  edit(Request $request){
		
	
		$id = $request->param('id');
		if($id >  0){
			$res = Db::name('hetong a')
        		->join('user b','a.uid=b.uid')
				->join('borrow c','a.bid=c.id')
				->field('a.*,b.name,b.phone,c.name as borrowname')
				->where('a.id',$id)
				->find();
		}
		//防重复提交
		$validata = new Validata();
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('hetong_data', $res);
		$this->assign('page_title','查看合同');
		return $this->fetch();
	}
	
	public function saveedit_borrows(Request $request){
		
		
		$_params = $request->param();
		//echo $_param['token'];
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_params['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_params['token']);
		//
		$res = BorrowModel::editsaveData($_params);
		if($res){
			$this->success('修改成功', 'Hborrow/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
	}
	
	//清除更新 redis缓存
	public function updatecache(){
		$redis = new \Redis();
		$ping = $redis->connect(Config("cache.host"),Config("cache.port"));
		$res = $redis->keys('hetongdata*');
		foreach ($res as $key => $value) {
			$redis->delete($value);
		}
		
		echo json_encode(1);
		exit();
		
	}
	
	
    
}