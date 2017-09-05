<?php
namespace app\index\controller;
use think\Request;
use think\Controller;
use app\index\model\User as DoUser;
use think\captcha\Captcha;
use app\index\model\Smtp;
use app\index\model\Sendemail;
class User extends Controller
{
	protected $user;

	public function register()
    {
        if(Douser::checkRegister($_POST))
        {
            $data = ['err' => 1];
        }else{
            $data = ['err' => 0];
        }
        echo json_encode($data);
    }

    // 判断用户是否存在
    public function yes()
    {
    	$username = $_POST['username'];
    	if(Douser::checkSet($username))
    	{
    		$data = ['errcode'=>1,'info'=>'用户名已存在'];
    	}else{
    		$data = ['errcode'=>0,'info'=>'用户名可以注册'];
    	}
    	echo json_encode($data);
    }

    // 判断手机号是否存在
    public function mobile()
    {
        $mobile = $_POST['mobile'];
        if(Douser::checkMobile($mobile))
        {
            $data = ['err'=>1,'info'=>'手机号已存在'];
        }else{
            $data = ['err'=>0,'info'=>'手机号可以注册'];
        }
        echo json_encode($data);
    }

    public function newMobile()
    {
        $mobile = $_POST['mobile'];
        $mobile = Douser::checknewMobile($mobile);
        if($mobile)
        {
            $data = ['err'=>1,'info'=>'手机号已存在'];
        }else{
            $data = ['err'=>0,'info'=>'手机号可以更新'];
        }
        echo json_encode($data);
    }
    public function yzm()
    {
        $yzm = $_POST['yzm'];
        $smscode = $_POST['smscode'];
        // dump($_POST);die();
        if($yzm == $smscode)
        {
            $data = ['err'=>1,'info'=>'验证码正确'];
        }else{
            $data = ['err'=>0,'info'=>'验证码不一致'];
        }
        echo json_encode($data);
    }

    public function logset()
    {
        $user = $_POST['user'];
        if(!Douser::checkSet($user))
        {
            $data = ['err' => 0, 'info' => '该用户不存在', 'pwd' => '该用户不存在'];
        }else{
            $data = ['err' => 1, 'info' => '允许登录', 'pwd' => '可以找回'];
        }
        echo json_encode($data);
    }
    public function logpwd()
    {
       
        $data = $_POST;

        $username = $_POST['username'];
        // Douser::checkLogin($data);die();
        // var_dump($username);die();
        if(Douser::checkLogin($data))
        {
            $info = ['err' => 1,'info' => '密码正确'];
            session('username',$username);
            $u_id = Douser::checkUid($username);
            session('u_id',$u_id);
        }else{
            $info = ['err' => 0,'info' => '密码错误'];
        }
        echo json_encode($info);
    }

   public function code()
   {
        $request = Request::instance();
        // var_dump($request->param()['code']);die();
        $code = $request->param()['code'];
        $captcha = new Captcha();
        if($captcha->check($code))
        {
            $data = ['err' => 1, 'code' => '验证码正确'];
        }else{
            $data = ['err' => 0, 'code' => '验证码错误'];
        }
        echo json_encode($data);
   }
   //找回密码ajax判断手机和用户名是否匹配
   public function finpwd()
   {
        $request = Request::instance();
        $username = $request->param('uname');
        $mobile = $request->param('mobile');
        $phone = Douser::checkFind($username)['mobile'];
        // var_dump($phone);die();
        if($mobile == $phone){
            $data = ['err' => 1,'info' => '手机号匹配'];
        }else{
            $data = ['err' => 0,'info' => '手机号不匹配'];
        }
        echo json_encode($data);
   }

   public function newpwd()
   {
        $request = Request::instance();
        $username = $request->param('username');
        $password = md5($request->param('password'));

        $newpwd = Douser::checkNewpwd($username,$password);
        if($newpwd)
        {   
             session(null, 'think');
            $data = ['err' => 1];
        }else{
            $data = ['err' => 0];
        }
        echo json_encode($data);
   }

   public function index()  
   {  
      //查询分类  
       $data= db('sg_type')->select();  
      $this->view->engine->layout(true);  
      //赋值  
      $this->assign('data',$data);  
      return view('index');  
   }  
  
   //网站后台首页  
   public function upload()  
   {  
        $file = request()->file('photo');  
        $data=$_POST;  
        if(isset($file)){  
          
        $info = $file->move('static/images/upload/');  
        //   var_dump($info) ;die;  
  
        if($info){  
        // 成功上传后 获取上传信息  
         $path = $info->getSaveName();  
         $path = '/static/images/upload/' . str_replace("\\", "/" ,$path);  
         
         // var_dump($path);die();
         if(session('?username'))
         {
            $username = session('username');
            $head = Douser::checkHead($username,$path);
            if($head)
            {
                $this->success('上传成功','index/index/account');
            }else{  
                $this->error('上传失败','index/index/account');  
             }  

         }
  
        }else{  
            $this->error('上传失败','account');  
        }  
      }else{
            $this->error('上传失败','account');  
      }
    }  

    public function changeinfo()
    {
       $request = Request::instance();
        $realname = $request->param('realname');
        $nickname = $request->param('nickname');
        $birthday = $request->param('birthday');
        $sex = $request->param('sex');
        $mobile = $request->param('mobile');
        $address = $request->param('address');
        $addrinfo = $request->param('addrinfo');
        $email = $request->param('email');
         $change = Douser::checkChange($realname, $nickname, $birthday, $sex, $mobile, $address, $addrinfo, $email);
        if($change)
        {
            $data = ['err' => 1];
        }else{
            $data = ['err' => 0];
        }
        echo json_encode($data);
    }
    public function email()
    {
        if(session('?u_id'))
        {
            $request = Request::instance();
            $email = $request->param('email');
            $u_id = session('u_id')['u_id'];
            $username = Douser::username($u_id)['username'];
            // dump($email);dump($u_id);dump($username);die();
            //这步没必要ajax了，可以直接调用下面的方法···
            $save = Douser::setEmail($u_id, $email);
            if($save)
            {
                 $data = ['err' => 1,'username' => $username];
            }else{
                $data = ['err' => 0,'username' => $username];
            }
            echo json_encode($data);
        }
        
    }
   public function sendEmail()
   {
    $request = Request::instance();
    $username = $request->param('username');
    $email = $request->param('email');
    $token = md5($username); //创建用于激活识别码
    $token_exptime = time()+60*60*24;//过期时间为24小时后

    if(session('?u_id'))
    {
        $u_id = session('u_id')['u_id'];
        $data = ['token' => $token, 'token_exptime' => $token_exptime, 'u_id' => $u_id, 'email' => $email];
    }else{
        $data = false;
    }
    
    if($data)
    {
        $send = Sendemail::sendEmail($data);
        // $MailServer = "smtp.qq.com"; //SMTP服务器
        // $MailPort = 465; //SMTP服务器端口
        // $smtpMail = "2420119440@qq.com"; //SMTP服务器的用户邮箱
        // $smtpuser = "2420119440@qq.com"; //SMTP服务器的用户帐号
        // $smtppass = "wqikxgocsirsdjeg"; //SMTP服务器的用户密码

        $MailServer = "smtp.163.com"; //SMTP服务器
        $MailPort = 25; //SMTP服务器端口
        $smtpMail = "hjl416148489_4@163.com"; //SMTP服务器的用户邮箱
        $smtpuser = "hjl416148489_4@163.com"; //SMTP服务器的用户帐号
        $smtppass = "hjl7233163"; //SMTP服务器的用户密码
        
        //创建$smtp对象 这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp = new Smtp($MailServer, $MailPort, $smtpuser, $smtppass, true); 
        $smtp->debug = false; 
        $mailType = "HTML"; //信件类型，文本:text；网页：HTML
        $emailTitle = "帐号邮箱激活"; //邮件主题
        $emailBody = "亲爱的".$username."：<br/>感谢您在我站注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='http://store.mociojove.com/index/index/active?verify=".$token."' target='_blank'>http://www.store.com/index/index/active?verify=".$token."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>敬上</p>";
        
        // sendmail方法
        // 参数1是收件人邮箱
        // 参数2是发件人邮箱
        // 参数3是主题（标题）
        // 参数4是邮件主题（标题）
        // 参数4是邮件内容  参数是内容类型文本:text 网页:HTML
        $rs = $smtp->sendmail($email, $smtpMail, $emailTitle, $emailBody, $mailType);
        if($rs==true){
        echo '<script>alert("恭喜您，注册成功！请登录到您的邮箱及时激活您的帐号！");window.history.go(-1);</script>';
        $senderr = ['err' => 1, 'info' => '发送成功'];
        }else{
            $senderr = ['err' => 0, 'info' => '发送失败'];
            echo "注册失败";
        }
    }
        echo json_encode($senderr);
   }

   public function music()
   {

    $request = Request::instance();
    header("Content-type: text/html; charset=utf-8");
    set_time_limit(0);
    $ch_1 = curl_init(); 
    @$page=$request->param('page');
    @$pagesize=$request->param('pagesize');
    @$keyword=$request->param('musicname');
    if(empty($page)){$page=1;}
    if(empty($pagesize)){$pagesize=30;}
    if(empty($keyword)){exit('没有输入');}
    $temp_get='format=json&keyword='.$keyword.'&page='.$page.'&pagesize='.$pagesize;
    curl_setopt ($ch_1, CURLOPT_URL,"http://mobilecdn.kugou.com/api/v3/search/song?".$temp_get);
    curl_setopt ($ch_1, CURLOPT_RETURNTRANSFER, 1);
    $a = curl_setopt ($ch_1, CURLOPT_CONNECTTIMEOUT, 10);
    $query_array=json_decode(curl_exec($ch_1),true);
    dump($query_array);die();
    curl_close($ch_1); 
    $__data=array();$__i=0;
    foreach ($query_array['data']['info'] as $__id => $__d) 
    {
    $__i++;
        foreach ($query_array['data']['info'][$__id] as $__n => $__v) 
        {
            if($__n=='filename')
                {
                    @$__data[$__i]['songdata']=$__v;
                }
            if($__n=='singername')
            {
                //匹配歌手头像
                $ch_2 = curl_init(); 
                curl_setopt ($ch_2, CURLOPT_URL,"http://m.kugou.com/app/i/getSingerHead_new.php?singerName=".$__v);
                curl_setopt ($ch_2, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt ($ch_2, CURLOPT_CONNECTTIMEOUT, 10); 
                $head_data=json_decode(curl_exec($ch_2),true);
                curl_close($ch_2);
                @$__data[$__i]['head']=$head_data['url'];
            }
            if($__n=='hash')
            {
                //根据hash搜索播放链接
                $ch_3 = curl_init();
                curl_setopt ($ch_3, CURLOPT_URL,"http://m.kugou.com/app/i/getSongInfo.php?cmd=playInfo&hash=".$__v);
                curl_setopt ($ch_3, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt ($ch_3, CURLOPT_CONNECTTIMEOUT, 10); 
                $play_data=json_decode(curl_exec($ch_3),true);
                curl_close($ch_3); 
                @$__data[$__i]['url']=$play_data['url'];
                //匹配歌词
                $ch_4 = curl_init(); 
                $temp_get='cmd=100&keyword='.$play_data['fileName'].'&hash='.$play_data['hash'].'&timelength='.$play_data['timeLength'].'000&d=0.'.rand(1000,9999);
                curl_setopt ($ch_4, CURLOPT_URL,"http://m.kugou.com/app/i/krc.php?".$temp_get);
                curl_setopt ($ch_4, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt ($ch_4, CURLOPT_CONNECTTIMEOUT, 10); 
                @$__data[$__i]['lrc']= curl_exec($ch_4); 
                curl_close($ch_4);
            }
    }
    }
    #print_r($__data);
    echo json_encode($__data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    #不转义斜杠和中文字符（php_5.5+）
    }

    public function weather()
    {
        $this->info('9962efa491f726d64cf456b997736d77');
    }


     //获得天气信息
    public function info($key)
    {
                                         //$key = '你在高德申请的秘钥';
        $ipInfo = $this->ipInfo($key);  //调用方法获得 Ip 定位信息;
        $city = $ipInfo->adcode;        //获得adcode;
        $weatherInfo = $this->weatherInfo($key, $city); //已经获取了天气信息;
        echo json_encode($weatherInfo);
    }

    //定位信息
    public function ipInfo($key)
        {
            $ch = curl_init("http://restapi.amap.com/v3/ip?key=".$key);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // 请求的数据不直接发送到浏览器
            $result = curl_exec($ch);    //执行请求,直接发送到浏览器
            // $city = json_decode($result)->adcode;
            $info = json_decode($result);
            return $info;
        }
    //天气信息
        public function weatherInfo($key, $city)
        {
            $ch = curl_init("http://restapi.amap.com/v3/weather/weatherInfo?city=" . $city ."&key=" . $key);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $info = json_decode($result)->lives[0];
            return $info;
        }
        public function ems()
        {
            $request = Request::instance();
            $ems_id = $request->param('ems_id');
            $host = "http://jisukdcx.market.alicloudapi.com";
            $path = "/express/query";
            $method = "GET";
            $appcode = "a4ffec45ba524119a0d3aa82dda9d81d";
            $headers = array();
            array_push($headers, "Authorization:APPCODE " . $appcode);
            $querys = "number=$ems_id&type=auto";
            $bodys = "";
            $url = $host . $path . "?" . $querys;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_FAILONERROR, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, true);
            if (1 == strpos("$".$host, "https://"))
            {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }
            $curl = curl_exec($curl);
            $curl = strstr($curl,'{');
            $curl = json_encode($curl);
            // var_dump($curl);die();
            echo $curl;

        }
}