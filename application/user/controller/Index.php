<?php
namespace app\user\controller;
use think\Db;


/**
*PHP是世界上最好的语言
*@param 首页控制器
**/
class Index extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//大厅首页
    public function index()
    {

        $userModel = Db::name('user');
        $assetsModel = Db::name('assets');
        $rechargeModel = Db::name('recharge');
        $challengeRecordModel = Db::name('challenge_record');

    	$admin_data = session('admin_data');
        $where = '';
        $where1 = "";
        $where3 = '';

        $scale = 1;
    	if($admin_data['type'] != 100){

            $where['user_id'] = $admin_data['uid'];
            $where1['uid'] = $admin_data['uid'];

            $uid_arr = $this->user_id($admin_data['uid']); 

            $where3['user_id'] = ['in',$uid_arr];
            //提现
            if($admin_data['type'] == 3 || $admin_data['type'] == 4){

                $withdrawal = Db::name('withdrawal')->where($where3)->order('add_time desc')
                    ->limit(20)
                    ->select();
                $scale = $this->scale($admin_data['uid']);


            }else{


                $withdrawal = [];
            }

		$rebort_gold = 0;

        }else{
            $withdrawal = Db::name('withdrawal')->order('add_time desc')
                ->limit(20)
                ->select();
          	$rebort_gold = Db::name('assets')->where('user_id','in',[100001,100002,100003,100004,100032,100033,100034,100035])->sum('gold');
          
          
        }
			//机器人金币
			$this->assign('rebort_gold',$rebort_gold);

            $total_recharge = $assetsModel->where($where)->find();

                    //玩家数量
            $gold_num = $assetsModel->where($where3)->sum('gold');
            $business_agent = $userModel->where($where1)->where('type',4)->count();
            $personal_agent = $userModel->where($where1)->where('type',3)->count();
            $salesman = $userModel->where($where1)->where('type',2)->count();

        //顶部数据
            $top_data['total_recharge'] = $total_recharge;
            $top_data['gold_num'] = $gold_num;
            $top_data['business_agent'] = $business_agent;
            $top_data['personal_agent'] = $personal_agent;
            $top_data['salesman'] = $salesman;


            //中部数据
            $mid_data = [];
            //当日入金  
            $mid_data['today_money'] = $rechargeModel->where($where3)->where('status',1)->whereTime('pay_time','today')->sum('pay_amount');

            // $mid_data['today_fee'] = $challengeRecordModel->where($where3)->whereTime('cha_time','today')->sum('fee');
            
            $mid_data['today_agent'] = $userModel->where($where1)->whereTime('reg_time','m')->where('type','in','3,4')->count();

            $mid_data['today_play'] = $userModel->where($where1)->whereTime('reg_time','m')->where('type',1)->count();

            //本周入金数据
            $weeks_order = $this->weeks_order($where3);

            
    	

        $this->assign('withdrawal',$withdrawal);



        //当日提成

        //提成比例
        $this->assign('scale',$scale);

    	$this->assign('top_data',$top_data);
        $this->assign('mid_data',$mid_data);
        $this->assign('weeks_order',$weeks_order);

		return $this->fetch();

    }

    public function weeks_order($where3){
        $this_time_d = strtotime(date('Y-m-d 00:00:00'));

        $rechargeModel = Db::name('recharge'); 
        //今日0点时间戳 开始时间

        for ($i=0; $i < 7; $i++) { 
            $where = [
                'status'=>1,
                'pay_time'=>['between',[$this_time_d,$this_time_d+60*60*24]],
            ];

            $sum_amount = $rechargeModel
                ->where($where3)
                ->where($where)
                ->sum('pay_amount');


            $weeks_order[date('m-d',$this_time_d)] = $sum_amount/100;
            $this_time_d -= 60*60*24;
        }

        return array_reverse($weeks_order);
    }
    
 
    public function edit_password(){

        $getData = input();

        $result = $this->validate($getData,
            [
                'oldpassword|原密码'  => 'require',
                'newpassword|新密码'   =>'require',
            ]);
        if(true !== $result){
            // 验证失败 输出错误信息
            return ['code'=>400,'msg'=>$result];die;
        }


        $admin_data =  Db::name('user')->where('uid',session('admin_data')['uid'])->find();


        if(!empty($admin_data['password'])){

            if($admin_data['password'] != encryption($getData['oldpassword'])){
                return ['code'=>400,'msg'=>'原密码不正确'];die;
            }

        }
        Db::name('user')->where('uid',$admin_data['uid'])->update(['password'=>encryption($getData['newpassword'])]);
        return ['code'=>'0000','msg'=>'修改成功'];die;




        
    }





    


}
