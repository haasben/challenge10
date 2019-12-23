<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 签到控制器
**/

class Signature extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//用户签名列表
	public function user_sign()
	{

		$sign_data = Db::name('signature')
			->where('uid',session('user_data')['uid'])
			->limit(1)
			->find();

		echo json(['code'=>'0000','msg'=>'请求成功','data'=>$sign_data]);



	}

//修改添加签名
	public function edit_sign()
	{


		$sign = input('sign');

		if(empty($sign)){

			echo json(return_msg(90001,'签名不能为空'));die;
		}

		$uid = session('user_data')['uid'];
		$signatureModel = Db::name('signature');

		$sign_data = $signatureModel
			->where('uid',$uid)
			->limit(1)
			->find();
		if(empty($sign_data)){

			$signatureModel->insert(['uid'=>$uid,'sign'=>$sign]);

			$data = ['code'=>'0000','msg'=>'添加成功'];
		}else{

			$signatureModel->where('uid',$uid)->update(['sign'=>$sign]);
			$data = ['code'=>'0000','msg'=>'修改成功'];

		}

		echo json($data);


	}








}