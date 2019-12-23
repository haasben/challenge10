<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:50:"E:\WWW\game/application/user\view\login\login.html";i:1545203536;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录界面</title>
    <link rel="stylesheet" href="/public/static/assets/css/main.css">
    <link rel="stylesheet" href="/public/static/assets/vendor/bootstrap/css/bootstrap.min.css">
</head>
<body class="login-wrap">

<div class="line-wrap-bg">
    <div class="plus-wrap-bg"></div>
    <!--line start-->
    <!--<div style="width: 100%;height: 75%;"><img src="/public/static/assets/img/line2.png" style="z-index: 999999;"/></div>-->
    <!--line end-->

    <!--白云动态 start-->
    <div id="mainBody">
        <div id="cloud1" class="cloud"></div>
        <div id="cloud2" class="cloud"></div>
        <div id="cloud4" class="cloud4"></div>
    </div>
    <!--白云动态 end-->

    <!--登录表单 start-->
    <div class="login-box-wrap">
            <div class="login-box">
                <div class="login-title"><img src="/public/static/assets/img/login-title.png"/></div>
                <div class="form-group login-line">
                    <div class="title">账号 ：</div>
                    <div class="entry"><input type="text" class="mobile"/></div>
                </div>
                <div class="form-group login-line">
                    <div class="title">密码 ：</div>
                    <div class="entry"><input type="password" class="password"/></div>
                </div>
                <div class="form-group login-line">
                    <div class="title">验证 ：</div>
                    <div class="entry"><input type="text" class="ca_code" style="width: 70%;"/></div>


                    <div class="code"><img src="<?php echo captcha_src(); ?>" alt="点击更换" onClick="this.src='<?php echo captcha_src(); ?>?seed='+Math.random()"></div>
                </div>
                <div class="form-group login-line">
                    <div class="title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <div class="entry">
                        <button type="button" class="login-btn"></button>
                        <button type="button" class="contact-btn"></button>
                    </div>
                </div>
            </div>
    </div>
    <!--登录表单 end-->

    <div class="footer-wrap">
        <div class="footer-des">紫光极客网络科技有限公司版权所有 | ICP备案号 ：蜀ICP备16033896号</div>
        <div class="footer-info">2018@www.10sgame.com Copyright(C)All Rights Reserved</div>
    </div>

</div>
<script src="/public/static/assets/vendor/jquery/jquery.min.js"></script>
<script type="text/javascript">
         $(function(){

          $('.login-btn').click(function(){

              var mobile = $('.mobile').val();
              var password = $('.password').val();
              var code = $('.ca_code').val();


               $.post("<?php echo url('user/login/login'); ?>",{mobile:mobile,password:password,code:code},function(result){
                  
                    if(result.code == '0000'){
                        
                      window.location.href = '<?php echo url("user/index/index"); ?>';
                    }else{
                      alert(result.msg);
                    }  



                });







          })




      })


</script>


<script>
    $(function () {
        var $main = $cloud = mainwidth = null;
        var offset1 = 450;
        var offset2 = 0;


        $(document).ready(
            function () {
                $main = $("#mainBody");
                $body = $("body");
                $cloud1 = $("#cloud1");
                $cloud2 = $("#cloud2");
                $cloud4 = $("#cloud4");
                mainwidth = $main.outerWidth();
            }
        );

        /// 飘动
        setInterval(function flutter() {
            if (offset1 >= mainwidth) {
                offset1 =  -580;
            }

            if (offset2 >= mainwidth) {
                offset2 =  -580;
            }
            offset1 += 1.1;

            offset2 += 1;

            $cloud1.css("background-position", offset1 + "px 30px")

            $cloud2.css("background-position", offset2 + "px 120px")

            $cloud4.css("background-position", offset2 + "px 140px")

        }, 150);
    })
</script>
</body>
</html>