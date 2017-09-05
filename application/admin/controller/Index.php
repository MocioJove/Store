<?php
namespace app\admin\controller;
use app\admin\controller\Auth;

class Index extends Auth
{	
	protected $is_login = ['*'];
	//首页
	public function index()
	{
		return $this->fetch();
		
	}
	

	//默认展示内容
	public function home()
	{
		return $this->fetch();
	}

}