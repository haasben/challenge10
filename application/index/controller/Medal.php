<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 勋章控制器
**/

class Medal extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}



//勋章列表
    public function user_medal(){
    	$medalModel = Model('medal');
    	$data = $medalModel->user_medal(); 

    	echo json(return_msg('0000',lang('REQUEST_SUCCESS'),$data));
    }


//单枚勋章详情
    public function medal_info(){
        $id = input('num_id');
        if(empty($id)){
            $data = return_msg('60001',lang('LACK_PARAM'));
        }else{
            $medalModel = Model('medal');
        //获取当前勋章的详细数据
            $data = $medalModel->medal_info($id); 
            $data = return_msg('0000',lang('REQUEST_SUCCESS'),$data);
        }
        echo json($data);
    }

//领取勋章金币

    public function receive_gold(){

        $num_id = input('num_id');
        if(empty($num_id)){
            echo json(return_msg('60001',lang('LACK_PARAM')));die;
        }

        $mdealModel = Db::name('medal');
        $medal = $mdealModel->where('num_id',$num_id)->limit(1)->find();

        if($medal['is_receive'] == 1){

            echo json(return_msg(40001,'勋章已被领取'));die;
        }
        $medal_list =  Db::name('medal_list')->where('id',$medal['medal_id'])->limit(1)->find();

        Db::name('assets')->where('user_id',cookie('user_data')['uid'])->setInc('gold',$medal_list['reward']);

        $mdealModel->where('num_id',$medal['num_id'])->update(['is_receive'=>1]);

        echo json(return_msg('0000','领取成功'));




    }








}