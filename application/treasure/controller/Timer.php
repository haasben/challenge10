<?php
namespace app\treasure\controller;
use think\Db;
use think\Controller;
use think\Cache;
use GatewayClient\Gateway;


/**
*PHP是世界上最好的语言
*@param 首页控制器
**/
class Timer extends controller
{	



	public function add_timer(){

		set_time_limit(0);
		ignore_user_abort(true);
		$bool = include_once(ROOT_PATH.'timer.php');
		
		do{

			$sys_data = Db::table('sys_set')->where('id',1)->find();

			if($sys_data['is_open'] == 1){

				$date = date('H');
				
				if($date >8 && $date<24){
					$package = Db::table('group_package')
					->field('id')
					->where('is_recive',1)
					->where('group_id',1)
					->select();

                    $robot_array = Db::table('user')->field('uid')->where('uid','>',100215)->where('is_robot',0)->select();
                    $robot_array = array_column($robot_array,'uid');	

					for ($i=0; $i <($sys_data['num_robot']-2);$i++) { 
						$mt_rand = mt_rand(1,3);
						sleep($mt_rand);
						$package_id = $package[array_rand($package)]['id'];

						$k = array_rand($robot_array);
						$uid = $robot_array[$k];
						
						$data = ['uid'=>$uid,'package_id'=>$package_id];
                      
						juhecurl(THIS_URL.'/treasure/group/grab_bag',$data,1);

					}

				}
                
                
                
                }

                
			    $mt_rand = mt_rand(3,5);
			    sleep($mt_rand);

			    $bool = include_once(ROOT_PATH.'timer.php');

			}while ($bool);




		}


	
}

