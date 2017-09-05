<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use traits\model\SoftDelete;

class Products extends Model
{
	//查找分类数据
	static public function treeshow($where = null, $data = null)
	{
		return Db::name('category')->where($where,$data)->select();
	}
	//添加分类数据
	static public function addcategory($data)
	{
		return Db::name('category')->insert($data);
	}
	//查找商品数据
	static public function goodslist($where = null, $data = null)
	{
		return Db::name('goods')->where($where,$data)->select();
	}
	//添加商品数据
	static public function addgoods($data)
	{
		return Db::name('goods')->insert($data);
	}
	
}