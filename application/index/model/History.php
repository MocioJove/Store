<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class History extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';

	static public function historyadd($data)
	{
		return Db::table('st_history')->insert($data);
	}
	static public function historyFind($data)
	{
		return Db::table('st_history')->where('goods_id',$data)->select();
	}
	static public function userFind($data)
	{
		return Db::table('st_history')->field('time,goods_id,history_id,u_id')->where('u_id',$data)->order('time desc')->select();
	}
	static public function upOld($goods_id,$time)
	{
		return Db::table('st_history')->where('goods_id', $goods_id)->update(['time' => $time]);
	}
	static public function footFind($data)
	{
		return Db::table('st_history')->field('time,goods_id,history_id,u_id')->where('u_id',$data)->order('time desc')->paginate(8);
	}
	static public function delFind()
	{
		return Db::table('st_history')->where('history_id','>',0)->select();
	}
	static public function del($id)
	{
		return Db::table('st_history')->where('history_id',$id)->delete();
	}
}