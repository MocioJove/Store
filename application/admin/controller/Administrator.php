<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use think\Request;
use app\admin\model\Useradmin;
use app\admin\model\Loginfo;

class Administrator extends Auth
{
	protected $is_login = ['*'];
	
	//权限管理页面
	public function admin_competence()
	{
		return $this->fetch();
	}
	//权限操作页面
	public function competence()
	{
		return $this->fetch();
	}
	//管理员列表
	public function administrator()
	{
        $shopid = session('admin')['s_id'];
        if ($shopid != 1) {
            $result = Useradmin::showshopadmin($shopid);
        } else {
            $result = Useradmin::showadmin();
        }
		
        if (!$result) {
            return false;
        }

        $this->assign('result', $result);
        return $this->fetch();
	}
    //添加管理员
    public function addadmin()
    {

        $info = $_POST;
        dump($info);
        $username = $info['user-name'];
        $password = md5($info['userpassword']);
        $shopid   = session('admin')['s_id'];
        $roleid   = $info['admin-role'];
       
        //检测用户名是否存在，若不存在就提示先注册
        $where    = 'username';
        $table    = 'user';
        $result   = Useradmin::checkInfo($where, $username, $table);
        if (empty($result)) {
            exit("<script>alert('该用户名不存在，请先注册');history.back();</script>");  
        }
        
        //检查该用户是否已是该商铺的管理员
        $where1 = 's_id';
        $where2 = 'username';
        $tips  = Useradmin::checkadmin($where1,$shopid,$where2,$username);
        
        if ($tips) {
            $where = 'u_id';
            $data2 = $tips['id'];
            $roleinfo['r_id'] = $shopid;
            //修改用户角色
            $table = 'userole';
            $tips1 = Useradmin::updateinfo($roleinfo,$table,$where,$data2);
            if ($tips1) {
                exit("<script>alert('管理员角色修改成功');history.back();</script>");
             } else {
                exit("<script>alert('管理员角色修改失败');history.back();</script>");
             }
        }
        //若不是该商铺管理员则增加
        $data['username'] = $username;
        $data['password'] = $password;
        $data['s_id'] = $shopid;
        $data['relname'] = $result['relname'];
        $data['nickname'] = $result['nickname'];
        $data['sex'] = $result['sex'];
        $data['age'] = $result['age'];
        $data['phone'] = $result['moblie'];
        $data['email'] = $result['email'];
        $data['qq'] = $result['qq'];
        $data['regtime'] = $result['$regtime'];

        $tips2 = Useradmin::insertinfo($data);

        if (!$tips2) {
            exit("<script>alert('添加失败');history.back();</script>");
         } 
        
        $where1   = 's_id';
        $where2   = 'username';
        //获得增加后的用户id
        $result2   = Useradmin::checkadmin($where1,$shopid,$where2,$username);
        $roleinfo['u_id'] = $result2['id'];
        $roleinfo['r_id'] = $info['roleid'];
        //插入用户角色表
        $tips3 = Useradmin::insertrole($roleinfo);

        if (!$tips3) {
            exit("<script>alert('添加失败');history.back();</script>");
        }
        exit("<script>alert('添加成功');history.back();</script>");
    }
	//管理员信息展示
	public function admin_info()
	{	
		$userid       = session('admin')['id'];
		$userinfo     = Useradmin::userinfo($userid);

		$regtime      = date('Y-m-d', $userinfo['regtime']);

		$adminloginfo = $this->adminloginfo($userid);
		
		$this->assign('adminloginfo', $adminloginfo);
		$this->assign('userinfo',$userinfo);
		$this->assign('regtime',$regtime);
		return $this->fetch();
	}
	//管理员登陆数据
	public function adminloginfo($userid)
	{
		$where = 'u_id';
		$data  = $userid;
		return Loginfo::select($where,$data);
	}


	//管理员信息修改
	public function updateinfo(Request $request)
	{
       	$updateinfo['id']      = session('admin')['id'];
       	$updateinfo['nickname'] = $request->post('nickname');
        $updateinfo['age']      = $request->post('age');
        $updateinfo['phone']    = $request->post('phone');
        $updateinfo['email']    = $request->post('email');
        $updateinfo['qq']       = $request->post('qq');
        $updateinfo['sex']      = $request->post('sex');
       	
       	//检查昵称
       	$datan = $this->checknickname($updateinfo);
        if (!$datan['err']) {
        	return $datan;
        }
        //检查手机号
        $datap = $this->checkphone($updateinfo);
        if (!$datap['err']) {
        	return $datap;
        }


       // 检查其他值是否合法 
        $code = $this->checkUpdateInfo($updateinfo);
         
        if(!$code['err']){
           return $code;    
        } 
       	
       	$succes = Useradmin::updateinfo($updateinfo); 

       	if ($succes) {
       		return ['err'=>1,'info'=>'信息修改成功'];
       	} else {
       		return ['err'=>0,'info'=>'数据库连接出错'];
       	} 

	}
	
	//修改信息的检查
	public function checkUpdateInfo($updateinfo)
	{	
        // 判断年龄格式
      	$reg = '/^[0-9]+.?[0-9]*$/';
       	if(!preg_match($reg, $updateinfo['age'], $match)){
            return ['err'=>0,'info'=>'年龄格式错误'];
        }

        // 邮箱格式是否正确
        $strr = '/^(\w+)@(\w+\.)+(cn|net|com)$/';
        if(!preg_match($strr, $updateinfo['email'], $match)){
            return ['err'=>0,'info'=>'邮箱格式错误'];
        }

        // 判断qq
        if(preg_match($reg, $updateinfo['qq'], $match)){
            if (strlen($updateinfo['qq']) < 4 || strlen($updateinfo['qq']) > 11) {
            	return ['err'=>0,'info'=>'QQ格式错误'];
        	}
        } else { 
            return ['err'=>0,'info'=>'QQ格式错误'];
        } 
        return ['err'=>1]  ;
	}
	
	//检查昵称
	public function checknickname($updateinfo)
	{	
		$request = Request::instance();
		$nickname = session('admin')['nickname'];
		$updateinfo['nickname'] = $request->post('nickname');
        
        // 判断昵称是否修改
        if ($updateinfo['nickname'] !=  $nickname) {
             
             // 检查昵称是否合法
	        if (strlen($updateinfo['nickname']) > 21 || strlen($updateinfo['nickname'])< 2) {
	            return ['err'=>0,'info'=>'昵称不合法'];
	        } 
            
            // 检查昵称是否存在
        	$where    = 'nickname';
	        $nickname = $updateinfo['nickname'];
	    	$isexist  = Useradmin::checkInfo($where,$nickname);
            if (!empty($isexist)) {
                return ['err'=>0,'info'=>'昵称已存在'];
            }
            return ['err'=>1];
        }
       	 	return ['err'=>1];
	}
	//检查电话
	public function checkphone()
	{
		$request = Request::instance();
		$phone = session('admin')['phone'];
		$updateinfo['phone'] = $request->post('phone');
		
		//判断电话是否修改
		if ($updateinfo['phone'] !=  $phone) {
			
			// 判断电话号格式是否正确
	        $str = '/13[123569]{1}\d{8}|15[1235689]\d{8}|188\d{8}/';
	        if (!preg_match_all($str, $updateinfo['phone'], $match)) {
	            return ['err'=>0,'info'=>'电话格式错误'];
	        }
	        
	        // 电话长度不能大于11
	        if (strlen($updateinfo['phone']) > 11) {
	            return ['err'=>0,'info'=>'电话长度错误'];
	        }
	        
	        // 检查电话是否存在
	        $where = 'phone';
	        $phone = $updateinfo['phone'];
	    	$isexist = Useradmin::checkInfo($where,$phone);
	        if (!empty($isexist)) {
	            return ['err'=>0,'info'=>'电话已注册'];
	        }
	        return ['err'=>1,'info'=>'电话可用'];
	    }
	    return ['err'=>1];
	}
    //修改密码
    public function updatepwd(Request $request)
    {
    	$id    = session('admin')['id'];
    	$newpwd = $request->post('newpwd');
    	$oldpwd = $request->post('oldpwd');

    	if (strlen($oldpwd) < 6 || strlen($oldpwd) >18) {
    		return ['err'=>0,'info'=>'密码长度为6-18位'];
    	}
    	if (strlen($newpwd) < 6 || strlen($newpwd) >18) {
    		return ['err'=>0,'info'=>'新密码长度为6-18位'];
    	}
        
        $info = ['id'=>$id, 'oldpwd'=>$oldpwd];
        $data = $this->checkpassword($info);
       
        if ($data['err']) {
        	$password   = md5($newpwd);
        	$updateinfo = ['id'=>$id, 'password'=>$password];
        	Useradmin::updateinfo($updateinfo);
        	return ['err'=>1,'info'=>'修改成功'];
        } else {
        	return $data;
        }

    }
    //检查原密码是否正确
    public function checkpassword($info)
    {	
    	$result = Useradmin::userinfo($info['id']);
    	
    	if (md5($info['oldpwd']) == $result['password']) {
    		$data = ['err'=>1];
    	} else {
    		$data =['err'=>0,'info'=>'原密码错误'];	
    	}
    	return $data;
    }


}