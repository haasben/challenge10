<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Cache;
use think\Validate;
/**
*PHP是世界上最好的语言
*@param 优惠券模型
**/
class Coupon extends Model
{
	protected $name = 'coupon';


//用户优惠券列表
	public function user_coupon($uid){


		$user_coupon = self::where('user_id',$uid)
			->where('status',1)
			->where('expire_time','>',time())
			->select();
		// if(!empty($user_coupon)){
		// 	foreach ($user_coupon as $key => $val) {
		// 		$user_coupon[$key]['get_time'] = date('m-d',$val['get_time']);
		// 		$user_coupon[$key]['expire_time'] = date('m-d',$val['expire_time']);
		// 	}
		// }
		return $user_coupon;


	}


}