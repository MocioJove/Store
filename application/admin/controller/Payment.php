<?php
namespace app\admin\controller;
use app\admin\controller\Auth;

class Payment extends Auth
{
	protected $is_login = ['*'];
	//账户管理
	public function cover_management()
	{
		return $this->fetch();
	}
	//支付方式
	public function payment_method()
	{
		return $this->fetch();
	}
	//支付配置
	public function payment_configure()
	{
		return $this->fetch();
	}
}