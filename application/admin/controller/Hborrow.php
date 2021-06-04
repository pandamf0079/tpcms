<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use app\admin\model\Borrow as BorrowModel;
use lib\Validata;
class Hborrow extends Basic
{
	
	
    public function index(Request $request)
    {	
    	$search_field = $request->param('keyword');
		if($search_field!=''){
			$sql = " name like '%".$search_field."%'";
		}else{
			$sql = '';
		}
		$list = BorrowModel::getList($sql);
		// 获取分页显示
	
		$page = $list->render();
		// 模板变量赋值
		$this->assign('list', $list);
		$this->assign('page', $page);
		$this->assign('keyword', $search_field);
		$this->assign('page_title','项目列表');
       	return $this->fetch();
    }

    public function add_borrow(){
		$validata = new Validata();
		//防重复提交
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
        $this->assign('page_title','添加项目');
        return $this->fetch();
    }
	
	public function save_borrow(Request $request){
		
        $_param = $request->param();
		$_param['addtime'] = time();

		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_param['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_param['token']);
		//
		$res = BorrowModel::saveData($_param);
		if($res){
			$this->success('添加成功', 'Hborrow/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
    }
	
	
	
	public function  del_borrow(Request $request){
		$request_id = $request->param('id'); 
		$res = Db::name('borrow')->delete($request_id);
		if($res){
			$this->success('删除成功', 'Hborrow/index');
		}else{
			$this->error('删除失败');  
		}
		
				
	}
	
	
	public function  edit_borrows(Request $request){
		
	
		$id = $request->param('id');
		if($id >  0){
			$res = Db::name('borrow')->where('id',$id)->find();
		}
		//防重复提交
		$validata = new Validata();
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('borrow_data', $res);
		$this->assign('page_title','项目编辑');
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
	
	
    
}