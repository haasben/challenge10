<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Cache;
use think\Validate;
/**
*PHP是世界上最好的语言
*@param 在线人数模型
**/
class Onlineuser extends Model
{
	protected $name = 'online_user';

	//在线人数
	public function online_user()
	{
		//初级房间在线人数
		$data['primary'] = self::where('room_level',1)->where('method',2)->count();

		//中级房间在线人数
		$data['middle'] = self::where('room_level',2)->where('method',2)->count();

		//高级房间在线人数
		$data['high'] = self::where('room_level',3)->where('method',2)->count();

		//自主创建房间在线人数
		$data['create'] = self::where('method',1)->count();

		return $data;



	}




}