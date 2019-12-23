<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"E:\WWW\game/application/user\view\index\index.html";i:1545363105;s:50:"E:\WWW\game\application\user\view\common\head.html";i:1545371954;}*/ ?>
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
							<a href="#subPages3" data-toggle="collapse" class="collapsed"><img src="/public/static/assets/img/left_icon_7.jpg"> <span>会员退款申请</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subPages3" class="collapse">
								<ul class="nav">
									<li><a href="<?php echo url('user/refund/processed'); ?>" class="">已处理</a></li>
									<li><a href="<?php echo url('user/refund/unprocess'); ?>" class="">未处理</a></li>
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
			<div class="row margin-left">
				<div class="col-sm-2" >
					<div class="index_bg_title">总入金</div>
					<div class="deposit">
						<div class="money-wrap"><span class="money"><?php echo $top_data['total_recharge']['recharge']/100; ?></span>元</div>
						<div class="title_icon"><img src="/public/static/assets/img/index_icon_1.png"/></div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="index_bg_title">总提成</div>
					<div class="deposit">
						<div class="money-wrap"><span class="money"><?php echo round($top_data['total_recharge']['money']/100*$top_data['total_recharge']['commission']*$scale,2); ?></span>元</div>
						<div class="title_icon"><img src="/public/static/assets/img/index_icon_2.png"/></div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="index_bg_title">总提现</div>
					<div class="deposit">
						<div class="money-wrap"><span class="money"><?php echo $top_data['total_recharge']['withdraw']/100; ?></span>元</div>
						<div class="title_icon"><img src="/public/static/assets/img/index_icon_3.png"/></div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="index_bg_title">玩家剩余总金币</div>
					<div class="deposit">
						<div class="money-wrap big-gold"><span class="money"><?php echo $top_data['gold_num']-$rebort_gold; ?></span>枚</div>
                      <?php if($admin_data['type'] == 100){?>
                      <div class="money-wrap small-gold"><span class="money"><?php echo $rebort_gold; ?></span>枚</div>
                      <?php };?>
						<div class="title_icon"><img src="/public/static/assets/img/index_icon_4.png"/></div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="index_bg_title">代理商</div>
					<div class="deposit">
						<div class="agent-wrap">
							<div class="des">企业代理：<a href="#" class="a-underline"><?php echo $top_data['business_agent']; ?></a></div>
							<div class="des">个人代理：<a href="#" class="a-underline"><?php echo $top_data['personal_agent']; ?></a></div>
							<div class="des">业&nbsp;&nbsp;&nbsp;务&nbsp;&nbsp;员：<a href="#" class="a-underline"><?php echo $top_data['salesman']; ?></a></div>
						</div>
						<div class="title_icon"><img src="/public/static/assets/img/index_icon_5.png"/></div>
					</div>
				</div>
			</div>
			<div class="row margin-left">
				<div class="col-sm-6 index-title-wrap">
					<div class="index-title">
						<div class="month-data"><img src="/public/static/assets/img/icon_11.png"/> &nbsp;&nbsp;&nbsp;本月数据</div>
			<!-- 			<div class="all-data"><a href="#">全部数据 ></a></div> -->
					</div>
					<div class="month-item">
						<div class="month-item-1">
							<div class="money"><?php echo $mid_data['today_money']/100; ?></div>
							<div class="des">当日入金 (元)</div>
						</div>
						<div class="month-item-1">
							<div class="money"><?php echo round($top_data['total_recharge']['today_money']/100*$top_data['total_recharge']['commission']*$scale,2); ?></div>
							<div class="des">当日提成 (元)</div>
						</div>
						<div class="month-item-1">
							<div class="money"><?php echo $mid_data['today_agent']; ?></div>
							<div class="des">新增代理商</div>
						</div>
						<div class="month-item-1">
							<div class="money"><?php echo $mid_data['today_play']; ?></div>
							<div class="des">新增玩家 (人)</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 index-title-wrap">
					<div class="index-title">
						<div class="month-data"><img src="/public/static/assets/img/icon_13.png"/> &nbsp;&nbsp;&nbsp;提现申请</div>
					<!-- 	<div class="all-data"><a href="#">未处理</a></div> -->
					</div>
					<div class="cash-wrap">
						<?php if(is_array($withdrawal) || $withdrawal instanceof \think\Collection || $withdrawal instanceof \think\Paginator): $i = 0; $__LIST__ = $withdrawal;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
						<div class="cash-apply-des">
							<div class="des"><?php echo date("Y-m-d H:i:s",$v['add_time']); ?></div>
							<div class="des">"提交人：<?php echo $v['name']; ?>"</div>
							<div class="des">申请提现<?php echo $v['w_amount']/100; ?>元</div>

							<?php if($v['status'] == 2){?>
							<div class="des active">未处理
							<?php }else{?>

									<div class="des">已处理
									

								<?php };?>
							</div>
						</div>
						<?php endforeach; endif; else: echo "" ;endif; ?>

						
					</div>
				</div>
			</div>
			<div class="row margin-left">
				<div class="col-sm-6 index-title-wrap" style="margin-top: -420px;">
					<div class="index-title">
						<div class="month-data"><img src="/public/static/assets/img/icon_9.png"/> &nbsp;&nbsp;&nbsp;历史充值金额 (元 )</div>
					<!-- 	<div class="all-data"><a href="#">充值记录 ></a></div> -->
					</div>
					<div class="laery-seo-box">
						<div class="larry-seo-stats" id="larry-seo-stats" style="width: 600px;height: 400px;"></div>
					</div>
				</div>
			</div>
		</div>
		<!-- content end -->

	</div>
	<script src="/public/static/assets/vendor/echarts/echarts.min.js"></script>
	<script src="/public/static/assets/vendor/jquery/jquery.min.js"></script>
<script src="/public/static/assets/vendor/toastr/toastr.min.js"></script>
	<script src="/public/static/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="/public/static/assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script src="/public/static/assets/vendor/js/main.js"></script>
	<script>
		$(function () {
			var myChart = echarts.init(document.getElementById('larry-seo-stats'));

			option = {
				color: ['#bdcdec'],
				tooltip : {
					trigger: 'axis',
					axisPointer : {            // 坐标轴指示器，坐标轴触发有效
						type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
					}
				},
				grid: {
					left: '3%',
					right: '4%',
					bottom: '3%',
					containLabel: true
				},
				xAxis : [
					{
						type : 'category',
						data : [

				<?php if(is_array($weeks_order) || $weeks_order instanceof \think\Collection || $weeks_order instanceof \think\Paginator): $i = 0; $__LIST__ = $weeks_order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
						'<?php echo $key; ?>', 
				<?php endforeach; endif; else: echo "" ;endif; ?>
						],
						axisTick: {
							alignWithLabel: true
						}
					}
				],
				yAxis : [
					{
						type : 'value'
					}
				],
				series : [
					{
						name:'金额',
						type:'bar',
						barWidth: '60%',
						data:[
<?php if(is_array($weeks_order) || $weeks_order instanceof \think\Collection || $weeks_order instanceof \think\Paginator): $i = 0; $__LIST__ = $weeks_order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <?php echo $vo; ?>, 
<?php endforeach; endif; else: echo "" ;endif; ?>

						]
					}
				]
			};

			myChart.setOption(option);
		})
	</script>
</body>
</html>
