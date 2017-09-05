<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;

class Sendemail extends Model
{
	static public function sendEmail($data)
	{
		return Db::table('st_sendemail')->insert($data);
	}
	static public function getToken($data)
	{
		return Db::table('st_sendemail')->field('token_exptime,u_id,sendemail_id')->where('token',$data)->order('sendemail_id desc')->limit(1)->find();
	}

	static public function changeStatus($id)
	{
		return Db::table('st_sendemail')->where('sendemail_id', $id)->update(['status' => 1]);
	}

	static public function findStatus($u_id)
	{
		return Db::table('st_sendemail')->field('status,email')->where('u_id',$u_id)->where('status',1)->order('sendemail_id desc')->limit(1)->find();
	}
}