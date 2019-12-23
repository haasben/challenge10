<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 公共控制器
**/
class Common extends Controller
{
	public $user_data;

    public function _initialize()
    {	
	

        if (!session('user_data')){
          	//if(input('?openid')){
         	//$user_data = Db::name('user')->where('openid',input('openid'))->limit(1)->find();
 
           // session('user_data',$user_data);
            //self::where('tel',$data['tel'])->update([
             // 'login_time'=>1,
            //  'ip'=>$_SERVER['REMOTE_ADDR'],
           // ]);
        //}else{
            	echo json(return_msg('10001',lang('LOGIN_FIRST')));die;
           // }

        }

        $this->user_data = session('user_data');
      	//Db::name('user')->where('uid',$this->user_data['uid'])->update(['login_time'=>time()]);
     


    }


//生成推广二维码
    protected function create_code(){
        $web_url = 'http://'.$_SERVER['SERVER_NAME'];

        $value = $web_url.'?uid='.base64_encode(session('user_data')['uid']); //二维码内容   
        $errorCorrectionLevel = 'L';//容错级别   
        $matrixPointSize = 10;//生成图片大小 
        //生成二维码图片  
        $time = date('Ymd',time());
        $dir = ROOT_PATH."/public/static/promote/".$time;

         if (!is_dir($dir)){
            if (mkdir($dir, 0777, true)) {
            }
        }
        $picName = md5($time.mt_rand(1000,9999).mt_rand(1,100));
        \PHPQRCode\QRcode::png($value, $dir.'/'.$picName.'.png', $errorCorrectionLevel, $matrixPointSize, 2);   
        $url = $web_url."/public/static/promote/$time/$picName.png";
        return $url;


    }



    


}
