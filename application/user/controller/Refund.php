<?php
namespace app\user\controller;
use think\Db;


/**
*PHP是世界上最好的语言
*@param 首页控制器
**/
class Refund extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//已处理
    public function processed(){

        $where = '';
        $uid = input('uid');

        $username = input('username');
        $tel = input('tel');
        $begin_time = input('begin_time');
        $end_time = input('end_time');

        $value['uid'] = $uid;
        $value['begin_time'] = $begin_time;
        $value['end_time'] = $end_time;
        $value['username'] = $username;
        $value['tel'] = $tel;


        $where['u.uid'] = ['like','%'.$uid.'%'];
        $where['u.username'] = ['like','%'.$username.'%'];
    
        $where['u.alipay|u.tel'] = ['like','%'.$tel.'%'];
            //$where['u.tel'] = ['like','%'.$tel.'%'];
            if(!empty($begin_time) && !empty($end_time)){
                $where['br.addtime'] = ['between time',[$begin_time, $end_time]];
            }


        $processedData = Db::name('buy_refund')
            ->alias('br')
            ->field('u.username,u.uid,u.alipay,br.addtime,br.money,br.member_type,br.duration,br.id,br.money,br.process_time,br.status')
            ->join('user u','u.uid = br.user_id')
            ->where('br.type',2)
            ->where('br.status','in','1,3')
            ->where($where)
            ->order('br.process_time desc')
            ->paginate(15,false,[
                    'query' => request()->param()
                    ]);
        //退款总额
         $sum_money = Db::name('buy_refund')
            ->alias('br')
            ->join('user u','u.uid = br.user_id')
            ->where('br.type',2)
            ->where('br.status',1)
            ->where($where)
            ->sum('money');


        $this->assign('value',$value);
        $this->assign('sum_money',$sum_money);

        $this->assign('processed_data',$processedData);

        return $this->fetch();


    }

//未处理
    public function unprocess(){

         $where = '';
        $uid = input('uid');

        $username = input('username');
        $tel = input('tel');
        $begin_time = input('begin_time');
        $end_time = input('end_time');

        $value['uid'] = $uid;
        $value['begin_time'] = $begin_time;
        $value['end_time'] = $end_time;
        $value['username'] = $username;
        $value['tel'] = $tel;


        $where['u.uid'] = ['like','%'.$uid.'%'];
        $where['u.username'] = ['like','%'.$username.'%'];
    
        $where['u.alipay|u.tel'] = ['like','%'.$tel.'%'];
            //$where['u.tel'] = ['like','%'.$tel.'%'];
            if(!empty($begin_time) && !empty($end_time)){
                $where['br.addtime'] = ['between time',[$begin_time, $end_time]];
            }
        $unprocessData = Db::name('buy_refund')
            ->alias('br')
            ->field('u.username,u.uid,u.alipay,br.addtime,br.money,br.member_type,br.duration,br.id,br.money,br.process_time,br.status')
            ->join('user u','u.uid = br.user_id')
            ->where('br.type',2)
            ->where('br.status',2)
            ->where($where)
            ->order('br.addtime desc')
            ->paginate(15,false,[
                    'query' => request()->param()
                    ]);

        //退款总额
         $sum_money = Db::name('buy_refund')
            ->alias('br')
            ->join('user u','u.uid = br.user_id')
            ->where('br.type',2)
            ->where('br.status',2)
            ->where($where)
            ->sum('money');


        $this->assign('value',$value);
        $this->assign('sum_money',$sum_money);
        $this->assign('unprocess_data',$unprocessData);

        return $this->fetch();


    }

//确认收款
    public function agree_confirm(){

        $id = input('id');

        $gold = Db::name('buy_refund')->where('id',$id)->limit(1)->find();
        Db::startTrans();
        $bool1 = Db::name('buy_refund')->where('id',$id)->update(['status'=>1,'process_time'=>time()]);
        $bool2 = Db::name('assets')->where('user_id',1)->setInc('withdraw',$gold['gold']);

        if($bool1&&$bool2){
            Db::commit();  
            return ['code'=>'0000','msg'=>'确认成功'];
        }else{
            Db::rollback();
            return ['code'=>'1111','msg'=>'确认失败，请稍后再试'];
        }


    }

//驳回退款
    public function rebut_confirm(){

        $id = input('id');
        
        $gold = Db::name('buy_refund')->where('id',$id)->limit(1)->find();
        Db::startTrans();
        $bool1 = Db::name('buy_refund')->where('id',$id)->update(['status'=>3,'process_time'=>time()]);
        $bool2 = Db::name('assets')->where('user_id',$gold['user_id'])->setInc('gold',$gold['gold']);

        if($bool1&&$bool2){
            Db::commit();    

            return ['code'=>'0000','msg'=>'驳回成功'];
        }else{
            Db::rollback();
            return ['code'=>'1111','msg'=>'驳回失败，请稍后再试'];
        }


    }
    


}
