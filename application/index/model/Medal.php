<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Cache;
use think\Validate;
/**
*PHP是世界上最好的语言
*@param 勋章模型
**/
class Medal extends Model
{
	protected $name = 'medal';


//用户优惠券列表
	public function user_medal(){

		$uid = session('user_data')['uid'];
		$data = self::alias('m')
			->field('m.num_id,m.user_id,m.get_time,m.is_receive,ml.name,ml.type,ml.level,ml.reward,ml.img')
			->join('medal_list ml','ml.id=m.medal_id')
			->where('user_id',$uid)
			->select();
		return $data;
	}

//勋章详情
	public function medal_info($num_id){
		$data = self::alias('m')
			->field('m.num_id,m.user_id,m.get_time,m.is_receive,ml.name,ml.type,ml.level,ml.reward,ml.img')
			->join('medal_list ml','ml.id=m.medal_id')
			->where('num_id',$num_id)
			->find();
		return $data;
	}


//勋章数量
	public function medal_num($uid)
	{
		
		$medalNum = self::where('user_id',$uid)->count();
		return $medalNum;


	}




}