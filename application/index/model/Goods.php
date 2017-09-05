<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class Goods extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';

	static public function checkGoods($goodsid)
	{
		return Db::table('st_goods')->field('goodsname,price,count,goods_id,photopath,editorValue,class_id')->where('goods_id',$goodsid)->find();
	}
	static public function checkInfo($goodsid)
	{
		return Db::table('st_goods')->field('goodsname,price,count,goods_id,photopath,editorValue,class_id')->where('goods_id',$goodsid)->select();
	}
	static public function firstGoods($class_id)
	{
		return Db::table('st_goods')->field('goodsname,price,count,goods_id,photopath,editorValue,class_id')->where('class_id','in',$class_id)->select();
	}
	static public function secendGoods($class_id)
	{
		return Db::table('st_goods')->field('goodsname,price,count,goods_id,photopath,editorValue,class_id')->where('class_id','in',$class_id)->select();
	}
	static public function thirdGoods($class_id)
	{
		return Db::table('st_goods')->field('goodsname,price,count,goods_id,photopath,editorValue,class_id')->where('class_id',$class_id)->select();
	}
	static public function goodsClassid()
	{
		return Db::table('st_goods')->field('class_id')->select();
	}
	// 购物车数据插入数据库
	static public function checkOrder($data)
	{
		return Db::table('st_order')->insert($data);
	}
	static public function checkFirst($class_id)
	{
		return Db::table('st_goods')->field('goodsname,price,count,goods_id,photopath,editorValue,class_id')->where('class_id','in',$class_id)->limit(6)->select();
	}
	static public function checkRight($class_id)
	{
		return Db::table('st_goods')->field('goodsname,price,count,goods_id,photopath,editorValue,class_id')->where('class_id','in',$class_id)->find();
	}
	static public function checkleft($class_id)
	{
		return Db::table('st_goods')->field('goodsname,price,count,goods_id,photopath,editorValue,class_id')->where('class_id','in',$class_id)->order('goods_id desc')->find();
	}
}