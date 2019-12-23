<?php
namespace app\index\controller;
use think\Db;


/**
*PHP是世界上最好的语言
*@param 首页控制器
**/
class Index extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//大厅首页
    public function index()
    {


    	$user_data = session('user_data');

    	//获取用户的资产信息
    	$assetsModel = Model('assets');
    	$user_assets = $assetsModel->user_assets($user_data['uid']);

    	//返回用户信息
		$return_data = [
			'uid'=>$user_data['uid'],
			'username'=>$user_data['username'],
			'tel'=>$user_data['tel'],
			'avatar'=>$user_data['avatar'],
		];
		$return_data = array_merge($return_data,$user_assets);

        $onlineUserModel = Model('Onlineuser');

        $onlineUserData = $onlineUserModel->online_user();
        $return_data = array_merge($return_data,$onlineUserData);

		echo json(return_msg('0000',lang('REQUEST_SUCCESS'),$return_data));
		return $this->fetch();

    }

//返回当前时间
  public function get_time(){
  
  	echo json(return_msg('0000','请求成功',time()));
  
  }







    


}
