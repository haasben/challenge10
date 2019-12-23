<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Cache;
use think\Validate;
/**
*PHP是世界上最好的语言
*@param 房间模型
**/
class Room extends Model
{
	protected $name = 'room';


//用户自主添加房间并返回房间信息
	public function create_room($room_id,$level)
	{

		$is_have_room_data = $this->is_have_room($room_id);
		
		if($is_have_room_data === false){

			$data = ['room_id'=>$room_id,'level'=>$level,'create_type'=>2];
			$room = new room;
			$room->isUpdate(false)->save($data);

			$id = $room->id;

			$data = ['id'=>$id,'room_id'=>$room_id,'level'=>$level];
			return return_msg('0000','房间添加成功',$data);

		}else{
			return return_msg('80001','该房间号已存在',$is_have_room_data);
		}

		//$room_id = 
	}


	//查询是否存在该房间

	public function is_have_room($room_id)
	{
		
		$data = self::field('id,room_id,level,password')
			->where('room_id',$room_id)
			->limit(1)
			->find();

		if(empty($data)){
			return false;
		}else{
			// if($data['is_in_game'] == 1){

			// 	 echo json(return_msg('0000','房间正在游戏中'));die;


			// }

			return $data->toArray();
		}

	}

	public function confirm_room_pass($room_id,$password){

		$data = self::field('id,room_id,level')
			->where('room_id',$room_id)
			->where('password',$password)
			->limit(1)
			->find();

		if(empty($data)){
			return return_msg('30003','密码错误');
		}else{
			return return_msg('0000',lang('REQUEST_SUCCESS'),$data);
		}


	}









//系统自动创建房间并返回房间信息



	public function sys_create_room($level = 1,$password = "",$room_gold = "")
	{

		$room_id = rand_room($level);

		while($this->is_have_room($room_id) !== false){
			$room_id = rand_room($level);
		}

		if(empty($password)){
			$use_pass = 0;
		}else{
			$use_pass = 1;
		}

		$data = ['room_id'=>$room_id,'level'=>$level,'password'=>$password,'c_time'=>time(),'room_gold'=>$room_gold,'use_pass'=>$use_pass];
		$room = new room;
		$room->isUpdate(false)->save($data);


		$id = $room->id;
		$data = ['id'=>$id,'room_id'=>$room_id,'level'=>$level,'room_gold'=>$room_gold];
		return $data;



	}

//


	// protected function room_info()
	// {
	// 	$data = self::
	// }




}