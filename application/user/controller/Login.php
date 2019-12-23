<?php
namespace app\user\controller;

use think\Controller;
use think\Db;
class Login extends Controller
{
    
    public function login(){

    	if(request()->isPost()){

    	$data = input();

   //   	if(!captcha_check($data['code'])){
		 // 	return ['code'=>'1111','msg'=>'验证码错误'];die;
				
		 // }

    	$user_data = Db::name('user')
	    	->where('tel',$data['mobile'])
	    	->find();

	    if(empty($user_data) ){
	    	return ['code'=>'1111','msg'=>'账号不存在'];die;
	    }elseif($user_data['is_lock'] == 1){

            return ['code'=>'1111','msg'=>'您的账户暂不可用，请联系管理员'];die;

        }elseif($user_data['type'] == 1){

            return ['code'=>'1111','msg'=>'账号不存在'];die;

        }elseif($user_data['password'] != encryption($data['password'])){

	    	return ['code'=>'1111','msg'=>'账号或密码错误'];die;

    	}else{

    		Db::name('user')->where('uid',$user_data['uid'])->update(['login_time'=>time(),'ip'=>$_SERVER["REMOTE_ADDR"]]);

    		session('admin_data',$user_data);


    		return ['code'=>'0000','msg'=>'登陆成功'];die;
    	}

    }else{

    		return $this->fetch();

    	}



    }

    public function logout(){

    	session('admin_data',NULL);
    	$this->redirect('user/login/login');

    }




}
