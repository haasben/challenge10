<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:53:"E:\WWW\game/application/push\view\send\add_timer.html";i:1545635246;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>

</html>
<script type="text/javascript" src="/public/static/home/js/jq1.6.min.js"></script>
<script type="text/javascript">
/**
 * 与GatewayWorker建立websocket连接，域名和端口改为你实际的域名端口，
 * 其中端口为Gateway端口，即start_gateway.php指定的端口。
 * start_gateway.php 中需要指定websocket协议，像这样
 * $gateway = new Gateway(websocket://0.0.0.0:7272);
 */
var ws = new WebSocket("ws://"+document.domain+":8282");

ws.onopen = function()
   {
      // Web Socket 已连接上，使用 send() 方法发送数据

      var data = '{"type":"add_timer","group_id":<?php echo $data['group_id']; ?>,"pack_type":<?php echo $data['pack_type']; ?>}';
      ws.send(data);
    
   };

// 服务端主动推送消息时会触发这里的onmessage
ws.onmessage = function(e){
    // json数据转换成js对象
    var data = JSON.parse(e.data);

    var type = data.type || '';
    switch(type){
        case 'ping':
                
            break;

        default :
        
            console.log(e.data);
    }
};

ws.onclose = function(){

    console.log('链接关闭');
}




</script>