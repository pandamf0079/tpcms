<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//获取单独项目信息
Route::get(':version/Borrow/:id', 'api/:version.Borrow/getone',[],['id'=>'\d+','version'=>'v[0-9]']);

//获取项目信息
Route::get(':version/Borrow/list', 'api/:version.Borrow/lists',[],['id'=>'\d+','version'=>'v[0-9]']);

//微信登陆
Route::get(':version/User/wxlogin', 'api/:version.User/wxlogin',[],['version'=>'v[0-9]']);
//api 回调
Route::post(':version/Callback', 'api/:version.Index/callback',[],['version'=>'v[0-9]']);

//用户发起合同
Route::get(':version/User/beginsign', 'api/:version.User/beginsign',[],['version'=>'v[0-9]']);

//用户预约
Route::get(':version/User/yuyue', 'api/:version.User/yuyue',[],['version'=>'v[0-9]']);

//取用户信息
Route::get(':version/User/getuinfo', 'api/:version.User/getuinfo',[],['version'=>'v[0-9]']);

//插入预约信息
Route::get(':version/Borrow/saveappoint', 'api/:version.Borrow/saveyuyue',[],['version'=>'v[0-9]']);

//获取用户预约信息
Route::get(':version/Borrow/appointlist', 'api/:version.Borrow/yuyuelist',[],['version'=>'v[0-9]']);


//获取用户合同信息
Route::get(':version/User/hetong', 'api/:version.User/hetong',[],['version'=>'v[0-9]']);



//插入手机信息
Route::get(':version/User/savephone', 'api/:version.User/savephone',[],['version'=>'v[0-9]']);

//插入实名信息
Route::get(':version/User/savereal', 'api/:version.User/savereal',[],['version'=>'v[0-9]']);
/*return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];*/
