<?php
namespace app\index\controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 支付方式控制器
**/

class Task extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//每日任务列表
	public function daily_task_list(){

		$taskListModel = Db::name('task_list');
		$challengeRecordModel = Db::name('challenge_record');
		$uid = session('user_data')['uid'];
//任务列表
		$task_list = $taskListModel
			->field('id,type,name,reach,img,gold')
			->where('type',1)
			->where('status',1)
			->select();
//今日已领取的奖励
		$join = [
		    ['task_list tl','tl.id = t.pid'],
		];
		$is_recived = Db::name('task')
			->alias('t')
			->field('tl.*,t.is_recived,t.pid')
			->join($join)
			->where('t.uid',$uid)
			->where('tl.status',1)
			->where('tl.type',1)
			->whereTime('t.time', 'today')
			->select();
		$array = [];
		foreach ($is_recived as $k => $v) {
			$array[] = $v['pid'];
		}

		$task_list_data = [];

		foreach ($task_list as $k => $v) {
			if(!in_array($v['id'],$array)){
				$task_list_data[$k] = $v;
					$count = $challengeRecordModel
					->where('user_id',$uid)
					->where('gold',$v['gold'])
                    ->where('cha_type','>',1)
					->whereTime('cha_time', 'today')
					->count();

				$task_list_data[$k]['cha_count'] = $count;
			}
		}
		foreach ($task_list_data as $k => $v) {
			if(!isset($v['id'])){

				unset($task_list_data[$k]);
			}
		}

		$data['task_list'] = array_values($task_list_data);

		$data['today_recived'] = $is_recived;

		echo json(return_msg('0000','获取成功',$data));

	}

//领取每日任务奖励
	public function receive_daily_task()
	{
		
		$data = input();
      	$result = $this->validate($data,
	        [
	            'id'  => 'require',
	        ]);
        if(true !== $result){
            // 验证失败 输出错误信息
            echo json(return_msg(400,$result));die;
        }

        $id = $data['id'];
        $uid = session('user_data')['uid'];	

        $is_recived = Db::name('task')->where('uid',$uid)->where('pid',$id)->whereTime('time','today')->limit(1)->find();

        if(!empty($is_recived)){

        	echo json(return_msg('40002','奖励已被领取'));die;

        }
        //查询金币
        $gold = Db::name('task_list')->where('id',$id)->value('reward');
        Db::startTrans();
        //添加领取记录
        $bool1 = Db::name('task')->insert(['uid'=>$uid,'pid'=>$id,'is_recived'=>1,'time'=>time()]);
        //修改资金
        $bool2 = Db::name('assets')->where('user_id',$uid)->setInc('gold',$gold);

        if($bool1&&$bool2){
        	Db::commit(); 
        	echo json(return_msg('0000','领取成功'));
        }else{
        	Db::rollback();
        	echo json(return_msg('40002','领取失败'));
        }

        

	}

//成就列表
	public function achievement_list(){

		$taskListModel = Db::name('task_list');
		$taskModel = Db::name('task');
		$challengeRecordModel = Db::name('challenge_record');
		$uid = session('user_data')['uid'];

	
//今日已领取的奖励
		$join = [
		    ['task_list tl','tl.id = t.pid'],
		];
		$is_recived = $taskModel
			->alias('t')
			->field('tl.*,t.is_recived,t.pid')
			->join($join)
			->where('t.uid',$uid)
			->where('t.is_recived',1)
			->where('tl.type',2)
			->select();

	//成就列表
		$str = '';
		if(!empty($is_recived)){
			foreach ($is_recived as $k => $v) {
				$str.= $v['pid'];
			}
		}
		$task_list = $taskListModel
			->field('id,type,name,reach,img')
			->where('type',2)
			->where('id','not in',$str)
			->where('status',1)
			->select();

		$no_recived = $taskModel
			->alias('t')
			->field('tl.*,t.is_recived,t.pid')
			->join($join)
			->where('t.uid',$uid)
			->where('t.is_recived',0)
			->where('tl.type',2)
			->select();

		$array = [];
		foreach ($no_recived as $k => $v) {
			$array[] = $v['pid'];
		}
		foreach ($task_list as $k => $v) {
			if(in_array($v['id'],$array)){
				$task_list[$k]['is_recived'] = 0;
			}else{
				$task_list[$k]['is_recived'] = 1;
			}
		}
		// dump($is_recived);die;
		$data['task_list'] = $task_list; 
		$data['is_recived'] = $is_recived;


		echo json(return_msg('0000','获取成功',$data));

	}

//领取成就奖励
	public function receive_achievement_task()
	{
		
		$data = input();
      	$result = $this->validate($data,
	        [
	            'id'  => 'require',
	        ]);
        if(true !== $result){
            // 验证失败 输出错误信息
            echo json(return_msg(400,$result));die;
        }

        $id = $data['id'];
        $uid = session('user_data')['uid'];	

        $is_recived = Db::name('task')
	        ->where('pid',$id)
	        ->where('uid',$uid)
	        ->where('is_recived',1)
	        ->limit(1)
          	->find();

        if(!empty($is_recived)){

        	echo json(return_msg('40002','奖励已被领取'));die;

        }
        //查询金币
        $gold = Db::name('task_list')->where('id',$id)->value('reward');
        //修改资金
        Db::name('assets')->where('user_id',$uid)->setInc('gold',$gold);
        Db::name('task')->where('uid',$uid)->where('pid',$id)->update(['is_recived'=>1]);

        echo json(return_msg('0000','领取成功'));

	}

//是否已经领取了红包
  
  	public function is_recive_active(){
    	$begin_time = date('Y-m-d').' '.input('begin_time');
      	$end_time = date('Y-m-d').' '.input('end_time');
      	
      $is_have = Db::name('active')
        ->where('user_id',session('user_data')['uid'])
        ->whereTime('time', 'between', [$begin_time, $end_time])
        ->limit(1)
        ->find();
      $data = 1;
     if(empty($is_have)){
     	$data = 0;
     }
     echo json(['code'=>'0000','msg'=>'获取成功','status'=>$data]);
    
    
    }






}