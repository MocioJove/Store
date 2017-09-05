<?php
namespace app\index\controller;
use think\Request;
use think\Controller;
use app\index\model\User as DoUser;
use think\captcha\Captcha;
use app\index\model\Smtp;
use app\index\model\Cart;
use app\index\model\Goodsaddr;
use app\index\model\Comments;
use app\index\model\Goods as modelGoods;
use app\index\model\Sendemail;
use think\Session;
class Goods extends Controller
{
	public function cart()
	{
		//没登陆时通过session构造购物车
		if(!session('?u_id'))
		{

		$request = Request::instance();
		if($request->param('goods_id'))
		{
			$goods_id = $request->param('goods_id');
			$goods_num = $request->param('goods_num');
			

			if(!session('?goods'))
			{
			    //1.购物车是空的，第一次点击添加购物车
			    $arr = array(
			        array($goods_id,$goods_num)
			    );
			    session('goods',$arr);
				$data = ['err' => 1,'info'=>'加入购物车成功','num' => $goods_num];
			}
			else
			{
			    //不是第一次点击
			    //判断购物车中是否存在该商品
			    $arr = session('goods'); //先存一下
			 
			    $chuxian = false;
			    foreach($arr as $v)
			    {
			        if($v[0]==$goods_id)
			        {
			            $chuxian = true;
			        }
			    }
			 
			    if($chuxian)
			    {
			        //3.如果购物车中有该商品
			 
			        for($i=0;$i<count($arr);$i++)
			        {
			            if($arr[$i][0]==$goods_id)
			            {
			                $arr[$i][1]+=1;
			            }
			        }
			        session('goods',$arr);
					$data = ['err' => 1,'info'=>'加入购物车成功','num' => $goods_num];

			    }
			    else
			    {
			        //2.如果购物车中没有该商品
			        $asg = array($goods_id,$goods_num);
			        $arr[] = $asg;
			        session('goods',$arr);
					$data = ['err' => 1,'info'=>'加入购物车成功','num' => $goods_num];

			    }
	 
			}
				
		}else{
			$data = ['err' => 0,'info'=>'加入购物车失败','num' => $goods_num];
		}
		// echo json_encode($data);
		}else{
			$request = Request::instance();
			$data['u_id'] = session('u_id')['u_id'];
			$data['goods_id'] = $request->param('goods_id');
			$data['goods_num'] = $request->param('goods_num');
			if(Cart::findGoods($data['goods_id']))
			{
				$data = ['err' => 0,'info'=>'购物车里已经有了','num' => $data['goods_num']];
			}else{
				$info = Cart::cart($data);
				if($info)
				{
					$data = ['err' => 1,'info'=>'加入购物车成功','num' => $data['goods_num']];
				}else{
				$data = ['err' => 0,'info'=>'加入购物车失败','num' => $data['goods_num']];
				}
			
			}
		}
		echo json_encode($data);

		
	}

	public function buy()
	{
		if(!session('?u_id'))
		{
			$request = Request::instance();
		if($request->param('goods_id'))
		{
			$goods_id = $request->param('goods_id');
			$goods_num = $request->param('goods_num');
			
			if(!session('?goods'))
			{
			    //1.购物车是空的，第一次点击添加购物车
			    $arr = array(
			        array($goods_id,$goods_num)
			    );
			    session('goods',$arr);
				$data = ['err' => 1,'info'=>'加入购物车成功','num' => $goods_num];
			}
			else
			{
			    //不是第一次点击
			    //判断购物车中是否存在该商品
			    $arr = session('goods'); //先存一下
			 
			    $chuxian = false;
			    foreach($arr as $v)
			    {
			        if($v[0]==$goods_id)
			        {
			            $chuxian = true;
			        }
			    }
			 
			    if($chuxian)
			    {
			        //3.如果购物车中有该商品			 
			        for($i=0;$i<count($arr);$i++)
			        {
			            if($arr[$i][0]==$goods_id)
			            {
			                $arr[$i][1]+=1;
			            }
			        }
			        session('goods',$arr);
					$data = ['err' => 1,'info'=>'加入购物车成功','num' => $goods_num];
			    }
			    else
			    {
			        //2.如果购物车中没有该商品
			        $asg = array($goods_id,$goods_num);
			        $arr[] = $asg;
			        session('goods',$arr);
					$data = ['err' => 1,'info'=>'加入购物车成功','num' => $goods_num];
			    }	 
			}				
		}else{
			$data = ['err' => 0,'info'=>'加入购物车失败','num' => $goods_num];
		}
		
		}else{
			$request = Request::instance();
			$data['u_id'] = session('u_id')['u_id'];
			$data['goods_id'] = $request->param('goods_id');
			$data['goods_num'] = $request->param('goods_num');
			if(Cart::findGoods($data['goods_id']))
			{
				$data = ['err' => 0,'info'=>'购物车里已经有了','num' => $data['goods_num']];
			}else{
				$info = Cart::cart($data);
				if($info)
				{
					$data = ['err' => 1,'info'=>'加入购物车成功','num' => $data['goods_num']];
				}else{
				$data = ['err' => 0,'info'=>'加入购物车失败','num' => $data['goods_num']];
				}
			
			}
		}
		echo json_encode($data);
	}
	//购物车里的东西
	static public function cartinfo()
	{
		if(session('?goods'))
		{
			return session('goods');
		}else{
			return false;
		}
	}
//支付 购物车货物加入订单 如果支付应该删除购物车对应物品 订单状态改变
	public function pay()
	{
		
		if(session('?u_id'))
		{
			$u_id = session('u_id');
			$request = Request::instance();
			$goods_id = $request->param('goods_id');
			$addr = Goodsaddr::info((int)$u_id['u_id']);
			$addr = join(',',$addr[0]);
			// dump($addr);die();
			$number = explode(',', $request->param('number'));
			$goods_id = explode(',', $goods_id);
			// dump($goods_id);die();
			foreach ($goods_id as $key => $value) {
				$time = time();
				$code = substr(md5(time()),16);
				$data = ['u_id' => (int)$u_id['u_id'],'goods_id' => (int)$value,'number' => (int)$number[$key],'create_time' => $time, 'order_code' => substr(md5(time().$key),16),'address' => $addr,'status' => null];
				// dump($data);
				$order = modelGoods::checkOrder($data);
				if($order)
				{
					// 加入订单，清除购物车
					Session::delete('goods');
					Cart::cartDel((int)$value);
					$info = ['err' => 1,'info' => '加入订单成功'];
				}else{
					$info = ['err' => 0,'info' => '加入订单失败'];
				}
			}
		}else{
			$info = ['err' => 0,'info' => '您还没有登录呢'];
		}
			echo json_encode($info);
	}
	//删除购物车选中的部分
	public function delcart()
	{
			$request = Request::instance();
			$goods_id = $request->param('goods_id');
			// dump($goods_id);die();
			if($goods_id)
			{
				$goods_id = explode(',', $goods_id);
				if(session('?goods'))
				{
					// dump(session('goods'));die();
					$goods_cart = session('goods')[0];
					$goods_num = session('goods')[1];
					foreach ($goods_cart as $key => $value) {
						if(in_array($value,$goods_id))
						{
							unset($goods_cart[$key]);
							unset($goods_num[$key]);
							$arr = array($goods_cart,$goods_num);
							// session('goods',$arr);
							// dump(session('goods'));
							$info = ['err'=>1,'info'=>'删除成功'];
						}else{
							$arr = session('goods');
						}
					}
					// dump($arr);die();
					session('goods',$arr);
				}
		}
		echo json_encode($info);
	}

	public function cartdel()
	{
			$request = Request::instance();
			$goods_id = $request->param('goods_id');
			// dump($goods_id);die();
			if($goods_id)
			{
				$goods_id = explode(',', $goods_id);
				foreach ($goods_id as $key => $value) 
				{
					// dump($value);
					if(Cart::findGoods((int)$value))
					{
						if(Cart::cartdel((int)$value))
						{
							$data = ['err' => 1];
						}else{
							$data = ['err' => 0];
						}
					}
				}				
			}
		echo json_encode($data);
	}
	public function comments()
	{
		if(session('?u_id'))
		{
			$request = Request::instance();
			if($request->param('goods_id'))
			{
				$data['u_id'] = session('u_id')['u_id'];
				$data['title'] = $request->param('title');
				$data['goods_id'] = (int)$request->param('goods_id');
				$data['comments'] = $request->param('comments');
				$data['grade'] = (int)$request->param('grade');
				$data['time'] = time();
			}else{
				$data = [];
			}
			// dump($data);die();
			if(Comments::comments($data))
			{
				$data = ['err' => 1];
			}else{
				$data = ['err' => 0];
			}
		}else{
			$data = ['err' => 0];
		}
		echo json_encode($data);
	}
	
}