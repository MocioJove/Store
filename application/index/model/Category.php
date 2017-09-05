<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class Category extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	static public function Category()
	{
		return Db::table('st_category')->where('class_id','>',0)->select();
	}

	static public function Category_find($class_id)
	{
		return Db::table('st_category')->field('class_id')->where('parentid',$class_id)->select();
	}

	public function make_tree($list,$pk='class_id',$pid='parentid',$child='path',$root=0)
	{
		    $tree=array();
		    foreach($list as $key=> $val){

		        if($val[$pid]==$root){
		            //获取当前$pid所有子类 
		                unset($list[$key]);
		                if(! empty($list)){
		                    $child=$this->make_tree($list,$pk,$pid,$child,$val[$pk]);
		                    if(!empty($child)){
		                        $val['path']=$child;
		                    }                   
		                }              
		                $tree[]=$val; 
		        }
		    }   
		    return $tree;
	}


    public function classarr($class_id)
    {
    	$select = Self::Category_find($class_id);
    	$category = $this->make_tree($select);
    	$arr = $arrb = $arrc = [];
    	// return $category;
    	foreach ($category as $first_class) {
    		$arr[] += $first_class['class_id'];
    		if(isset($first_class['path']))
    		{
    			foreach ($first_class['path'] as $secend_class) {
    				$arrb[] += $secend_class['class_id'];
    				if(isset($secend_class['path']))
    				{
    					foreach ($secend_class['path'] as $third_class) {
    						$arrc[] += $third_class['class_id'];
    					}
    				}
    			}
    		}
    	}

    	if($arr && $arrb && $arrc)
    	{
    		$arr = array_merge($arr,$arrb);
    		$arr = array_merge($arr,$arrc);
    	}else{
    		$arr = [];
    	}
    	return $arr;
    }
	//一级板块
	static public function classFind($class_id)
	{
		return Db::table('st_category')->field('classname,class_id')->where('class_id',$class_id)->find();
	}
	static public function classSecond($class_id)
	{
		return Db::table('st_category')->field('classname,class_id')->where('parentid',$class_id)->select();
	}

	static public function classSecond_parent($class_id)
	{
		return Db::table('st_category')->field('classname,class_id')->where('class_id',Db::table('st_category')->field('parentid')->where('class_id',$class_id)->find()['parentid'])->select();
	}
	static public function parentid($class_id)
	{
		return Db::table('st_category')->field('classname,class_id,parentid')->where('class_id',$class_id)->find();
	}
	static public function category_first()
	{
		return Db::table('st_category')->field('class_id,classname')->where('parentid',0)->select();
	}
}