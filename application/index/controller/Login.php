<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Validate;
/**
*PHP是世界上最好的语言
*@param 登陆注册控制器
**/

class Login extends Controller
{

//用户登录
    public function login()
    {

		$data = input();
		$result = $this->validate($data,
        [
            'tel|手机号'  => 'require',
            'password|密码'   => 'require',
        ]);
        if(true !== $result){
            // 验证失败 输出错误信息
            echo json(return_msg(400,$result));die;
        } 
		//实例化user模型
		$userModel = Model('User');
		$return_data = $userModel->login($data);
		echo json($return_data);exit;


 
    }

//用户注册
    public function register(){

    	$data = input();

        $result = $this->validate($data,
        [
            'tel|手机号'  => 'require',
            'code|验证码'    => 'require',
            'password|密码'   =>'require|min:6',
            //'username|用户名' => 'require|min:1',
            'newpassword' => 'require',
        ]);
    
      
    //验证判断必填项
        if(true !== $result){
            // 验证失败 输出错误信息
            echo  json(['code'=>20001,'msg'=>$result]);die;
        }
        if(!is_mobile_phone($data['tel'])){

          $data = ['code'=>20002,'msg'=>lang('TEL_ERROR')];

        }elseif($this->mobile_is_reg($data['tel'])){

          $data = ['code'=>20003,'msg'=>lang('TEL_REGED')];

        }elseif($data['password'] != $data['newpassword']){

          $data = ['code'=>20004,'msg'=>lang('DIF_PASS')];
         }elseif(!cookie('?game_code') || $data['code'] != cookie('game_code')){
          $data = ['code'=>20005,'msg'=>lang('CODE_ERROR')];
        }else{
          	$refer_id = 1;
          	$user_level = "";
          	if(isset($data['refer_id'])){
              if(empty($data['refer_id'])){
              	$refer_id = $data['refer_id'];
              	$user_level = Db::name('user')->where('uid',$refer_id)->value('user_level');
              }
			}
          
          	$user_level = trim($user_level.','.$refer_id,',');
            $head_rand = mt_rand(1,10);
            
        	$reg_id = Db::name('user')->insertGetId([
              
              'tel'=>$data['tel'],//手机号
              'password'=>encryption($data['password']),//密码
              'avatar'=>THIS_URL.'/public/static/home/images/head/'.$head_rand.'.png',//默认头像
              'username'=>'用户'.substr($data['tel'],-4),//默认用户名
              'reg_time'=>time(),
              'refer_id'=>$refer_id,
              'user_level'=>$user_level,
          ]);

            Db::name('assets')->insert([
              'user_id'=>$reg_id,
         	]);
              
            //添加初来乍到成就
            //Db::name('task')->insert(['uid'=>$reg_id,'pid'=>6,'is_recived'=>0,'time'=>time()]);


            

        	$data = ['code'=>'0000','msg'=>lang('REG_SUCCESS')];

        }
        echo json($data);exit;




    }
//发送验证码
     public function send_verifi_code()
    {
      //if(request()->isAjax()){

        $mobile = input('tel');
        
        if($this->mobile_is_reg($mobile)){
          echo json(return_msg('400',lang('TEL_REGED')));die;
        }

        $rand = mt_rand(1000,9999);
        if(send_code($mobile,$rand)){

          cookie('game_code',$rand,300);
          echo json(return_msg('0000',lang('CODE_SUCCESS')));

        }else{
        	echo json(return_msg('70001',lang('SERVER_ERROR')));
        }

      //}else{
       // echo lang('ILLEGAL');
      //}
    }
	
  
  //绑定手机号发送验证码
     public function bind_send_verifi_code()
    {

        $mobile = input('tel');

        $rand = mt_rand(1000,9999);
        if(send_code($mobile,$rand)){

          cookie('game_bind_code',$rand,300);
          echo json(return_msg('0000',lang('CODE_SUCCESS')));

        }else{
        	echo json(return_msg('70001',lang('SERVER_ERROR')));
        }

    }



//验证手机号是否已经注册
    protected function mobile_is_reg($mobile)
    {
      $is_reg = Db::name('user')
          ->where('tel',$mobile)
          ->limit(1)
          ->find();
      if(empty($is_reg)){
        return false;
      }else{
        return true;
      }
    }

  //绑定手机号
	public function bind_phone(){

       $data = input();

        $result = $this->validate($data,
        [
            'tel|手机号'  => 'require',
            'code|验证码'    => 'require',
            'password|密码'   =>'require|min:6',
            'newpassword' => 'require',
        ]);
    //验证判断必填项
        if(true !== $result){
            // 验证失败 输出错误信息
            echo  json(['code'=>20001,'msg'=>$result]);die;
        }
        if(!is_mobile_phone($data['tel'])){

          $data = ['code'=>20002,'msg'=>lang('TEL_ERROR')];

        }elseif($data['password'] != $data['newpassword']){

          $data = ['code'=>20004,'msg'=>lang('DIF_PASS')];
        }elseif(!cookie('?game_bind_code') || $data['code'] != cookie('game_bind_code')){
          $data = ['code'=>20005,'msg'=>lang('CODE_ERROR')];
        }else{
            
          	$userModel = Db::name('user');
          	$user_data = $userModel->where('openid',$data['openid'])->limit(1)->find();
            $uid = $user_data['uid'];
            
			
          if($this->mobile_is_reg($data['tel'])){

              	
              $userModel->where('uid',$uid)->delete();
              Db::name('assets')->where('user_id',$uid)->delete();
              Db::name('task')->where('uid',$uid)->delete();

              $userModel->where('tel',$data['tel'])->update([
                  'openid'=>$user_data['openid'],
                  'avatar'=>$user_data['avatar'],
                  'password'=>encryption($data['password']),
                  'username'=>$user_data['username'],
              ]);
          }else{

            $userModel->where('uid',$uid)->update([
                  'tel'=>$data['tel'],
                  'password'=>encryption($data['password']),

             ]);

          }

          $user_data = $userModel->where('uid',$uid)->limit(1)->find();

          session('user_data',$user_data);

          $data = ['code'=>'0000','msg'=>'绑定成功'];

        }
        echo json($data);exit;

    }
  
  
//退出登录

    public function logout()
    {
      //Db::name('user')->where('uid',session('user_data')['uid'])->update(['login_time'=>0]);
      session('user_data',NULL);
      echo json(return_msg('0000',lang('REQUEST_SUCCESS')));

    }
  
//忘记密码发送验证码
    public function forget_send_verifi_code()
    {

        $mobile = input('tel');

        $rand = mt_rand(1000,9999);
        if(send_code($mobile,$rand)){

          if(!$this->mobile_is_reg($mobile)){

            echo json(return_msg('70002','该手机号还没有注册'));die;

          }

          cookie('game_forget_code',$rand,300);
          cookie('tel',$mobile,300);

          echo json(return_msg('0000',lang('CODE_SUCCESS')));

        }else{
          echo json(return_msg('70001',lang('SERVER_ERROR')));
        }

    }
//绑定手机号
  public function forget_password(){

       $data = input();

        $result = $this->validate($data,
        [
            'tel|手机号'  => 'require',
             'code|验证码'    => 'require',
            'password|密码'   =>'require|min:6',
            'newpassword|新密码' => 'require',
        ]);
    //验证判断必填项
        if(true !== $result){
            // 验证失败 输出错误信息
            echo  json(['code'=>20001,'msg'=>$result]);die;
        }
        if(!is_mobile_phone($data['tel'])){

          $data = ['code'=>20002,'msg'=>lang('TEL_ERROR')];

        }elseif($data['password'] != $data['newpassword']){

          $data = ['code'=>20004,'msg'=>lang('DIF_PASS')];
        }elseif($data['code'] != cookie('game_forget_code')){
          $data = ['code'=>20005,'msg'=>lang('CODE_ERROR')];

        }elseif(cookie('tel') != $data['tel']){

            $data = ['code'=>20006,'msg'=>'手机号不一致'];

        }else{
            $update_pass = Db::name('user')->where('tel',$data['tel'])->update(['password'=>encryption($data['password'])]);
            $data = ['code'=>'0000','msg'=>'密码修改成功'];

        }
        echo json($data);exit;

    }
    


}
