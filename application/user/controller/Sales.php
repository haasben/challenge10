<?php
namespace app\user\controller;
use think\Db;


/**
*PHP是世界上最好的语言
*@param 首页控制器
**/
class Sales extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

//销售列表
    public function index()
    {

    	$where = '';
        $uid = input('uid');

        $username = input('username');
        $tel = input('tel');

        $value['uid'] = $uid;

        $value['username'] = $username;
        $value['tel'] = $tel;
        if(request()->isPost()){

            $where['uid'] = ['like','%'.$uid.'%'];
            $where['username'] = ['like','%'.$username.'%'];
            $where['tel'] = ['like','%'.$tel.'%'];
            


        }
    	$user_data = session('admin_data');
       
    	 if($user_data['type'] == 100){

    		$user_data = Db::name('user')
    			->alias('u')
    			->field('u.uid,u.tel,u.refer_id,u.username,u.reg_time,u.level,a.integral,a.gold,u.refer_id,u.ext,u.is_lock,a.commission,a.today_money,u.realname,a.money')
    			->join('assets a','a.user_id = u.uid')
    			->where($where)
                ->where('refer_id',1)
                ->where('type',2)
    			->order('level desc')
    			->paginate(15,false,[
                'query' => request()->param()
                ])->each(function($item,$key){
                    $uid = $item['uid'];
                    $userModel = Db::name('user');
                    $item['business_num'] = $userModel->where('refer_id',$uid)->where('type',4)->count();
                    $item['person_num'] = $userModel->where('refer_id',$uid)->where('type',3)->count();
                    $item['player_num'] = $userModel->where('refer_id',$uid)->where('type',1)->count();

                    return $item;

                });


    	 }elseif($user_data['type']== 4 ){

    		$user_data = Db::name('user')
                ->alias('u')
                ->field('u.uid,u.tel,u.refer_id,u.username,u.reg_time,u.level,a.integral,a.gold,u.refer_id,u.ext,u.is_lock,a.commission,a.today_money,u.realname,a.money')
                ->join('assets a','a.user_id = u.uid')
                ->where($where)
                ->where('refer_id',$user_data['uid'])
                ->where('type',2)
                ->order('level desc')
                ->paginate(15,false,[
                'query' => request()->param()
                ])->each(function($item,$key){
                    $uid = $item['uid'];
                    $userModel = Db::name('user');
                    $item['business_num'] = $userModel->where('refer_id',$uid)->where('type',4)->count();
                    $item['person_num'] = $userModel->where('refer_id',$uid)->where('type',3)->count();
                    $item['player_num'] = $userModel->where('refer_id',$uid)->where('type',1)->count();
                    return $item;

                });

    	}
      	$challenge_recordModel = Db::name('challenge_record');

    	$this->assign('value',$value);
    	$this->assign('user_data',$user_data);
        return $this->fetch();

    }
//修改业务员信息
    public function edit_sales(){

        

        if(request()->isPost()){

            $data = input();

            $result = $this->validate($data,
            [   
                'type|用户代理类型'=>'require',
                'tel|手机号'  => 'require',
                'username|用户名'=>'require',
                'commission|佣金'=>'require|float',
                'realname|真实姓名'=>'require',


            ]);
                //验证判断必填项
            if(true !== $result){
                // 验证失败 输出错误信息
                return ['code'=>'4000','msg'=>$result];die;
            }


            Db::name('user')->where('uid',$data['uid'])->update([
                'username'=>$data['username'],
                'type'=>$data['type'],
                'tel'=>$data['tel'],
                'realname'=>$data['realname'],
            ]);

            Db::name('assets')->where('user_id',$data['uid'])->update(['commission'=>$data['commission']]);

            return ['code'=>'0000','msg'=>'修改成功'];
            



        }else{

            $uid = input('uid');
            $userData = Db::name('user')->alias('u')->field('u.*,a.commission')->join('assets a','a.user_id=u.uid')->where('u.uid',$uid)->limit(1)->find();

            $this->assign('user_data',$userData);
            return $this->fetch();


        }



    }

//添加业务员
    public function add_sales()
    {

         if(request()->isPost()){

            $data = input();

            $result = $this->validate($data,
            [   
                'type|用户代理类型'=>'require',
                'tel|手机号'  => 'require',
                'password|密码'=>'require',
                'username|用户名'=>'require',
                'commission|佣金'=>'require|float',

            ]);
                //验证判断必填项
            if(true !== $result){
                // 验证失败 输出错误信息
                return ['code'=>'4000','msg'=>$result];die;
            }

            $is_hava_user = Db::name('user')->where('tel',$data['tel'])->limit(1)->find();
            if(!empty($is_hava_user)){

                return ['code'=>'400','msg'=>'该手机号已存在'];die;

            }

            if(session('admin_data')['type'] == 100){
                $refer_id = 1;
                $user_level = 1;
            }else{
                $refer_id = session('admin_data')['uid'];
                $user_level = Db::name('user')->where('uid',$refer_id)->value('user_level');
                $user_level = trim($user_level.','.$refer_id,',');
            }
            
            
            $head_rand = mt_rand(1,10);
            
            $reg_id = Db::name('user')->insertGetId([
              
              'tel'=>$data['tel'],//手机号
              'password'=>encryption($data['password']),//密码
              'avatar'=>'http://www.10sgame.com/public/static/home/images/head/'.$head_rand.'.png',//默认头像
              'username'=>$data['username'],//默认用户名
              'reg_time'=>time(),
              'refer_id'=>$refer_id,
              'user_level'=>$user_level,
              'type'=>$data['type'],
          ]);

             Db::name('assets')->insert([
              'user_id'=>$reg_id,
              'commission'=>$data['commission']
            ]);
              
            //添加初来乍到成就
            Db::name('task')->insert(['uid'=>$reg_id,'pid'=>6,'is_recived'=>0,'time'=>time()]);

            return ['code'=>'0000','msg'=>'添加成功'];

        }else{

            return $this->fetch();

        }

    }

//业务员详情
    public function detail($uid)
    {

        
        if(request()->isAjax()){

            $uid = input('uid');
            

            $userModel = Db::name('user');
            $assetsModel = Db::name('assets');

            //用户下属企业代理

            $company_agent = $userModel
                ->alias('u')
                ->field('u.*,a.recharge,a.money,a.commission')
                ->join('assets a','a.user_id = u.uid')
                ->where('u.refer_id',$uid)
                ->where('type',4)
                // ->limit(10)
                ->select();

            //用户下属个人代理
            $person_agent = $userModel
                ->alias('u')
                ->field('u.*,a.recharge,a.money,a.commission')
                ->join('assets a','a.user_id = u.uid')
                ->where('u.refer_id',$uid)
                ->where('type',3)
                // ->limit(10)
                ->select();


            $data['company_agent'] = $company_agent;
            $data['person_agent'] = $person_agent;


            return $data;




        }











    }




    


}
