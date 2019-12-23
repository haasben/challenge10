<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 在线人数控制器
**/

class OnlineUser extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//请求更新房间在线人数
	public function online_user()
	{
		$onlineUserModel = Model('Onlineuser');
        $onlineUserData = $onlineUserModel->online_user();
        echo json(return_msg('0000',lang('REQUEST_SUCCESS'),$onlineUserData));

	}

}