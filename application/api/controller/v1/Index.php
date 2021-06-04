<?php
namespace app\api\controller\v1;
use app\api\model\Borrow as iBorrowModel;
use think\Request;
use think\Db;
use esign\Token;
use esign\AccountID;
use esign\UploadUrl;
use esign\SignFlows;
use think\Config;
class Index extends \think\Controller
{
    public function index()
    {
        $info =[  
        	'name' => 'Dale',  
        	'tel' => '123',  
        	'qq' => '123'  
    	];  
     	$data = [  
        	'status' => '200',  
        	'message' => 'ok',  
        	'data' =>$info,  
    	];  
		echo "<pre>";
     	echo  json_encode($data);
		exit(); 
    }
	
	
	public function callback(){
		
		$data = file_get_contents("php://input");
		$fp = fopen("log.txt","a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$data."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
		
		//固定信息
		$API_Host= Config::get('api_host'); 
		$getToken=$API_Host."/v1/oauth2/access_token";
		$archiveSign=$API_Host."/v1/signflows/{flowId}/archive";
		$downloadDocument=$API_Host."/v1/signflows/{flowId}/documents";
		//公司信息
		$config_company = Config::get('config_company');

		//以下执行网站逻辑
		$flow_data = json_decode($data,true);
	
		if($flow_data['action']=='SIGN_FLOW_UPDATE' and $flow_data['signResult']==2){//归档
			Db::startTrans();
			$RES = Db::name('hetong')->where(['flowid'=>"$flow_data[flowId]",'account_id'=>"$flow_data[accountId]"])->update(array('status'=>2));
			if($RES){//
				$INFO = Db::name('hetong')->where('flowid="'.$flow_data['flowId'].'"')->find();
				$T_RES = Db::name('borrow_tender')->where('id='.$INFO['yid'])->update(array('status'=>1));
				if($T_RES){
					//归档
					$mid = $INFO['mid'];
					$appId = 	$config_company[$mid]['appid'];
					$secret	=  $config_company[$mid]['secret'];
					
					$token = new Token();
        			$stoken=$token->getToken($appId,$secret,$getToken);
					
					$SignFlows=new SignFlows();
        			$res = $SignFlows->archiveSign($appId,$stoken,$archiveSign,$INFO['flowid']);
					
					
					if($res['code']==0){
						Db::commit();
						echo json_encode(array('code'=>200));
						exit();	
					}else{
						Db::rollback();
						echo json_encode(array('code'=>400));
						exit();	
					}
					
				}else{
					Db::rollback();
				}
			}else{
				echo 'fail';
				exit();
			
			}
		}else if($flow_data['action']=='SIGN_FLOW_FINISH'){//签署和下载
			/*Db::startTrans();
			$RES = Db::name('hetong')->where(['flowid'=>$flow_data['flowId'],'account_id'=>$flow_data['accountId']])->update(array('status'=>2));
			if($RES){//
				$INFO = Db::name('hetong')->where('flowid='.$flow_data['flowId'])->find();
				
			}*/
			$INFO = Db::name('hetong')->where('flowid="'.$flow_data['flowId'].'"')->find();
			$mid = $INFO['mid'];
			$appId = 	$config_company[$mid]['appid'];
			$secret	=  $config_company[$mid]['secret'];
			$token = new Token();
        	$stoken=$token->getToken($appId,$secret,$getToken);
			$SignFlows=new SignFlows();
        	$res = $SignFlows->downloadDocument($appId,$stoken,$downloadDocument,$flow_data['flowId']);
        	//header("Content-type: text/html; charset=utf-8");
			//print_r($res);
			$res = $this->object_array($res);//转换数组
			$fileurl = $res['data']['docs'][0]['fileUrl'];
			$r = $this->download_image($fileurl);//下载
			if($r){
				//更新合同
				$hetong_update = Db::name('hetong')->where('flowid="'.$flow_data['flowId'].'"')->update(array('download'=>$r));
				echo json_encode(array('code'=>200));
				exit();	
			}
			exit;
		}
		
				
		
	}
	
	public function object_array($array) {  
		if(is_object($array)) {  
			$array = (array)$array;  
		 } if(is_array($array)) {  
			 foreach($array as $key=>$value) {  
				 $array[$key] = self::object_array($value);  
				 }  
		 }  
		 return $array;  
	}
	
	
	//下载文件
	public function download_image($pic_url)
	{
		$file = file_get_contents($pic_url);
		$time = time();
		$pic_local_path = './useragreement/complete/';
		$pic_local = $pic_local_path . $time . rand(1,9999). '.pdf';
		//print_r($pic_local_path);
		if (!file_exists($pic_local_path)) {
			mkdir($pic_local_path, 0777);
			@chmod($pic_local_path, 0777);
		}
		file_put_contents($pic_local, $file);
		return $pic_local;
	}
	
	
}
