<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use app\index\model\Goods;
use think\Db;
use think\helper\Time;
class Order extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';

	static public function checkOrder($u_id)
	{
		return Db::table('st_order')->where('u_id',$u_id)->order('order_id desc')->paginate(5);
	}
	//单独查询订单
	static public function singleOrder($order_id)
	{
		return Db::table('st_order')->where('order_id',$order_id)->field('goods_id,number')->find();
	}
	static public function checkOrder_nopay($u_id)
	{
		return Db::table('st_order')->where('u_id',$u_id)->where('status',null)->where('delete_time',null)->order('order_id desc')->paginate(5);
	}
	static public function checkOrder_reorder($u_id)
	{
		return Db::table('st_order')->where('u_id',$u_id)->where('status',null)->where('delete_time','>',0)->order('order_id desc')->paginate(5);
	}
	static public function payOrder($u_id,$data)
	{
		return Db::table('st_order')->where('u_id',$u_id)->where('create_time','>',$data)->select();
	}
	static public function secend_payOrder($order_id,$data)
	{
		return Db::table('st_order')->where('order_id',$order_id)->update(['create_time' => $data]);
	}
	static public function delOrder($order_id)
	{
		// return Db::table('st_order')->where('order_id',$order_id)->destroy();
		return Order::get($order_id)->delete();
	}
	static public function delOrder_real($order_id)
	{
		// return Db::table('st_order')->where('order_id',$order_id)->destroy();
		return Order::destroy($order_id,true);
	}
	//支付状态变为1
	static public function moneyOrder($order_id)
	{
		return Db::table('st_order')->where('order_id',$order_id)->update(['status' => 1]);
	}
	static public function orderCode($order_id,$code)
	{
		return Db::table('st_order')->where('order_id',$order_id)->update(['code' => $code]);
	}
	static public function reorder_back($order_id)
	{
		return Db::table('st_order')->where('order_id',$order_id)->update(['delete_time' => null]);
	}
	static public function checkOrder_history($u_id)
	{
		return Db::table('st_order')->where('u_id',$u_id)->where('status',1)->where('delete_time',null)->order('order_id desc')->paginate(5);
	}
	static public function payOrder_time($u_id,$data)
	{
		return Db::table('st_order')->field('order_id')->where('u_id',$u_id)->where('create_time','>',$data)->select();
	}
	static public function reorder_acept($order_id)
	{
		return Db::table('st_order')->where('order_id',$order_id)->update(['acept' => 1]);
	}
	static public function checkOrder_nosave($u_id)
	{
		return Db::table('st_order')->where('u_id',$u_id)->where('status',1)->where('delete_time',null)->where('acept',null)->order('order_id desc')->paginate(5);
	}
	static public function checkOrder_nochat($u_id)
	{
		return Db::table('st_order')->where('u_id',$u_id)->where('status',1)->where('delete_time',null)->where('comments',null)->where('acept',1)->order('order_id desc')->paginate(5);
	}
	static public function reorder_comments($order_id)
	{
		return Db::table('st_order')->where('order_id',$order_id)->update(['comments' => 1]);
	}
	static public function reorder_refund($order_id)
	{
		return Db::table('st_order')->where('order_id',$order_id)->update(['refund' => 1]);
	}

	static public function checkOrder_refund($u_id)
	{
		return Db::table('st_order')->where('u_id',$u_id)->where('delete_time',null)->where('refund',1)->order('order_id desc')->paginate(5);
	}

	static public function refundCancle($order_id)
	{
		return Db::table('st_order')->where('order_id',$order_id)->update(['refund' => null]);
	}
}