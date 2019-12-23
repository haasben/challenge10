<?php

/**
*@param
*/
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);



use \GatewayWorker\Lib\Gateway;
use Workerman\Lib\Timer;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events 
{

  /**
     * 新建一个类的静态成员，用来保存数据库实例
     */
    public static $db = null;
  	public static $url = null;
    public static function onConnect($client_id)
    {


        $data = ['type'=>'init','client_id'=>$client_id];
        Gateway::sendToClient($client_id,json_encode($data));

        // 向所有人发送
      //  Gateway::sendToAll("$client_id login\r\n");
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {


      
      $message_data = json_decode($message, true);
var_dump($message_data);

        if(!$message_data)
        {
            return ;
        }

        switch ($message_data['type']) {
          case 'join_room':
              
            //绑定用户id和房间
              $uid = $message_data['uid'];
              $room_id = $message_data['room_id'];
              Gateway::bindUid($client_id,$uid);
              $_SESSION['uid'] = $message_data['uid'];

              Gateway::joinGroup($client_id,$room_id);
              $_SESSION['room_id'] = $room_id;
            
            
            
			  $count = self::$db->query('SELECT count(*) from online_user where room_id="'.$room_id.'" and user_type = 1');

              if($count[0]['count(*)'] >=6){
                $message_data['user_type'] = 0;
              }
            	
              $is_user = self::$db->query('SELECT * from online_user where room_id="'.$room_id.'" and user_id = '.$uid);
              if($is_user){
              	self::$db->delete('online_user')->where('room_id = "'.$room_id.'" AND user_id ='.$uid)->query();
              
              }
            
            
              //添加数据到数据库
              self::$db->insert('online_user')->cols(array(
                  'user_id'=>$uid,
                  'room_id'=>$room_id,
                  'join_time'=>time(),
                  'user_type'=>$message_data['user_type']
              ))->query();

              //数据库在线人数+1
              self::$db->query('update `room` set max = max+1 where room_id="'.$room_id.'"');

              //获取该房间下所有的用户
              $user_data = self::$db->query('SELECT `user`.`vip`,`user`.`uid`,`user`.`username`,`user`.`avatar`,`user`.`music`,`user`.`sound_effect`,`user`.`shield`,`user`.`watch`,`online_user`.`user_type`,`online_user`.`join_time`,`assets`.`gold`,`assets`.`silver`  FROM `user` INNER JOIN `online_user` ON `user`.`uid` = `online_user`.`user_id` INNER JOIN  `assets` on `assets`.`user_id` = `online_user`.`user_id` WHERE `online_user`.`room_id` = "'.$room_id.'" ORDER BY `online_user`.`join_time`');
			  //修改用户位置状态
        	  self::$db->query("UPDATE `user` SET `status`=2 WHERE `uid`=".$uid);
              //设置房主
            	$i = 0;
                foreach ($user_data as $k => $v) {
                  if($v['user_type'] == 0){
                  		$user_data[$k]['is_homeowner'] = 0;
                  }else{
                    ++$i;
                  	if($i == 1){
                   		 $user_data[$k]['is_homeowner'] = 1;
                    }else{
                     	 $user_data[$k]['is_homeowner'] = 0;
                    }
                  }
                }
              $data = json_encode(['type'=>'active','data'=>$user_data]);
              GateWay::sendToGroup($room_id,$data);

            break;

          case 'bind':
            //绑定用户
              $uid = $message_data['uid'];
              $_SESSION['uid'] = $uid;
              $_SESSION['room_id'] = NULL;
              self::$db->query("UPDATE `user` SET `status`=1 WHERE `uid`=".$uid);
              Gateway::bindUid($client_id,$uid);
              $data = json_encode(['type'=>'bind','msg'=>'绑定成功']);
              Gateway::sendToClient($client_id,$data);
            break;

          case 'ceshi':
              $data = json_encode(['type'=>'ceshi','msg'=>date('H:i:s').'我收到了你的请求']);
              Gateway::sendToClient($client_id,$data);

            break;
          case 'ping':
              return;

            break;
          case 'treasure_bind':
                //绑定用户和群组
                $uid = $message_data['uid'];
                $group_id = $message_data['group_id'];

            	if(empty($uid) || empty($group_id)){
                	return ;
                }
            
                Gateway::bindUid($client_id,$uid);
                $_SESSION['uid'] = $uid;

                Gateway::joinGroup($client_id,$group_id);
                $_SESSION['group_id'] = $group_id;

                $is_hava_group_package = self::$db->query('select * from `group_package` where group_id='.$group_id);

                if(empty($is_hava_group_package)){
                  //查询房间等级及金币
                  $gold_package = self::$db->query('select * from `group` where group_id='.$group_id);

                  $gold = $gold_package[0]['gold'];

                  //添加数据到数据库
                  self::$db->insert('group_package')->cols(array(
                          'gold'=>$gold,
                          'group_id'=>$group_id,
                          'package'=>self::getPack($gold),
                          'ctime'=>time(),

                    ))->query();

                  $is_hava_group_package = self::$db->query('select * from `group_package` where group_id='.$group_id);

                }else{

                  $is_hava_group_package = self::$db->query('select * from `group_package` where group_id='.$group_id.' AND is_recive = 1');
      
                  if(empty($is_hava_group_package)){
                      return ;
                    }elseif((time()-$is_hava_group_package[0]['ctime']) <=2){
                      return ;
                    }

                }
                $package_id = $is_hava_group_package[0]['id'];
                    //发包人的ID
                $user_id = $is_hava_group_package[0]['user_id'];

                $user_data = self::$db->query('select username,avatar from `user` where uid='.$user_id);

                $data = ['type'=>1,'username'=>$user_data[0]['username'],'avatar'=>$user_data[0]['avatar'],'package_id'=>$package_id];
				
                //数据库在线人数+1
                self::$db->query('update `group` set max = max+1 where group_id="'.$group_id.'"');
                self::$db->query("UPDATE `user` SET `status`=3 WHERE `uid`=".$uid);
                Gateway::sendToClient($client_id,json_encode(['type'=>'package','data'=>$data]));
            	//获取该宝箱当前抢的人
            	 $record_data = self::$db->query('SELECT `user`.`username`,`user`.`uid`,`group_record`.`gold` FROM `user` INNER JOIN `group_record` ON `user`.`uid` = `group_record`.`user_id` WHERE `group_record`.`package_id` = "'.$package_id.'" and `group_record`.`type`=2 ORDER BY `group_record`.`get_time`');
                    if(!empty($record_data)){
                      $contractor = $user_data[0]['username'];
                      foreach ($record_data as $k => $v) {

                        $data = ['type'=>2,'username'=>$v['username'],'recipient'=>$contractor,'gold'=>$v['gold'],'uid'=>$v['uid']];
                                  $data = ['type'=>'package','data'=>$data];
                        Gateway::sendToClient($client_id,json_encode($data));
                      }
                    }

            break;



            case 'timed_robot':

              $is_open = $message_data['is_open'];
               $num_robot = $message_data['num_robot'];
               $time = $message_data['time'];
                if($worker->id === 0)
                {
                  $_SESSION['auth_timer_id']= Timer::add($time, function($num_robot){

                      var_dump($num_robot);


                    },array($num_robot), true);
                }



                $is_open = $message_data['is_open'];

                if($is_open == 1){

                  // $num_robot = $message_data['num_robot'];
                  // $time = $message_data['time'];

                  // $_SESSION['auth_timer_id']= Timer::add($time, function($num_robot){

                  //   var_dump($num_robot);


                  // },array($num_robot), true);


                }else{

                  var_dump(1);

                  Timer::del($_SESSION['auth_timer_id']);



                }

              break;

            
            

          default:
            
            return ;
            break;
        }


        
        //$uid = Gateway::getUidByClientId($client_id);

        //var_dump($message);
        // 向所有人发送 

        
   }

   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {

      if(isset($_SESSION['uid'])){
         $user_id = $_SESSION['uid'];
      }else{
        $user_id = '';
      }

      
      // 向所有人发送 
      
      if(empty($user_id)){
        return ;
      }
      // 向所有人发送 
      self::$db->query("UPDATE `user` SET `status`=0 WHERE `uid`=".$user_id);

      

      if(isset($_SESSION['group_id'])){

        $group_id = $_SESSION['group_id'];
        
        if(!empty($group_id)){

            self::$db->delete('group_online')->where('group_id = '.$group_id.' AND user_id ='.$user_id)->query();
             self::$db->query('update `group` set max = max-1 where group_id="'.$group_id.'"');
            $_SESSION['group_id'] = NULL;
        }
          
          return ;
      }
     
     
     
      $room_id = $_SESSION['room_id'];
      if(empty($room_id)){
         return ;
      }
      
 //提交掉线用户的数据到计算方法

      $challenge = self::$db->query("SELECT * FROM `challenge_record` WHERE  `user_id` = ".$user_id."  AND `is_win` = 3 order by cha_time DESC limit 1");

      if(!empty($challenge)){
        
                //添加数据到数据库

        
        $room_order = $challenge[0]['room_order'];
        file_get_contents('http://api.times168.net/chall_res?room_id='.$room_id.'&uid='.$user_id.'&result=2000&room_order='.$room_order);
      }
     
     
      //删除在线人数
      $row_count = self::$db->delete('online_user')->where('room_id = "'.$room_id.'" AND user_id ='.$user_id)->query();
      //房间人数减一
      self::$db->query('update `room` set max = max-1 where room_id="'.$room_id.'"');

      //返回在线用户数据
      $user_data = self::$db->query('SELECT `user`.`vip`,`user`.`uid`,`user`.`username`,`user`.`avatar`,`user`.`music`,`user`.`sound_effect`,`user`.`shield`,`user`.`watch`,`online_user`.`user_type`,`online_user`.`is_ready`,`online_user`.`join_time`,`assets`.`gold`,`assets`.`silver`  FROM `user` INNER JOIN `online_user` ON `user`.`uid` = `online_user`.`user_id` INNER JOIN  `assets` on `assets`.`user_id` = `online_user`.`user_id` WHERE `online_user`.`room_id` = "'.$room_id.'" ORDER BY `online_user`.`join_time`');
	  //修改用户为处于大厅状态 
      
      if(!empty($user_data)){
        $i = 0;
          foreach ($user_data as $k => $v) {
            $user_ready[] = ['uid'=>$v['uid'],'is_ready'=>$v['is_ready']];
            if($v['user_type'] == 0){
                  $user_data[$k]['is_homeowner'] = 0;
            }else{
              ++$i;
              if($i == 1){
                   $user_data[$k]['is_homeowner'] = 1;
              }else{
                   $user_data[$k]['is_homeowner'] = 0;
              }

            }
         
      }

        $return_data = json_encode(['type'=>'ready','msg'=>'用户准备就绪开始游戏','data'=>$user_ready]);
        GateWay::sendToGroup($room_id,$return_data);
        $data = json_encode(['type'=>'leave','data'=>$user_data]);
        GateWay::sendToGroup($room_id,$data);
        
      }else{

          //删除当前的空房间
          self::$db->delete('room')->where('room_id = "'.$room_id.'"')->query();

      }
      $_SESSION['room_id'] = NULL;
      
                     

      


      
   }

//主进程链接数据库
   public static function onWorkerStart($worker)
    {

      // self::$db = new \Workerman\MySQL\Connection('127.0.0.1', '3306', 'root', 'root', '10sgame');
      // self::$url = 'http://game.io';
    }
  	
        //生成指定个数的随机红包
	public static function getPack($total)
    {

        $totalnum = $total*0.9;
        $num = 5;
        $min = 1;
        $readPack = [];

          for ($i=1;$i<$num;$i++)   
          {   
              $safe_total=($totalnum-($num-$i)*$min)/($num-$i);//随机安全上限   
              $money=round(mt_rand($min*100,$safe_total*100)/100);   
              
              while (in_array($money,$readPack)) {
                
                $money +=1;
              }
              $totalnum=$totalnum-$money;
              //红包数据
              $readPack[$i-1]= $money;
          }

          //最后一个红包，不用随机       
          $readPack[] = $totalnum;

          if(count(array_unique($readPack)) !=5){

            $readPack = json_decode(self::getPack($total),true);
          }

		 	sort($readPack);
            $robot = $readPack[3];
            unset($readPack[3]);
            shuffle($readPack);   
            $readPack[] = $robot;
          //返回结果
         return trim(json_encode($readPack),'"');

      }
  
  
}
