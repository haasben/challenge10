<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Wxlogin extends Controller{

  public function index()
  {

    return $this->fetch();
  }

  public function login_user()
  {
    $getData = input();

    $result = $this->validate($getData,
        [
            'name|手机号'  => 'require',
            'password|密码'   =>'require',
        ]);
    if(true !== $result){
        // 验证失败 输出错误信息
        return ['code'=>400,'msg'=>$result];die;
    }
    if(!is_mobile_phone($getData['name'])){
      return ['code'=>400,'msg'=>'手机号格式错误'];die;
    }
    
    $is_reg = Db::name('users')
      ->where('mobile',$getData['name'])
      ->find();

    if(empty($is_reg)){
      return ['code'=>400,'msg'=>'该手机号没有注册'];die;
    }elseif($is_reg['is_lock'] == 1){
      return ['code'=>400,'msg'=>'您已被锁定，请联系管理员'];die;
    }elseif($is_reg['password'] != md5($getData['password'])){
      return ['code'=>400,'msg'=>'手机号和密码不匹配'];die;
    }

    session('userData',$is_reg);
    return ['code'=>1,'msg'=>'登陆成功'];


  }

//通过微信登陆
  public function login()
    {

		session('refer_id',input('refer_id'));

        if(!input('?code')){
          return $this->redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid='.APPID.'&redirect_uri=http://api.times168.net/index/wxlogin/login&response_type=code&scope=snsapi_userinfo');
        }else{
         
          $data = input();
          $data = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.APPID.'&secret='.APPSECRET.'&code='.$data["code"].'&grant_type=authorization_code');
          $data = json_decode($data,true);

          $data = file_get_contents('https://api.weixin.qq.com/sns/userinfo?access_token='.$data['access_token'].'&openid='.$data['openid'].'&lang=zh_CN');
          $data = json_decode($data);

            $is_reg = Db::name('user')->where('openid',$data->openid)->find();
  		      $user_level = "";
          if(empty($is_reg)){
            $refer_id = 1;
            if(!empty(session('refer_id'))){
              $refer_id = session('refer_id');
              $user_level = Db::name('user')->where('uid',$refer_id)->value('user_level'); 
            }
			     $user_level = trim($user_level.','.$refer_id,',');
            $reg_id = Db::name('user')->insertGetId([
              'avatar'=>$data->headimgurl,//默认头像
              'username'=>$data->nickname,//默认用户名
              'reg_time'=>time(),
              'refer_id'=>$refer_id,
              'openid'=>$data->openid,
              'sex'=>$data->sex,
              'user_level'=>$user_level

          ]);
            Db::name('assets')->insert([
              'user_id'=>$reg_id,
          ]);
            //添加初来乍到成就
            $openid = $data->openid;
            Db::name('task')->insert(['uid'=>$reg_id,'pid'=>6,'is_recived'=>0,'time'=>time()]);
            $data = Db::name('user')->where('uid',$reg_id)->find();
			
            session('user_data',$data);
           
          }else{

              $data = Db::name('user')->where('openid',$data->openid)->find();
              $openid = $data['openid'];
              session('user_data',$data);
          }
            $session_id = session_id();
              Db::name('user')
                ->where('tel',$data['tel'])
                ->update([
                  'login_time'=>time(),
                  'ip'=>$_SERVER['REMOTE_ADDR'],
                  'session_id'=>$session_id
                ]);
			
          	$user_data = Db::name('user')->where('uid',$data['uid'])->limit(1)->find();
            if($user_data['is_lock'] == 1){

               echo "<script>alert('你的账号已被锁定，请联系管理员');setTimeout(function(){document.addEventListener('WeixinJSBridgeReady', function(){ WeixinJSBridge.call('closeWindow');},false);WeixinJSBridge.call('closeWindow');},100)</script>";die;

            }

          	$openid = $user_data['openid'];
          	session('user_data',$user_data);	
          
          
			$this->redirect('http://10s.times168.net/#/Dashboard/index?oid='.$openid);die;
		echo '<script>window.location.href="http://192.168.0.185:3000/#/Dashboard/index?oid='.$openid.'";</script>';
          
        }
    }
//微信注册账号并绑定手机号
    public function bind_phone()
    {
        if(request()->isAjax())
        {

            $data = input();

            $result = $this->validate($data,
            [
                'mobile|手机号'  => 'require',
                'code|验证码'    => 'require',
                'password|密码'   =>'require|min:6',
            ]);

            if(true !== $result){
                // 验证失败 输出错误信息
                return ['code'=>400,'msg'=>$result];die;
            }
    //验证手机格式已经两次密码是否正确
            if(!is_mobile_phone($data['mobile'])){

              $data = ['code'=>400,'msg'=>'手机号格式错误'];

            }elseif($this->mobile_is_bind($data['mobile'])){

              $data = ['code'=>400,'msg'=>'该手机号已被其他微信绑定'];

            }elseif($data['password'] != $data['newpassword']){

              $data = ['code'=>400,'msg'=>'两次输入密码不一致'];
            }elseif(!cookie('?timeglod_code') || $data['code'] != cookie('timeglod_code')){
              $data = ['code'=>400,'msg'=>'验证码错误'];
            }else{

              $is_have = Db::name('users')->where('mobile',$data['mobile'])->limit(1)->find();
              $wechatUser = session('wechatUser');
              if(empty($is_have)){
                  $refereName = '';
                  $refereMobile = '';
                  if(session('refere_id') != ""){
                    $refereData = Db::name('users')
                      ->where('id',session('refere_id'))
                      ->limit(1)
                      ->find();
                    $refereName = $refereData['username'];
                    $refereMobile =$refereData['mobile'];
                  }
                
                $nickname = str_replace( " ", "",$wechatUser->nickname);
                if(empty($nickname)){
                  $nickname = '时光用户';
                }
                  Db::name('users')->insert(
                    [
                      'mobile'=>$data['mobile'],//手机号
                      'password'=>md5($data['password']),//密码
                      'openid'=>$wechatUser->openid,
                      'username'=>$nickname,
                      'avatar'=>$wechatUser->headimgurl,
                      'sex'=>$wechatUser->sex,
                      'reg_time'=>time(),
                      'refere_id'=>session('refere_id'),
                      'refere_name'=>$refereName,
                      'refere_mobile'=>$refereMobile,
                  ]);
              }else{
                    Db::name('users')->where('id',$is_have['id'])->update([

                      'openid'=>$wechatUser->openid,
                  ]);
              }

              $userData = Db::name('users')->where('id',$is_have['id'])->limit(1)->find();
              cookie('userData',$userData,604800);
              $data = ['code'=>1,'msg'=>'手机号绑定成功'];
            }
            return $data;

        }else{
            return $this->fetch();
        }
    }

     public function verifi_code()
    {
      if(request()->isAjax()){

        $mobile = input('mobile');
        
        if($this->mobile_is_bind($mobile)){
          return ['code'=>0,'msg'=>'该手机号已绑定其他微信'];
        }
        
        $rand = mt_rand(3000,9999);

        if(send_code($mobile,$rand)){

          $timeout = 120;
          cookie('timeglod_code',$rand,300);
          cookie('timeout',time()+$timeout,$timeout);

          return ['code'=>1,'msg'=>'发送成功，验证码有效期五分钟'];

        }else{
          return ['code'=>400,'msg'=>'服务器出错，请稍后再试'];
        }

      }else{
        echo '非法请求';
      }
    }
      public function mobile_is_bind($mobile){

        $data = Db::name('users')->where('mobile',$mobile)
            ->limit(1)
            ->value('openid');
        if(empty($data)){
          return false;
        }else{
          return true;
        }
    }

}