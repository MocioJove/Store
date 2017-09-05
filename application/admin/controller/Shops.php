<?php
namespace app\admin\controller;
use app\admin\controller\Auth;

class Shops extends Auth
{
	protected $is_login = ['*'];
	//店铺列表
	public function shop_list()
	{
		return $this->fetch();
	}
	//店铺审核
	public function shops_audit()
	{
		return $this->fetch();
	}
}