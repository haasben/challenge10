<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 签到控制器
**/

class Sign extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//用户签到
	public function user_sign()
	{
	
	//获取用户今日是否签到

	$signModel = Db::name('sign');
	$uid = session('user_data')['uid'];
	$sign_data = $signModel->where('user_id',$uid)
		->where('stime',date('Ymd'))
		->limit(1)
		->find();

	if(!empty($sign_data)){
		echo json(return_msg(60001,'今日已签到'));die;
	}

//获取用户昨日是否签到 修改连续签到天数
	$sign = $signModel->where('user_id',$uid)
		->where('stime',date("Ymd",time()-86400))
		->limit(1)
		->find();

	$assetsModel = Db::name('assets');
	if(empty($sign)){
		$assetsModel->where('user_id',$uid)->update(['check_day'=>1]);

	}else{
		$assetsModel->where('user_id',$uid)->setInc('check_day');
	}

	//添加用户签到
	$signModel->insert([
		'user_id'=>$uid,
		'sign_time'=>time(),
		'stime'=>date('Ymd'),
		'date'=>date('d')
	]);
	
      	$sign_day = $signModel->whereTime('sign_time','month')->count();

      	    switch ($sign_day) {
              case 3:
                  $gold = 10;
                  break;
              case 7:
                  $gold = 11;
                  break;
              case 10:
                  $gold = 12;
                  break;
              case 15:
                  $gold = 13;
                  break;
              case 20:
                  $gold = 14;
                  break;
              case 3:
                  $gold = 15;
                  break;

              default:
                  $gold = 10;
                  break;
          }

		//签到加金币

		$assetsModel->where('user_id',$uid)->setInc('gold',$gold);


		echo json(return_msg('0000','签到成功',['gold'=>$gold]));
	}

//用户签到列表
	public function sign_list()
	{	

		$uid = session('user_data')['uid'];
		//用户本月签到数据
		$signModel = Db::name('sign');
		$sign_data = $signModel
			->field('sign_time,stime,date')
			->where('user_id',$uid)
			->whereTime('sign_time', 'm')
			->select();


		$data['total_day'] = date('t');
		$data['today'] = date('d');
		$data['sign_data'] = $sign_data;

		$is_sign = $signModel->where('user_id',$uid)
		->where('stime',date('Ymd'))
		->limit(1)
		->find();
		$data['is_sign'] = 0;
		if(!empty($is_sign)){
			$data['is_sign'] = 1;
		}


		echo json(return_msg('0000','获取成功',$data));




	}




}