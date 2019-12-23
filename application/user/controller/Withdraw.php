<?php
namespace app\user\controller;
use think\Db;


/**
*PHP是世界上最好的语言
*@param 首页控制器
**/
class Withdraw extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//大厅首页
    public function index()
    {


        $where = '';
        $order_num = input('order_num');

        $username = input('username');
        $tel = input('tel');
        $begin_time = input('begin_time');
        $end_time = input('end_time');
        $value['order_num'] = $order_num;
        $value['begin_time'] = $begin_time;
        $value['end_time'] = $end_time;
        $value['username'] = $username;
        $value['tel'] = $tel;
        if(request()->isPost()){

            $where['order_num'] = ['like','%'.$order_num.'%'];
            $where['name'] = ['like','%'.$username.'%'];
            $where['phone_num'] = ['like','%'.$tel.'%'];
            if(!empty($begin_time) && !empty($end_time)){
                $where['add_time'] = ['between time',[$begin_time, $end_time]];
            }

            


        }
        $admin_data = session('admin_data');

        $WithdrawModel = Db::name('withdrawal');

        if($admin_data['type'] == 100){

            $withdralData = $WithdrawModel
                ->where($where)
                ->order('add_time desc')
                ->paginate(15,false,[
                    'query' => request()->param()
                    ]);

        }else{

            $user_data = session('admin_data');
            $arr_id[] = $user_data['uid'];
            
            $seles = Db::name('user')->field('uid')->where('refer_id',$user_data['uid'])->select();
            if(!empty($seles)){
                $seles = array_column($seles,'uid');
                $arr_id = array_merge($arr_id,$seles); 

            }

            $withdralData = $WithdrawModel
                ->where('user_id','in',$arr_id)
                ->where($where)
                ->order('add_time desc')
                ->paginate(15,false,[
                'query' => request()->param()
                ]);

        }
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

//提现申请
    public function add_withdraw(){

        if(request()->isPost()){
            $data = input();
            $result = $this->validate($data,
            [
                // 'name|用户名'  => 'require|token',
                'withdrawal_type|开户银行'  => 'require',
                'id_num|银行卡号'  => 'require',
                'bank_name|开户地址'  => 'require',
                'w_amount|提现金额'  => 'require|number|>=:50',

            ]);
            if(true !== $result){
                // 验证失败 输出错误信息
                echo "<script>alert('".$result."');window.location.href='/user/withdraw/add_withdraw';</script>";die;
            }

        $admin_data = session('admin_data');
        $assetsData = Db::name('user')
            ->alias('u')
            ->field('u.type,u.uid,a.commission,a.money,u.refer_id')
            ->join('assets a','a.user_id = u.uid')
            ->where('user_id',$admin_data['uid'])
            ->limit(1)
            ->find();

        //判断用户金额是否足够
        

        $referData = Db::name('user')->alias('u')
            ->field('u.type,u.uid,a.commission')
            ->join('assets a','a.user_id = u.uid')
            ->where('uid',$assetsData['refer_id'])
            ->limit(1)
            ->find();

        if($referData['type'] == 3 || $referData['type'] == 4){

            $minus_money = $data['w_amount']*100/$assetsData['commission']/$referData['commission'];

        }else{

            $minus_money = $data['w_amount']*100/$assetsData['commission'];
        }

        if($minus_money < $data['w_amount']*100){

            echo "<script>alert('可用余额不足');window.location.href='/user/withdraw/add_withdraw';</script>";die;

        }  

        $insertData = Db::name('withdrawal')->insert([
            'user_id'=>$admin_data['uid'],
            'order_num'=>'TZSM_'.date('YmdHis').$admin_data['uid'],
            'name'=>$data['name'],
            'add_time'=>time(),
            'w_amount'=>$data['w_amount']*100,
            'withdrawal_type'=>$data['withdrawal_type'],
            'id_num'=>$data['id_num'],
            'bank_name'=>$data['bank_name']

        ]);
        if($insertData){

            echo "<script>alert('提交成功');window.location.href='/user/withdraw/index';</script>";die;
        }





        }else{




            return $this->fetch();


        }



    }





    


}
