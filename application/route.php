<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    //index控制器
     '/' => 'index/index/index',
  	'get_time'=>'index/index/get_time',



     'edit_username'=>'index/user/edit_username',
     'leaderboard'=>'index/user/leaderboard',
     'user_info' =>'index/user/user_info',
     'other_user_info'=>'index/user/other_user_info',
     'update_userinfo'=>'index/user/update_userinfo',
     'add_user_list'=>'index/user/add_user_list',
     'add_user'=>'index/user/add_user',
     'batch_add_user'=>'index/user/batch_add_user',
     'batch_agree_apply'=>'index/user/batch_agree_apply',
     'batch_refuse_apply'=>'index/user/batch_refuse_apply',
     'self_friend'=>'index/user/self_friend',
     'online_friend'=>'index/user/online_friend',
  	 'del_friend'=>'index/user/del_friend',
  	 'member_expire'=>'index/user/member_expire',





     'user_medal'=>'index/medal/user_medal',
     'medal_info' =>'index/medal/medal_info',
     'receive_gold'=>'index/medal/receive_gold',

     'coupon' => 'index/doings/coupon',


     'bind_user' =>'push/bind/bind_user',


     'login'=>'index/login/login',
     'register'=>'index/login/register',
     'send_verifi_code'=>'index/login/send_verifi_code',
     'logout'=>'index/login/logout',
     'bind_phone'=>'index/login/bind_phone',
  	 'bind_send_verifi_code'=>'index/login/bind_send_verifi_code',
  	 'forget_password'=>'index/login/forget_password',
     'forget_send_verifi_code'=>'index/login/forget_send_verifi_code',

     'upload'=>'index/Upfiles/upload',


     'payment' =>'index/payment/payment',


     'online_user'=>'index/Online_user/online_user',


     'join_room'=>'index/room/join_room',
     'create_room'=>'index/room/create_room',
     'join_room_id'=>'index/room/join_room_id',
     'confirm_room_pass'=>'index/room/confirm_room_pass',
     'watch_game'=>'index/room/watch_game',
     'invite_friend_join'=>'index/room/invite_friend_join',
  	 




     'chall_res' => 'push/room/chall_res',
     'begin_game'=>'push/room/begin_game',
     'user_ready'=>'push/room/user_ready',
     'invite_friend'=>'push/room/invite_friend',
     'stranger'=>'push/room/stranger',
     'user_ready_home'=>'push/room/user_reday_home',
  	 'Kick_people'=>'push/room/Kick_people',
    'red_envelope'=>'push/room/red_envelope',
  	'sys_send_message'=>'push/room/sys_send_message',



     'user_sign'=>'index/sign/user_sign',
     'sign_list'=>'index/sign/sign_list',


     'set_music'=>'index/setting/set_music',
     'set_sound_effect'=>'index/setting/set_sound_effect',

     'set_shield'=>'index/setting/set_shield',
     'set_watch'=>'index/setting/set_watch',
  	 'set_info'=>'index/setting/set_info',


     'receive_daily_task'=>'index/task/receive_daily_task',
     'daily_task_list'=>'index/task/daily_task_list',
     'achievement_list'=>'index/task/achievement_list',
     'receive_achievement_task'=>'index/task/receive_achievement_task',
  	
  
  	 'recharge_active'=> 'index/active/recharge_active',
  	 'receive_silver'=>'index/active/receive_silver',
  	'is_recive_silver'=>'index/active/is_recive_silver',
  
//退款
    'refund_request'=>'index/refund/refund_request',
  	'refund_money_num'=>'index/refund/refund_money_num',
  
 //购买会员 
    'buy_vip'=>'index/buyvip/buy_vip',


];


