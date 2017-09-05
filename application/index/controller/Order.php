<?php
namespace app\index\controller;
use think\Request;
use think\Controller;
use app\index\model\User;
use app\index\model\Businessman;
use app\index\model\Goodsaddr;
use app\index\model\Goods;
use app\index\model\Category;
use app\index\model\Cart;
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
class Order extends Controller
{
    protected $is_login = ['*'];
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $updateTime = 'update_time';

    public function single_cart()
    {
    	$request = Request::instance();
    	if($request->param('goods_id') && session('?u_id'))
    	{	
    		$data['goods_num'] = 1;
    		$data['goods_id'] = $request->param('goods_id');
    		$data['u_id'] = session('u_id')['u_id'];
    		if(Cart::cartAdd($data))
    		{
    			$this->success('加入购物车成功');
    		}else{
    			$this->error('加入购物车失败');
    		}
    	}
    }

    public function first()
    {
        $select = Category::category();
        $category = new Category();

        $category = $category->make_tree($select);
        $arr = [];
        $arrb = [];
        foreach ($category as $key => $first_value) {
            if(isset($first_value['path']))
            {
                foreach ($first_value['path'] as $key => $secend_value) {
                    if(isset($secend_value['path']))
                    {
                        foreach ($secend_value['path'] as $key => $value) {
                            $arr[] = $value['class_id'];
                            $arrb[] = Goods::checkFirst($arr);
                        }
                    }
                }
            }
        }
        return $arrb;
    }

}