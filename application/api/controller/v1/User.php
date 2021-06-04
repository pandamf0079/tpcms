<?php
namespace app\api\controller\v1;
use app\api\model\User as iUserModel;
use think\Request;
use think\Db;
use lib\WXBizDataCrypt;
use esign\Token;
use esign\AccountID;
use esign\UploadUrl;
use esign\SignFlows;
use think\Config;
class User extends \think\Controller
{
    
	public function wxlogin(){
		//echo '123';
		$encryptedData = trim($_REQUEST['encryptedData']);
		$iv = trim($_REQUEST['iv']);
		$js_code = trim($_REQUEST['js_code']);
		$appid = 'wx6fb5c4c0xxxxxxxxx';
        $secret= '994bc38c0d66f9f55exxxxxxxxxxxx';

        //print_r($encryptedData);
        //echo "<br/>";
        //print_r($iv);
        //echo "<br/>";
        //print_r($js_code);
        //echo "<br/>";*/
        //请求session_key
        $res_sk = file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$js_code."&grant_type=authorization_code");
        $res_sk_arr = json_decode($res_sk,true);
		
		//print_r($res_sk_arr);echo '===';
		
        if($res_sk_arr['session_key']!=''){
        	$pc = new WXBizDataCrypt($appid, $res_sk_arr['session_key']);
			$errCode = $pc->decryptData($encryptedData, $iv, $data );
			//print_r($errCode);
			if ($errCode == 0) {
				$data = json_decode($data,true);
				$data['openId'] = $res_sk_arr['openid']; //把openid加进去，它是用户与当前小程序的唯一标识
				//注册
				$is_register = Db::name('user')->where('token', $data['openId'])->find();
				if(!$is_register['uid']){
					if(!$data['nickName']){!$data['nickName']='noset';}
					if(!$data['city']){!$data['city']='noset';}
					if(!$data['avatarUrl']){!$data['avatarUrl']='noset';}
					if(!$data['gender']){!$data['gender']=1;}
					iUserModel::saveData(
						['type'=>1,'name'=>$data['nickName'],'nickname'=>$data['nickName'],'area'=>$data['city'],'token'=>$data['openId'],'avatar'=>$data['avatarUrl'],'sex'=>$data['gender'],'addtime'=>time()]
					);
					//print_r('gg');
				}
				//输出
				$render_data = [  
					'status' => '200',  
					'message' => 'suc',  
					'data' => $data,  
				];  
				echo  json_encode($render_data);
				exit();
				
			} else {
			    $render_data = [  
					'status' => '500',  
					'message' => 'fail',  
					'data' => '',  
				];  
				echo  json_encode($render_data);
				exit();
			}
        }else{
        	echo json_encode(array('code' => '400', 'mes' => 'sessionkey-fail' ));
        }
		
		
	}
	
	
	
	public function beginsign(){
		$API_Host= Config::get('api_host');
		//获取鉴权Token
		$getToken=$API_Host."/v1/oauth2/access_token";
		//创建个人账户
		$addPersonAccountID=$API_Host."/v1/accounts/createByThirdPartyUserId";
		//创建企业账户
		$addOrganizeAccountID=$API_Host."/v1/organizations/createByThirdPartyUserId";
		//文件直传创建带签署文件
		$upsloadUrl=$API_Host."/v1/files/getUploadUrl";
		//创建签署流程，返回flowID
		$creataFlow=$API_Host."/v1/signflows";
		//流程文档添加
		$addaDocumnet=$API_Host."/v1/signflows/{flowId}/documents";
		//流程文档添加
		$addaDocumnet=$API_Host."/v1/signflows/{flowId}/documents";
		//流程签名域添加
		$addPlatformSign=$API_Host."/v1/signflows/{flowId}/signfields/platformSign";
		$addHandSign = $API_Host."/v1/signflows/{flowId}/signfields/handSign";
		//签署流程开启
		$startSign=$API_Host."/v1/signflows/{flowId}/start";
		//公司信息
		$config_company = Config::get('config_company');
		//接收参数
		$mid = intval($_REQUEST['mid']);
		$token = trim($_REQUEST['token']);
		$bid  =  trim($_REQUEST['bid']);
		$yid = intval($_REQUEST['yid']);
		//查询客户信息
		$uinfo = Db::name('user')->where('token',$token)->find();
		$binfo = Db::name('borrow')->where('id',$bid)->find();
		if($uinfo['is_real']!=1){
			
			$codedata = [  
				'status' => '201',  
				'message' => 'use_noreal',  
				'data' =>[],  
			];
			echo  json_encode($codedata);
			exit(); 
		}
		//检查预约
		$yinfo = Db::name('borrow_tender')->where('id',$yid)->find();
		if($yinfo['status']==1){
			$codedata = [  
				'status' => '202',  
				'message' => 'yuyue_complete',  
				'data' =>[],  
			];
			echo  json_encode($codedata);
			exit(); 
		}
		
		//生成pdf
		$pdf_address = $this->makepdf($uinfo,$binfo,$mid,$yinfo['money']);
		//exit(123);
		//测试appId
		$appId = $config_company[$mid]['appid'];
		//测试环境secret
		$secret = $config_company[$mid]['secret'];
		
		#1
		$token = new Token();
        $stoken=$token->getToken($appId,$secret,$getToken);
		#2 
		$accountID=new AccountID();
        $accountId = $accountID->addPersonAccountID($appId,$stoken,$addPersonAccountID,$uinfo);
		$orgAccountId = $accountID->addOrganizeAccountID($appId,$stoken,$addPersonAccountID,$config_company[$mid]);
		#3
		$SignFlows=new SignFlows();
        $flowid = $SignFlows->creatFlow($appId,$stoken,$creataFlow);
		#4
		
		
        //文件直传创建带签署文件
        $filePath =  $pdf_address; //pdf地址
        $uploadUrl=new UploadUrl();
        $data2=$uploadUrl->getuploadUrl($appId,$stoken,$upsloadUrl,$filePath);
        //文件fileId
        $fileId = $data2['fileId'];
        //文件直传地址
        $uploadUrls = $data2['uploadUrl'];
        //上传文件
        $uploadUrl->upLoadFile($uploadUrls,$filePath);
        //流程文档添加
        $SignFlows->addDocumnet($appId,$stoken,$addaDocumnet,$flowid,$fileId);
        //取印章位置
		$yinz_pos = $this->get_seal_pos($mid,$fileId,$accountId);
		//添加平台自动盖章签署区
        $res = $SignFlows->addPlatformSign($appId,$stoken,$addPlatformSign,$flowid,$fileId,$yinz_pos['auto']);
		//var_dump($res);
        //添加手动盖章签署区
        $res = $SignFlows->addHandSign($appId,$stoken,$addHandSign,$flowid,$fileId,$accountId,$yinz_pos['man']);
        //echo "流程文本域添加结果\n";
        //var_dump($res);
		#5
        $startSignRes = $SignFlows->startSign($appId,$stoken,$startSign,$flowid);
		
		//保存该条合同信息
		$hetong_arr = array('mid'=>$mid,'yid'=>$yid,'bid'=>$bid,'token'=>$stoken,'flowid'=>$flowid,'account_id'=>$accountId,'addtime'=>time(),'status'=>1,'uid'=>$uinfo['uid'],'download'=>$pdf_address,'hetong_name'=>$binfo['name']);
		$hetong_res = Db::name('hetong')->insert($hetong_arr);
		if($hetong_res){
			$codedata = [  
				'status' => '200',  
				'message' => 'suc',  
				'data' =>[],  
			];
			
		}else{
			$codedata = [  
				'status' => '500',  
				'message' => 'sysbusy',  
				'data' =>[],  
			];
		}
		echo  json_encode($codedata);
		exit(); 

	}
	
	public function  get_seal_pos($mid,$fileId,$accountId){
		if($mid==1){
			$sealarr = array(
				'auto'=>array(//企业章
							array('order'=>'1',"actorIndentityType"=>2,'fileId'=>$fileId,'sealId'=>'b3b09a81-3b52-4729-915e-xxx','signType'=>'1','posBean'=>array('posPage'=>'14','posX'=>'453.5433','posY'=>'680.31')),
							array('order'=>'1',"actorIndentityType"=>2,'fileId'=>$fileId,'sealId'=>'b3b09a81-3b52-4729-915e-xxx','signType'=>'1','posBean'=>array('posPage'=>'17','posX'=>'210.68','posY'=>'714.82')),
							array('order'=>'1',"actorIndentityType"=>2,'fileId'=>$fileId,'sealId'=>'b3b09a81-3b52-4729-915e-xxx','signType'=>'1','posBean'=>array('posPage'=>'19','posX'=>'159.51','posY'=>'609.48')),
							array('order'=>'1',"actorIndentityType"=>2,'fileId'=>$fileId,'sealId'=>'b3b09a81-3b52-4729-915e-xxx','signType'=>'1','posBean'=>array('posPage'=>'22','posX'=>'359.66','posY'=>'216.70')),		
						),
				'man'=>array(//个人章
							array('order'=>'1','fileId'=>$fileId,'signerAccountId'=>$accountId,'signType'=>'1','posBean'=>array('posPage'=>'14','posX'=>'173.46','posY'=>'376.22')),
							array('order'=>'1','fileId'=>$fileId,'signerAccountId'=>$accountId,'signType'=>'1','posBean'=>array('posPage'=>'16','posX'=>'121.89','posY'=>'717.08')),
							array('order'=>'1','fileId'=>$fileId,'signerAccountId'=>$accountId,'signType'=>'1','posBean'=>array('posPage'=>'17','posX'=>'210.68','posY'=>'406.32')),
							array('order'=>'1','fileId'=>$fileId,'signerAccountId'=>$accountId,'signType'=>'1','posBean'=>array('posPage'=>'19','posX'=>'159.51','posY'=>'257.33')),
							array('order'=>'1','fileId'=>$fileId,'signerAccountId'=>$accountId,'signType'=>'1','posBean'=>array('posPage'=>'20','posX'=>'511.66','posY'=>'337.09')),
							array('order'=>'1','fileId'=>$fileId,'signerAccountId'=>$accountId,'signType'=>'1','posBean'=>array('posPage'=>'23','posX'=>'464.25','posY'=>'674.19'))
					    )
			);
		}else if($mid==2){
			$sealarr = array(
				'auto'=>array(
							array('order'=>'1',"actorIndentityType"=>2,'fileId'=>$fileId,'sealId'=>'2a68ce35-13d2-47e7-9ed4-xxxxxx','signType'=>'1','posBean'=>array('posPage'=>'1','posX'=>'100','posY'=>'100'))		
						),                                                                          
				'man'=>array(
							array('order'=>'1','fileId'=>$fileId,'signerAccountId'=>$accountId,'signType'=>'1','posBean'=>array('posPage'=>'1','posX'=>'300','posY'=>'200'))
					    )
			);
		
		}
		
		return $sealarr;
		
	}
	public  function makepdf($uinfo,$binfo,$mid,$ymoney){
		//查找配置信息
		$config_company = Config::get('config_company');
		//生成pdf
		$html=file_get_contents('./static/agreement/'.$mid.'.html');
		$subarray = array('#realname#'=>@iconv('UTF-8', 'UTF-8//IGNORE',$uinfo['realname']),
						  '#apr#'=>@iconv('UTF-8','utf-8//IGNORE',$binfo['apr']),
						  '#success_time_d#'=> @iconv('UTF-8','utf-8//IGNORE',date("d",time())),
						  '#cardid#'=> @iconv('UTF-8','utf-8//IGNORE',$uinfo['cardid']),
						  '#phone#'=> @iconv('UTF-8','utf-8//IGNORE',$uinfo['phone']),
						  '#year#'=> @iconv('UTF-8','utf-8//IGNORE',date("Y",time())),
						  '#month#'=> @iconv('UTF-8','utf-8//IGNORE',date("m",time())),
						  '#day#'=> @iconv('UTF-8','utf-8//IGNORE',date("d",time())),
						  '#appmoney#'=> $ymoney,
						  '#appmoney_wanyuan#'=>floor($ymoney/10000),
						  '#appmoney_hanzi#'=>@iconv('UTF-8','utf-8//IGNORE',$this->ch_num(floor($ymoney))),
						  '#timelimit#'=> $binfo['months'],
						  '#address#'=>'',
						  '#youbian#'=>'',
						  '#email#'=>'',
						  '#faren#'=> @iconv('UTF-8','utf-8//IGNORE',$config_company[$mid]['faren']),
						  '#bankno#'=>'',
						  );
						
		foreach($subarray as $k => $v){
			$html  = str_replace($k,$v,$html);
		}
		require_once('./MPDF60/mpdf.php');
		//实例化mpdf
		$mpdf=new \mPDF('utf-8','A4','','仿宋',0,0,20,20);
		//设置字体,解决中文乱码
		$mpdf->useAdobeCJK = true;
		$mpdf ->autoScriptToLang = true;  
		$mpdf -> autoLangToFont = true;  
		//设置PDF页眉内容
		$header='<table width="95%" style="margin:0 auto;border-bottom: 2px solid #4F81BD; vertical-align: middle; font-family:
		serif; font-size: 9pt; color: #000088;"><tr>
		<td width="10%"></td>
		<td width="80%" align="center" style="font-size:16px;color:#A0A0A0">微诚集团合同</td>
		<td width="10%" style="text-align: right;"></td>
		</tr></table>';
		 
		//设置PDF页脚内容
		$footer='<table width="95%" style="margin:0 auto;  vertical-align: bottom; font-family:
		serif; font-size: 9pt; color: #000088;"><tr style="height:30px"></tr><tr>
		<td width="10%"></td>
		<td width="80%" align="center" style="font-size:14px;color:#A0A0A0">微诚集团合同</td>
		<td width="10%" style="text-align: left;">页码：{PAGENO}/{nb}</td>
		</tr></table>';
		//添加页眉和页脚到pdf中
		$mpdf->SetHTMLHeader($header);
		$mpdf->SetHTMLFooter($footer);
		 
		//设置pdf显示方式
		$mpdf->SetDisplayMode('fullpage');
		//设置pdf的尺寸为270mm*397mm
		//$mpdf->WriteHTML('<pagebreak sheet-size="270mm 397mm" />');
		//创建pdf文件
		header('Content-Type: text/html; charset=utf-8');
		$mpdf->WriteHTML($html);
		
		//删除pdf第一页(由于设置pdf尺寸导致多出了一页)
		//$mpdf->DeletePages(1,1);
		 
		//输出pdf
		$pdf_filename = $uinfo['uid'].rand(1,99).time().'.pdf';
		
		$mpdf->Output("./useragreement/".$pdf_filename);
		return "./useragreement/".$pdf_filename;
	
	
	}
	
	
	//数字转汉数字
	public function ch_num($num,$mode=true) {
		$char = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖");
		$dw = array("","拾","佰","仟","","萬","億","兆");
		$dec = "點";
		$retval = "";
		
		if($mode)
			preg_match_all("/^0*(\d*)\.?(\d*)/",$num, $ar);
		else
			preg_match_all("/(\d*)\.?(\d*)/",$num, $ar);
		
		if($ar[2][0] != "")
			$retval = $dec . self::ch_num($ar[2][0],false); //如果有小数，先递归处理小数
		if($ar[1][0] != "") {
			$str = strrev($ar[1][0]);
			for($i=0;$i<strlen($str);$i++) {
			$out[$i] = $char[$str[$i]];
			if($mode) {
				$out[$i] .= $str[$i] != "0"? $dw[$i%4] : "";
				if($str[$i]+$str[$i-1] == 0)
				$out[$i] = "";
				/*if($i%4 == 0)
				$out[$i] .= $dw[4+floor($i/4)];*/
			}
			}
			$retval = join("",array_reverse($out)) . $retval;
		}
		return $retval;
	}
	
	
	
	public function getuinfo(){
		$token = trim($_REQUEST['token']);
		$uinfo = Db::name('user')->where('token',$token)->find();
		$codedata = [  
        	'status' => '200',  
        	'message' => 'suc',  
        	'data' => $uinfo,  
    	];  
     	echo  json_encode($codedata);
		exit();
	}
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
        	'message' => 'ok',  
        	'data' => $resData['data'],  
    	];  
     	echo  json_encode($data);
		exit();
		
	}
	
	
	public function hetong(Request $request){
	
		$token = $request->param('token');
		if($token){
			$uinfo = Db::name('user')->where(['token'=>$token])->find();
		}
	    $list = Db::name('hetong a')->join('borrow b','a.bid=b.id')->field("a.*,b.name,b.apr,b.months")->order('a.id desc')->where("a.uid",$uinfo['uid'])->paginate(10)->toArray();
		//print_r($list);
		foreach( $list['data'] as $key => $value){
			$list['data'][$key]['downloadurl'] = str_replace("./","",$value['download']);
		}
     	$codedata = [  
        	'status' => '200',  
        	'message' => 'suc',  
        	'data' => $list,  
    	];  
     	echo  json_encode($codedata);
		exit();
	}
	
	
	
	
	public function savephone(Request $request){
		$token = trim($request->param('token'));
		$phone = trim($request->param('phone'));
		if(!$this->Verify_Phone($phone)){
			$codedata = [  
				'status' => '201',  
				'message' => 'phone_not_legal',  
				'data' => '',  
			];  
			echo  json_encode($codedata);
			exit();
		}
		Db::startTrans();
		try{
			Db::name('user')->where('token', $token)->update(array('phone'=>$phone));
			// 提交事务
			Db::commit();
			$codedata = [  
				'status' => '200',  
				'message' => 'suc',  
				'data' => '',  
			];  
			echo  json_encode($codedata);
			exit();
		} catch (\Exception $e) {
			// 回滚事务
			Db::rollback();
			
			$codedata = [  
				'status' => '400',  
				'message' => 'fail',  
				'data' => '',  
			];  
			echo  json_encode($codedata);
			exit();
		}
		
	}
	
	
	public function Verify_Phone($Phone = null){
        
        $ret = false;
        //判断是否有值
        if($Phone){
            $Phone_preg = '#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#';
            //判断是否是正确手机号
            if(preg_match($Phone_preg,$Phone)){
                $ret = true;
            }
        }
        return $ret;
    }
	
	//保存实名信息
	public function savereal(Request $request){
		$token = trim($request->param('token'));
		$realname = trim($request->param('realname'));
		$cardid = trim($request->param('cardid'));
		if(!$this->is_idcard($cardid)){
			$codedata = [  
				'status' => '201',  
				'message' => 'card_not_legal',  
				'data' => '',  
			];  
			echo  json_encode($codedata);
			exit();
		}
		Db::startTrans();
		try{
			Db::name('user')->where('token', $token)->update(array('realname'=>$realname,'cardid'=>$cardid,'is_real'=>1,));
			// 提交事务
			Db::commit();
			$codedata = [  
				'status' => '200',  
				'message' => 'suc',  
				'data' => '',  
			];  
			echo  json_encode($codedata);
			exit();
		} catch (\Exception $e) {
			// 回滚事务
			Db::rollback();
			
			$codedata = [  
				'status' => '400',  
				'message' => 'fail',  
				'data' => '',  
			];  
			echo  json_encode($codedata);
			exit();
		}
		
	}
	
	
	
	public function is_idcard( $id ) 
	{ 
		  $id = strtoupper($id); 
		  $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/"; 
		  $arr_split = array(); 
		  if(!preg_match($regx, $id)) 
		  { 
			return FALSE; 
		  } 
		  if(15==strlen($id)) //检查15位 
		  { 
			$regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/"; 
		  
			@preg_match($regx, $id, $arr_split); 
			//检查生日日期是否正确 
			$dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
			if(!strtotime($dtm_birth)) 
			{ 
			  return FALSE; 
			} else { 
			  return TRUE; 
			} 
		  } 
		  else      //检查18位 
		  { 
			$regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/"; 
			@preg_match($regx, $id, $arr_split); 
			$dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
			if(!strtotime($dtm_birth)) //检查生日日期是否正确 
			{ 
			  return FALSE; 
			} 
			else
			{ 
			  //检验18位身份证的校验码是否正确。 
			  //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。 
			  $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
			  $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
			  $sign = 0; 
			  for ( $i = 0; $i < 17; $i++ ) 
			  { 
				$b = (int) $id{$i}; 
				$w = $arr_int[$i]; 
				$sign += $b * $w; 
			  } 
			  $n = $sign % 11; 
			  $val_num = $arr_ch[$n]; 
			  if ($val_num != substr($id,17, 1)) 
			  { 
				return FALSE; 
			  } 
			  else
			  { 
				return TRUE; 
			  } 
			} 
		  } 
	  
	} 
	
	

}
