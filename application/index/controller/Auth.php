<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User;
class Auth extends Controller
{
	protected $is_login = [''];
	public function _initialize()
	{
		if (!$this->checklogin() && $this->is_login[0] == '*') {
			$this->error('您还没有登录',url('index/auth/login'));
		}
	}
	
    public function regsuc()
    {
         $this->success('注册成功','index/auth/login');
    }
    public function regerr()
    {
         session(null, 'think');
         $this->error('注册失败','index/auth/reg');
    }

     public function logsuc()
    {
         $this->success('欢迎回来','index/index/index');
    }

    public function logout()
    {
         session(null, 'think');
         $this->success('退出成功','index/auth/login');
    }
    public function repwd()
    {
      $this->success('修改密码成功','index/auth/login');
    }

     public function wrongrepwd()
    {
      $this->error('修改密码失败','index/auth/login');
    }

  public function changesuc()
    {
      $this->success('修改成功','index/index/account');
    }
    public function changeerr()
    {
      $this->error('修改失败','index/index/account');
    }
	public function login()
	{
		return $this->fetch();
	}
	public function findpwd()
	{
		return $this->fetch();
	}
	public function reg()
	{
		return $this->fetch();
	}
	public function checklogin()
	{
		return session('?username');//真|jia 
	}
	
}