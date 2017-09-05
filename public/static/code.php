<?php
// var_dump($_POST);die();
//载入ucpass类
require_once('lib/Ucpaas.class.php');

//初始化必填
$options['accountsid']='b2ceccdeed61679a28d5cc011c5ed31e';
$options['token']='894b55f81a338dddb8aff20f3b6b67c4';


//初始化 $options必填
$ucpass = new Ucpaas($options);

//开发者账号信息查询默认为json或xml
$str = '1234567890';
$str = str_shuffle($str);
$str = substr($str, 0,4);
// echo $ucpass->getDevinfo('json');
//短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
$appId = "fa3c16840c7a40bf81a63446afb8ec7a";
$to = (int)$_POST['mobile'];
// var_dump($to);die();
$templateId = "62597";
$param=$str;

$ucpass->templateSMS($appId,$to,$templateId,$param);
echo $str;