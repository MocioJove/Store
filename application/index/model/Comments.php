<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class Comments extends Model
{
	static public function comments($data)
	{
		return Db::table('st_comments')->insert($data);
	}

	static public function comments_info($u_id)
	{
		return Db::table('st_comments')->where('u_id',$u_id)->order('comments_id desc')->paginate(5);
	}

	static public function commentsPage($u_id)
	{
		return Db::table('st_comments')->where('u_id',$u_id)->paginate(5);
	}

	static public function comments_del($comments_id)
	{
		return Db::table('st_comments')->where('comments_id',$comments_id)->delete();
	}

	public function comments_goods($goods_id)
	{
		return Db::table('st_comments')->where('goods_id',$goods_id)->select();
	}
	public function comments_goods_good($goods_id)
	{
		return Db::table('st_comments')->where('goods_id',$goods_id)->where('grade',1)->select();
	}
	public function comments_goods_mid($goods_id)
	{
		return Db::table('st_comments')->where('goods_id',$goods_id)->where('grade',2)->select();
	}
	public function comments_goods_bad($goods_id)
	{
		return Db::table('st_comments')->where('goods_id',$goods_id)->where('grade',3)->select();
	}
	public function comments_count($goods_id)
	{
		return Db::table('st_comments')->where('goods_id',$goods_id)->count();
	}
	public function comments_count_good($goods_id)
	{
		return Db::table('st_comments')->where('goods_id',$goods_id)->where('grade',1)->count();
	}
	public function comments_count_mid($goods_id)
	{
		return Db::table('st_comments')->where('goods_id',$goods_id)->where('grade',2)->count();
	}
	public function comments_count_bad($goods_id)
	{
		return Db::table('st_comments')->where('goods_id',$goods_id)->where('grade',3)->count();
	}
}