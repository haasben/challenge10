<?php
namespace app\index\controller;
use think\Db;


/**
*PHP是世界上最好的语言
*@param 首页控制器
**/
class Active extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//大厅首页
    public function recharge_active()
    {


    	$recharge_active = Db::name('recharge_active')->where('status',1)->order('order')->select();


    	echo json(return_msg('0000','请求成功',$recharge_active));

    }
  
  	//今日是否已经领取过银币
 	public function is_recive_silver(){
 		$uid = session('user_data')['uid'];
      	$activeModel = Db::name('active');
 		$is_recived = $activeModel->where('user_id',$uid)->where('type',4)->whereTime('time','today')->limit(1)->find();

		if(!empty($is_recived)){
			$status = 1;
		}else{
			$status = 0;
		}

		echo json_encode(['code'=>'0000','msg'=>'请求成功','status'=>$status]);

 	}
  
	//每日领银币
	public function receive_silver(){

		$uid = session('user_data')['uid'];

		//查询用户今日是否已经领取了银币
		$activeModel = Db::name('active');

		$is_recived = $activeModel->where('user_id',$uid)->where('type',4)->whereTime('time','today')->limit(1)->find();

		if(!empty($is_recived)){
			echo json(return_msg(60001,'今日已经领取过银币'));die;
		}

		//查询用户的会员是否到期
		$user_data = Db::name('user')->where('uid',$uid)->limit(1)->find();
		$silver = 2000;
		if($user_data['expire_time'] >= time()){

			switch ($user_data['vip']) {
				case '1':
					$silver = 3000;
					break;
				case '2':
					$silver = 4000;
					break;
				case '3':
					$silver = 5000;
					break;
				default:
					$silver = 2000;
					break;
			}

		}
		$activeModel->insert([
			'user_id'=>$uid,
			'money'=>$silver,
			'time'=>time(),
			'type'=>4
		]);

		Db::name('assets')->where('user_id',$uid)->setInc('silver',$silver);

		echo json(return_msg('0000','领取成功'));
	}
	







    


}
