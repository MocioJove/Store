<?php
namespace app\index\controller;
use think\Request;
use think\Controller;
use app\index\model\User as DoUser;
use app\index\model\Businessman as BusinessmanModel;
use think\captcha\Captcha;
use app\index\model\Smtp;
use app\index\model\Sendemail;
class Businessman extends Controller
{
	public function reg()
	{
		if(session('?u_id'))
		{
			$data = [];
		$request = Request::instance();
        $data['type'] = $request->param('ec_shoprz_type');
        $data['categoryMain'] = $request->param('ec_shop_categoryMain');
        $data['username'] = $request->param('contactName');
        $data['phone'] = $request->param('contactPhone');
        $data['email'] = $request->param('contactEmail');
        $data['address'] = $request->param('adress');
        $data['date'] = $request->param('date');
        $data['office'] = $request->param('youZheng');
        $data['content'] = $request->param('content');
        // dump(session('u_id')['u_id']);
        $data['u_id'] = session('u_id')['u_id'];
        // dump($request->param());die();
        // $file = $request->param('file');
        $file = request()->file('photo');
        if(isset($file)){  
          
        $upload = $file->move('static/images/upload/');  
        //   var_dump($upload) ;die;  
  
        if($upload){  
	        // 成功上传后 获取上传信息  
	         $path = $upload->getSaveName();  
	         $path = '/static/images/upload/' . str_replace("\\", "/" ,$path);  
	         $data['file'] = $path;
	         $end = BusinessmanModel::checkReg($data);
	         if($end)
	         {
	         	$info = ['err' => 1];
	         	session('tobetwo',1);

	         	$this->success('提交成功,下一步','index/index/tobefour');
	         }else{
	         	$info = ['err' => 0];
	         	$this->error('提交失败');

	         }
	        }else{
	         	$info = ['err' => 0];
	         	$this->error('提交失败');

	        }
	        
      }
        
		}
		
	}

	// public function check()
	// {
	// 	if(session('?u_id'))
	// 	{
	// 		$u_id = session('u_id');
	// 		$checkk = BusinessmanModel::sureReg($u_id);

	// 		if($check)
	// 		{
	// 			return false;
	// 		}else{
	// 			return true;
	// 		}
	// 	}	
		
	// }

	public function four()
	{
		if(session('?u_id'))
		{
		$data = [];
		$request = Request::instance();
        $data['brandname'] = $request->param('ec_brandName');
        $data['word'] = $request->param('ec_bank_name_letter');
        $data['brandtype'] = $request->param('ec_brandType');
        $data['operatetype'] = $request->param('ec_brand_operateType');
        $data['endTime'] = $request->param('ec_brandEndTime');

        // dump(session('u_id')['u_id']);
        $data['u_id'] = session('u_id')['u_id'];
        // dump($request->param());die();
        $file = request()->file('ec_brandLogo');
        if(isset($file)){  
          
        $upload = $file->move('static/images/upload/');  
        //   var_dump($upload) ;die;  
  
        if($upload){  
	        // 成功上传后 获取上传信息  
	         $path = $upload->getSaveName();  
	         $path = '/static/images/upload/' . str_replace("\\", "/" ,$path);  
	         $data['logo'] = $path;
	         $end = BusinessmanModel::checkRegfour($data);
	         if($end)
	         {
	         	$info = ['err' => 1];
	         	session('tobefour',1);

	         	$this->success('提交成功,下一步','index/index/tobefive');
	         }else{
	         	$info = ['err' => 0];
	         	$this->error('提交失败','');

	         }
	        }else{
	         	$info = ['err' => 0];
	         	$this->error('提交失败','');

	        }
	        
      }
		}
		
	}
}