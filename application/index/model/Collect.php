<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class Collect extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';

	static public function collectAdd($data)
	{
		return Db::table('st_collect')->insert($data);
	}
	static public function collectFind($goods_id)
	{
		return Db::table('st_collect')->where('goods_id',$goods_id)->find();
	}
	static public function collectUser($u_id)
	{
		return Db::table('st_collect')->where('u_id',$u_id)->paginate(5);
	}
	static public function nocollect($goods_id)
	{
		return Db::table('st_collect')->where('goods_id',$goods_id)->delete();
	}
}