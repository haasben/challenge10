<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"/www/wwwroot/api.times168.net/application/index/view/pay/jsapi_pay.html";i:1542197126;}*/ ?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付</title>


    <script type="text/javascript">
        callpay();
  //调用微信JS api 支付
  function jsApiCall()
  {
    var data=<?php echo $data; ?>;
    WeixinJSBridge.invoke(
      'getBrandWCPayRequest', data, 
      function(res){
        WeixinJSBridge.log(res.err_msg);
         //alert('err_code:'+res.err_code+'err_desc:'+res.err_desc+'err_msg:'+res.err_msg);
        //alert(res.err_desc);

          if(res.err_msg == "get_brand_wcpay_request:ok"){  
            alert("支付成功!");
          window.location.href="http://10s.times168.net";
          }else if(res.err_msg == "get_brand_wcpay_request:cancel"){  
            window.location.href="http://10s.times168.net";
          }else{  
            alert("支付失败!"); 
            window.location.href="http://10s.times168.net";
          }  
      }
    );
  }

  function callpay()
  {
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
            document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
        }
    }else{
        jsApiCall();
    }
  }

  </script>

  </head>

  <body>

      <a class="music-logo playing"   id="play"></a>
    <!--<audio id='test' src="lanjingl.mp3" loop="loop" autoplay="autoplay"></audio>
    <a href="tel:18782037563"><div class="box" style="background: url(/public/static/home/img/music.gif) no-repeat;height:100px;width: 100px;position: fixed;right: 0;top: 10px;background-size: 100%;"></div></a>-->
   <!--  <div class="box" style="background: url(/public/static/home/img/art1.png) no-repeat;height:100%;background-size: 100%;"> -->


      
  </body>
</html>
