<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 退款控制器
**/

class Refund extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}


//用户退款申请
	public function refund_request()
	{
		
		$uid = session('user_data')['uid'];
		$userModel = Db::name('user');

		//会员到期时间
		$expire =  $userModel->where('uid',$uid)->limit(1)->find();

		if(empty($expire['alipay'])){
			echo json(return_msg(50001,'未绑定支付宝账号'));die;
		}elseif($expire['vip'] == 0){
			echo json(return_msg(20001,'当前没有可退的会员月份数'));die;
		}

		//会员剩余时间
		$remain_time_men = $expire['expire_time']+24*3600 - time();

		//可以兑换的月数
		$remain_time = intval($remain_time_men/(24*60*60*30));

		if($remain_time == 0){
			echo json(return_msg(50002,'当前没有可退的会员月份数'));die;
		}
		
		switch ($expire['vip']) {
			case 1:
				$money = $remain_time *5000;
				break;
			case 2:
				$money = $remain_time *50000;
				break;
			case 3:
				$money = $remain_time *500000;
				break;
			default:
				# code...
				break;
		}

		//添加退款记录
		Db::name('buy_refund')->insert([
			'user_id'=>$uid,
			'addtime'=>time(),
			'number'=>1,
			'type'=>2,
			'status'=>2,
			'member_type'=>$expire['vip'],
			'gold'=>$money,
			'duration'=>$remain_time,
			'money'=>$money
		]);
		//修改用户会员到期时间以及会员等级

		$time = $remain_time_men - $remain_time*30*24*60*60;
		if($time >0){
			$time -=24*3600;

			if($time< 0){
				$userModel->where('uid',$uid)->update(['expire_time'=>0,'vip'=>0]);

			}else{
				$userModel->where('uid',$uid)->update(['expire_time'=>time()+$time]);
			}


		}else{

			$userModel->where('uid',$uid)->update(['expire_time'=>0,'vip'=>0]);

		}

		echo json(return_msg('0000','兑换成功'));


	}

//查询可退多少钱
	public function refund_money_num(){

		$uid = session('user_data')['uid'];
		$userModel = Db::name('user');

		//会员到期时间
		$expire =  $userModel->where('uid',$uid)->limit(1)->find();

		if(empty($expire['alipay'])){
			echo json(return_msg(50001,'未绑定支付宝账号'));die;
		}elseif($expire['vip'] == 0){
			echo json(return_msg(20001,'当前没有可退的会员月份数'));die;
		}

		//会员剩余时间
		$remain_time_men = $expire['expire_time']+24*3600 - time();

		//可以兑换的月数
		$remain_time = intval($remain_time_men/(24*60*60*30));

		if($remain_time == 0){
			echo json(return_msg(50002,'当前没有可退的会员月份数'));die;
		}
		
		switch ($expire['vip']) {
			case 1:
				$money = $remain_time *5000;
				break;
			case 2:
				$money = $remain_time *50000;
				break;
			case 3:
				$money = $remain_time *500000;
				break;
			default:
				# code...
				break;
		}


		echo json(return_msg('0000','获取成功',$money));

	}







}