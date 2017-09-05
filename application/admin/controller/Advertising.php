<?php
namespace app\admin\controller;
use app\admin\controller\Auth;

class Advertising extends Auth
{
	protected $is_login = ['*'];
	//广告管理
	public function advertising()
	{
		return $this->fetch();
	}
	//分类管理
	public function sort_ads()
	{
		return $this->fetch('sort_ads');
	}
}