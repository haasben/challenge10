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
class Group extends controller
{	

	public function _initialize(){
       // if(session('user_data')){
            //Db::name('user')->where('uid',session('user_data')['uid'])->update(['login_time'=>time()]);
       // }
        
        Gateway::$registerAddress = '127.0.0.1:1238';

    }



//加入宝箱群组
	public function join_group(){


		$group_level = input('level');

		if(empty($group_level)){

			echo json(return_msg(60001,'房间等级不能为空'));die;
		}

		switch ($group_level) {
				case '1':
					$gold = 100;
					break;
				case '2':
					$gold = 500;
					break;
				case '3':
					$gold = 1000;
					break;
				case '4':
					$gold = 5000;
					break;
				case '5':
					$gold = 10000;
					break;
				default:
					$gold = 100;
					break;
		}

		//判断用户金币是否足够
		$uid = session('user_data')['uid'];

		$groupModel = Db::name('group');

		$group_id = $groupModel->where('max','<',100)->where('level',$group_level)->limit(1)->value('group_id');

		if(empty($group_id)){

			$group_id = $groupModel->insertGetId([
				'max'=>0,
				'level'=>$group_level,
				'c_time'=>time(),
				'gold'=>$gold,
			]);

		}

		echo json(return_msg('0000','请求成功',$group_id));

	}


//用户抢包
	public function grab_bag(){


		 //cache::clear();die;

		if(empty($package_id)){
			$package_id = input('package_id');
		}


		$group_packageModel = Db::name('group_package');
			//包信息
		$package_info = $group_packageModel->where('id',$package_id)->where('is_recive',1)->limit(1)->find();

     // Db::name('ceshi')->insert(['info1'=>$package_id,'info2'=>json_encode($package_info)]);

		if(empty($package_info)){
				echo json(return_msg(50003,'该宝箱已空'));die;
			}

		if(empty($uid)){
			$uid = session('user_data')['uid'];
		}

		
      	//$uid = input('uid');
		 //$uid = 100004;
		//判断用户金币是否足够
		$assetsModel = Db::name('assets');
		$gold = $assetsModel->where('user_id',$uid)->limit(1)->value('gold');
		
		if($gold < $package_info['gold']){

			echo json(return_msg(30006,'金币不足'));die;

		}


		$group_recordModel = Db::name('group_record');

		if(cache('package_id_'.$package_id.'_'.$uid)){

			echo json(return_msg(60001,'你已经抢过这个宝箱了'));die;

		}else{

			cache('package_id_'.$package_id.'_'.$uid,1);
		}

		if(!cache('package_id'.$package_id.'_l')){
			$num = 1;
			cache('package_id'.$package_id.'_l',$num);
		}else{

			$num = cache('package_id'.$package_id.'_l')+1;
			cache('package_id'.$package_id.'_l',$num);

		}
		//当前点击该包的人数

		$num = cache('package_id'.$package_id.'_l');
      	Db::name('ceshi')->insert(['info1'=>$package_id,'info2'=>json_encode($num)]);
		if($num<5){
			//扣除用户对应金币
			
			$assetsModel->where('user_id',$uid)->setDec('gold',$package_info['gold']);
	       
			$package = json_decode($package_info['package'],true);

			//发包人
			$contractor = Db::name('user')
				->field('username,avatar')
				->where('uid',$package_info['user_id'])
				->limit(1)
				->find();
			

			$gold = $package[$num-1];
			//添加用户抢红包记录

			$add_data = [
				'user_id'=>$uid,
				'gold'=>$gold,
				'get_time'=>time(),
				'deposit'=>$package_info['gold'],
				'type'=>2,
				'group_id'=>$package_info['group_id'],
				'package_id'=>$package_info['id'],

			];
			$group_recordModel->insert($add_data);
			$data = ['type'=>2,'username'=>session('user_data')['username'],'recipient'=>$contractor['username'],'gold'=>$gold,'uid'=>$uid];
			
			$data = ['type'=>'package','data'=>$data];

			Gateway::sendToGroup($package_info['group_id'],json($data));
			echo json(return_msg('0000','领取成功'));

			if($num ==4){
				ignore_user_abort(true);
              	set_time_limit(0);
				//如果没有机器人缓存
					$gold = $package[$num];
					$num+=1;
					$robot = $this->robot();

					$add_data = [
						'user_id'=>$robot['uid'],
						'gold'=>$gold,
						'get_time'=>time(),
						'deposit'=>$package_info['gold'],
						'type'=>2,
						'group_id'=>$package_info['group_id'],
						'package_id'=>$package_info['id'],

					];
					$group_recordModel->insert($add_data);
					$data = ['type'=>2,'username'=>$robot['username'],'recipient'=>$contractor['username'],'gold'=>$gold];
					
					$data = ['type'=>'package','data'=>$data];

					Gateway::sendToGroup($package_info['group_id'],json($data));
					$assetsModel->where('user_id',$robot['uid'])->setDec('gold',$package_info['gold']);
				
			}


			if($num == 5){

				$win_data = $group_recordModel->alias('gr')
					->field('gr.gold,u.username,u.uid,u.avatar,u.type,u.is_robot')
					->join('user u','u.uid=gr.user_id')
					->where('package_id',$package_id)
					->where('gr.type',2)
					->order('gold')
					->select();
				$uid_str = '';
				foreach ($win_data as $k => $v) {
					cache('package_id_'.$package_id.'_'.$v['uid'],NULL);

					if($package_info['user_id'] ==1){
						if($k !=0){
							$assetsModel->where('user_id',$v['uid'])->setInc('gold',$package_info['gold']);
						}

					}else{
						if($k != 0){
						//红包领取完毕给用户返回压的金币
							$assetsModel->where('user_id',$v['uid'])->setInc('gold',($v['gold']+$package_info['gold']));
						}else{
							$assetsModel->where('user_id',$v['uid'])->setInc('gold',$v['gold']);

							if($v['type'] !=2 && $v['is_robot'] == 1){

									$user_level = Db::name('user')->where('uid',$v['uid'])->limit(1)->value('user_level');
									$assetsModel->where('user_id','in',$user_level)->update([
										'today_money' => Db::raw('today_money+'.$package_info['gold']*0.1),//加总房费
		                            	'money'=>Db::raw('money+'.$package_info['gold']*0.1),//加今日房费

									]);


								
							}
							
						}
					}

				}

				$data = ['type'=>3,'max_username'=>$win_data[4]['username'],'max_gold'=>$win_data[4]['gold'],'min_username'=>$win_data[0]['username'],'min_gold'=>$win_data[0]['gold'],'package_id'=>$package_id];
				$data = ['type'=>'package','data'=>$data];
				Gateway::sendToGroup($package_info['group_id'],json($data));
				//改变当前红包的状态
				$group_packageModel->where('id',$package_id)->update(['is_recive'=>0]);
              
                


                $package_id = $group_packageModel->insertGetId([
                      'gold'=>$package_info['gold'],
                      'user_id'=>$win_data[0]['uid'],
                      'group_id'=>$package_info['group_id'],
                      'package'=>$this->getPack($package_info['gold']),
                      'ctime'=>time(),
                ]);

                $group_recordModel->insert([
                	'user_id'=>$win_data[0]['uid'],
                	'gold'=>$package_info['gold'],
                	'get_time'=>time(),
                	'group_id'=>$package_info['group_id'],
                	'package_id'=>$package_id,


                ]);

                file_get_contents(THIS_URL.'/push/send/add_timer?pack_type=1&group_id='.$package_info['group_id']);

              //cache('package_id'.$package_id.'_l',NULL);


			}

			
		}else{

			echo json(return_msg(60001,'该宝箱已空'));die;

		}



	}

public function group_result(){

	$group_id = input('group_id');

	$package = Db::name('group_package')->alias('gp')
		->field('gp.id,gp.gold,u.username,u.avatar')
		->join('user u','u.uid = gp.user_id')
		->where('gp.group_id',$group_id)
		->where('gp.is_recive',1)
		->find();



    $data = ['type'=>1,'username'=>$package['username'],'avatar'=>$package['avatar'],'package_id'=>$package['id']];
  	$data = ['type'=>'package','data'=>$data];
  	Gateway::sendToGroup($group_id,json($data));
  	echo json_encode(['code'=>'0000','msg'=>'请求成功']);



}





//随机机器人
protected function robot(){

	$robot_array = [100001,100002,100003,100004,100032,100033,100034,100035,100155];

	$uid = $robot_array[array_rand($robot_array)];


	$robot = Db::name('user')->field('username,uid')->where('uid',$uid)->limit(1)->find();

	return $robot;

}




//生成指定个数的随机红包
	public function getPack($total)
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

        	$readPack = json_decode($this->getPack($total),true);
        }elseif(min($readPack) == 0){
        	$readPack = json_decode($this->getPack($total),true);
        }

            sort($readPack);
            $robot = $readPack[3];
            unset($readPack[3]);
            shuffle($readPack);   
            $readPack[] = $robot;

        //返回结果
        return trim(json_encode($readPack),'"');
    }


	//查询抢宝箱记录

    public function package_record()
    {
    	$package_id = input('package_id');
    	if(empty($package_id)){

    		echo json(return_msg(60001,'缺少必要参数'));die;

    	}
   //查询记录
    	$group_recordModel = Db::name('group_record');

    	$group_record_data = $group_recordModel
    	->alias('gr')
    	->field('u.uid,u.username,u.avatar,gr.gold,gr.get_time')
    	->join('user u','u.uid = gr.user_id')
    	->where('package_id',$package_id)
    	->where('gr.type',2)
    	->order('gr.gold desc')
    	->select();
    	foreach ($group_record_data as $k => $v) {
    		$group_record_data[$k]['get_time'] = date('m.d H:i:s',$v['get_time']);
    	}

    	$gold = $group_recordModel->where('package_id',$package_id)->where('type',1)->value('gold');


    	$data['gold'] = $gold;
    	$data['package_record'] = $group_record_data;

    	echo json(return_msg('0000','查询成功',$data));


    }

	//查询今日我抢到的

    public function today_grabbed_record(){

    	$uid = session('user_data')['uid'];


    	$group_recordModel = Db::name('group_record');


//我抢到的宝箱个数
    	$totalGet = $group_recordModel
    	->whereTime('get_time','today')
    	->where('type',2)
    	->where('user_id',$uid)
    	->count();

    	$data['totalGet'] = $totalGet;
//我抢到的宝箱总金币
    	$totalGetGold = $group_recordModel
    	->whereTime('get_time','today')
    	->where('type',2)
    	->where('user_id',$uid)
    	->sum('gold');
    	$data['totalGetGold'] = $totalGetGold;

    	$group_record_data = $group_recordModel
	    	->alias('gr')
	    	->field('u.uid,gr.gold,gr.get_time,gr.package_id as id')
	    	->join('user u','u.uid = gr.user_id')
	    	->whereTime('get_time','today')
	    	->where('gr.type',2)
	    	->where('gr.user_id',$uid)
	    	->order('gr.package_id desc')
	    	->select();

	    $package_id = '';
	    foreach ($group_record_data as $key => $value) {
	    
	    	$package_id.=$value['id'].',';

	    }

	    $group_packageModel = Db::name('group_package');
	    //循环获取该用户对应领取的数据
	    $send_record_data = $group_packageModel
	    	->alias('gp')
	    	->field('u.username,u.avatar,gp.id')
	    	->join('user u','u.uid = gp.user_id')
	    	->where('gp.id','in',$package_id)
	    	->order('gp.id desc')
	    	->select();
	    foreach ($send_record_data as $k => $v) {
	    	$send_record_data[$k]['gold'] = $group_record_data[$k]['gold'];
	    	$send_record_data[$k]['get_time'] = date('m.d H:i:s',$group_record_data[$k]['get_time']);
	    }
	    $data['IGot'] = $send_record_data;
//我发出的
	    $totalPut = $group_recordModel
    	->whereTime('get_time','today')
    	->where('type',1)
    	->where('user_id',$uid)
    	->count();
    	$data['totalPut'] = $totalPut;
//我发出的金币
    	$totalPutGold = $group_recordModel
    	->whereTime('get_time','today')
    	->where('type',1)
    	->where('user_id',$uid)
    	->sum('gold');
    	$data['totalPutGold'] = $totalPutGold;

    	$group_put_data = $group_recordModel
	    	->alias('gr')
	    	->field('u.uid,gr.gold,gr.get_time,u.username,u.avatar')
	    	->join('user u','u.uid = gr.user_id')
	    	->whereTime('get_time','today')
	    	->where('gr.type',1)
	    	->where('gr.user_id',$uid)
	    	->order('gr.package_id desc')
	    	->select();
		foreach ($group_put_data as $k => $v) {
	    	$group_put_data[$k]['get_time'] = date('m.d H:i:s',$v['get_time']);
	    }
      
      
	    $data['IPut'] = $group_put_data;


    	echo json(return_msg('0000','请求成功',$data));


    }

//获取当前局抢宝箱的记录
    public function this_record()
    {

    	$group_id = input('group_id');
    	$uid = input('uid');
//包ID
    	$package_id = Db::name('group_package')
    	->where('is_recive',1)
    	->where('group_id',$group_id)
    	->order('ctime')
    	->limit(1)
    	->find();

		if(!empty($package_id['id'])){

			$record_data = Db::name('group_record')
				->alias('gr')
				->field('u.username,gr.gold,u.uid')
				->join('user u','u.uid =gr.user_id')
				->where('gr.package_id',$package_id['id'])
				->where('gr.type',2)
				->order('get_time')
				->select();

			if(!empty($record_data)){
				$contractor = Db::name('user')->where('uid',$package_id['user_id'])->limit(1)->value('username');
				foreach ($record_data as $k => $v) {
					
					$data = ['type'=>2,'username'=>$v['username'],'recipient'=>$contractor,'gold'=>$v['gold']];
                  	$data = ['type'=>'package','data'=>$data];
					//Gateway::sendToUid($uid,json_encode($data));
				}
			}
	
		}

		// echo json(return_msg('0000','请求成功'));



    }

		
//定时任务机器人抢发红包

public function timed_robot(){

	$sys_data = Db::name('sys_set')->where('id',1)->find();

	if($sys_data['is_open'] == 1){


		$date = date('H');
		$package = Db::name('group_package')
			->field('id')
			->where('is_recive',1)
			->where('group_id','<',4)
			->select();

		$robot_array = [100001,100002,100003,100004,100032,100033,100034,100035,100155];


		if($date >8 && $date<22){

			for ($i=0; $i <$sys_data['num_robot'];$i++) { 

				$rand = mt_rand(1,3);
				sleep($rand);

				$package_id = $package[array_rand($package)]['id'];

				$k = array_rand($robot_array);
				$uid = $robot_array[$k];

				$data = ['uid'=>$uid,'package_id'=>$package_id];
				juhecurl(THIS_URL.'/treasure/group/grab_bag',$data,0);

			}

		}elseif($date>=22 && $date<=8){

			echo 2;

		}





	}else{

		return false;

	}


}



	






    


}
