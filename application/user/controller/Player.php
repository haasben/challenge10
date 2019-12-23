<?php
namespace app\user\controller;
use think\Db;


/**
*PHP是世界上最好的语言
*@param 首页控制器
**/
class Player extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//大厅首页
    public function index()
    {

    	$where = '';
        $uid = input('uid');

        $begin_time = input('begin_time');
        $end_time = input('end_time');
        $username = input('username');
        $value['uid'] = $uid;

        $value['username'] = $username;
        $value['begin_time'] = $begin_time;
        $value['end_time'] = $end_time;
        if(request()->isPost()){

            $where['uid'] = ['like','%'.$uid.'%'];
            $where['username'] = ['like','%'.$username.'%'];
            if(!empty($begin_time) && !empty($end_time)){
                $where['reg_time'] = ['between time',[$begin_time, $end_time]];
            }


        }
    	$user_data = session('admin_data');
        $userModel = Db::name('user');
    	 if($user_data['type'] == 100){
            $where1 = '';
    		$user_data = $userModel
    			->alias('u')
    			->field('u.uid,u.refer_id,u.username,u.reg_time,u.level,a.integral,a.gold,u.refer_id,u.ext,u.is_lock')
    			->join('assets a','a.user_id = u.uid')
    			->where($where)
                ->where('type',1)
    			->order('level desc')
    			->paginate(15,false,[
                'query' => request()->param()
                ]);


    	 }elseif($user_data['type']==4){

            $uid_str = $this->agent_person_uid();
            $where1['refer_id'] = ['in',$uid_str];
    		$user_data = $userModel
    			->alias('u')
    			->field('u.uid,u.refer_id,u.username,u.reg_time,u.level,a.integral,a.gold,u.refer_id,u.ext,u.is_lock')
    			->join('assets a','a.user_id = u.uid')
    			->where($where)
    			->where('refer_id','in',$uid_str)
                ->where('type',1)
    			->order('level desc')
    			->paginate(15,false,[
                'query' => request()->param()
                ]);



    	}elseif(in_array($user_data['type'],[2,3])){
            $where1['refer_id'] = $user_data['uid'];
			$user_data = $userModel
    			->alias('u')
    			->field('u.uid,u.refer_id,u.username,u.reg_time,u.level,a.integral,a.gold,u.refer_id,u.ext,u.is_lock')
    			->join('assets a','a.user_id = u.uid')
    			->where($where)
    			->where('refer_id',$user_data['uid'])
                ->where('type',1)
    			->order('level desc')
    			->paginate(15,false,[
                'query' => request()->param()
                ]);
			

        }

        $player_num = $userModel
        ->where($where1)
        ->where($where)
        ->where('type',1)
        ->count();

        //玩家总人数
        $this->assign('player_num',$player_num);

    	$this->assign('value',$value);
    	$this->assign('user_data',$user_data);



        return $this->fetch();

    }
    
	//用户转移，只转移推荐人不更改用户类型
    public function transfer(){

        //转移人uid
        $uid = input('uid'); 
        if(!empty($uid)){
            
            $userModel = Db::name('user');
            //被转移人UID
            $refer_id = input('refer_id');

            $user_level = $userModel->where('uid',$refer_id)->value('user_level');
            $refer_level = trim($user_level.','.$refer_id,',');

            $bool = $userModel->where('uid',$uid)->update(['refer_id'=>$refer_id,'user_level'=>$refer_level]);

            if($bool){

                $this->edit_transfer($uid);
            }

           return ['code'=>'0000','msg'=>'修改成功'];

        }else{


            if(session('admin_data')['type'] == 100){
                $refer_id = 1;
            }else{

                $refer_id = session('admin_data')['uid'];

            }

            $seles_data = Db::name('user')->field('uid,refer_id,user_level,username,realname')->where('refer_id',$refer_id)->where('type',2)->select();
            return $seles_data;
        }

        
    }

//递归操作改推荐人
    public function edit_transfer($uid){

        $userModel = Db::name('user');

        //取得该用户的上级所有人信息
        $user_level = $userModel->where('uid',$uid)->limit(1)->value('user_level');
        $user_level = trim($user_level.','.$uid,',');


        $data = $userModel->where('refer_id',$uid)->select();
        if(!empty($data)){
             foreach ($data as $k => $v) {
                $userModel
                ->where('uid',$v['uid'])
                ->update([
                    'refer_id'=>$uid,
                    'user_level'=>$user_level
                ]);
                $this->edit_transfer($v['uid']);

            }
        }
       
        return true;

    }





    


}
