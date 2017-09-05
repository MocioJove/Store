<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use traits\model\SoftDelete;

class Loginfo extends Model
{
	//插入数据
	static public function insert($datarr)
	{
		
		return Db::name('loginfo')->insert($datarr);
	}
	//查询数据
	static public function select($where,$data)
	{
		return Db::name('loginfo')->where($where,$data)->select();
	}
}