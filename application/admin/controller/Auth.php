<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Useradmin;
use app\admin\model\Loginfo;

class Auth extends Controller
{
	protected $is_login = [''];

	public function _initialize()
	{
		if(!$this->checklogin() && $this->is_login[0] == '*'){
			$this->error('未登陆，无权访问', 'admin/auth/login');
		}
	}
	public function checklogin()
	{
		return session('?admin');
	}
	
	//登陆
	public function login()
	{
		return $this->fetch();
		
	}
	public function loginJud(Request $request)
	{
		$verify = $request->post('code');
		$username = $request->post('username');
		$password = md5($request->post('password'));

		if (!captcha_check($verify)) {
			return ['code'=>2];
		} 
		
		$result = Useradmin::login($username,$password);
		

		if ($result) {
			$this->setsession($result);
			$this->loginfoJud($result);
			return ['code'=>1];
		} else {
			return ['code'=>0];
		}
	}
	//处理session
	public function setsession($result)
	{
		$data = [
				'id'=>$result['id'],
				's_id'=>$result['s_id'],
				'nickname'=>$result['nickname'],
				'phone'=>$result['phone']
			];
			session('admin', $data);
	}
	//处理登陆信息
	public function loginfoJud($result)
	{
		$loginip    = $_SERVER['REMOTE_ADDR']; 
		$loginaddre = '';
		$logintime  = time();
		$datarry    = [
						'u_id'=>$result['id'],
						'nickname'=>$result['nickname'],
						'loginip'=>$loginip,
						'loginaddre'=>$loginaddre,
						'logintime'=>$logintime
					];
		Loginfo::insert($datarry);
	}
	//用户名权限检查
	public function checkname(Request $request)
	{	
		
		$where = 'username';
       	$username = $request->post('username');
    	
    	$isexist = Useradmin::checkInfo($where,$username);
    	
    	if ($isexist) {
    		return ['err'=>1];
    	} else {
    		return ['err'=>0];
    	}
	}
	// 验证码检测
	public function Captcha(Request $request)
	{
		if (true !== $this->validate($request->param(),['code|验证码'=>'require|captcha'])) {
			return ['err'=>0];
		} else {
			return ['err'=>1];
		}
	}
	//登陆结果页面展示
	public function loginsuc()
	{
		$this->success('登陆成功','admin/index/index');
		
	}
	public function loginerr()
	{
		$this->error('用户名或密码错误','admin/auth/login');
	}
	public function loginyzm()
	{
		$this->error('验证码错误','admin/auth/login');
	}
	//退出
	public function logout()
	{
		session(null, 'think');
        $this->success('退出成功','admin/auth/login');
	}
}