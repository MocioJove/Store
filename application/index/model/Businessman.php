<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class Businessman extends Model
{
	static public function checkReg($data)
	{
		return Db::name('businessman')->insert($data);
	}

	static public function checkRegfour($data)
	{
		return Db::name('brand')->insert($data);
	}
	static public function regname($u_id)
	{
		return Db::table('st_businessman')->field('username')->where('u_id',$u_id)->order('businessman_id desc')->limit(1)->find();
	}
	static public function regcontent($u_id)
	{
		return Db::table('st_businessman')->field('content')->where('u_id',$u_id)->order('businessman_id desc')->limit(1)->find();
	}

	static public function sureReg($u_id)
	{
		return Db::table('st_businessman')->where('u_id',$u_id)->find();
	
	}
}