<?php
use think\Db;
/** 
 * 微信支付 jsapi
 * @param string $openId  openid 
 * @param string $body  商品名称 
 * @param string $attach  附加参数,我们可以选择传递一个参数,比如订单ID 
 * @param string $order_sn 订单号 
 * @param string $total_fee 金额 
 */
function wxpay($openId,$body,$order_sn,$total_fee,$attach,$type){ 

 $recharge_type = 0;
 $gold = $total_fee;
 if(!empty($type)){
      $active = Db::name('recharge_active')->where('id',$type)->find();
      if(!empty($active)){
        $gold = $active['gold']+$active['gift_gold'];
        $recharge_type = $active['type'];
        $total_fee = $active['money']*100;
     }   
}


vendor('pay.lib.WxPay.Api');
require_once VENDOR_PATH.'pay/example/WxPay.JsApiPay.php';

vendor('pay.example.log');


$logHandler= new \CLogFileHandler(LOG_PATH.date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
//①、获取用户openid
$tools = new \JsApiPay();
$openId = $tools->GetOpenid();
//②、统一下单
$input = new \WxPayUnifiedOrder();
$input->SetBody("挑战十秒金币");
$input->SetAttach("test");
$order_sn = WxPayConfig::MCHID.date("YmdHis");
$input->SetOut_trade_no($order_sn); 
$input->SetTotal_fee($total_fee);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("十秒");
$input->SetNotify_url("http://api.times168.net/index/pay/notify");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
$user_data = session('user_data');

//把用户信息以及订单信息录入数据库
    Db::name('recharge')->insert([
      'user_id'=>$user_data['uid']
      ,'addtime'=>time()
      ,'pay_amount'=>$total_fee
      ,'ext'=>$body
      ,'order_id'=>$order_sn
      ,'pay_type'=>'wechat_pay'
      ,'status'=>2
      ,'type'=>$recharge_type
      ,'gold'=>$gold
       ]);

$jsApiParameters = $tools->GetJsApiParameters($order);
//获取共享收货地址js函数参数
//$editAddress = $tools->GetEditAddressParameters();
return $jsApiParameters; 
}

/** 
 * 微信支付 
 * @param string $openId  openid 
 * @param string $body  商品名称 
 * @param string $attach  附加参数,我们可以选择传递一个参数,比如订单ID 
 * @param string $order_sn 订单号 
 * @param string $total_fee 金额 
 */
function wehpay($body,$total_fee,$attach,$type){ 
  
 $recharge_type = 0;
 $gold = $total_fee;
 if(!empty($type)){
      $active = Db::name('recharge_active')->where('id',$type)->find();
      if(!empty($active)){
        $gold = $active['gold']+$active['gift_gold'];
        $recharge_type = $active['type'];
        $total_fee = $active['money']*100;
     }   
}

vendor('pay.lib.WxPay.Api');
require_once VENDOR_PATH.'pay/example/WxPay.JsApiPay.php';

vendor('pay.example.log');
//初始化日志
$logHandler= new \CLogFileHandler(LOG_PATH.date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
//②、统一下单
$input = new \WxPayUnifiedOrder();
$order_sn = WxPayConfig::MCHID.date("YmdHis");
$input->SetBody($body);
$input->SetAttach($body);
$input->SetOut_trade_no($order_sn); 
$input->SetTotal_fee($total_fee);
$input->get_client_ip();
$input->SetTime_start(date("YmdHis"));
//$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($body);
$input->SetNotify_url("http://api.times168.net/index/pay/h5_notify");
$input->SetTrade_type("MWEB");

$order = WxPayApi::unifiedOrder($input);

    $user_data = session('user_data');
    
  
  
    //把用户信息以及订单信息录入数据库
    Db::name('recharge')->insert([
      'user_id'=>$user_data['uid']
      ,'addtime'=>time()
      ,'pay_amount'=>$total_fee
      ,'ext'=>$body
      ,'order_id'=>$order_sn
      ,'pay_type'=>'wechat_pay'
      ,'status'=>2
      ,'type'=>$recharge_type
      ,'gold'=>$gold
       ]);

return ['url'=>$order['mweb_url'],'order_sn'=>$order_sn]; 
}
/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装） 
 * @return mixed
 */
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}
/**
 * @param 数据转JSON格式返回数据
 */
function json($data){
    return json_encode($data,JSON_UNESCAPED_UNICODE);
}
/**
 * 返回数据
 * @param $code //返回码
 * @param string $msg //返回信息
 * @param array $data //返回数据
 */
function return_msg($code,$msg='',$data=NULL)
{
    $return_data['code'] = $code;
    $return_data['msg'] = $msg;

    if(!empty($data)){
        $return_data['data'] = $data;
    }
    return $return_data;
}


// 快速文件数据读取和保存 针对简单类型数据 字符串、数组
function Fcache($name,$value='',$path=DATA_PATH) {
    static $_cache = array();
    $filename   =   $path.$name.'.php';

    if('' !== $value) {
        if(is_null($value)) {
            // 删除缓存
            return unlink($filename);
        }else{
            // 缓存数据
            $dir   =  dirname($filename);
            // 目录不存在则创建
            if(!is_dir($dir))  $res=mkdir($dir,0777,true);
            return file_put_contents($filename,"<?php\nreturn ".var_export($value,true).";\n?>");
        }
    }
    if(isset($_cache[$name])) return $_cache[$name];
    // 获取缓存数据
    if(is_file($filename)) {
        $value   =  include $filename;
        $_cache[$name]   =   $value;
    }else{
        $value  =   false;
    }
    return $value;
}

//缓存
function savecache($name = '',$id='') {
    if($name=='Field'){
        if($id){
            $Model = db($name);
            $data = $Model->order('listorder')->where('moduleid='.$id)->column('*', 'field');
            $name=$id.'_'.$name;
            Fcache($name,$data);
        }else{
            $module = Fcache('Module');
            foreach ( $module as $key => $val ) {
                savecache($name,$key);
            }
        }
    }elseif($name=='System'){
        $Model = db ( $name );
        $list = $Model->where(array('id'=>1))->find();
        $data=$sysdata=$list;
        Fcache('System',$list);
    }elseif($name=='Module'){
        $Model = db ( $name );
        $list = $Model->order('listorder')->select ();
        $pkid = $Model->getPk ();
        $data = array ();
        $smalldata= array();
        foreach ( $list as $key => $val ) {
            $data [$val [$pkid]] = $val;
            $smalldata[$val['name']] =  $val [$pkid];
        }
        Fcache($name,$data);
        Fcache('Mod',$smalldata);
        //savecache
    }else{
        $Model = db ($name);
        $list = $Model->order('listorder')->select ();
        $pkid = $Model->getPk ();
        $data = array ();
        foreach ( $list as $key => $val ) {
            $data [$val [$pkid]] = $val;
        }
        Fcache($name,$data);
    }
    return true;
}

//加密方式
function encryption($password){
    //加密
    $password = md5(md5($password));
    $password = substr($password,5,6);
    $password = md5($password.'zgjk');
    return $password;
}

//聚合数据短信接口。
function send_code($phone,$rand_code){
    header('content-type:text/html;charset=utf-8');

    $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
    $smsConf = array(
        'key'   => '5881e3fa205af1443ac1259b45fc1657', //您申请的APPKEY
        'mobile'    => $phone, //接受短信的用户手机号码
        'tpl_id'    => '116423', //您申请的短信模板ID，根据实际情况修改
        'tpl_value' =>'#code#='.$rand_code.'&#company#=聚合数据' //您设置的模板变量，根据实际情况修改
    );
     
    $content = juhecurl($sendUrl,$smsConf,1); //请求发送短信
     
    if($content){
        $result = json_decode($content,true);
        $error_code = $result['error_code'];
        if($error_code == 0){
            //状态为0，说明短信发送成功
            // echo "短信发送成功,短信ID：".$result['result']['sid'];
            return true;
        }else{
            //状态非0，说明失败
            // $msg = $result['reason'];
            // echo "短信发送失败(".$error_code.")：".$msg;
            return false;
        }
    }else{
        //返回内容异常，以下可根据业务逻辑自行修改
        return false;
    }
}

function juhecurl($url,$params=false,$ispost=0){
    //发送短信验证码
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}

/**
 * 验证输入的手机号码是否合法
 */
function is_mobile_phone($mobile_phone)
{
    $chars = "/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$|19[0-9]{1}[0-9]{8}$|16[0-9]{1}[0-9]{8}$/";
    if (preg_match($chars, $mobile_phone)) {
        return true;
    }
    return false;
}

//产生一个6位数的随机房间
function rand_room($level){
    $arr = Array(1,2,3,4,5,6,7,8,9,0);

    $rndKey = array_rand($arr, 5);
    shuffle($rndKey);
    if($level == 1){
        $str = 'S';
    }elseif($level == 2){
        $str = 'M';
    }else{
        $str = 'H';
    }
    foreach ($rndKey as $k => $v) {
        $str.= $arr[$v];
    }
    return $str;
}

function create_code($uid){


    $web_url = 'http://api.times168.net';
    
    $value ='http://10s.times168.net/#/Dashboard/index?uid='.$uid; //二维码内容   

    $errorCorrectionLevel = 'H';//容错级别   
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
      $logo = ROOT_PATH.'public/static/logo.png';//准备好的logo图片  需要加入到二维码中的logo

      $QR = $dir.'/'.$picName.'.png';//已经生成的原始二维码图

      $QR = imagecreatefromstring(file_get_contents($QR));

      $logo = imagecreatefromstring(file_get_contents($logo));

      $QR_width = imagesx($QR);//二维码图片宽度

      $QR_height = imagesy($QR);//二维码图片高度

      $logo_width = imagesx($logo);//logo图片宽度

      $logo_height = imagesy($logo);//logo图片高度

      $logo_qr_width = $QR_width / 5;

      $scale = $logo_width/$logo_qr_width;

      $logo_qr_height = $logo_height/$scale;

      $from_width = ($QR_width - $logo_qr_width) / 2;

   //重新组合图片并调整大小

      imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,

      $logo_qr_height, $logo_width, $logo_height);

   //输出图片

     imagepng($QR, $dir.'/'.$picName.'.png');

     $url = $web_url."/public/static/promote/$time/$picName.png";
     return $url;


}




//打乱数组二维数组多维数组
function rec_assoc_shuffle($array)
{
  $ary_keys = array_keys($array);
  $ary_values = array_values($array);
  shuffle($ary_values);
  foreach($ary_keys as $key => $value) {
    if (is_array($ary_values[$key]) AND $ary_values[$key] != NULL) {
      $ary_values[$key] = rec_assoc_shuffle($ary_values[$key]);
    }
    $new[$value] = $ary_values[$key];
  }
  return $new;
}


/*socket收发数据
    @host(string) socket服务器IP
    @post(int) 端口
    @str(string) 要发送的数据
    @back 1|0 socket端是否有数据返回
    返回true|false|服务端数据
*/
function sendSocketMsg($host,$port,$str,$back=0){
        $socket = socket_create(AF_INET,SOCK_STREAM,0);
        if ($socket < 0) return false;
        $result = @socket_connect($socket,$host,$port);
        if ($result == false)return false;
        socket_write($socket,$str,strlen($str));
        
        if($back!=0){
            $input = socket_read($socket,1024);
            socket_close ($socket);    
            return $input;
        }else{
            socket_close ($socket);    
            return true;    
        }    
}
