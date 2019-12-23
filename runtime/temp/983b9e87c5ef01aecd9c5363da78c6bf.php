<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"/www/wwwroot/api.times168.net/application/user/view/withdraw/index.html";i:1544437088;s:68:"/www/wwwroot/api.times168.net/application/user/view/common/head.html";i:1544436826;}*/ ?>
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
							<a href="#subPages1" data-toggle="collapse" class="collapsed active"><img src="/public/static/assets/img/left_icon_5.png"> <span>提现申请</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subPages1" class="collapse in">
								<ul class="nav">
									<li><a href="<?php echo url('user/withdraw/index'); ?>" class="active">订单列表</a></li>
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
		<div class="main" style="margin-top: 80px;">
			<form action="<?php echo url('user/withdraw/index'); ?>" method="post">
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-bordered form-table">
								<tbody>
								<tr>
									<td class="search-data" width="10%">编号</td>
									<td><input type="text" name="order_num" class="form-control"/></td>
									<td class="search-data" width="10%">姓名</td>
									<td><input type="text"  name="username" class="form-control"/></td>
									<td class="search-data" width="10%">联系方式</td>
									<td><input type="text" name="tel" class="form-control"/></td>
                                  <td class="search-data" width="10%">时间段</td>
									<td>
										<div class="clearfix-date">
											<div class="select-time"><input class="form-control" name="begin_time" value="<?php echo $value['begin_time']; ?>" placeholder="开始时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',readonly:true})" style="width: 95%;"></div>
											<div class="select-time-to">至</div>
											<div class="select-time"><input class="form-control" name="end_time" value="<?php echo $value['end_time']; ?>" placeholder="结束时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',readonly:true})" style="width: 95%;"></div>
										</div>
									</td>
<!-- 									<td class="search-data" width="10%">等级</td>
									<td><input type="text" class="form-control"/></td> -->
								</tr>
					<!-- 				<td class="search-data" width="10%">等级</td> -->
								<!-- 	<td>
										<div class="input-group" style="width: 100%">
											<select name="" class="select2 form-control">
												<option value="">请选择</option>
												<option value="">1级</option>
												<option value="">2级</option>
												<option value="">3级</option>
												<option value="">4级</option>
												<option value="">5级</option>
												<option value="">6级</option>
												<option value="">7级</option>
												<option value="">8级</option>
												<option value="">9级</option>
												<option value="">10级</option>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<td class="search-data" width="10%">总入金</td>
									<td>
										<div class="input-group" style="width: 100%">
											<select name="" class="select2 form-control">
												<option value="">请选择</option>
												<option value="">1-5000元</option>
												<option value="">5000-10000元</option>
											</select>
										</div>
									</td>
									<td class="search-data" width="10%">总提成</td>
									<td>
										<div class="input-group" style="width: 100%">
											<select name="" class="select2 form-control">
												<option value="">请选择</option>
												<option value="">1-5000元</option>
												<option value="">5000-10000元</option>
											</select>
										</div>
									</td>
									<td class="search-data" width="10%">费率筛选</td> -->
									<!-- <td><input type="text" class="form-control"/></td> -->
								<tr>
									<td colspan="8" align="center">
										<button type="submit" class="btn btn-primary">筛 选</button>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<button type="button" class="btn btn-warning">取 消</button>
									</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</form>
			<div class="row margin-left"><div class="col-sm-12 table-title-wrap">订单列表</div></div>
			<div class="row">
				<div class="col-sm-12">
					<table class="table table-hover table-spacing" style="margin-top: 20px;">
						<thead>
						<tr class="table-title">
							<td>编号</td>
							<td>账户名</td>
							<td>联系方式</td>
							<td>申请时间</td>
				<!-- 			<td>充值/提现</td> -->
							<td>金额</td>
							<td>交易账户</td>
							<td>订单状态</td>
				<!-- 			<td>操作</td> -->
						</tr>
						</thead>
						<tbody>
					<?php if(is_array($withdrawalData) || $withdrawalData instanceof \think\Collection || $withdrawalData instanceof \think\Paginator): $i = 0; $__LIST__ = $withdrawalData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
						<tr class="table-data">
							<td><?php echo $v['order_num']; ?></td>
							<td><?php echo $v['name']; ?></td>
							<td><?php echo $v['phone_num']; ?></td>
							<td><?php echo date("Y-m-d H:i:s",$v['add_time']); ?></td>
<!-- 							<td class="recharge-green">充值</td> -->
							<td><?php echo $v['w_amount']/100; ?></td>
							<td><?php echo $v['withdrawal_type']; ?> <?php echo $v['id_num']; ?></td>
							<td class="untreated">
									<?php if($v['status'] == 1){
									echo '处理成功';
								}elseif($v['status'] == 2){

									echo '处理中';

								}else{

									echo '转账失败';
								}
							?>


							</td>
<!-- 							<td>
								<a href="#" class="a-underline agree">同意</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="#" class="a-underline reject">驳回</a>
							</td> -->
						</tr>
					<?php endforeach; endif; else: echo "" ;endif; ?>

						</tbody>
					</table>
					<?php echo $withdrawalData->render(); ?>
				</div>
			</div>

			<!--同意弹层 start-->
			<div class="modal fade" id="confirm-agree" tabindex="-1" role="dialog" >
				<div class="modal-dialog" role="document">
					<div class="modal-content" style="width: 400px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" style="text-align:center;">操作确认</h4>
						</div>
						<div class="modal-body">
							<div class="config-des">同意<span class="yellow">HYZF_1</span>用户处理充值或提现操作</div>
						</div>
						<div class="modal-footer" style="text-align: center;">
							<button type="button" class="btn btn-primary">确 认</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">取 消</button>
						</div>
					</div>
				</div>
			</div>
			<!--同意弹层 end-->

			<!-- 驳回弹层 start-->
			<div class="modal fade reject-reason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content" style="width: 400px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">操作确认</h4>
						</div>
						<div class="modal-body">
							<div class="config-des"><textarea name="" placeholder="请输入驳回理由....." class="write-reason"></textarea></div>
						</div>
						<div class="modal-footer" style="text-align: center;">
							<button type="button" class="btn btn-primary">确 认</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">取 消</button>
						</div>
					</div>
				</div>
			</div>
			<!--驳回弹层 end-->

		</div>
		<!-- content end -->

	</div>
	<!-- wrapper end -->

	<!-- Javascript -->
	<script src="/public/static/assets/vendor/jquery/jquery.min.js"></script>
	<script src="/public/static/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/public/static/assets/vendor/toastr/toastr.min.js"></script>
	<script src="/public/static/assets/vendor/My97DatePicker/WdatePicker.js"></script>
	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>-->
	<script src="/public/static/assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script src="/public/static/assets/vendor/js/main.js"></script>
</body>
</html>
