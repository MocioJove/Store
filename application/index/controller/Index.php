<?php
namespace app\index\controller;
use think\Request;
use think\Controller;
use app\index\model\User;
use app\index\model\Businessman;
use app\index\model\Goodsaddr;
use app\index\model\Goods;
use app\index\model\Order;
use app\index\model\Cart;
use app\index\model\Comments;
use app\index\model\Category;
use app\index\Controller\Goods as Goodscart;
use app\index\model\Classify;
use app\index\controller\Auth;
use traits\model\SoftDelete;
use app\index\model\Smtp;
use app\index\model\Collect;
use app\index\model\Sendemail;
use think\helper\Time;
use think\Cookie;
use \think\Session;
use app\index\model\History;
class Index extends Controller
{
    protected $is_login = ['*'];
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $updateTime = 'update_time';
    public function share()
    {
      return $this->fetch();
    }
     public function test()
    {
      return $this->fetch();
    }
    public function regsuc()
    {
         $this->success('注册成功','index/index/login');
    }
    public function regerr()
    {
         session(null, 'think');
         $this->error('注册失败','index/index/reg');
    }

     public function logsuc()
    {
         $this->success('欢迎回来','index/index/index');
    }

    public function logout()
    {
         session(null, 'think');
         $this->success('退出成功','index/index/login');
    }
    public function repwd()
    {
      $this->success('修改密码成功','index/index/login');
    }

     public function wrongrepwd()
    {
      $this->error('修改密码失败','index/index/login');
    }

    public function changesuc()
    {
      $this->success('修改成功','index/index/account');
    }
    public function changeerr()
    {
      $this->error('修改失败','index/index/account');
    }
    public function goodsaddrsuc()
    {
      $this->success('添加成功','index/index/address');
    }
    public function  goodsaddrerr()
    {
      $this->error('添加失败','index/index/address');
    }
    
    public function goodsaddrchgsuc()
    {
      $this->success('修改成功','index/index/address');
    }
    public function goodsaddrchgerr()
    {
      $this->error('修改失败','index/index/address');
    }

    public function goodsaddrdelsuc()
    {
      $this->success('删除成功','index/index/address');
    }
    public function goodsaddrdelerr()
    {
      $this->error('删除失败','index/index/address');
    }
    
    public function goodsaddrdefaultsuc()
    {
      $this->success('设置成功','index/index/address');
    }
    public function goodsaddrdefaulterr()
    {
      $this->error('设置失败','index/index/address');
    }
    public function index()
    {
      $category_first = Category::category_first();
      $select = Category::category();
      $category = new Category();

      $category = $category->make_tree($select);
      // dump($category);die();
      // echo '<pre>';
      // print_r($category);
      $this->assign('category',$category); 
      // $this->assign('class',$class); 
      $data = [];
      foreach ($category_first as $key => $value)
      {
        // var_dump($value['classname']);
        $classname = $value['classname'];
        $class_id = $value['class_id'];

        $categoryfunc = new Category();
        $category = $categoryfunc->Category_find($value['class_id']);
        $arr = [];
        foreach ($category as $key => $value) {
          $arr[] = $categoryfunc->Category_find($value['class_id']);
        }
        $arrb = [];
        foreach ($arr as $key => $value) {
          foreach ($value as $k => $v) {
            $arrb[] = $v['class_id'];
          }
        }
        // dump($arrb);die();
        $goods = Goods::checkFirst($arrb);

        $right = Goods::checkRight($arrb);
        $left = Goods::checkleft($arrb);
        // dump($right);die;
        // dump($goods);die();
        // $classname = explode(',', $classname);
        $data['class_id'] = $class_id;
        $data['classname'] = $classname;
        $data['right'] = $right['photopath'];
        $data['left'] = $left['photopath'];
        $data['goods'] = $goods;
        $topclass[] = $data;
      }
      // dump($topclass);die();
      //用户头像
      $info = User::checkInfo();
      //替换
      $this->assign('headphoto',$info['headphoto']);
      $this->assign('topclass',$topclass);
      //一级板块 直接里边的商品
    	return $this->fetch();
    }

    public function cart()
    {
      if(!session('?u_id'))
      {
        $arr = [];
        $arrb = [];
      
      // dump($info);die();
      //查找session里存储的东西
      if(session('?goods'))
      {
        $info = session('goods');
        if(!empty(session('goods')[0]))
        {
          $info = session('goods');
        foreach ($info as $key => $value) {
          //遍历取出session里的goodsid
          $arr[] = $value[0];
          $arrb[] = (int)$value[1];
        }
        // dump($arrb);die();
        $arra = [];
        //根据session的goodsid查找商品
        foreach ($arr as $key => $value) {
          $arra[] = Goods::checkGoods((int)$value);
        }
        // dump($arra);die();
        // $arrc = array_merge($arra,$arrb);
        // dump($arrc);die();
        $arrc = [];
        foreach ($arra as $key => $value) {
          $value['num'] =  $arrb[$key];
          $arrc[] = $value;
        }
        // die();
        // dump($arrc);die();

        $this->assign('arr',$arr);
        $this->assign('arra',$arra);
        $this->assign('arrb',(int)$arrb);
        if($arrc)
        {
          $this->assign('arrc',$arrc);
      }else{
          $this->assign('arrc',false);
      }
        }
        
      }else{
        $this->assign('cart',false);
      }
      return $this->fetch();
      }else{
         $u_id = session('u_id')['u_id'];
          $arrb = [];
        if(session('?goods'))
        {
          $arrb = session('goods');
          
          // dump($arrb);die();
          $arre = [];
          foreach ($arrb as $key => $value) {
            $data['goods_id'] = (int)$value[0];
            $data['goodsname'] = Goods::checkGoods($data['goods_id'])['goodsname'];
            $data['photo'] = Goods::checkGoods($data['goods_id'])['photopath'];
            $data['price'] = Goods::checkGoods($data['goods_id'])['price'];
            $data['goods_num'] = (int)$value[1];
            $data['u_id'] = $u_id;
            $arre[] = $data;
            $info['goods_id'] = (int)$value[0];
            $info['goods_num'] = (int)$value[1];
            $info['u_id'] = $u_id;
            if(!Cart::findGoods($info['goods_id']))
            {
                $collect = Cart::cartAdd($info);
                session::delete('goods');
            }
          }
          // dump($arre);die();
          
        }
        //购物车信息
        $arrz = [];
         $u_id = session('u_id')['u_id'];
          $info = Cart::findCart($u_id);
          // dump($info);die();
          foreach ($info as $key => $value) {
            $arr['goodsname'] = Goods::checkGoods((int)$value['goods_id'])['goodsname'];
            $arr['price'] = Goods::checkGoods((int)$value['goods_id'])['price'];
            $arr['photo'] = Goods::checkGoods((int)$value['goods_id'])['photopath'];
            $arr['num'] = $value['goods_num'];
            $arr['goods_id'] = $value['goods_id'];
            $arr['car_id'] = $value['goods_id'];
            $arrz[] = $arr;
          }


            $count = User::goodsaddrCount($u_id);
            if($count < 5)
            {
              $this->assign('add',true);
            }else{
              $this->assign('add',false);
            }

        $info = User::goodsaddrInfo($u_id);
        // dump($info);
        $this->assign('addinfo',$info);
        $this->assign('count',$count);
          // dump($arrz);die();
          $this->assign('info',$arrz);
      }
      return $this->fetch();
    }

    public function findpwd()
    {
      return $this->fetch();
    }

    public function first()
    {
      
      $request = Request::instance();
      if($request->param('class_id'))
      {
        $categoryfunc = new Category();
        $category = $categoryfunc->Category_find($request->param('class_id'));
        $arr = [];
        foreach ($category as $key => $value) {
          $arr[] = $categoryfunc->Category_find($value['class_id']);
        }
        $arrb = [];
        foreach ($arr as $key => $value) {
          foreach ($value as $k => $v) {
            $arrb[] = $v['class_id'];
          }
        }
        // dump($arrb);die();
        $goods = Goods::firstGoods($arrb);
        // dump($goods);die();
        $classname = Category::classFind($request->param('class_id'))['classname'];
        $class_id = Category::classFind($request->param('class_id'))['class_id'];
        
        $secend_class = Category::classSecond($request->param('class_id'));
        
        $this->assign('classname',$classname);
        $this->assign('goods',$goods);
        $this->assign('class_id',$class_id);
        $this->assign('secend_class',$secend_class);

        return $this->fetch();
      }
      
    }
     public function secend()
    {
      $request = Request::instance();
      if($request->param('class_id'))
      {
        $categoryfunc = new Category();
        $category = $categoryfunc->Category_find($request->param('class_id'));
        $arr = [];
        foreach ($category as $key => $value) {
          $arr[] = $value['class_id'];
        }
        $classname = Category::classFind($request->param('class_id'))['classname'];
        $parent_classname = Category::classSecond_parent($request->param('class_id'))[0];
        // dump($classname);die();
        $secend_class = Category::classSecond($request->param('class_id'));
        // dump($secend_class);die();

        // dump($arr);die();
        $goods = Goods::secendGoods($arr);

        // dump($goods);die();
        $this->assign('class_id',$request->param('class_id'));
        $this->assign('goods',$goods);
        $this->assign('classname',$classname);
        $this->assign('secend_class',$secend_class);
        $this->assign('parent_classname',$parent_classname);

        return $this->fetch();
      }
    }
    public function third()
    {
      $request = Request::instance();
      if($request->param('class_id'))
      {
        $goods = Goods::thirdGoods($request->param('class_id'));
        $classname = Category::classFind($request->param('class_id'));

        $class_secend = Category::parentid($request->param('class_id'))['parentid'];

        $classname_secend = Category::classFind($class_secend);
        $classname_secend_id = Category::classFind($class_secend)['class_id'];
        $class_secend = Category::parentid($classname_secend_id)['parentid'];

        $class_first = Category::parentid($class_secend);
        
        $this->assign('goods',$goods);
        $this->assign('classname_secend',$classname_secend);
        $this->assign('classname',$classname);
        $this->assign('class_first',$class_first);
        return $this->fetch();
      }
    }
 
    public function fourth()
    {
      $request = Request::instance();
      if($request->param('goods_id'))
      {
        if(session('?u_id'))
        {
          $data['goods_id'] = $request->param('goods_id');
          $data['u_id'] = session('u_id')['u_id'];
          $data['time'] = date('Y-m-d H:i:s',time());

          $goods = History::userFind($data['u_id']);
          // dump($goods);die();
          $id = [];
          $time = [];
          if(!empty($goods))
          {
              // die();
              // dump($goods);die();
              foreach ($goods as $key => $value) {
                //3天
                $id[] = $value['goods_id'];
                $time[] = $value['time'];
                // dump($value['goods_id']);
                // if(time() - $value['time'] > 300)
                // {
                //   $id[] = $value['goods_id'];
                // }
              }
              // dump($id);die();
              if(!in_array($data['goods_id'],$id))
              {
                  History::historyadd($data);
              }else{
                  History::upOld($data['goods_id'],date('Y-m-d H:i:s',time()));
              }
          }else{
            History::historyadd($data);
          }
          $this->assign('goods',$goods);
        }
        
        $id = $request->param('goods_id');
        // dump((int)$id);die();
        $comment = new Comments();
        $comments = $comment->comments_goods((int)$id);
        $count = $comment->comments_count((int)$id);
        $count_good = $comment->comments_count_good((int)$id);
        $count_mid = $comment->comments_count_mid((int)$id);
        $count_bad = $comment->comments_count_bad((int)$id);
        // dump($count);die();
        $arr = [];
        foreach ($comments as $key => $value) {
          $reply['username'] = User::userInfo($value['u_id'])['username'];
          $reply['photo'] = User::userInfo($value['u_id'])['headphoto'];
          $reply['grade'] = $value['grade'];
          $reply['title'] = $value['title'];
          $reply['time'] = date('Y-m-d H:i:s',$value['time']);
          $reply['comments'] = $value['comments'];
          $arr[] = $reply;
        }
        // dump($count);
        // die();
        $class_id = Goods::checkGoods($id)['class_id'];
        // dump($class_id);die();
        $classname = Category::classFind($class_id);
        // dump($classname);die();
        $class_secend = Category::parentid($class_id)['parentid'];

        $classname_secend = Category::classFind($class_secend);
        $classname_secend_id = Category::classFind($class_secend)['class_id'];
        $class_secend = Category::parentid($classname_secend_id)['parentid'];

        $class_first = Category::parentid($class_secend);
        
        $this->assign('classname_secend',$classname_secend);
        $this->assign('classname',$classname);
        $this->assign('class_first',$class_first);


        $goods = Goods::checkGoods($id);
        $this->assign('goods',$goods);

        // dump($goods);die();
        $this->assign('name',$goods['goodsname']);
        $this->assign('photo',$goods['photopath']);
        $this->assign('comments',$arr);
        $this->assign('page',$comments);
        $this->assign('grade_count',$count);
        $this->assign('count_good',$count_good);
        $this->assign('count_mid',$count_mid);
        $this->assign('count_bad',$count_bad);
        $this->assign('price',$goods['price']);
        $this->assign('editorValue',$goods['editorValue']);
        $this->assign('count',$goods['count']);
        $this->assign('goods_id',$id);
        // dump($goods);die();
        return $this->fetch();
      }
    }


    public function freight()
    {
      return $this->fetch();
    }
    public function pay()
    {
      if(session('?u_id'))
      {
        $u_id = session('u_id')['u_id'];
        $time = time() - 30;
        $info = Order::payOrder($u_id,$time);
        $msg = [];
        foreach ($info as $key => $value) {
           $data['goodsname'] = Goods::checkGoods($value['goods_id'])['goodsname'];
           $data['photo'] = Goods::checkGoods($value['goods_id'])['photopath'];

           $data['price'] = Goods::checkGoods($value['goods_id'])['price'];
           $data['goods_id'] = Goods::checkGoods($value['goods_id'])['goods_id'];
           $data['order_id'] = $value['order_id'];
           $data['num'] = $value['number'];
           $msg[] = $data;
           $this->assign('info',$msg);
        }
        // die();
        // dump($msg);die();
        return $this->fetch();
      }
      
    }

    public function secendPay()
    {
      $request = Request::instance();
      if($request->param('order_id'))
      {
        $order = Order::singleOrder($request->param('order_id'));
        // dump($order);
            $data['goods_id'] = $order['goods_id'];
            $data['goodsname'] = Goods::checkGoods($data['goods_id'])['goodsname'];
            $data['photo'] = Goods::checkGoods($data['goods_id'])['photopath'];
            $data['price'] = Goods::checkGoods($data['goods_id'])['price'];
            $data['num'] = $order['number'];
            $data['order_id'] = $request->param('order_id');
            // dump($data);die();
            $this->assign('value',$data);
      }
        return $this->fetch();
    }
    public function guide()
    {
      return $this->fetch();
    }
    //支付 清除订单里未支付的
    public function paypwd()
    {
      // dump(substr(md5(time().'1')),12);die();
      if(session('?u_id'))
      {
        $u_id = session('u_id')['u_id'];
        $request = Request::instance();
        if($request->param('money'))
        {
          //钱够才执行 不够不执行 执行完 更改订单状态和剩余金钱
          $over = User::checkMoney(session('u_id')['u_id'])['money'];
          $money = (float)$request->param('money');
              // dump($request->param('order_id'));die();

          if($over - $money > 0)
          {
            if($request->param('order_id'))
            {
              $order_id = explode(',', $request->param('order_id'));
              foreach ($order_id as $key => $value) {
                // dump(substr(md5(time().$key),20));die();
                Order::orderCode((int)$value,substr(md5(time().$key),20));
                Order::moneyOrder((int)$value);
              }
              $money = $over - $money;
              if(User::userpay($u_id,$money))
              {
                $data = ['err' => 1];
              }else{
                $data = ['err' => 0];
              }
            }else{
                $data = ['err' => 0];
              }
          }else{
            $data = ['err' => 0];
          }
          echo json_encode($data);
        }
        
        // die();
      }
      
    }

    public function paysingle()
    {
      if(session('?u_id'))
      {
        $u_id = session('u_id')['u_id'];
        if($request = Request::instance('order_id'))
        {
            $order_id = $request->param('order_id');
            $num = $request->param('num');
            // dump($request->param('num'));die();
            $over = User::checkMoney(session('u_id')['u_id'])['money'];
            $money = (float)$request->param('price')*$request->param('num');
            if($over - $money > 0)
            {
              Order::orderCode((int)$order_id,substr(md5(time().$order_id),20));
              Order::moneyOrder((int)$order_id);
              $money = $over - $money;
            }
            if(User::userpay($u_id,$money))
            {
              $this->success('购买成功','order');
            }else{
              $this->error('购买失败');
            }
            // User::userpay($u_id,$money);
        }

      }
    }
    public function help()
    {
      return $this->fetch();
    }
    public function login()
    {
      return $this->fetch();
    }
    public function order()
    {
      // $a = Order::checkOrder_bycode();
      // dump($a);die();
      $goods_id = [];
      $order_id = [];
      $order_num = [];
      $order_info = [];
      if(session('?u_id'))
      {
        $order = Order::checkOrder(session('u_id')['u_id']);
        foreach ($order as $key => $value) {
          $goods_id[] = $value['goods_id'];
          $order_id[] = $value['order_id'];
          $order_comments[] = $value['comments'];
          $order_num[] = $value['number'];
          $order_time[] = date('Y-m-d H:i:s',$value['create_time']);
          $order_code[] = $value['order_code'];
          $order_acept[] = $value['acept'];
          $order_status[] = $value['status'];
          $order_refund[] = $value['refund'];
             // dump($value['goods_id']);
        }
       foreach ($goods_id as $key => $value) {
         $info = Goods::checkGoods($value);
         // dump($info);
         $order_info[] = ['order_id' => $order_id[$key],'order_num' => $order_num[$key],'order_name' => $info['goodsname'],'price' => $info['price'],'create_time' => $order_time[$key],'order_code' => $order_code[$key],'goods_id' => $info['goods_id'],'photo' => $info['photopath'],'code' => $order_code[$key],'acept' => $order_acept[$key],'refund' => $order_refund[$key],'comments' => $order_comments[$key],'status' => $order_status[$key]];
       }
       // dump($order_info);
        $this->assign('order',$order_info);
        $this->assign('info',$order);
      }
      return $this->fetch();
    }

    public function acept()
    {
        $request = Request::instance();
        if($request->param('order_id'))
        {
          if(Order::reorder_acept($request->param('order_id')))
          {
            $this->success('确认收货');
          }else{
            $this->error('确认失败');
          }
        }
    }
    public function orderdel()
    {
      if(session('?u_id'))
      {
        $u_id = session('u_id')['u_id'];
        $request = Request::instance();
        if($request->param('order_id'))
        {
          $order_id = $request->param('order_id');
          if(Order::delOrder($order_id))
          {
            $this->success('删除成功');
          }else{
            $this->errir('删除失败');
          }

        }
      }
      
    }

    public function orderdel_real()
    {
      if(session('?u_id'))
      {
        $u_id = session('u_id')['u_id'];
        $request = Request::instance();
        if($request->param('order_id'))
        {
          $order_id = $request->param('order_id');
          if(Order::delOrder_real($order_id))
          {
            $this->success('删除成功');
          }else{
            $this->errir('删除失败');
          }

        }
      }
      
    }
    public function orderdel_nopay()
    {
      if(session('?u_id'))
      {
        $u_id = session('u_id')['u_id'];
        $request = Request::instance();
        if($request->param('order_id'))
        {
          $order_id = $request->param('order_id');
          if(Order::delOrder($order_id))
          {
            $this->success('删除成功');
          }else{
            $this->errir('删除失败');
          }

        }
      }
    }

    public function order_nopay()
    {
      // $a = Order::checkOrder_bycode();
      // dump($a);die();
      $goods_id = [];
      $order_id = [];
      $order_num = [];
      $order_info = [];
      if(session('?u_id'))
      {
        $order = Order::checkOrder_nopay(session('u_id')['u_id']);
        foreach ($order as $key => $value) {
          $goods_id[] = $value['goods_id'];
          $order_id[] = $value['order_id'];
          $order_num[] = $value['number'];
          $order_time[] = date('Y-m-d H:i:s',$value['create_time']);
          $order_code[] = $value['order_code'];
             // dump($value['goods_id']);
        }
       foreach ($goods_id as $key => $value) {
         $info = Goods::checkGoods($value);
         // dump($info);
         $order_info[] = ['order_id' => $order_id[$key],'order_num' => $order_num[$key],'order_name' => $info['goodsname'],'price' => $info['price'],'create_time' => $order_time[$key],'order_code' => $order_code[$key],'goods_id' => $info['goods_id'],'photo' => $info['photopath']];
       }
       // dump($order_info);
        $this->assign('order',$order_info);
        $this->assign('info',$order);
      }
      return $this->fetch();
    }

    public function order_nosend()
    {
      return $this->fetch();
    }
     public function order_nosave()
    {
      $goods_id = [];
      $order_id = [];
      $order_num = [];
      $order_info = [];
      if(session('?u_id'))
      {
        $order = Order::checkOrder_nosave(session('u_id')['u_id']);
        foreach ($order as $key => $value) {
          $goods_id[] = $value['goods_id'];
          $order_id[] = $value['order_id'];
          $order_num[] = $value['number'];
          $order_time[] = date('Y-m-d H:i:s',$value['create_time']);
          $order_code[] = $value['order_code'];
          $order_acept[] = $value['acept'];
          $order_refund[] = $value['refund'];
             // dump($value['goods_id']);
        }
       foreach ($goods_id as $key => $value) {
         $info = Goods::checkGoods($value);
         // dump($info);
         $order_info[] = ['order_id' => $order_id[$key],'order_num' => $order_num[$key],'order_name' => $info['goodsname'],'price' => $info['price'],'create_time' => $order_time[$key],'order_code' => $order_code[$key],'goods_id' => $info['goods_id'],'photo' => $info['photopath'],'code' => $order_code[$key],'acept' => $order_acept[$key],'refund' => $order_refund[$key]];
       }
       // dump($order_info);
        $this->assign('order',$order_info);
        $this->assign('info',$order);
      }
      return $this->fetch();
    }
     public function order_nochat()
    {
      $goods_id = [];
      $order_id = [];
      $order_num = [];
      $order_info = [];
      if(session('?u_id'))
      {
        $order = Order::checkOrder_nochat(session('u_id')['u_id']);
        foreach ($order as $key => $value) {
          $goods_id[] = $value['goods_id'];
          $order_id[] = $value['order_id'];
          $order_num[] = $value['number'];
          $order_time[] = date('Y-m-d H:i:s',$value['create_time']);
          $order_code[] = $value['order_code'];
          $order_acept[] = $value['acept'];
          $order_refund[] = $value['refund'];
             // dump($value['goods_id']);
        }
       foreach ($goods_id as $key => $value) {
         $info = Goods::checkGoods($value);
         // dump($info);
         $order_info[] = ['order_id' => $order_id[$key],'order_num' => $order_num[$key],'order_name' => $info['goodsname'],'price' => $info['price'],'create_time' => $order_time[$key],'order_code' => $order_code[$key],'goods_id' => $info['goods_id'],'photo' => $info['photopath'],'code' => $order_code[$key],'acept' => $order_acept[$key],'refund' => $order_refund[$key],'refund' => $order_refund[$key]];
       }
       // dump($order_info);
        $this->assign('order',$order_info);
        $this->assign('info',$order);
      }
      return $this->fetch();
    }
    //商品收藏
    public function collect()
    {
      if(session('?u_id'))
      {
        $request = Request::instance();
        if($request->param('goods_id'))
        {
          $data['goods_id'] = $request->param('goods_id');
          if(!Collect::collectFind($data['goods_id']))
          {
              $data['u_id'] = session('u_id')['u_id'];
              $collect = Collect::collectAdd($data);
              if($collect)
              {
                $this->success('收藏成功');
              }else{
                $this->error('收藏失败');
              }
          }else{
            $this->error('您已经收藏过了');
          }
         
        }
      }else{
          $this->error('您还没有登录呢','login');
        }
      
    }
    //取消收藏
    public function nocollect()
    {
      $request = Request::instance();
        if($request->param('goods_id'))
        {
          $goods_id = $request->param('goods_id');
          if(Collect::nocollect($goods_id))
          {
            $this->success('取消收藏成功');
          }else{
            $this->error('取消收藏失败');
          }
        }
    }
    public function myorder()
    {
      return $this->fetch();
    }
    public function point()
    {
      return $this->fetch();
    }
    public function reg()
    {
      return $this->fetch();
    }
     public function timespike()
    {
      return $this->fetch();
    }
    //页面显示
    public function address()
    {
      if(session('?u_id'))
        {
            $u_id = session('u_id')['u_id'];
            $count = User::goodsaddrCount($u_id);
            if($count < 5)
            {
              $this->assign('add',true);
            }else{
              $this->assign('add',false);
            }

        $info = User::goodsaddrInfo($u_id);
        // dump($info);
        $this->assign('info',$info);
        $this->assign('count',$count);
        return $this->fetch();
        }else{
            $this->assign('count',0);
            $this->assign('add',false);
            $this->assign('info','');
            return $this->fetch();
        }
    }
    //页面显示
    public function goodsadd_delsure()
    {
      if(session('?u_id'))
        {
            $u_id = session('u_id')['u_id'];
            $count = User::goodsaddrCount($u_id);
            if($count < 5)
            {
              $this->assign('add',true);
            }else{
              $this->assign('add',false);
            }

        $info = User::goodsaddrInfo($u_id);
        // dump($info);
        $this->assign('info',$info);
        $this->assign('count',$count);
        return $this->fetch();
        }else{
            $this->assign('count',0);
            $this->assign('add',false);
            $this->assign('info','');
            return $this->fetch();
        }
    }
    //页面显示
    public function goodsadd_defalult()
    {
      if(session('?u_id'))
        {
            $u_id = session('u_id')['u_id'];
            $count = User::goodsaddrCount($u_id);
            if($count < 5)
            {
              $this->assign('add',true);
            }else{
              $this->assign('add',false);
            }

        $info = User::goodsaddrInfo($u_id);
        // dump($info);
        $this->assign('info',$info);
        $this->assign('count',$count);
        return $this->fetch();
        }else{
            $this->assign('count',0);
            $this->assign('add',false);
            $this->assign('info','');
            return $this->fetch();
        }
    }
    //删除收获地址
    public function goodsaddrdel()
    {
        $request = Request::instance();
      // dump($request);die();
        $goodsaddr_id = $request->param('goodsaddr_id');
        $goodsaddr_id = (int)$goodsaddr_id;
        $change = Goodsaddr::user_goodsaddrDel($goodsaddr_id);
        // dump($change);die();
        if($change)
        {
            $data = ['err' => 1];
        }else{
            $data = ['err' => 0];
        }
        echo json_encode($data);
    }
    //设为默认收获地址
    public function addrsetdefault()
    {
      // dump(session('u_id'));die();
      if(session('?u_id'))
      {
        $u_id = session('u_id')['u_id'];
        $request = Request::instance();
      // dump($request);die();
        $goodsaddr_id = $request->param('goodsaddr_id');
        $goodsaddr_id = (int)$goodsaddr_id;
        $change = Goodsaddr::user_goodsaddrClear($u_id);
        $set = Goodsaddr::user_goodsaddrDefault($goodsaddr_id);
        // dump($change);die();
        if($change && $set)
        {
            $data = ['err' => 1];
        }else{
            $data = ['err' => 0];
        }
        echo json_encode($data);
      }
        
    }
    //修改收获地址页面
    public function addressChange()
    {
      $request = Request::instance();
      // dump((int)$request->param('goodsaddr_id'));die();
      if(!null == $request->param('goodsaddr_id'))
      {
        $goodsaddr_id  = (int)$request->param('goodsaddr_id');

        $info = User::user_goodsaddrInfo($goodsaddr_id);
        // dump($info);die();
        $this->assign('info',$info);
        return $this->fetch();
      }
      
    }
    public function history()
    {
      // $a = Order::checkOrder_bycode();
      // dump($a);die();
      $goods_id = [];
      $order_id = [];
      $order_num = [];
      $order_info = [];
      if(session('?u_id'))
      {
        $order = Order::checkOrder_history(session('u_id')['u_id']);
        foreach ($order as $key => $value) {
          $goods_id[] = $value['goods_id'];
          $order_id[] = $value['order_id'];
          $order_num[] = $value['number'];
          $order_time[] = date('Y-m-d H:i:s',$value['create_time']);
          $order_code[] = $value['order_code'];
             // dump($value['goods_id']);
        }
       foreach ($goods_id as $key => $value) {
         $info = Goods::checkGoods($value);
         // dump($info);
         $order_info[] = ['order_id' => $order_id[$key],'order_num' => $order_num[$key],'order_name' => $info['goodsname'],'price' => $info['price'],'create_time' => $order_time[$key],'order_code' => $order_code[$key],'goods_id' => $info['goods_id'],'photo' => $info['photopath']];
       }
       // dump($order_info);
        $this->assign('order',$order_info);
        $this->assign('info',$order);
      }
      return $this->fetch();
    }
    public function reorder()
    {
      // $a = Order::checkOrder_bycode();
      // dump($a);die();
      $goods_id = [];
      $order_id = [];
      $order_num = [];
      $order_info = [];
      if(session('?u_id'))
      {
        $order = Order::checkOrder_reorder(session('u_id')['u_id']);
        foreach ($order as $key => $value) {
          $goods_id[] = $value['goods_id'];
          $order_id[] = $value['order_id'];
          $order_num[] = $value['number'];
          $order_time[] = date('Y-m-d H:i:s',$value['create_time']);
          $order_code[] = $value['order_code'];
             // dump($value['goods_id']);
        }
       foreach ($goods_id as $key => $value) {
         $info = Goods::checkGoods($value);
         // dump($info);
         $order_info[] = ['order_id' => $order_id[$key],'order_num' => $order_num[$key],'order_name' => $info['goodsname'],'price' => $info['price'],'create_time' => $order_time[$key],'order_code' => $order_code[$key],'goods_id' => $info['goods_id'],'photo' => $info['photopath']];
       }
       // dump($order_info);
        $this->assign('order',$order_info);
        $this->assign('info',$order);
      }
      return $this->fetch();
    }

    public function reorder_back()
    {
      $request = Request::instance();
      if($request->param('order_id'))
      {
        if(Order::reorder_back($request->param('order_id')))
        {
          $this->success('还原成功');
        }else{
          $this->error('还原失败');
        }
      }
    }
    public function account()
    {
      //用户信息
      $info = User::checkInfo();
      $username = $info['realname'];
      $nickname = $info['nickname'];
      $birthday = $info['birthday'];
      $sex = $info['sex'];
      $mobile = $info['mobile'];
      $address = $info['address'];
      $addrinfo = $info['addrinfo'];
      $email = $info['email'];
      $headphoto = $info['headphoto'];
      //替换
      $this->assign(['realname' => $username,'nickname' => $nickname,'birthday' => $birthday,'sex' => $sex,'mobile' => $mobile,'address' => $address,'addrinfo' => $addrinfo,'email' => $email,'headphoto' => $headphoto]);
      $this->assign('headphoto',$headphoto);
      return $this->fetch();
    }
    public function evaluate()
    {
      if(session('?u_id'))
      {
        $u_id = session('u_id')['u_id'];
        $info = Comments::comments_info($u_id);
        // $page = Comments::commentsPage($u_id);
        // dump($info);die();
        $arr = [];
        foreach ($info as $key => $value) {
            $data['goods_id'] = (int)$value['goods_id'];
            $data['comments_id'] = $value['comments_id'];
            $data['time'] = date('Y-m-d H:i:s',$value['time']);
            $data['grade'] = $value['grade'];
            $data['title'] = $value['title'];
            $data['comments'] = $value['comments'];
            $data['goodsname'] = Goods::checkGoods($data['goods_id'])['goodsname'];
            $data['photo'] = Goods::checkGoods($data['goods_id'])['photopath'];
            $data['price'] = Goods::checkGoods($data['goods_id'])['price'];
            $arr[] = $data;
        }
        $this->assign('info',$arr);
        $this->assign('page',$info);
      }
      
      return $this->fetch();
    }
    public function fav()
    {
      $goods_id = [];
      $goods_info = [];
      $request = Request::instance();
      if(session('?u_id'))
      {   $u_id = session('u_id')['u_id'];
          $goods = Collect::collectUser($u_id);
          if($goods)
          {
            //遍历收藏信息 获取货物id
            foreach ($goods as $key => $value) {
              // dump($value);
              $goods_id[] = $value['goods_id'];
            }
          }
          //遍历获取的货物id 查找信息
          foreach ($goods_id as $key => $value) {
            $goods_info[] = Goods::checkGoods($value);
          }
      }
      // dump($goods_info);
      $this->assign('goods_info',$goods_info);
      $this->assign('goods',$goods);
      return $this->fetch();
    }
    public function footprint()
    {
      if(session('?u_id'))
      {
          $arr = [];
          $history = History::footFind(session('u_id')['u_id']);
          foreach ($history as $key => $value) {
            // dump($value);
            $info[] = Goods::checkGoods($value['goods_id']);
          }
          // dump($info);
          $msg = [];
          foreach ($history as $key => $value) {
            $msg[] = ['history_id' => $value['history_id'],'goods_id' => $value['goods_id'],'time' => $value['time'],'goodsname' => $info[$key]['goodsname'],'price' => $info[$key]['price'],'photo' => $info[$key]['photopath']];
          }
          // dump($msg);
          $this->assign('msg',$msg);
          $this->assign('pg',$history);
      }
      // die();
      return $this->fetch();
    }
    public function footdel()
    {
      $info = History::delFind();
      foreach ($info as $key => $value) {
          History::del($value['history_id']);
      }
      $this->success('清空历史成功');
    }
    public function delfoot()
    {
      $request = Request::instance();
      if($request->param('history_id'))
      {
        History::del($request->param('history_id'));
        $this->success('删除成功');
      }else{
        $this->error('删除失败');
      }
    }
    public function msg()
    {
      return $this->fetch();
    }
    public function refund()
    {
      $goods_id = [];
      $order_id = [];
      $order_num = [];
      $order_info = [];
      if(session('?u_id'))
      {
        $order = Order::checkOrder_refund(session('u_id')['u_id']);
        foreach ($order as $key => $value) {
          $goods_id[] = $value['goods_id'];
          $order_id[] = $value['order_id'];
          $order_num[] = $value['number'];
          $order_time[] = date('Y-m-d H:i:s',$value['create_time']);
          $order_code[] = $value['order_code'];
          $order_acept[] = $value['acept'];
          $order_refund[] = $value['refund'];
             // dump($value['goods_id']);
        }
       foreach ($goods_id as $key => $value) {
         $info = Goods::checkGoods($value);
         // dump($info);
         $order_info[] = ['order_id' => $order_id[$key],'order_num' => $order_num[$key],'order_name' => $info['goodsname'],'price' => $info['price'],'create_time' => $order_time[$key],'order_code' => $order_code[$key],'goods_id' => $info['goods_id'],'photo' => $info['photopath'],'code' => $order_code[$key],'acept' => $order_acept[$key],'refund' => $order_refund[$key],'refund' => $order_refund[$key]];
       }
       // dump($order_info);
        $this->assign('order',$order_info);
        $this->assign('info',$order);
      }
      return $this->fetch();
    }
    public function refundCancle()
    {
      $request = Request::instance();
      if($request->param('order_id'))
      {
        if(Order::refundCancle($request->param('order_id')))
        {
          $this->success('取消成功');
        }else{
          $this->error('取消失败');
        }
      }
    }
    public function refundgoods()
    {
      $request = Request::instance();
      if($request->param('order_id'))
      {
        if(Order::reorder_refund($request->param('order_id')))
        {
          $this->success('申请成功,请耐心等待');
        }else{
          $this->error('申请失败');
        }
      }
    }
    public function safe()
    {
      if(session('?u_id'))
      {
        $u_id = session('u_id')['u_id'];
        $find = Sendemail::findStatus($u_id);
        if($find)
        {
          // dump($find);die();
          $this->assign('email',$find);
          return $this->fetch();
        }else{
          $this->assign('email',['email' => '']);
          return $this->fetch();
        }
      }
     
    }
    public function verify()
    {
      return $this->fetch();
    }
     public function helper()
    {
      return $this->fetch();
    }
    public function tobe()
    {
      if($this->check())
      {
        return $this->fetch();
      }else{
        $this->error('您申请过了');
      }
    }

    public function check()
    {
      if(session('?u_id'))
      {
        $u_id = session('u_id')['u_id'];
        $check = Businessman::sureReg($u_id);
        // dump($check);die();

        if($check)
        {
          return false;
        }else{
          return true;
        }
      }else{
        $this->error('还没登录呢','index/index/login');
      }
    }
    public function tobeone()
    {
      if(session('?u_id'))
      {
          session('tobeone',1);
      return $this->fetch();
      }
      else{
        $this->error('还没登录呢','index/index/login');
      }
    }
    public function tobetwo()
    {
      if(session('?tobeone'))
      {
        return $this->fetch();
      }else{
        $this->error('请按顺序执行','index/index/tobeone');
      }
      
    }
    public function tobethree()
    {
      return $this->fetch();
    }
    public function tobefour()
    {
      if(session('?tobetwo'))
      {
        return $this->fetch();
      }else{
        $this->error('请按顺序执行','index/index/tobeone');
      }
    }
    public function tobefive()
    {
      if(session('?tobefour'))
      {
        if(session('u_id'))
        {
          $u_id = session('u_id')['u_id'];
          session('tobeone',1);
          session('tobetwo',1);
          session('tobefour',1);
          $name = Businessman::regname($u_id);
          $content = Businessman::regcontent($u_id);
          $this->assign('name',$name['username']);
          $this->assign('content',$content['content']);
          return $this->fetch();
        }
        
      }else{
        $this->error('请按顺序执行','index/index/tobeone');
      }
    }
    public function ems()
    {
      return $this->fetch();
    }
    public function active()
   {
        $request = Request::instance();
        $verify = $request->param('verify');
        $final = Sendemail::getToken($verify);
        $id = $final['sendemail_id'];
        if(time() > $final['token_exptime'])
        {
            $this->error('您的激活有效期已过，请您重新发送激活邮件','','index/index/verify');
        }else{
          $change = Sendemail::changeStatus($id);
          if($change)
          {
            $this->success('激活成功','index/index/safe');
          }
        }
        // dump($send);
   }

   public function comments()
   {
      $request = Request::instance();
      if($request->param('goods_id') && $request->param('order_id'))
      {
        $info = Goods::checkGoods($request->param('goods_id'));
        Order::reorder_comments($request->param('order_id'));
        // dump($info);die();
        $this->assign('info',$info);
      }
      return $this->fetch();
   }

   public function comments_del()
   {
      $request = Request::instance();
      if($request->param('comments_id'))
      {
        $info = Comments::comments_del($request->param('comments_id'));
        // dump($info);die();
        if($info)
        {
          $this->success('删除成功');
        }else{
          $this->error('删除失败');
        }
      }
   }
}
