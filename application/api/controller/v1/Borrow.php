<?php
namespace app\api\controller\v1;
use app\api\model\Borrow as iBorrowModel;
use think\Request;
use think\Db;
class Borrow extends \think\Controller
{
    
	
	
	public function lists(Request $request){
		$search_word = $request->param('keyword');
		$offset = $request->param('offset');
		if($search_word!=''){
			$msql = " name like '%".$search_word."%'";
		}else{
			$msql = '';
		}
	    $resData = iBorrowModel::getList($msql,$offset)->toArray();
		
     	$data = [  
        	'status' => '200',  
        	'message' => 'suc',  
        	'data' => $resData['data'],  
    	];  
     	echo  json_encode($data);
		exit();
		
	}
	
	
	public function getone(Request $request){
		
		$id = $request->param('id');
		if($id >  0){
			$res = Db::name('borrow')->where('id',$id)->find();
		}
		if($res['id'] > 0 ){
			$data = [  
        		'status' => '200',  
        		'message' => 'ok',  
        		'data' => $res,  
    		]; 
		}else{
			$data = [  
        		'status' => '500',  
        		'message' => 'nodata or busy', 
        		'data' => $res,  
    		];
		}
		 
     	echo  json_encode($data);
		exit();
		
	}
	
	public function saveyuyue(Request $request){
		$money = trim($request->param('money'));
		$uid = intval($request->param('uid'));
		$appoint_time = trim($request->param('time'));
		$remark = trim($request->param('text'));
		$bid = intval($request->param('bid'));
		
		//检查是否有预约
		$check = Db::name('borrow_tender')->where(['uid'=>$uid,'bid'=>$bid,'status'=>0])->find();
		if($check['id'] > 0 ){
			$codedata = [  
        		'status' => '201',  
        		'message' => 'exist_appoint', 
        		'data' => ''
    		];
     		echo  json_encode($codedata);
			exit;
		}
		
		$_arr = array('money'=>$money,'uid'=>$uid,'appoint_time'=>$appoint_time ,'bid'=>$bid,'remark'=>$remark,'addtime'=>time());
		
		Db::startTrans();
		try{
			Db::name('borrow_tender')->insert($_arr);
			// 提交事务
			Db::commit();
			$codedata = [  
        		'status' => '200',  
        		'message' => 'suc', 
        		'data' => '',  
    		];
     		echo  json_encode($codedata);
			exit;    
		} catch (\Exception $e) {
			// 回滚事务
			Db::rollback();
			$codedata = [  
        		'status' => '500',  
        		'message' => 'fail', 
        		'data' => '',  
    		];
     		echo  json_encode($codedata);
			exit; 
		}
		
	}
	
	
	
	
	public function yuyuelist(Request $request){
	
		$token = $request->param('token');
		if($token){
			$uinfo = Db::name('user')->where(['token'=>$token])->find();
		}
	    $list = Db::name('borrow_tender a')->join('borrow b','a.bid=b.id')->field("a.*,b.name,b.apr,b.months,b.mid")->order('a.id desc')->where("a.uid",$uinfo['uid'])->paginate(10)->toArray();
		
     	$codedata = [  
        	'status' => '200',  
        	'message' => 'suc',  
        	'data' => $list,  
    	];  
     	echo  json_encode($codedata);
		exit();
	}
	
}
