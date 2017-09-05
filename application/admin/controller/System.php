<?php
namespace app\admin\controller;
use app\admin\controller\Auth;

class System extends Auth
{
	protected $is_login = ['*'];
	//系统设置
	public function systems()
	{
		return $this->fetch();
	}
	//系统栏目管理
	public function system_section()
	{
		return $this->fetch();
	}
	//系统日志
	public function system_logs()
	{
		return $this->fetch();
	}
}