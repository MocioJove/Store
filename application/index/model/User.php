<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class User extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';

	static public function checkSet($data)
	{
		//检查用户是否存在 用于登录
		if(db('user')->where('username',"$data")->find() || db('user')->where('mobile',"$data")->find())
		{
			return true;
		}else{
			return false;
		}
	}

	static public function checkMobile($data)
	{
		//检查手机号是否存在 用于注册
		if(db('user')->where('mobile',"$data")->find())
		{
			return true;
		}else{
			return false;
		}
	}

	static public function checknewMobile($data)
	{
		if(session('?username'))
		{
			$username = session('username');
			return Db::table('st_user')->where('mobile',$data)->find();
		}else{
			return false;
		}
		
	}
	//注册
	static public function checkRegister($data)
	{
		$username = trim($data['username']);
		$mobile = trim($data['mobile']);
		$password = md5(trim($data['password']));
		// $password = trim($data['password']);
		
		$agree = trim($data['agree']);

		$info = [
			'username' => $username,
			'password' => $password,
			'mobile'   => $mobile,
			'agree'    => $agree
		];
		$user = db('user')->insert($info);
		if($user)
		{
			return true;
		}else{
			return false;
		}

	}
	//登录
	static public function checkLogin($data)
	{
		$username=trim($data['username']);
		$password=md5(trim($data['password']));
		$sel = Db::table('st_user')->field('password')->where('username',"$username")->find();
		// var_dump($sel);die();
		if($sel)
		{
			if($sel['password'] == $password)
			{
				return true;
			}else{
				return false;
			}
		}
		$selone = Db::table('st_user')->field('password')->where('mobile',"$username")->find();
		if($selone)
		{
			if($selone['password'] == $password)
			{
				return true;
			}else{
				return false;
			}
		}
		
		
	}

	static public function checkInfo()
	{
		if(session('?username'))
		{
			$username = session('username');
			$head = Db::table('st_user')->field('realname,nickname,birthday,sex,mobile,address,addrinfo,email,headphoto')->where('username',$username)->find();
			return $head;
		}else{
			$head = ['realname' => '','nickname' => '','birthday' => '','sex' => '','mobile' => '','address' => '','addrinfo' => '','email' => '','headphoto' => ''];
			return $head;
		}
		
	}

	static public function checkHead($username,$photo)
	{
		return Db::table('st_user')->where('username',$username)->setField('headphoto', $photo);
	}


	static public function checkFind($data)
	{
		return Db::table('st_user')->field('mobile')->where('username',"$data")->find();
	}

	static public function checkNewpwd($username, $password)
	{
		return Db::table('st_user')->where('username',$username)->setField('password', $password);
	}

	static public function checkChange($realname, $nickname, $birthday, $sex, $mobile, $address, $addrinfo, $email)
	{
		if(session('?username'))
		{
			$username = session('username');
			return db('user')->where('username',$username)->update(['realname' => $realname, 'nickname' => $nickname, 'birthday' => $birthday, 'sex' => $sex, 'mobile' => $mobile, 'address' => $address, 'addrinfo' => $addrinfo, 'email' => $email]);
		}else{
			return false;
		}
		
	}

	static public function goodsAddr($data)
	{
		if(session('?username'))
		{
			return Db::name('goodsaddr')->insert($data);
		}else{
			return false;
		}
		
	}

	static public function goodsAddrchg($mobile, $address, $addrinfo)
	{
		if(session('?username'))
		{
			$username = session('username');
			return db('st_goodsaddr')->where('username',$username)->update(['mobile' => $mobile, 'address' => $address, 'addrinfo' => $addrinfo]);
		}else{
			return false;
		}
		
	}

	static public function checkUid($username)
	{
		return Db::table('st_user')->field('u_id')->where('username',"$username")->find();
	}

	static public function goodsaddrCount($u_id)
	{
		return Db::table('st_goodsaddr')->where('u_id',$u_id)->where('delete_time',null)->count();
	}
	static public function goodsaddrInfo($u_id)
	{
		return Db::table('st_goodsaddr')->where('u_id',$u_id)->where('delete_time',null)->select();
	}

	static public function user_goodsaddrInfo($goodsaddr_id )
	{
		return Db::table('st_goodsaddr')->where('goodsaddr_id',$goodsaddr_id )->find();
	}

	static public function user_goodsaddrChg($id,$addr,$addrinfo,$mobile,$accept)
	{
		return Db::table('st_goodsaddr')->where('goodsaddr_id', $id)->update(['goodsmobile' => $mobile, 'goodsaddrss' => $addr, 'goodsaddrinfo' => $addrinfo, 'accept' => $accept]);
	}

	static public function setEmail($u_id, $email)
	{
		return Db::table('st_user')->where('u_id', $u_id)->update(['email' => $email]);
	}

	static public function username($u_id)
	{
		return Db::table('st_user')->field('username')->where('u_id',$u_id)->find();
	}
	static public function userpay($u_id,$money)
	{
		return Db::table('st_user')->where('u_id',$u_id)->update(['money' => $money]);
	}
	static public function checkMoney($u_id)
	{
		return Db::table('st_user')->where('u_id',$u_id)->field('money')->find();
	}
	static public function userInfo($u_id)
	{
		return Db::table('st_user')->field('username,headphoto')->where('u_id',$u_id)->find();
	}
}

