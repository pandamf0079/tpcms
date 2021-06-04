<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use app\admin\model\Rule as RuleModel;
use lib\Validata;
class Rulelist extends Basic
{
	
	
    public function index(Request $request)
    {	
    	$data = Db::name('auth_rule')->order('id asc')->select();
		
		// 模板变量赋值
		$tree = $this->getTree($data);
		$this->assign('tree', $tree);
		
		$validata = new Validata();
		$token = $validata->set_token();
		$this->assign('token',$token);
		
		$this->assign('page_title','权限列表');
       	return $this->fetch();
    }

	public function addrule(){
		$validata = new Validata();
		//防重复提交
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('page_title','添加权限');
       	return $this->fetch();
	}
    
	public function save_rule(Request $request){
		$_param = $request->param();
		if(!isset($_param['pid'])){
			$_param['pid'] = 0;
		}
		$_param['type'] = 1;
		$_param['status'] = 1;
		$_param['name'] = ltrim($_param['name'],'/');
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_param['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_param['token']);
		$res = RuleModel::saveData($_param);
		if($res){
			$this->success('添加成功', 'Rulelist/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
	}
	
	
	public function  edit_rule(Request $request){
		
	
		$id = $request->param('id');
		if($id >  0){
			$res = Db::name('auth_rule')->where('id',$id)->find();
		}
		//防重复提交
		$validata = new Validata();
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('rule_data', $res);
		$this->assign('page_title','权限编辑');
		return $this->fetch();
	}
	
	
	public function save_editrule(Request $request){
		
		
		$_params = $request->param();
		if(!isset($_params['pid'])){
			$_params['pid'] = 0;
		}
		//echo $_param['token'];
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_params['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_params['token']);
		$_params['name'] = ltrim($_params['name'],'/');
		//
		$res = RuleModel::editsaveData($_params);
		if($res){
			$this->success('规则修改成功', 'Rulelist/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
	}
	
	
	public function  delete(Request $request){
		$request_id = $request->param('id'); 
		$res = Db::name('auth_rule')->delete($request_id);
		if($res){
			$this->success('删除成功', 'Rulelist/index');
		}else{
			$this->error('删除失败');  
		}
		
				
	}
	
	public function getTree($list)
    {
		
		$tree = [];
		foreach($list as $key =>$val){
			if($val['pid'] ==0){
				$tree[] = $val;	
			}else{
				foreach($tree as $k =>$v){
					if($val['pid']==$v['id']){
						$tree[$k]['child'][] = $val;
					}else{
						//
						if(isset($tree[$k]['child'])){
							foreach($tree[$k]['child'] as $kk => $vv){
								if($val['pid']==$vv['id']){
									$tree[$k]['child'][$kk]['child'][] = $val;
								}
							}
						}
						//
					}
				}
			}
		}
		return $tree;
    }

	
	
	public function usecurl($url,$params=false,$ispost=0){
		$httpInfo = array();
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
		curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
		
		if( $ispost )
		{
			curl_setopt( $ch , CURLOPT_POST , true );
			curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
			curl_setopt( $ch , CURLOPT_URL , $url );
		}
		else
		{
			if($params){
				curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
			}else{
				curl_setopt( $ch , CURLOPT_URL , $url);
			}
		}
		$response = curl_exec( $ch );
		if ($response === FALSE) {
			//echo "cURL Error: " . curl_error($ch);
			return false;
		}
		$httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
		$httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
		curl_close( $ch );
		return $response;
	}
	
	
    
}