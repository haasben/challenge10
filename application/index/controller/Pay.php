<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
/**
*PHP是世界上最好的语言
*@param 活动页面控制器
**/
class Pay extends controller
{ 

  public function _initialize()
  {
        $uid = input('uid');
        $user_data = Db::name('user')->where('uid',$uid)->limit(1)->find();
        session('user_data',$user_data);
      parent::_initialize();
  }
  public function recharge(){

    $price = input('money');

    $pay_type = input('pay_type');
        
    $type = input('id');
        
    if($pay_type == 'pay_wechat'){
      
      $this->wehpay($price*100,$type);die;
    }elseif($pay_type == 'pay_alipay'){

      $this->alipay_pay($price*100,$type);die;
      
    }elseif($pay_type == 'pay_quick'){


        $this->pay_quick($price*100);die;


    }

  }

  public function jsapi_pay()
  {
    $order_sn = '';
    $total_fee = input('price')*100;
    $type = input('type');
    $openId = '';
    $body = '挑战十秒金币充值';
    $jsApiParameters = wxpay($openId,$body,$order_sn,$total_fee,$attach='',$type); 
     $this->assign(array( 
      'data' => $jsApiParameters
     )); 

    return $this->fetch();

  }

 //微信JSapi支付回调验证 
    public function notify(){ 
    $xml = file_get_contents("php://input");
     //将服务器返回的XML数据转化为数组 
     //$data = json_decode(json_encode(simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA)),true); 
     $data = $this->xmlToArray($xml); 
     // 保存微信服务器返回的签名sign 
     $data_sign = $data['sign']; 
     // sign不参与签名算法 
     unset($data['sign']); 
     $sign = $this->makeSign($data); 

     // 判断签名是否正确 判断支付状态
     if ( ($sign===$data_sign) && ($data['return_code']=='SUCCESS') && ($data['result_code']=='SUCCESS') ) 
     { 

            $result = $data; 

              //获取服务器返回的数据 
              $order_id = $data['out_trade_no']; //订单单号 
              // $order_id = $data['attach=NULL'];  //附加参数,选择传递订单ID 
              $openid = $data['openid'];   //付款人openID 

              if(!$this->update_order($order_id)){

                  $result = false; 

              }


       }else{ 
        $result = false; 
       } 
       // 返回状态给微信服务器 
       if ($result) { 
        $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>'; 
       }else{ 
        $str='<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>'; 
       } 
       echo $str;
       return $result; 
      }






//微信H5支付
  public function wehpay($price,$type){
    
        $body = '挑战十秒金币充值';
        $data = wehpay($body,$price,$attach=NULL,$type);

        $url = $data['url'];

        echo '<script>window.location.href="'.$url.'"</script>';die;
        // $this->assign('levelData',$levelData);
  }


          //微信H5支付回调验证 
    public function h5_notify(){ 
  
     $xml = file_get_contents("php://input");
     //将服务器返回的XML数据转化为数组 
     //$data = json_decode(json_encode(simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA)),true); 
     $data = $this->xmlToArray($xml); 
     // 保存微信服务器返回的签名sign 
     $data_sign = $data['sign']; 
     // sign不参与签名算法 
     unset($data['sign']); 
     $sign = $this->makeSign($data); 
     
     // 判断签名是否正确 判断支付状态
     if (($sign===$data_sign) && ($data['return_code']=='SUCCESS') && ($data['result_code']=='SUCCESS') ) { 
      $result = $data; 
  Db::name('ceshi')->insert(['info1'=>date('Y-m-d H:i:s'),'info2'=>json_encode($data)]);
      //获取服务器返回的数据 
      $order_id = $data['out_trade_no']; //订单单号 
      // $order_id = $data['attach=NULL'];  //附加参数,选择传递订单ID 
      $openid = $data['openid'];   //付款人openID 

        if(!$this->update_order($order_id)){

             $result = false; 

        }

     }else{ 
      $result = false; 
      
     } 
     // 返回状态给微信服务器 
     if ($result) { 
      $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>'; 
     }else{ 
      $str='<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>'; 
     } 

     echo $str;
     return $result; 
    }



    public function xmlToArray($xml)
    {    
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $values;
    }
    protected function makeSign($data){ 
   //获取微信支付秘钥
  require_once VENDOR_PATH.'pay/lib/WxPay.Api.php';

   $key = \WxPayConfig::KEY; 
   // 去空
   $data=array_filter($data); 
   //签名步骤一：按字典序排序参数
   ksort($data); 
   $string_a=http_build_query($data);
   $string_a=urldecode($string_a); 
   //签名步骤二：在string后加入KEY 
   //$config=$this->config;;
   $string_sign_temp=$string_a."&key=".$key; 
   //签名步骤三：MD5加密 
   $sign = md5($string_sign_temp);;
   // 签名步骤四：所有字符转为大写 
   $result=strtoupper($sign); 
   return $result; 
  }


public function alipay_pay($price,$type){


        $order_sn = "TZSM_".time();
      //生成订单
        $user_data = session('user_data');
      
       $recharge_type = 0;
         $gold = $price;
         if(!empty($type)){
              $active = Db::name('recharge_active')->where('id',$type)->find();
              if(!empty($active)){
                $gold = $active['gold']+$active['gift_gold'];
                $recharge_type = $active['type'];
                $price = $active['money']*100;
             }   
        }
  
      Db::name('recharge')->insert([
          'user_id'=>$user_data['uid']
          ,'addtime'=>time()
          ,'pay_amount'=>$price
          ,'ext'=>'挑战十秒金币充值'
          ,'order_id'=>$order_sn
          ,'pay_type'=>'pay_alipay'
          ,'status'=>2
          ,'type'=>$recharge_type
          ,'gold'=>$gold
            ]);

      //报备参数
      
      $key = '518077e950794d04e65fb0a560576694';
      //商户key，API平台提供
      
        $data = array(
        'mch_id'=>'KQ_4',
        // Y,商户号，API平台提供
        
        'order_num'=>$order_sn,
        // Y,订单号，全局唯一
        
        'pay_amount'=>$price,
        // Y,订单金额 分为单位,最低一元 

        'notify_url'=>'http://api.times168.net/index/pay/alipay_notify',
        // Y,填写你自己的服务器异步回调地址（通知地址,post访问携带参数），不可以为本地地址。
        
        'return_url'=>'http://10s.times168.net/',
        // Y,同步跳转地址,带一个参数$_GET['order_num'],订单号
        
        'pay_type'=>'alipay_wap',
        // Y，支付宝支付 alipay_web

        'body'=>'挑战十秒金币充值',

        'ext'=>''
      );
      
      $data['sign'] = $this->get_sign_str($data,$key);
      //生成sign
      
      $url_str = $this->arrayToKeyValueString($data);
    
      //拼接url参数
      $location_href = 'http://eh5.redcloudsys.com/api/Recharge/pay.html';
      //请求地址，API平台提供

      $location_href .= '?'.$url_str;

      header("Location: $location_href");
    }




//微信扫码支付回调

  public function wechat_qrpay_notify(){

      $data = input();
      $sign = $data['sign'];
      $key = 'c1f19b1f2793821d6c3b89e004c3626c';
      $return_sign = $this->get_sign_str($data,$key);

      if($sign == $return_sign){
          if($data['pay_status'] == 'success'){
            if($this->update_order($data['order_num'])){
                echo 'success';
          }
       }
     }
  }




  //支付宝支付回调地址
    public function alipay_notify(){

      $data = input();
      $sign = $data['sign'];
      $key = '518077e950794d04e65fb0a560576694';
      $return_sign = $this->get_sign_str($data,$key);

      if($sign == $return_sign){
          if($data['pay_status'] == 'success'){
            if($this->update_order($data['order_num'])){
                echo 'success';
          }
       }
     }
  }

 //修改订单状态
  public function update_order($order_id){

        $rechargeModel = Db::name('recharge');
        $order_data = $rechargeModel
                ->where('order_id',$order_id)
                ->limit(1)
                ->find();
        Db::name('ceshi')->insert(['info1'=>date('Y-m-d H:i:s'),'info2'=>json_encode($order_data)]);
      if(empty($order_data)){
              echo 'fail';die;
            }   
    
            if($order_data['status'] == 1){
                echo 'success';die;
            }
  
            $userModel = Db::name('user');
            $assetsModel = Db::name('assets');
            Db::startTrans();

            //修改订单状态
            $bool = $rechargeModel
                ->where('order_id',$order_data['order_id'])
                ->update(['status'=>1,'pay_time'=>time()]);
    Db::name('ceshi')->insert(['info1'=>1,'info2'=>json_encode($bool)]);
            //资金表加充值记录以及金币
            $bool1 = $assetsModel->where('user_id',$order_data['user_id'])->update([
                'recharge' => Db::raw('recharge+'.$order_data['pay_amount']),
                'gold' => Db::raw('gold+'.$order_data['gold']),
               ]);
      Db::name('ceshi')->insert(['info1'=>2,'info2'=>json_encode($bool1)]);
            //添加上级充值总金额
            $user_level = $userModel->where('uid',$order_data['user_id'])->value('user_level');

             $bool2 = $assetsModel
                  ->where('user_id','in',$user_level)
                  ->setInc('recharge',$order_data['pay_amount']);
             Db::name('ceshi')->insert(['info1'=>3,'info2'=>json_encode($bool2)]);
            


            if ($bool&&$bool1&&$bool2) {
                Db::commit();
                return true;
            }else{
                Db::rollback();
                return false;
            }


            
   }     


    public function get_sign_str($data, $key){
      if(isset($data['sign'])) {
        unset($data['sign']);
      }
        ksort($data);
        $sign_str = '';
        foreach($data as $k => $v) {
            $sign_str .= $k . '='.$v.'&';
        }
        $sign_str = substr($sign_str,0,strlen($sign_str)-1);
        $sign_str = strtoupper(md5( $sign_str."&key=".$key));
        return $sign_str;
    }

    public function arrayToKeyValueString($data){
      $url_str = '';
      foreach($data as $key => $value) {
        $url_str .= $key .'=' . $value . '&';
      }
      $url_str = substr($url_str,0,strlen($url_str)-1);
      return $url_str;
    }






}

