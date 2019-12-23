<?php
namespace app\user\controller;

use think\Controller;
use think\Db;
class Common extends Controller
{
    
    public function _initialize(){



        if(!session('admin_data')){

            $this->redirect('user/login/login');
        }

        $admin_data = session('admin_data');
        $this->assign('admin_data',$admin_data);



    }


    public function user_id($uid){
		
 		cache('user_data_id',null);
        if(empty(cache('user_data_id'))){
            $user_data_id = Db::name('user')->field('uid,refer_id')->select();
            cache('user_data_id',$user_data_id,60*60);
        }
        return $this->get_all_child(cache('user_data_id'),$uid);

    }

    //递归获取所有的子分类的ID
    private static function get_all_child($data,$id)
    {
        static $ret = array();
        foreach ($data as $key => $v) {
            if($v['refer_id']==$id)
            {
                $ret[] = $v['uid'];
                self::get_all_child($data,$v['uid']);
            }
        }

        return $ret;
    }
	    //获取该用户的上游提成比例
    public function scale($uid)
    {
        $user_level = Db::name('user')->where('uid',$uid)->value('user_level');


            $scale = 1;
                $commission_data = Db::name('assets')
                    ->alias('a')
                    ->field('a.commission')
                    ->join('user u','u.uid = a.user_id')
                    ->where('a.user_id','in',$user_level)
                    ->where('u.type','in','3,4')
                    ->select();
                if(!empty($commission_data)){
                        
                    foreach ($commission_data as $k => $v) {
                            
                        $scale = $v['commission']*$scale;

                    }


                }
            return $scale;

    }

//获取type100 和4的下级代理商uid
    public function agent_person_uid(){

        
        $admin_data = session('admin_data');
        $str_uid = $admin_data['uid'];
        if($admin_data['type'] == 100){
            $refer_id = 1;

        }else{

            $refer_id = $admin_data['uid'];

        }
        $salse_uid = Db::name('user')->field('uid')->where('refer_id',$refer_id)->where('type',2)->select();
        foreach ($salse_uid as $k => $v) {
            $refer_id.=','.$v['uid'];
        }

        return ltrim($refer_id,',');

    }



}
