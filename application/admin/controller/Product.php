<?php
namespace app\admin\controller;
use app\admin\model\Products;
use app\admin\controller\Auth;
use think\Request;


class Product extends Auth
{	
	protected $is_login = ['*'];
	//商品列表展示
	public function products_list()
	{
		
		$goodslist = Products::goodslist();
		$this->assign('goodslist',$goodslist);
		return $this->fetch();
	}
	//树状图展示
	public function treeshow()
	{
		$classlist = Products::treeshow();
		// dump($classlist);
		return json($classlist);

	}
	//树状图分类选择商品展示
	public function treeselect()
	{
		$request = Request::instance();
		$classid = $request->get('id');
		$where   = 'class_id';
		$data    = $classid;
		$info    = Products::goodslist($where, $data);
		// dump($info);die;
		return $info;
		
	}
	//查找商品类型
	public function goodsstyle()
	{
		$request      = Request::instance();
		$data         = $request->post('id');
		$where        = 'class_id';
		$info   = Products::treeshow($where,$data);
		$id     =$info[0]['class_id'];
		$classname = $info[0]['classname'];

		return ['id'=>$id,'info'=>$classname];

	}
	//添加商品页面
	public function picture_add()
	{
		return $this->fetch();
	}
	//商品添加
	public function goodsadd()
	{
		$file = request()->file('photo');  
        $data = $_POST; 
        
        if(!isset($file)){
        	$this->error('上传失败','admin/product/picture_add'); 
        }
    	$info = $file->move('static/images/upload/');
    	
    	if (!$info) {
    		$this->error('上传失败','admin/product/picture_add');
    	}
    	
    	$path             = $info->getSaveName();
    	$path             = '/static/images/upload/' . str_replace("\\", "/" ,$path);
    	$data['photopath']    = $path;
    	$data['jointime'] = time();
    	dump($data);
    	$info = Products::addgoods($data);
    	
    	if ($info) {
    		$this->success('商品添加成功','admin/product/products_list');
    	} else {
    		$this->error('商品添加失败','admin/product/picture_add');
    	}

	}
	//分类管理页面展示
	public function category_manage()
	{
		return $this->fetch();
	}
	//新增分类页面展示
	public function product_category_add()
	{
		$where = 'isbrand';
		$data  = 0;
		$parent = Products::treeshow($where, $data);
		$this->assign('parent', $parent);
		return $this->fetch();
	}
	//添加品牌
	public function addbrand()
	{
		$request      = Request::instance();
		$brandinfo    = $request->post('addbrand');
		$where = 'isbrand';
		$data  = 1;
		$info = Products::treeshow($where, $data);
		// dump($info);die;
		if ($info) {
			return $info;
		} else {
			exit("<script>alert('无品牌数据');history.back();</script>");
		}
	}
	//分类或品牌的数据库处理
	public function category_add()
	{
		$data  = $_POST;
		$sign  = $_POST['sign'];

		if ($sign) {
			if ($data['parentid'] == 0) {
				$data['isbrand'] = 0;
			} else {
				$data['isbrand'] = 1;
			}	
		} else {
			if ($data['parentid'] == 0) {
				exit("<script>alert('品牌类型不得为空');history.back();</script>");
			} else {
				$data['isbrand'] = 2;
			}
		}
		array_shift($data);
		
		$info = Products::addcategory($data);
		// dump($info);

		if ($info) {
			exit("<script>alert('操作成功');history.back();</script>");
		} else {
			exit("<script>alert('操作失败');history.back();</script>");
		}
	}

}

