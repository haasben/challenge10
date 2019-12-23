<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Cache;
use think\Validate;
/**
*PHP是世界上最好的语言
*@param 用户模型
**/
class User extends Model
{
	protected $name = 'user';

	public function login($data){

		//判断该用户是否已经注册
		$user_data = self::where('tel',$data['tel'])->find();

		//根据参数返回状态
		if(empty($user_data)){
			return return_msg(20006,lang('REG_FIRST'));
		}elseif($user_data['password'] != encryption($data['password'])){
			return return_msg(20007,lang('NAME_OR_PASS_ERROR'));
		}elseif($user_data['is_lock'] == 1){

			return return_msg(20008,lang('LOCK'));

		}else{

			session('user_data',$user_data);
			$session_id = session_id();
			self::where('tel',$data['tel'])->update(['login_time'=>time(),'ip'=>$_SERVER['REMOTE_ADDR'],'session_id'=>$session_id]);
			
          	$user_data = self::where('uid',$user_data['uid'])->limit(1)->find();
          	session('user_data',$user_data);
          
			$assetsModel = Model('assets');
    		$user_assets = $assetsModel->user_assets($user_data['uid']);

			$return_data = [
				'uid'=>$user_data['uid'],
				'username'=>$user_data['username'],
				'tel'=>$user_data['tel'],
				'avatar'=>$user_data['avatar'],
			];
			$return_data = array_merge($return_data,$user_assets);

			return return_msg('0000',lang('LOGIN_SUCCESS'),$return_data);
		}

	}


//排行榜数据
	public function leaderboard(){

		$uid = session('user_data')['uid'];
		$assetsModel = Model('assets');


		//总排行榜
		$rank = $assetsModel->total_rank();

		$person_rank = $assetsModel->user_rank($uid);

		$data['rank'] = $rank;
		$data['person_rank'] = $person_rank;
		$data['count_num'] = $this->total_user();

		return $data;
	}

  //是否签到

	protected function is_sign($uid)
	{
		$signModel = Db::name('sign');
		$sign_data = $signModel->where('user_id',$uid)
		->where('stime',date('Ymd'))
		->limit(1)
		->find();
		$is_sign = 0;
		if(!empty($sign_data)){
			$is_sign = 1;
		}
		return $is_sign;


	}

//个人信息详情

	public function user_info(){
		
		$uid = session('user_data')['uid'];
      	//注册改推荐人
		if(!empty(input('refer_id'))){
			$refer_id = input('refer_id');
	        $reg_time = self::where('uid',$uid)->value('reg_time');
		        $date = time()-$reg_time;
		        if($date<30){
		        	$user_level = self::where('uid',$refer_id)->value('user_level'); 
                  	$user_level = trim($user_level.','.$refer_id,',');
		        	self::where('uid',$uid)->update(['refer_id'=>$refer_id,'user_level'=>$user_level]);
		        }
	      }
      	//获取用户信息
		$user_info = self::alias('u')
			->field('u.tel,a.victory,a.total_office,a.gold,a.silver,a.integral,u.vip,u.alipay,u.uid,u.username,u.level,u.avatar,u.signature,u.promote,a.friend,a.friend_request,u.session_id,u.openid')
			->join('assets a','a.user_id = u.uid')
			->where('uid',$uid)
			->find()
          	->toArray();
      	if(empty($user_info['tel'])){
	    	echo json(return_msg(20009,'请绑定手机号'));die;
	    }

      	if(session('user_data')['session_id'] != $user_info['session_id']){

			echo json(return_msg(20050,'你的账号已在其他地方登陆，请重新登陆'));die;

		}

		if(empty($user_info['promote'])){
	          $promote = create_code($uid);
	          self::isUpdate(true)->save(['uid'=>$uid,'promote'=>$promote]);
	          $user_info['promote'] = $promote;
      	}

//用户好友总数。添加自己好友的人数
      	$user_info['total_friends'] = 0;
      	if(!empty($user_info['friend'])){
      		$user_info['total_friends'] = count(explode(',', trim($user_info['friend'],',')));
      	}
      	$user_info['total_friends_request'] = 0;
      	if(!empty($user_info['friend_request'])){

      		//添加改用户的好友人数
      		$total_friends_request = array_unique(explode(',', trim($user_info['friend_request'],',')));
      		$user_info['total_friends_request'] = count($total_friends_request);
      	}

      	unset($user_info['friend']);
      	unset($user_info['friend_request']);

//根据积分更改等级
      	$integral = $user_info['integral'];
	    $level = 0;
	    while($integral >= 0) {
	        $integral -= 100 + $level++ * 100;

	    }
	    $level -=1;
      	$sumption = 0;
	    for ($i=0; $i <$level+1; $i++) { 
	    	$sumption +=$i*100;
	    }
	    $user_info['sumption'] = $sumption;
	    $user_info['next_int'] = abs($integral);
	    if($level != $user_info['level']){
	    	self::where('uid',$uid)->update(['level'=>$level]);
	    	$user_info['level'] = $level;
	    }
      //获取用户是否签到
		$user_info['is_sign'] = $this->is_sign($uid);
		$medalModel = Model('medal');

		$medalNum = $medalModel->medal_num($uid);
		$user_info['medal_num'] = $medalNum;
		return $user_info;

	}


//其他人个人信息详情
	public function other_user_info($uid){
		

		$user_info = self::alias('u')
			->field('a.victory,a.total_office,a.gold,a.silver,a.integral,u.uid,u.username,u.level,u.avatar,a.friend,u.vip')
			->join('assets a','a.user_id = u.uid')
			->where('u.uid',$uid)
			->find();
		
		$user_info['is_friend'] = 0;
		if(in_array(session('user_data')['uid'], explode(',', $user_info['friend']))){

			$user_info['is_friend'] = 1;

		}
		unset($user_info['friend']);

//根据积分更改等级
		$integral = $user_info['integral'];
	    $level = 0;
	    while($integral >= 0) {
	        $integral -= 100 + $level++ * 100;

	    }
	    $level -=1;
      	$sumption = 0;
	    for ($i=0; $i <$level+1 ; $i++) { 
	    	$sumption +=$i*100;
	    }
	    $user_info['sumption'] = $sumption;
	    $user_info['next_int'] = abs($integral);
	    if($level != $user_info['level']){
	    	self::where('uid',$uid)->update(['level'=>$level]);
	    	$user_info['level'] = $level;
	    }

		 $medalModel = Model('medal');

		 $medalNum = $medalModel->medal_num($uid);
		 $user_info['medal_num'] = $medalNum;

		 return $user_info;

	}



/**
*@param 修改昵称
*/
	public function edit_username($username){

		$update_username = self::where("uid",session('user_data')['uid'])
			->update(['username'=>$username]);


	}

//修改用户头像，用户名，签名

	public function update_userinfo($data){
		$data['uid'] = session('user_data')['uid'];
		$data = self::isUpdate(true)
			->save($data);

	}

//添加好友列表

	public function add_user_list()
	{

		$uid = session('user_data')['uid'];

		$assetsModel = Model('assets');
		$friend_request = $assetsModel->friend_request($uid);
		$count = self::total_user();
		if(empty($friend_request['friend_request'])){

			$user_data = $this->random_friend($friend_request['friend'],$uid);
			

			return ['code'=>'0000','msg'=>'获取成功','type'=>2,'data'=>$user_data,'count'=>$count];

		}else{

			$user_data = $this->add_self_list($friend_request['friend_request']);

			return ['code'=>'0000','msg'=>'获取成功','type'=>1,'data'=>$user_data,'count'=>$count];
		}
	}

//添加自己好友的列表

	public function add_self_list($friend)
	{

		$assetsModel = Model('assets');
		$user_data = $assetsModel->user_rank($friend);

		return $user_data;


	}

//随机出现添加好友列表

	public function random_friend($friend,$uid)
	{

		$friend.= ','.$uid;
		$user_data = self::field('uid')
			->where('uid','not in',$friend)
			->limit(10)
			->select();

		if(!empty($user_data)){
			$assetsModel = Model('assets');

			$user_id = '';
			foreach ($user_data as $k => $v) {
				$user_id.=$v['uid'].',';
			}
			$user_data = $assetsModel->user_rank($user_id);

		}

		return $user_data;
	}



//点击发送单个好友请求

	public function add_user($add_uid){

		$uid = session('user_data')['uid'];

		$assetsModel = Db::name('assets');


		$friend = $assetsModel->where('user_id',$add_uid)->limit(1)->find();

		//判断是否是该用户的好友
		$is_have_friend = explode(',',$friend['friend']);

		if(in_array($uid,$is_have_friend)){
			echo json(return_msg('70001','该用户已经是你的好友'));die;
		}
		//判断是否发送给好友请求
		$is_have_friend = explode(',',$friend['friend_request']);

		if(in_array($uid,$is_have_friend)){
			echo json(return_msg('70001','你已发送过好友请求，请等待用户确认'));die;
		}	
      
		$friend_request = $friend['friend_request'];

		Db::execute("update assets set friend_request=CONCAT('".$uid."',',',friend_request) where user_id=".$add_uid);

	}



//点击批量发送好友请求
	public function batch_add_user($add_uid){

		$uid = session('user_data')['uid'];

		$assetsModel = Db::name('assets');

		$add_uid = '('.trim($add_uid,',').')';

		Db::execute("update assets set friend_request=CONCAT(friend_request,',','".$uid."') where user_id in".$add_uid);

	}

//点击批量同意好友请求
	public function batch_agree_apply($add_uid){

		$uid = session('user_data')['uid'];

		$assetsModel = Db::name('assets');


		$friend_uid = explode(',',$add_uid);
		//添加个人好友
		Db::execute("update assets set friend=CONCAT(friend,',','".$add_uid."') where user_id=".$uid);
		
		//删除好友申请信息
		$friend_request =  Db::name('assets')->where('user_id',$uid)->limit(1)->value('friend_request');
		$friend_request = explode(',',trim($friend_request,','));
		$new_request = '';
		foreach ($friend_request as $k => $v) {
			if(!in_array($v, $friend_uid)){
				$new_request .= $v.',';
			}
		}
		Db::name('assets')->where('user_id',$uid)->update(['friend_request'=>trim($new_request,',')]);

		//修改其他用户的好友
		$add_uid = '('.trim($add_uid,',').')';
		Db::execute("update assets set friend=CONCAT(friend,',','".$uid."') where user_id in ".$add_uid);

	}

//批量拒绝添加好友

	public function batch_refuse_apply($refuse_uid)
	{
		$uid = session('user_data')['uid'];
		$friend_uid = explode(',',$refuse_uid);
		$friend_request =  Db::name('assets')->where('user_id',$uid)->limit(1)->value('friend_request');
		$friend_request = explode(',',trim($friend_request,','));
		$new_request = '';
		foreach ($friend_request as $k => $v) {
			if(!in_array($v, $friend_uid)){
				$new_request .= $v.',';
			}
		}
		Db::name('assets')->where('user_id',$uid)->update(['friend_request'=>trim($new_request,',')]);

	}

//我的好友列表

	public function self_friend($page){
		$uid = session('user_data')['uid'];
		$assetsModel = Model('assets');
		$friend =  $assetsModel->where('user_id',$uid)->limit(1)->value('friend');
		$friend = $friend.','.$uid;
		$data = $assetsModel->friend_rand($friend,$page);
		return $data;



	}

//当前数据库用户总人数
	public function total_user()
	{

		$count = self::count();
		return $count;

	}
  
//删除好友
	public function del_friend($friend_id)
	{

		$uid = session('user_data')['uid'];

		$assetsModel = Db::name('assets');

//删除自己好友列表用户
		$friend = $assetsModel->where('user_id',$uid)->value('friend');

		$friend_arr = explode(',', trim($friend,','));

		foreach ($friend_arr as $k => $v) {

			if($v == $friend_id){
				unset($friend_arr[$k]);
			}
		}
		$friend_arr = implode(',',$friend_arr);

		$assetsModel->where('user_id',$uid)->update(['friend'=>$friend_arr]);

//删除对方好友列表自己
		$friend1 = $assetsModel->where('user_id',$friend_id)->value('friend');

		$friend_arr1 = explode(',',trim($friend1,','));
		foreach ($friend_arr1 as $k => $v) {
			if($v == $uid){
				unset($friend_arr1[$k]);
			}
		}

		$friend_arr1 = implode(',',$friend_arr1);

		$assetsModel->where('user_id',$friend_id)->update(['friend'=>$friend_arr1]);




	}





}
