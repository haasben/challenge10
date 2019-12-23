<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 支付方式控制器
**/

class Payment extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}


	public function payment()
	{
		$paymentModel = Model('payment');
		$data = $paymentModel->payment();
		echo json(return_msg('0000',lang('REQUEST_SUCCESS'),$data));
	}
}