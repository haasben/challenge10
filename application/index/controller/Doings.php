<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 活动页面控制器
**/
class Doings extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//获取优惠券
	public function coupon(){

		$couponModel = Model('coupon');
		$uid = session('user_data')['uid'];
		$user_coupon = $couponModel->user_coupon($uid);

		echo json(return_msg('0000',lang('REQUEST_SUCCESS'),$user_coupon));
	}


}