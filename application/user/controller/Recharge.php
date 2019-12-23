<?php
namespace app\user\controller;
use think\Db;


/**
*PHP是世界上最好的语言
*@param 首页控制器
**/
class Recharge extends Common
{   

    public function _initialize()
    {
        parent::_initialize();
    }

//大厅首页
    public function index()
    {


        $where = '';
        $user_id = input('user_id');

        $username = input('username');
        $tel = input('tel');
        $begin_time = input('begin_time');
        $end_time = input('end_time');
        $value['user_id'] = $user_id;
        $value['begin_time'] = $begin_time;
        $value['end_time'] = $end_time;
        $value['username'] = $username;
        $value['tel'] = $tel;


        $where['user_id'] = ['like','%'.$user_id.'%'];
        $where['u.username'] = ['like','%'.$username.'%'];
        $where['u.tel'] = ['like','%'.$tel.'%'];
            if(!empty($begin_time) && !empty($end_time)){
                $where['r.addtime'] = ['between time',[$begin_time, $end_time]];
            }
   
        $admin_data = session('admin_data');

        $recharge = Db::name('recharge');

        if($admin_data['type'] == 100){

            $withdralData = $recharge
                ->alias('r')
                ->field("r.*,u.username,u.tel")
                ->join('user u','u.uid = r.user_id')
                ->where($where)
                ->where('r.status',1)
                ->order('addtime desc')
                ->paginate(20,false,[
                'query' => request()->param()
                ]);
			$where1= '';
        }else{

            $user_data = session('admin_data');
            $arr_id[] = $user_data['uid'];
            
            $seles = Db::name('user')->field('uid')->where('refer_id',$user_data['uid'])->select();
            if(!empty($seles)){
                $seles = array_column($seles,'uid');
                $arr_id = array_merge($arr_id,$seles); 

            }
			
          $where1['r.user_id'] = ['in',$arr_id];
          	
            $withdralData = $recharge->alias('r')
                ->field("r.*,u.username,u.tel")
                ->join('user u','u.uid = r.user_id')
                ->where($where)
                ->where($where1)
                ->where('r.status',1)
                ->order('addtime desc')
                ->paginate(20,false,[
                'query' => request()->param()
                ]);


        }

        $sum_amount = $recharge
                ->alias('r')
                ->field("r.*,u.username,u.tel")
                ->join('user u','u.uid = r.user_id')
                ->where($where)
          		->where($where1)
                ->where('r.status',1)
                ->sum('pay_amount');


        $this->assign('sum_amount',$sum_amount);
        $this->assign('value',$value);

        $this->assign('withdrawalData',$withdralData);



        return $this->fetch();

    }
    
 //大厅首页
    public function cash()
    {

        $persondata = Db::name('user')->where('type',2)->select();



        return $this->fetch();

    }


    //大厅首页
    public function nocash()
    {

        $persondata = Db::name('user')->where('type',2)->select();



        return $this->fetch();

    }






    


}
