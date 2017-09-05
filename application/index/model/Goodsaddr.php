<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class Goodsaddr extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $updateTime = 'update_time';
	// protected $updateTime = false;
	
	static public function goodsaddrCount($u_id)
	{
		return Db::table('st_goodsaddr')->where('u_id',$u_id)->count();
	}
	static public function goodsaddrInfo($u_id)
	{
		return Db::table('st_goodsaddr')->where('u_id',$u_id)->select();
	}

	static public function user_goodsaddrInfo($goodsaddr_id )
	{
		return Db::table('st_goodsaddr')->where('goodsaddr_id',$goodsaddr_id )->find();
	}

	static public function user_goodsaddrChg($id,$addr,$addrinfo,$mobile,$accept)
	{
		return Db::table('st_goodsaddr')->where('goodsaddr_id', $id)->update(['goodsmobile' => $mobile, 'goodsaddrss' => $addr, 'goodsaddrinfo' => $addrinfo, 'accept' => $accept]);
	}

	static public function user_goodsaddrDel($id)
	{
		// 软删除
		return Goodsaddr::destroy($id);
	}
	static public function user_goodsaddrDefault($id)
	{
		// 设为默认收获地址
		return Db::table('st_goodsaddr')->where('goodsaddr_id', $id)->update(['set_default' => 1]);
	}
	//清空自己的其他默认地址
	static public function user_goodsaddrClear($u_id)
	{
		// 设为默认收获地址
		return Db::table('st_goodsaddr')->where('u_id', $u_id)->update(['set_default' => 0]);
	}

	static public function Info($u_id)
	{
		return Db::table('st_goodsaddr')->field('goodsaddrss,goodsaddrinfo')->where('u_id',$u_id)->where('set_default',1)->where('delete_time',null)->select();
	}
}

