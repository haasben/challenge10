<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 设置控制器
**/
class Setting extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

	public function set_info(){



		$set_info = Db::name('user')
			->field('music,watch,shield,sound_effect')
			->where('uid',session('user_data')['uid'])
			->limit(1)
			->find();
		echo json(['code'=>'0000','msg'=>'获取成功','data'=>$set_info]);


	}


//修改音乐开关
	public function set_music()
	{

		$status = input('status');
		$userModel = Db::name('user');

		$userModel->where('uid',session('user_data')['uid'])->update(['music'=>$status]);
		echo json(['code'=>'0000','msg'=>'修改成功']);


	}

//修改音效开关
	public function set_sound_effect()
	{

		$status = input('status');
		$userModel = Db::name('user');

		$userModel->where('uid',session('user_data')['uid'])->update(['sound_effect'=>$status]);
		echo json(['code'=>'0000','msg'=>'修改成功']);

	}

//修改是否屏蔽邀请开关
	public function set_shield()
	{

		$status = input('status');
		$userModel = Db::name('user');

		$userModel->where('uid',session('user_data')['uid'])->update(['shield'=>$status]);
		echo json(['code'=>'0000','msg'=>'修改成功']);

	}

//修改是否观战界面开关
	public function set_watch()
	{

		$status = input('status');
		$userModel = Db::name('user');

		$userModel->where('uid',session('user_data')['uid'])->update(['watch'=>$status]);
		echo json(['code'=>'0000','msg'=>'修改成功']);

	}


}