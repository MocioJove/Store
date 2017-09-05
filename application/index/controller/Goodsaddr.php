<?php
namespace app\index\controller;
use think\Request;
use think\Controller;
use app\index\model\User as DoUser;
use think\captcha\Captcha;

class Goodsaddr extends Controller
{
	protected $user;

	
    //添加收获地址
     public function goodsaddr()
    {
        $request = Request::instance();
        $mobile = $request->param('mobile');
        $address = $request->param('address');
        $addrinfo = $request->param('addrinfo');
        $accept = $request->param('accept');
        if(session('?u_id'))
        {
            $u_id = session('u_id')['u_id'];
            $count = Douser::goodsaddrCount($u_id);
            if($count < 6)
            {
                $data = ['goodsaddrss' => $address, 'goodsaddrinfo' => $addrinfo, 'goodsmobile' => $mobile, 'accept' => $accept, 'u_id' => $u_id];
                $change = Douser::goodsAddr($data);
                if($change)
                {
                    $data = ['err' => 1];
                }else{
                    $data = ['err' => 0];
                }
                echo json_encode($data);
            }else{
                $this->error('添加达到上限','address');
            }

            
        }
        
    }

    public function goodsaddrchg()
    {
    	$request = Request::instance();
    	// dump($request);die();
        $mobile = $request->param('mobile');
        $address = $request->param('address');
        $addrinfo = $request->param('addrinfo');
        $accept = $request->param('accept');
        $goodsaddr_id = $request->param('goodsaddr_id');
        $goodsaddr_id = (int)$goodsaddr_id;
    	// dump($request);die();
        // var_dump($goodsaddr_id);die();
        $change = Douser::user_goodsaddrChg($goodsaddr_id,$address,$addrinfo,$mobile,$accept);

        if($change)
        {
            $data = ['err' => 1];
        }else{
            $data = ['err' => 0];
        }
        echo json_encode($data);
    }
}