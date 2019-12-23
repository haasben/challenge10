<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Cache;
use think\Validate;
/**
*PHP是世界上最好的语言
*@param 用户资产模型
**/
class Assets extends Model
{
	protected $name = 'assets';

//获取用户资产信息
	public function user_assets($uid){

		$user_assets = self::field('diamond,gold,integral')
			->where('user_id',$uid)
			->limit(1)
			->find()
			->toArray();
		
		return $user_assets;
	}

//好友请求

	public function friend_request($uid){

		$user_assets = self::field('friend,friend_request')
			->where('user_id',$uid)
			->limit(1)
			->find()
			->toArray();
		
		return $user_assets;
	}

//排行榜
	public function total_rank()
	{

		$rank_data = Db::query('SELECT us.uid,b.rownum,us.username,us.avatar,us.level,b.integral from(SELECT t.*, @rownum := @rownum + 1 AS rownum 
			FROM (SELECT @rownum := 0) r, assets AS t
			ORDER BY t.integral DESC) as b left join user as us on us.uid=b.user_id order by b.integral desc limit 100');
		return $rank_data;


	}

//好友排行榜
	public function friend_rand($user_id,$page){

		$user_id = trim($user_id,',');

		$data = Db::query('SELECT us.uid,b.rownum,us.username,us.avatar,us.level from(SELECT t.*, @rownum := @rownum + 1 AS rownum 
			FROM (SELECT @rownum := 0) r, assets AS t
			ORDER BY t.integral DESC ) as b left join user as us on us.uid=b.user_id where b.user_id in ('.$user_id.') order by b.integral limit '.$page.',10');
		return $data;


	}


//指定用户排名
	public function user_rank($user_id){
		$user_id = trim($user_id,',');

		$data = Db::query('SELECT us.uid,b.rownum,us.username,us.avatar,us.level,b.integral from(SELECT t.*, @rownum := @rownum + 1 AS rownum 
			FROM (SELECT @rownum := 0) r, assets AS t
			ORDER BY t.integral DESC ) as b left join user as us on us.uid=b.user_id where b.user_id in ('.$user_id.')');

		return $data;
      
	}

//游戏结束获取用户排名
	public function game_user_rank($user_id){
		$user_id = trim($user_id,',');

		$data = Db::query('SELECT us.username,b.user_id,b.rownum,b.integral from(SELECT t.*, @rownum := @rownum + 1 AS rownum 
			FROM (SELECT @rownum := 0) r, assets AS t
			ORDER BY t.integral DESC ) as b left join user as us on us.uid=b.user_id where b.user_id in ('.$user_id.')');

		return $data;
      
	}




}