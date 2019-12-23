<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 用户控制器
**/
class User extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}


//排行榜
    public function leaderboard()
    {

        $userModel = Model('user');

        $leaderboardData = $userModel->leaderboard();

        echo json(return_msg('0000',lang('REQUEST_SUCCESS'),$leaderboardData));

    }

    
//修改用户名
    public function edit_username(){
    	$user_name = input('username');

    	if(empty($user_name)){
    		echo json(return_msg('60001',lang('LACK_PARAM')));
    	}else{
    		$userModel = Model('user');

        	$userModel->edit_username($user_name);
			echo json(return_msg('0000',lang('EDIT_SUCCESS')));
    	}
    }


//个人信息详情
  	public function user_info()
  	{
  		$userModel = Model('user');
  		$data = $userModel->user_info();

  		echo json(return_msg('0000',lang('REQUEST_SUCCESS'),$data));


  	}


//获取别人的用户信息详情

    public function other_user_info(){

      $uid = input('uid');

      if(empty($uid)){
        echo json(return_msg('60001',lang('LACK_PARAM')));die;
      }

      $userModel = Model('user');
      $data = $userModel->other_user_info($uid);

      echo json(return_msg('0000',lang('REQUEST_SUCCESS'),$data));


    }

//修改用户信息
    public function update_userinfo()
    {
      $data = input();

      $result = $this->validate($data,
        [
            'username|用户名'  => 'require',
            //'signature|个性签名'   => 'require',
        ]);
        if(true !== $result){
            // 验证失败 输出错误信息
            echo json(return_msg(400,$result));die;
        }

      $userModel = Model('user');
      $data = $userModel->update_userinfo($data);


      echo json(return_msg('0000','修改成功'));


    }


//添加好友列表
    public function add_user_list()
    {

      $userModel = Model('user');

      $data = $userModel->add_user_list();

      echo json($data);

    }

//添加单个好友发送请求
    public function add_user(){

      $uid = input('uid');
      if(empty($uid)){
        echo json(return_msg('60001',lang('LACK_PARAM')));die;
      }

      $userModel = Model('user');

      $data = $userModel->add_user($uid);

      echo json(return_msg('0000','好友请求发送成功'));
    }



//批量添加好友

    public function batch_add_user(){

      $uid = input('uid');

      if(empty($uid)){
        echo json(return_msg('60001',lang('LACK_PARAM')));die;
      }

      $userModel = Model('user');

      $data = $userModel->batch_add_user($uid);

      echo json(return_msg('0000','好友请求发送成功'));


    }

//批量同意好友申请
    public function batch_agree_apply(){

      $uid = input('uid');
      if(empty($uid)){
          echo json(return_msg('60001',lang('LACK_PARAM')));die;
      }

      $userModel = Model('user');

      $data = $userModel->batch_agree_apply($uid);

      echo json(return_msg('0000','好友添加成功'));

      

    }

//批量拒绝添加好友

    public function batch_refuse_apply()
    {

      $uid = input('uid');
      if(empty($uid)){
          echo json(return_msg('60001',lang('LACK_PARAM')));die;
      }

      $userModel = Model('user');

      $data = $userModel->batch_refuse_apply($uid);

      echo json(return_msg('0000','已拒绝添加好友'));

    }


//我的好友列表

    public function self_friend(){

      $page = input('page');
  //分页页数
      if(empty($page)){
        $page = 0;
      }else{
        $page -=1;
      }

      $userModel = Model('user');
      //好友数据
      $data = $userModel->self_friend($page);
      //总人数
      $count = $userModel->total_user();

      // dump($data);die;

      echo json(['code'=>'0000','msg'=>'获取成功','data'=>$data,'count'=>$count]);

    }
//在线好友
  public function online_friend(){


    $uid = session('user_data')['uid'];
    //获取好友列表
    $friend = Db::name('assets')->where('user_id',$uid)->value('friend');

    $friend_data = Db::name('user')
      ->field('uid,username,level,avatar')
      ->where('uid','in',$friend)
      ->where('status',1)
      ->where('shield',0)
      ->select();

      echo json_encode(['code'=>'0000','msg'=>'获取成功','data'=>$friend_data]);


  }    

//删除好友
  public function del_friend(){

    $friend_id = input('friend_id');
    if(empty($friend_id)){
        echo json(return_msg('60001',lang('LACK_PARAM')));die;
    }
    $userModel = Model('user');
    $data = $userModel->del_friend($friend_id);

    echo json(return_msg('0000','删除成功'));

  }

 //会员到期时间和等级
  public function member_expire(){

    $uid = session('user_data')['uid'];

    $user_vip = Db::name('user')->field('vip,expire_time')->where('uid',$uid)->limit(1)->find();
    if($user_vip < time()){

      $user_vip['expire_time'] = NULL;
      $user_vip['vip'] = 0;

    }

    echo json(return_msg('0000','请求成功',$user_vip));die;


  }

  public function user_gold(){
  
  	$uid = session('user_data')['uid'];
    $gold = Db::name('assets')->where('user_id',$uid)->limit(1)->value('gold');
    
    echo json(['code'=>'0000','msg'=>'获取成功','gold'=>$gold]);

  
  }




}