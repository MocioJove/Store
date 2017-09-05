<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class Classify extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';

	static public function firstClassify()
	{
		return Db::table('st_classify')->where('pid',0)->select();
	}
	static function generateTree($items){
     foreach($items as $item)
         $items[$item['pid']]['son'][$item['classify_id']] = &$items[$item['classify_id']];
     return isset($items[0]['son']) ? $items[0]['son'] : array();
 	}

	 /**
	  * 如何取数据格式化的树形数据
	 */
	 static function getTreeData($tree){
	     foreach($tree as $t){
	         echo $t['catename'].'/';
	         if(isset($t['son'])){
	             Self::getTreeData($t['son']);
	         }
	     }
	 }
	 
}