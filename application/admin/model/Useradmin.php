<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use traits\model\SoftDelete;

class Useradmin extends Model
{
	//检测用户数据
	static public function checkInfo($where, $data, $table = 'useradmin')
	{
		return 	Db::name($table)->where($where, $data)->find();
	}
	//检测用户登陆信息
	static public function login($username,$password)
	{
		return Db::name('useradmin')
					->where('username', $username)
					->where('password',$password)
					->find();
	}
	//查询商铺管理员信息
	static public function checkadmin($where1,$shopid,$where2,$username)
	{
		return Db::name('useradmin')
					->where($where1, $shopid)
					->where($where2, $username)
					->find();

		 
	}
	//查询用户角色数据
	static public function userinfo($id)
	{
		return Db::field('*')
					->table(['st_useradmin'=>'user', 'st_userole'=>'ur', 'st_role'=>'role'])
					->where("user.id = '$id'")
					->where('user.id = ur.u_id')
					->where('ur.r_id = role.id')
					->find();
	}
	//添加用户信息
	static public function insertinfo($data)
	{
		return Db::name('username')->insert($data);
	}
	//添加用户角色
	static public function insertrole()
	{
		return Db::name('userrole')->insert($data);
	}
	//修改用户信息
	static public function updateinfo($data, $table ='useradmin', $where = null, $data2 = null)
	{
		return Db::name($table)->where($where, $data2)->Update($data);
									
	}
	//管理员信息角色权限等内容
	static public function showadmin()
	{	
		//五表联查，查到用户的角色已经拥有的权限路径
		$result =Db::field('*')
					->table(['st_shop'=>'shop','st_useradmin'=>'user', 'st_userole'=>'ur', 'st_role'=>'role', 'st_rolemodule'=>'rm'])
					->where('user.s_id = shop.id')
					->where('user.id = ur.u_id')
					->where('ur.r_id = role.id')
					->where('role.id = rm.r_id')
					->select();
		return $result;

	}

	static public function showshopadmin($shop)
	{
		//五表联查，查到用户的角色已经拥有的权限路径和所属商城
		$result =Db::field('*')
					->table(['st_shop'=>'shop', 'st_useradmin'=>'user', 'st_userole'=>'ur', 'st_role'=>'role', 'st_rolemodule'=>'rm'])
					->where("shop.id = '$shop'")
					->where('user.s_id = shop.id')
					->where('user.id = ur.u_id')
					->where('ur.r_id = role.id')
					->where('role.id = rm.r_id')
					->select();
		return $result;
	}
}