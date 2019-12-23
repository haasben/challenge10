<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:73:"/www/wwwroot/api.times168.net/application/user/view/refund/unprocess.html";i:1544437005;s:68:"/www/wwwroot/api.times168.net/application/user/view/common/head.html";i:1544436826;}*/ ?>
<!doctype html>
<html lang="en">
<head>
	<title>十秒挑战游戏后台管理系统</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="/public/static/assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/public/static/assets/vendor/font-awesome/css/font-awesome.min.css">
  	<!-- MAIN CSS -->
    <link rel="stylesheet" href="/public/static/assets/vendor/toastr/toastr.css">
	<link rel="stylesheet" href="/public/static/assets/css/main.css">
	<!-- ICONS -->
	<link rel="icon" type="image/png" sizes="96x96" href="/public/static/assets/img/favicon.png">
</head>

<body>
	<!-- WRAPPER -->
	<div id="wrapper">
		<!-- header start -->
		<nav class="navbar navbar-default navbar-fixed-top nav-wrap-bg">
			<!--<div class="brand">-->
				<!--<img src="/public/static/assets/img/logo-dark.png" alt="" class="img-responsive logo">-->
			<!--</div>-->
			<div class="brand">
              <div class="game-title">挑战十秒钟游戏后台</div>
              <div class="nav-title">上次登录时间：<?php echo date("Y-m-d H:i:s",$admin_data['login_time']); ?> | 上次登录IP ：<?php echo $admin_data['ip']; ?></div>
			</div>
			<div class="container-fluid">
				<div id="navbar-menu">
					<ul class="nav navbar-nav navbar-right">
<!-- 						<li class="dropdown">
							<a href="#" class="dropdown-toggle"> <span class="nav-title">上次登录时间：<?php echo date("Y-m-d H:i:s",$admin_data['login_time']); ?> | 上次登录IP ：<?php echo $admin_data['ip']; ?></span> </a>
						</li> -->
						<li class="dropdown">
							<a href="#" class="dropdown-toggle"><img src="/public/static/assets/img/nav-right_1.png"> <span>欢迎您，<?php echo $admin_data['username']; ?></span> <i class="icon-submenu lnr "></i></a>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" id="edit-password"><img src="/public/static/assets/img/nav-right_2.png"> <span>修改密码</span> <i class="icon-submenu lnr "></i></a>
						</li>
						<li class="dropdown">
							<a href="#" onclick="window.history.go(0);" class="dropdown-toggle"><img src="/public/static/assets/img/nav-right_3.png"> <span>刷新</span> <i class="icon-submenu lnr "></i></a>
						</li>
						<li class="dropdown">
							<a href="<?php echo url('user/login/logout'); ?>" class="dropdown-toggle"><img src="/public/static/assets/img/nav-right_4.png"> <span>退出</span> <i class="icon-submenu lnr "></i></a>
						</li>
					</ul>
				</div>
			</div>
		</nav>

		<!-- 修改密码弹层 start -->
		<div class="modal fade" id="editPassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="width: 400px;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">修改</h4>
					</div>
					<div class="modal-body">
						<div class="form-group edit">
							<div class="group_title "><label>请输入旧密码</label></div>
							<div class="group_value"><input type="password" name="" class="oldpassword form-control"  placeholder="旧密码" style="width: 100%;"></div>
						</div>
						<div class="form-group edit">
							<div class="group_title "><label>请输入新密码</label></div>
							<div class="group_value"><input type="password" name="" class="newpassword form-control"  placeholder="新密码"></div>
						</div>
						<div class="form-group edit">
							<div class="group_title "><label>请再确认密码</label></div>
							<div class="group_value"><input type="password" name="" class="newpassword2 form-control"  placeholder="确认密码"></div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center;">
						<button type="button" class="btn btn-primary btn_save">保 存</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取 消</button>
					</div>
				</div>
			</div>
		</div>
		<!-- 修改密码弹层 end -->
		<!-- header end -->


		

<!-- left start -->
		<div id="sidebar-nav" class="sidebar">
			<div class="sidebar-scroll">
				<nav>
					<ul class="nav">
						<li><a href="<?php echo url('user/index/index'); ?>"><img src="/public/static/assets/img/left_icon_1.png"> <span>主页</span></a></li>
						<li>
							<a href="#subPages" data-toggle="collapse" class="collapsed"><img src="/public/static/assets/img/left_icon_2.png"> <span>代理商</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subPages" class="collapse">
								<ul class="nav">
									<li><a href="<?php echo url('user/agent/business'); ?>" class="">企业代理</a></li>
									<li><a href="<?php echo url('user/agent/person'); ?>" class="">个人代理</a></li>
								</ul>
							</div>
						</li>
						<li><a href="<?php echo url('user/player/index'); ?>" class=""><img src="/public/static/assets/img/left_icon_3.png"> <span>玩家列表</span></a></li>

				<?php if($admin_data['type'] ==100 || $admin_data['type'] ==4 ){ ?>
						<li><a href="<?php echo url('user/sales/index'); ?>" class=""><img src="/public/static/assets/img/left_icon_4.png"> <span>业务员</span></a></li>
				<?php };if($admin_data['type'] == 100){?>
                      <li>
							<a href="#subPages3" data-toggle="collapse" class="collapsed active"><img src="/public/static/assets/img/left_icon_7.jpg"> <span>会员退款申请</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subPages3" class="collapse in ">
								<ul class="nav">
									<li><a href="<?php echo url('user/refund/processed'); ?>" class="">已处理</a></li>
									<li><a href="<?php echo url('user/refund/unprocess'); ?>" class="active">未处理</a></li>
								</ul>
							</div>
						</li>
				<?php };if($admin_data['type'] !=2){ ?>
						<li>
							<a href="#subPages1" data-toggle="collapse" class="collapsed"><img src="/public/static/assets/img/left_icon_5.png"> <span>提现申请</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subPages1" class="collapse">
								<ul class="nav">
									<li><a href="<?php echo url('user/withdraw/index'); ?>" class="">订单列表</a></li>
									<li><a href="<?php echo url('user/withdraw/add_withdraw'); ?>" class="">提现申请</a></li>
									<!-- <li><a href="<?php echo url('user/withdraw/nocash'); ?>" class="">未处理</a></li> -->
								</ul>
							</div>
						</li>

						<li>
							<a href="#subPages2" data-toggle="collapse" class="collapsed"><img src="/public/static/assets/img/left_icon_6.png"> <span>充值记录</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subPages2" class="collapse ">
								<ul class="nav">
									<li><a href="<?php echo url('user/recharge/index'); ?>" class="">订单列表</a></li>
					<!-- 				<li><a href="<?php echo url('user/withdraw/cash'); ?>" class="">已处理</a></li>
									<li><a href="<?php echo url('user/withdraw/nocash'); ?>" class="">未处理</a></li> -->
								</ul>
							</div>
						</li>


				<?php };?>	
<!-- 						<li>
							<a href="#subPages2" data-toggle="collapse" class="collapsed"><img src="/public/static/assets/img/left_icon_6.png"> <span>账户管理</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subPages2" class="collapse ">
								<ul class="nav">
									<li><a href="#" class="">修改登录密码</a></li>
									<li><a href="#" class="">修改交易密码</a></li>
								</ul>
							</div>
						</li> -->
					</ul>
				</nav>
			</div>
		</div>
		<!-- left end -->


<!-- content satrt -->
    <div class="main" style="margin-top: 30px;">

        <form action="<?php echo url('user/refund/unprocess'); ?>" method="post">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered form-table">
                            <tbody>
                            <tr>
                                <td class="search-data" width="10%">账户ID</td>
                                <td><input style="width: 30%" type="text" name="uid" value="<?php echo $value['uid']; ?>" class="form-control"></td>
<!--                                 <td class="search-data" width="10%">充值时间</td>
                                <td>
                                    <div class="clearfix-date">
                                        <div class="select-time"><input class="form-control" name="" value="" placeholder="请选择" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',readonly:true})" ></div>
                                    </div>
                                </td>
                                <td class="search-data" width="10%">充值方式</td>
                                <td><input type="text" name="" value="" class="form-control"></td> -->
                            </tr>
                            <tr>
                                <td colspan="8" align="center">
                                    <button type="submit" class="btn btn-primary">筛 选</button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="button" class="btn btn-warning">重 置</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
        <div class="row margin-left"><div class="col-sm-12 table-title-wrap">会员退款申请</div></div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-hover table-spacing" style="margin-top: 20px;">
                    <thead>
                    <tr class="table-title">
                        <td>账户ID</td>
                        <td>退款数量及金额</td>
                        <td>会员档次</td>
                        <td>充值方式</td>
                        <td>申请提现</td>
                        <td>提现至(支付宝)</td>
                        <td>操作</td>
                    </tr>
                    </thead>
                    <tbody>

                <?php if(is_array($unprocess_data) || $unprocess_data instanceof \think\Collection || $unprocess_data instanceof \think\Paginator): $i = 0; $__LIST__ = $unprocess_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <tr class="table-data">
                        <td><?php echo $v['uid']; ?>(<?php echo $v['username']; ?>)</td>
                        <td><?php echo $v['duration']; ?>（<?php echo $v['money']/100; ?>元）</td>
                        <td><?php switch($v['member_type']): case "2": ?>钻石会员<?php break; case "3": ?>皇冠会员<?php break; default: ?>普通会员
                    <?php endswitch; ?> </td>
                        <td>金币充值</td>
                        <td><?php echo date("Y-m-d H:i:s",$v['addtime']); ?> 申请提现</td>
                        <td>提现账号：<?php echo $v['alipay']; ?></td>
                        <td>
                            <a href="#" class="a-underline agree" value="<?php echo $v['id']; ?>">确认已打款</a>&nbsp;
                            <a href="#" class="a-underline rebut" value="<?php echo $v['id']; ?>">
                                驳回
                            </a>&nbsp;
                        </td>
                    </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>

                    </tbody>
                </table>
                <?php echo $unprocess_data->render(); ?>
            </div>

        <!--同意确认 satrt-->
        <div class="modal fade" id="confirm-agree">
            <div class="modal-dialog">
                <div class="modal-content" style="width: 400px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" style="text-align:center;">操作确认</h4>
                    </div>
                    <div class="modal-body adjust-box">
                        <div class="config-des config">确认已为账户<span class="yellow agree_user_name">8888</span>退款</div>
                    </div>
                    <div class="modal-footer" style="text-align: center;">
                        <input type="hidden" class="agree_user_name_id" name="">
                        <button type="button" class="btn btn-primary agree_confirm-adjust">确 认</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取 消</button>
                    </div>
                </div>
            </div>
        </div>
        <!--同意确认 end-->

        <!--驳回操作 start-->
        <div class="modal fade" id="config-rebut">
            <div class="modal-dialog">
                <div class="modal-content" style="width: 400px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" style="text-align:center;">操作确认</h4>
                    </div>
                    <div class="modal-body adjust-box">
                        <div class="config-des config">确认驳回账户<span class="yellow rebut_user_name"></span>的退款申请</div>
                    </div>
                    <div class="modal-footer" style="text-align: center;">
                        <input type="hidden" class="rebut_user_name_id" name="">
                        <button type="button" class="btn btn-primary rebut_confirm-adjust" data-dialog="modal">确 认</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取 消</button>
                    </div>
                </div>
            </div>
        </div>
        <!--驳回操作 end-->


    </div>
    <!-- content end -->
</div>
<!-- wrapper end -->
</div>
<!-- Javascript -->
<script src="/public/static/assets/vendor/jquery/jquery.min.js"></script>
<script src="/public/static/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="/public/static/assets/vendor/toastr/toastr.min.js"></script>
<script src="/public/static/assets/vendor/My97DatePicker/WdatePicker.js"></script>
<script src="/public/static/assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="/public/static/assets/vendor/js/main.js"></script>
<script type="text/javascript">
    $('.agree').click(function(){
        var username = $(this).parent().parent().find('td:eq(0)').html();

        var id = $(this).attr('value');

        $('#confirm-agree').modal();
        $('.agree_user_name').text(username);
        $('.agree_user_name_id').val(id)

    })
    $('.agree_confirm-adjust').click(function(){

        var id = $('.agree_user_name_id').val();

        $.post('<?php echo url("user/refund/agree_confirm"); ?>',{id:id},function(data){

            if(data.code == '0000'){

                alert('确认成功');
                $('#confirm-agree').modal('hide');
                location.reload();
            }else{

                alert('确认失败');

            }



        })



    })
    $('.rebut').click(function(){

        var username = $(this).parent().parent().find('td:eq(0)').html();

        var id = $(this).attr('value');

        $('#config-rebut').modal();
        $('.rebut_user_name').text(username);
        $('.rebut_user_name_id').val(id)

    })
    $('.rebut_confirm-adjust').click(function(){

        var id = $('.rebut_user_name_id').val();

        $.post('<?php echo url("user/refund/rebut_confirm"); ?>',{id:id},function(data){

            if(data.code == '0000'){

                alert(data.msg);
                $('#config-rebut').modal('hide');
                location.reload();
            }else{

                alert(data.msg);

            }



        })



    })


</script>


</body></html>