<?php
//公共函数
function set_salts($num = 10){
    $str = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    $salt = substr(str_shuffle($str), 10, $num);
    return $salt;
}


function set_passwords($pwd, $salt){
    return md5(md5($pwd.$salt).$salt);
}