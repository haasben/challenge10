<?php
namespace app\index\controller;
use think\Db;


/**
*PHP是世界上最好的语言
*@param 微信扫码支付控制器
**/
class Paymethod extends Common
{	

	public function _initialize()
	{
		parent::_initialize();
	}

    //微信扫码支付
    public function wechat_qrpay(){

        $price = input('price')*100;
        
      //生成订单
        $user_data = session('user_data');
        $order_sn = "TZSM_".time().mt_rand(1000,10000).'_'.$user_data['uid'];
        $recharge_type = 0;
        $gold = $price;

  
      Db::name('recharge')->insert([
          'user_id'=>$user_data['uid']
          ,'addtime'=>time()
          ,'pay_amount'=>$price
          ,'ext'=>'挑战十秒金币充值'
          ,'order_id'=>$order_sn
          ,'pay_type'=>'wechat_qrpay'
          ,'status'=>2
          ,'type'=>$recharge_type
          ,'gold'=>$gold
            ]);

      //报备参数
      
      $key = 'c1f19b1f2793821d6c3b89e004c3626c';
      //商户key，API平台提供
      
        $data = array(
        'mch_id'=>'10S_95',
        // Y,商户号，API平台提供
        
        'order_num'=>$order_sn,
        // Y,订单号，全局唯一
        
        'pay_amount'=>$price,
        // Y,订单金额 分为单位,最低一元 

        'notify_url'=>THIS_URL.'/index/pay/wechat_qrpay_notify.html',
        // Y,填写你自己的服务器异步回调地址（通知地址,post访问携带参数），不可以为本地地址。
        
        'return_url'=>WEB_URL,
        // Y,同步跳转地址,带一个参数$_GET['order_num'],订单号
        
        'pay_type'=>'wechat',
        // Y，支付宝支付 alipay_web

        'pay_method'=>'pc',

        'body'=>'挑战十秒金币充值',

        'ext'=>''
      );
      
      $data['sign'] = $this->get_sign_str($data,$key);
      //生成sign
      
      $url_str = $this->arrayToKeyValueString($data);
    
      //拼接url参数
      $location_href = 'http://pcode.cloud-esc.com/api/Recharge/pay.html';
      //请求地址，API平台提供

      $location_href .= '?'.$url_str;

      echo file_get_contents($location_href);
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
