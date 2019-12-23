<?php
namespace app\user\controller;
use think\Db;


/**
*PHP是世界上最好的语言
*@param 首页控制器
**/
class Agent extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}


    
 //公司代理
    public function business()
    {

        $where = '';
        $uid = input('uid');
        $username = input('username');
        $tel = input('tel');
        $begin_time = input('begin_time');
        $end_time = input('end_time');

        $value['uid'] = $uid;
        $value['username'] = $username;
        $value['tel'] = $tel;
        $value['begin_time'] = $begin_time;
        $value['end_time'] = $end_time;
        if(request()->isPost()){


            $where['uid'] = ['like','%'.$uid.'%'];
            $where['username'] = ['like','%'.$username.'%'];
            $where['tel'] = ['like','%'.$tel.'%'];
            if(!empty($begin_time) && !empty($end_time)){
                $where['reg_time'] = ['between time',[$begin_time, $end_time]];
            }


        }


        $user_data = session('admin_data');
        $userModel = Db::name('user');
        if($user_data['type'] == 100){
            $uid_str = $this->agent_person_uid();
            $persondata = $userModel
                ->alias('u')
                ->field('u.uid,u.username,u.tel,a.recharge,a.room_fee,u.ext,u.refer_id,a.commission,u.is_lock')
                ->join('assets a','a.user_id=u.uid')
                ->where('type',4)
                ->where('u.refer_id','in',$uid_str)
                ->where($where)
                ->order('reg_time desc')
                ->paginate(15,false,[
                'query' => request()->param()
                ])->each(function($item,$key){
                    $uid = $item['uid'];
                    $userModel = Db::name('user');
                    $item['business_num'] = $userModel->where('refer_id',$uid)->where('type',4)->count();
                    $item['person_num'] = $userModel->where('refer_id',$uid)->where('type',3)->count();

                    return $item;

                });

        }else{


            $persondata = $userModel
                ->alias('u')
                ->field('u.uid,u.username,u.tel,a.recharge,a.room_fee,u.ext,u.refer_id,a.commission,u.is_lock')
                ->join('assets a','a.user_id=u.uid')
                ->where('u.refer_id',$user_data['uid'])
                ->where($where)
                ->where('type',4)
                ->order('reg_time desc')
                ->paginate(15,false,[
                'query' => request()->param()
                ])->each(function($item,$key){
                    $uid = $item['uid'];
                    $userModel = Db::name('user');
                    $item['business_num'] = $userModel->where('refer_id',$uid)->where('type',4)->count();
                    $item['person_num'] = $userModel->where('refer_id',$uid)->where('type',3)->count();

                    return $item;

                });
        }




        $this->assign('personData',$persondata);
        $this->assign('value',$value);


        return $this->fetch();

    }

    //个人代理
    public function person()
    {

        $where = '';
        $uid = input('uid');
        $username = input('username');
        $tel = input('tel');
        $begin_time = input('begin_time');
        $end_time = input('end_time');

        $value['uid'] = $uid;
        $value['username'] = $username;
        $value['tel'] = $tel;
        $value['begin_time'] = $begin_time;
        $value['end_time'] = $end_time;
        if(request()->isPost()){


            $where['uid'] = ['like','%'.$uid.'%'];
            $where['username'] = ['like','%'.$username.'%'];
            $where['tel'] = ['like','%'.$tel.'%'];
            if(!empty($begin_time) && !empty($end_time)){
                $where['reg_time'] = ['between time',[$begin_time, $end_time]];
            }


        }
        $user_data = session('admin_data');
        $userModel = Db::name('user');
        if(in_array($user_data['type'],[4,100])){

            $uid_str = $this->agent_person_uid();

            $persondata = $userModel
                ->alias('u')
                ->field('u.uid,u.username,u.tel,a.recharge,a.room_fee,u.ext,u.refer_id,a.commission,u.is_lock')
                ->join('assets a','a.user_id=u.uid')
                ->where('type',3)
                ->where($where)
                ->where('u.refer_id','in',$uid_str)
                ->order('reg_time desc')
                ->paginate(15,false,[
                'query' => request()->param()
                ])->each(function($item,$key){
                    $uid = $item['uid'];
                    $userModel = Db::name('user');
                    $item['business_num'] = $userModel->where('refer_id',$uid)->where('type',4)->count();
                    $item['person_num'] = $userModel->where('refer_id',$uid)->where('type',3)->count();

                    return $item;

                });

        }else{

            $persondata = $userModel
                ->alias('u')
                ->field('u.uid,u.username,u.tel,a.recharge,a.room_fee,u.ext,u.refer_id,a.commission,u.is_lock')
                ->join('assets a','a.user_id=u.uid')
                ->where('u.refer_id',$user_data['uid'])
                ->where($where)
                ->where('type',3)
               ->order('reg_time desc')
                ->paginate(15,false,[
                'query' => request()->param()
                ])->each(function($item,$key){
                    $uid = $item['uid'];
                    $userModel = Db::name('user');
                    $item['business_num'] = $userModel->where('refer_id',$uid)->where('type',4)->count();
                    $item['person_num'] = $userModel->where('refer_id',$uid)->where('type',3)->count();

                    return $item;

                });
        }


        $this->assign('personData',$persondata);
        $this->assign('value',$value);


        return $this->fetch();

    }

//冻结
    public function dongjie(){

        $uid = input('uid');

        $lock = Db::name('user')->where('uid',$uid)->value('is_lock');

        if($lock == 0){
           $lock = 1;
           $code = '0000';
           $msg = '冻结成功'; 

        }else{
           $lock = 0;
           $code = '1111';
           $msg = '解冻成功'; 

        }
        
        $a = Db::name('user')->where('uid',$uid)->update(['is_lock'=>$lock]);

        return ['code'=>$code,'msg'=>$msg];


    }
//调级
    public function tiaoji()
    {

        $type = input('type');
        $uid = input('uid');


        Db::name('user')->where('uid',$uid)->update(['type'=>$type]);

        return ['code'=>'0000','msg'=>'修改成功'];



    }

//返佣比
    public function fanyongbi()
    {


        $uid = input('uid');
        $fanyongbi_value = input('fanyongbi_value');
        if($fanyongbi_value > 1){

            return ['code'=>'4000','msg'=>'返佣比例不能大于100%'];

        }


        Db::name('assets')->where('user_id',$uid)->update(['commission'=>$fanyongbi_value]);

        return ['code'=>'0000','msg'=>'修改成功'];



    }
    


}
