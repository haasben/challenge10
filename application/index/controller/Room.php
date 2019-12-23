<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 房间控制器
**/

class Room extends Common
{   

    public function _initialize()
    {
        parent::_initialize();
    }


//输入密码创建房间
    public function create_room()
    {

       $level = input('level');
       $room_gold = input('room_gold');
       $password = '';
       if(input('?homePassword')){
            $password = input('homePassword');
       }

       if(empty($level)){
            echo json(return_msg('60001',lang('LACK_PARAM')));
        }else{
            $roomModel = Model('room');
            $data = $roomModel->sys_create_room($level,$password,$room_gold);
            //判断用户的资金是否足够或者是否高于该场次
            if($level == 1){
                $gold = Db::name('assets')->where('user_id',session('user_data')['uid'])->value('silver');
                $msg = '银币不足';
            }else{
                $gold = Db::name('assets')->where('user_id',session('user_data')['uid'])->value('gold');
                $msg = '金币不足';
            }
            
            if($gold < $room_gold){

                echo json(return_msg(30006,$msg));die;

            }

            echo json(return_msg('0000',lang('REQUEST_SUCCESS'),$data));
        }



    }


//输入房间号加入房间
    public function join_room_id(){

        $room_id = input('room_id');
        if(empty($room_id)){
            echo json(return_msg('60001',lang('LACK_PARAM')));
        }else{

            $roomModel = Model('room');
            $data = $roomModel->is_have_room($room_id);

            if(!$data){
                echo json(return_msg('30001','房间不存在'));
            }else{
             
                //判断用户金币或者银币是否充足
                    $room_data = Db::name('room')->where('room_id',$room_id)->limit(1)->find();
                    if($room_data['level'] == 1){
                        $gold = Db::name('assets')->where('user_id',session('user_data')['uid'])->value('silver');
                        $msg = '银币不足';
                    }else{
                        $gold = Db::name('assets')->where('user_id',session('user_data')['uid'])->value('gold');
                        $msg = '金币不足';
                    }

                    if($gold < $room_data['room_gold']){
                        echo json(return_msg(30006,$msg));die;
                    }
		
                if(empty($data['password'])){
                    //判断房间人数
                    if($room_data['max'] >=6){
                        echo json(return_msg('30003','房间人数已满'));die;
                    }
                    echo json(return_msg('0000',lang('REQUEST_SUCCESS'),$data));
                }else{
                    echo json(return_msg('30002','请输入房间密码'));
                }
            }

        }

    }

//验证密码是否正确
    public function confirm_room_pass(){

        $room_id = input('room_id');
        $password = input('homePassword');

        if(empty($room_id) || empty($password)){
            echo json(return_msg('60001',lang('LACK_PARAM')));
        }else{
            $max = Db::name('room')->where('room_id',$room_id)->value('max');
            if($max >=6){
                echo json(return_msg('30003','房间人数已满'));die;

            }
            $roomModel = Model('room');
            $data = $roomModel->confirm_room_pass($room_id,$password);
            echo json($data);
        }


    }


    //匹配房间没有房间自动创建
    public function join_room()
    {

        $level = input('level');

        if(empty($level)){
            echo json(return_msg('60001',lang('LACK_PARAM')));die;
        }

        $room_gold = input('room_gold');
        
        //判断用户金币或者银币是否充足
        if($level == 1){
            $gold = Db::name('assets')->where('user_id',session('user_data')['uid'])->value('silver');
            $msg = '银币不足';
        }else{
            $gold = Db::name('assets')->where('user_id',session('user_data')['uid'])->value('gold');
            $msg = '金币不足';
        }
        if($gold < $room_gold){
            echo json(return_msg(30006,$msg));die;
        }

        $roomModel = Db::name('room');
        $empty_room = $roomModel
            ->field('id,room_id,level,create_type,room_gold')
            ->where('max','<',6)
            ->where('level',$level)
            ->where('use_pass',0)
            ->where('room_gold',$room_gold)
            ->where('is_in_game',0)
            ->limit(1)
            ->order('max desc')
            ->find();
        
        if(empty($empty_room)){
            $roomModel = Model('room');
            $data = $roomModel->sys_create_room($level,$password='',$room_gold);
        }else{
            $data = ['id'=>$empty_room['id'],'room_id'=>$empty_room['room_id'],'level'=>$empty_room['level'],'room_gold'=>$empty_room['room_gold']];
        }


        echo json(return_msg('0000',lang('REQUEST_SUCCESS'),$data));

    }

//观战
     public function watch_game(){


       $room_level = input('room_level');

       $data = Db::name('online_user')
            ->field('room_id,room_level')
            ->where('room_level',$room_level)
            ->group('room_id')->having('room_id<14')->limit(1)
            ->find();
        if(empty($data)){
             echo json(return_msg(30007,'暂无可观战房间'));die;

        }

        $data['user_type'] = 0;
        $data['uid'] = session('user_data')['uid'];
        echo json(return_msg('0000','获取成功',$data));

    }
  
  //邀请加入房间
    public function invite_friend_join(){

        $room_id = input('room_id');
        $uid = session('user_data')['uid'];
        //查询用户金币是否充足
        $user_data = Db::name('assets')->where('user_id',$uid)->find();
        $room_data = Db::name('room')->where('room_id',$room_id)->limit(1)->find();
        if($room_data['level'] == 1){
            $gold = $user_data['silver'];
            $msg = '银币不足';
        }else{
            $gold = $user_data['gold'];
            $msg = '金币不足';
        }
		if($gold < $room_data['room_gold']){

            echo json(return_msg(30006,$msg));die;
        }elseif($room_data['max'] >=6){

            echo json(return_msg(30009,'房间人数已满'));
            
        }elseif($room_data['max'] == 0){

            echo json(return_msg(30009,'房间已空'));
        }else{

            echo json(return_msg('0000','请求成功',$room_data));

        }
    }
    










}