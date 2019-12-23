<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 购买VIP控制器
**/

class Buyvip extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

	public function buy_vip()
	{

		$data = input();



	    $uid = session('user_data')['uid'];

	    switch ($data['type']) {
	    	case '001':
	    		$member_type = 1;
	    		$gold = 5000;

	    		break;
	    	case '002':
	    		$member_type = 2;
	    		$gold = 50000;

	    		break;
	    	case '003':
	    		$member_type = 3;
	    		$gold = 100000;
	    		break;
	    	
	    	default:
	    		# code...
	    		break;
	    }

	    $month = $data['month'];
	    //实例化模型
	    $gold = $month*$gold;

	    $assetsModel = Db::name('assets');

	    $assetsData = $assetsModel->where('user_id',$uid)->limit(1)->find();

	    if($assetsData['gold']<$gold){

	    	echo json(return_msg(40001,'金币不足'));die;

	    }

	Db::startTrans();
	    //添加购买金币会员记录
		$bool1 = Db::name('buy_refund')->insert([
			'user_id'=>$uid,
			'addtime'=>time(),
			'number'=>1,
			'type'=>1,
			'status'=>1,
			'member_type'=>$member_type,
			'gold'=>$gold,
			'duration'=>$month,
			'money'=>$gold
		]);

		//扣除用户金币
		$userModel = Db::name('user');

		$user_data = $userModel->where('uid',$uid)->limit(1)->find();

		$expire = $month*30*24*60*60;

		if(empty($user_data['expire_time'])){

			$bool2 = $userModel->where('uid',$uid)->update([
				'vip'=>$member_type,
				'expire_time'=>time()+$expire,
				'buy_time'=>time()
			]);

		}else{

			$bool2 = $userModel->where('uid',$uid)->update([
				'vip'=>$member_type,
				'expire_time'=>Db::raw('expire_time+'.$expire),
				'buy_time'=>time()
			]);

		}

		$bool3 = $assetsModel->where('user_id',$uid)->setDec('gold',$gold);

		if($bool1&&$bool2&&$bool3){

			Db::commit();  
			echo json(return_msg('0000','购买成功'));
		}else{

			Db::rollback();
			echo json(return_msg(40001,'购买失败，请稍后再试'));
		}
	

	












	}







}