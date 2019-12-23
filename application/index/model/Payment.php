<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Cache;
use think\Validate;
/**
*PHP是世界上最好的语言
*@param 支付方式模型
**/
class Payment extends Model
{
	protected $name = 'payment';

//获取支付方式数据
	public function payment(){
		$data = self::where('status',1)->select();
		return $data;

	}

}