<?php
namespace app\admin\controller;
use app\admin\controller\Auth;

class Transaction extends Auth
{
	protected $is_login = ['*'];
	//交易信息
	public function transaction()
	{
		return $this->fetch();
	}
	//交易订单
	public function order_chart()
	{
		return $this->fetch();
	}
	//订单管理
	public function orderform()
	{
		return $this->fetch();
	}
	//交易金额
	public function amounts()
	{
		return $this->fetch();
	}
	//订单处理
	public function order_handling()
	{
		return $this->fetch();
	}
	//退款管理
	public function refund()
	{
		return $this->fetch();
	}
}