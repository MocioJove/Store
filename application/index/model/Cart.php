<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class Cart extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $updateTime = false;//必须
	static public function Cart($data)
	{
		return Db::table('st_cart')->insert($data);
	}
	static public function findCart($u_id)
	{
		return Db::table('st_cart')->where('u_id',$u_id)->select();
	}
	static public function findGoods($goods_id)
	{
		return Db::table('st_cart')->where('goods_id',$goods_id)->select();
	}
	static public function cartAdd($data)
	{
		return Db::table('st_cart')->insert($data);
	}
	static public function cartDel($data)
	{
		return Db::table('st_cart')->where('goods_id',$data)->delete();
	}
//加入订单 删除购物车对应物品
	static public function inorderDel($data)
	{
		return Db::table('st_cart')->where('car_id',$data)->delete();
	}
}