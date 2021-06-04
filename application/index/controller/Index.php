<?php
namespace app\index\controller;
use app\index\model\Borrow as iBorrowModel;
class Index extends \think\Controller
{
    public function index()
    {
		print_r(iBorrowModel::getList(''));
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
}
