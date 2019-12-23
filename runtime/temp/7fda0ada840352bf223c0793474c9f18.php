<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:51:"E:\WWW\game/application/user\view\agent\person.html";i:1545363121;s:50:"E:\WWW\game\application\user\view\common\head.html";i:1545362866;}*/ ?>
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
							<div id="subPages3" class="collapse in">
								<ul class="nav">
									<li><a href="<?php echo url('user/refund/processed'); ?>" class="active">已处理</a></li>
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
				<form action="<?php echo url('user/agent/person'); ?>" method="post">
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-bordered form-table">
								<tbody>
								<tr>
									<td class="search-data" width="10%">编号</td>
									<td><input type="text" value="<?php echo $value['uid']; ?>" name="uid" class="form-control"/></td>
									<td class="search-data" width="10%">姓名</td>
									<td><input type="text" value="<?php echo $value['username']; ?>" name="username" class="form-control"/></td>
									<td class="search-data" width="10%">联系方式</td>
									<td><input type="text" value="<?php echo $value['tel']; ?>" name="tel" class="form-control"/></td>
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
								<tr>
<!-- 									<td class="search-data" width="10%">总入金</td>
									<td>
										<div class="input-group" style="width: 100%">
											<select name="" class="select2 form-control">
												<option value="">请选择</option>
												<option value="">1-5000元</option>
												<option value="">5000-10000元</option>
											</select>
										</div>
									</td> -->
<!-- 									<td class="search-data" width="10%">总提成</td>
									<td>
										<div class="input-group" style="width: 100%">
											<select name="" class="select2 form-control">
												<option value="">请选择</option>
												<option value="">1-5000元</option>
												<option value="">5000-10000元</option>
											</select>
										</div>
									</td> -->
<!-- 									<td class="search-data" width="10%">费率筛选</td>
									<td><input type="text" class="form-control"/></td> -->
									
								</tr>
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
			<div class="row margin-left"><div class="col-sm-12 table-title-wrap">个人代理列表</div></div>
			<div class="row">
				<div class="col-sm-12">
					<table class="table table-hover table-spacing" style="margin-top: 20px;">
						<thead>
						<tr class="table-title">
							<td>编号</td>
							<td>姓名</td>
							<td>联系方式</td>
							<td>下级代理</td>
							<td>业绩</td>
                          	<td>返佣比例</td>
							<td>备注</td>
							<td>操作</td>
						</tr>
						</thead>
						<tbody>
						<?php if(is_array($personData) || $personData instanceof \think\Collection || $personData instanceof \think\Paginator): $i = 0; $__LIST__ = $personData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
							<tr class="table-data">
								<td><?php echo $v['uid']; ?></td>
								<td><?php echo $v['username']; ?></td>
								<td><?php echo $v['tel']; ?></td>
								<td><a href="#" class="company-detail">企业（<?php echo $v['business_num']; ?>）</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="company-detail">个人（<?php echo $v['person_num']; ?>）</a></td>
								<td>总入金（<?php echo $v['recharge']/100; ?>） 总提成（<?php echo round($v['room_fee']/100*$v['commission'],2); ?>）</td>
                              	<td><?php echo $v['commission']*100; ?>%</td>
								<td><?php echo $v['ext']; ?></td>
								<td>	
                                  <?php if($admin_data['type'] != 2){?>
									<a href="#" class="a-underline adjust">调级</a>&nbsp;
                                    <a href="#" class="a-underline frozen">冻结</a>&nbsp;
                                    <a href="#" class="a-underline rake-back">返佣比</a>
                                  	<?php if($admin_data['type'] == 100){?>
                                	<a href="#"  class="a-underline transfer">转移</a>
                               		<?php };};?>
								</td>
							</tr>
						<?php endforeach; endif; else: echo "" ;endif; ?>



						</tbody>
					</table>
                  <?php echo $personData->render(); ?>
				</div>
			</div>
          <!--调级确认 satrt-->
			<div class="modal fade" id="confirm-edit">
				<div class="modal-dialog">
					<div class="modal-content" style="width: 400px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"  style="text-align:center;">操作确认</h4>
						</div>
						<div class="modal-body adjust-box">
							<div class="config-des config">确认将<span class="yellow user_name">HYZF_1</span>调整为</div>
							<div class="input-group select-adjust">
								<select name="" class="select2 form-control form-control_select">
									<option value="">请选择</option>
									<option value="3">个人代理</option>
									<option value="4">企业代理</option>
									<option value="2">业务员</option>
									<option value="1">普通用户</option>
								</select>
								<input type="hidden" class="user_uid">
							</div>
						</div>
						<div class="modal-footer" style="text-align: center;">
							<button type="button" class="btn btn-primary confirm-adjust">确 认</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">取 消</button>
						</div>
					</div>
				</div>
			</div>
          <!--调级确认 end-->
          	          		<!--转移确认 satrt-->
			<div class="modal fade" id="transfer_modal">
				<div class="modal-dialog">
					<div class="modal-content" style="width: 400px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"  style="text-align:center;">操作确认</h4>
						</div>
						<div class="modal-body adjust-box">
							<div class="config-des config">确认将<span class="yellow transfer_user_name">HYZF_1</span>转移给</div>
							<div class="input-group select-adjust">
								<select name="" class="select2 form-control transfer_select">

								</select>
								<input type="hidden" class="transfer_user_uid">
							</div>
						</div>
						<div class="modal-footer" style="text-align: center;">
							<button type="button" class="btn btn-primary confirm_transfer">确 认</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">取 消</button>
						</div>
					</div>
				</div>
			</div>

			<!--转移确认 end-->

			<!--返佣比 satrt-->
			<div class="modal fade" id="rakeBack">
				<div class="modal-dialog">
					<div class="modal-content" style="width: 400px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"  style="text-align:center;">操作确认</h4>
						</div>
						<div class="modal-body adjust-box">
							<div class="config-des config margin-left-5">返佣比 ：</div>
							<div class="rackBack margin-left-5"><input type="text" class="fanyongbi_value" class="form-control"/></div>
						</div>
						<div class="modal-footer" style="text-align: center;">
							<button type="button" class="btn btn-primary fanyongbi" data-dialog="modal">确 认</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">取 消</button>
							<input type="hidden" class="fanyong" name="">
						</div>
					</div>
				</div>
			</div>
			<!--返佣比 end-->

			<!--冻结确认 satrt-->
<!-- 			<div class="modal fade" id="confirm-frozen" tabindex="-1" role="dialog" aria-labelledby="myModal">
				<div class="modal-dialog" role="document">
					<div class="modal-content" style="width: 400px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModal" style="text-align:center;">操作确认</h4>
						</div>
						<div class="modal-body">
							<div class="config-des">确认将<span class="yellow">HYZF_1</span>用户冻结</div>
						</div>
						<div class="modal-footer" style="text-align: center;">
							<button type="button" class="btn btn-primary">确 认</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">取 消</button>
						</div>
					</div>
				</div>
			</div> -->
			<!--冻结确认 end-->

			<!--个人、企业代理商弹层 start-->
			<div class="modal fade myModal"  tabindex="-1" role="dialog" id="company-dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="elastic-dialog">
							<div class="next-agent">下级代理商 ( HYZF_1 ) <span class="close-dialog-img" class="close" data-dismiss="modal" aria-label="Close"><img src="/public/static/assets/img/close-img.png"/></span></div>
							<div class="elastic-dialog-data">
								<ul id="myTab" class="nav-style">
									<li class="company-agent active">
										<a href="#home" data-toggle="tab" style="color: #fff">
											企业代理商
										</a>
									</li>
									<li class="personal-agent">
										<a href="#ios" data-toggle="tab" style="color: #fff;">
											个人代理商
										</a>
									</li>

									<!--弹层查询按钮-->
									<div class="table-search-img" id="table-search-wrap"><img src="/public/static/assets/img/table-search.png"/></div>

									<!--弹层条件搜索-->
									<div class="modal" id="search-con" role="dialog" style="z-index: 999999;">
										<form action="" method="">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="close-con" id="close-condition"></div>
													<div class="condition-search">
														<div class="search_1 margin-top-10">
															<div class="search_number">编号：</div>
															<div class="search_input"><input type="text" class=""/></div>
														</div>
														<div class="search_1">
															<div class="search_number">姓名：</div>
															<div class="search_input"><input type="text" class=""/></div>
														</div>
														<div class="search_1">
															<div class="search_number">电话：</div>
															<div class="search_input"><input type="text" class=""/></div>
														</div>
														<div class="search_1">
															<div class="search_number">等级：</div>
															<div class="search_input"><input type="text" class=""/></div>
														</div>
														<div class="search_1">
															<div class="search_number">总入金：</div>
															<div class="search_input"><input type="text" class=""/></div>
														</div>
														<div class="search_1">
															<div class="search_number">总提成：</div>
															<div class="search_input"><input type="text" class=""/></div>
														</div>
														<div class="search_1">
															<div class="search_number">时间：</div>
															<div class="search_input">
																<input class="form-control"  name="" value="" placeholder="开始时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readonly:true})" style="width: 100%;">
																<input class="form-control" name="" value="" placeholder="结束时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readonly:true})" style="width: 100%;">
															</div>
														</div>
														<div class="btn-search">
															<button class="btn btn-primary btn-sm" type="">查询</button>
														</div>
													</div>
												</div>
											</div>
										</form>
									</div>
								</ul>
								<div id="myTabContent" class="tab-content">
									<div class="tab-pane fade in active" id="home">
										<div class="row">
											<div class="col-sm-12 table-overflow">
												<table class="table table-spacing ">
													<thead>
													<tr class="table-title">
														<td width="8%">编号</td>
														<td width="8%">姓名</td>
														<td width="15%">联系方式</td>
														<td width="25%">下级代理</td>
														<td width="20%">业绩</td>
														<td width="10%">备注</td>
														<td width="15%">操作</td>
													</tr>
													</thead>
													<tbody>
													<tr class="table-data">
														<td>hyzf_1</td>
														<td>张三</td>
														<td>15600018901</td>
														<td>企业（10）&nbsp;个人（10）</td>
														<td>总入金（10000） 总提成（88888）</td>
														<td>信息</td>
														<td><a href="#" class="a-underline">调级</a>&nbsp;&nbsp;&nbsp;<a href="#" class="a-underline">冻结</a></td>
													</tr>
													<tr class="table-data">
														<td>hyzf_1</td>
														<td>张三</td>
														<td>15600018901</td>
														<td>企业（10）&nbsp;个人（10）</td>
														<td>总入金（10000） 总提成（88888）</td>
														<td>信息</td>
														<td><a href="#" class="a-underline">调级</a>&nbsp;&nbsp;&nbsp;<a href="#" class="a-underline">冻结</a></td>
													</tr>
													<tr class="table-data">
														<td>hyzf_1</td>
														<td>张三</td>
														<td>15600018901</td>
														<td>企业（10）&nbsp;个人（10）</td>
														<td>总入金（10000） 总提成（88888）</td>
														<td>信息</td>
														<td><a href="#" class="a-underline">调级</a>&nbsp;&nbsp;&nbsp;<a href="#" class="a-underline">冻结</a></td>
													</tr>
													<tr class="table-data">
														<td>hyzf_1</td>
														<td>张三</td>
														<td>15600018901</td>
														<td>企业（10）&nbsp;个人（10）</td>
														<td>总入金（10000） 总提成（88888）</td>
														<td>信息</td>
														<td><a href="#" class="a-underline">调级</a>&nbsp;&nbsp;&nbsp;<a href="#" class="a-underline">冻结</a></td>
													</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="ios">
										<div class="row">
											<div class="col-sm-12 table-overflow">
												<table class="table table-spacing ">
													<thead>
													<tr class="table-title">
														<td width="8%">编号</td>
														<td width="8%">姓名</td>
														<td width="15%">联系方式</td>
														<td width="25%">下级代理</td>
														<td width="20%">业绩</td>
														<td width="10%">备注</td>
														<td width="15%">操作</td>
													</tr>
													</thead>
													<tbody>
													<tr class="table-data">
														<td>hyzf_1</td>
														<td>张三</td>
														<td>15600018901</td>
														<td>企业（10）&nbsp;个人（10）</td>
														<td>总入金（10000） 总提成（88888）</td>
														<td>信息</td>
														<td><a href="#" class="a-underline">调级</a>&nbsp;&nbsp;&nbsp;<a href="#" class="a-underline">冻结</a></td>
													</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--个人、企业代理商弹层 end-->

		</div>
		<!-- content end -->
	</div>
	<!-- wrapper end -->

	<!-- Javascript -->
	<script src="/public/static/assets/vendor/jquery/jquery.min.js"></script>
	<script src="/public/static/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/public/static/assets/vendor/toastr/toastr.min.js"></script>
	<script src="/public/static/assets/vendor/My97DatePicker/WdatePicker.js"></script>
	<script src="/public/static/assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script src="/public/static/assets/vendor/js/main.js"></script>
	<script>
      		$(function(){

		$('.transfer').click(function(){
			var uid = $(this).parent().parent().find('td:eq(0)').html();
	       	var username = $(this).parent().parent().find('td:eq(1)').html();

	       	
	       	$.post('<?php echo url("user/player/transfer"); ?>',{},function(data){
	       		var html = '<option value="">请选择</option>';
              	html += '<option value="1">游戏平台</option>';
	       		$.each(data,function(key,item){

	       			html+='<option value="'+item.uid+'">'+item.realname+'('+item.username+')</option>';
	       		})

	       		
	       		$('.transfer_select').html(html);
	       		

	       	});

	       	

	       	$('.transfer_user_uid').val(uid);



	       	$('.transfer_user_name').text(username+'('+uid+')');
	       	$('#transfer_modal').modal();

		})

		$('.confirm_transfer').click(function(){

			var uid = $('.transfer_user_uid').val();
		    var refer_id = $('.transfer_select').val();
			if(refer_id == ""){

		    	alert('转移用户不能为空');return false;

		    }

			var mymessage=confirm("你确定要操作吗?");
		    if(mymessage==true)
		    {   
			
		    	$.post('<?php echo url("user/player/transfer"); ?>',{uid,refer_id},function(data){

			    		if(data.code == '0000'){
			    			alert(data.msg);
	                        location.reload();
			    		}else{
			    			alert(data.msg);

			    		}


		    	})

			}

		})

	})
      	
      
        // function searchCondition(){
        //     debugger
        //     var obj = document.getElementById('search-con')
        //     if(obj.style.display=="none"){
        //         obj.style.display="block";
        //     }else{
        //         obj.style.display="none";
        //     }
        // }

        // $("document").on("click", "#table-search-wrap" , function(){
        //     debugger
        //     $('#search-con').show();
		// });

        $("#table-search-wrap").on('click',function () {
            $('#search-con').show();
        })
		//
        // $("#close-condition").on('click',function () {
        //     var obj = document.getElementById('search-con')
		// 	obj.style.display = "none"
        // })


		// window.onload = function () {
        //     var btn = document.getElementById("table-search-img");
        //     var obj = document.getElementById('search-con')
        //     btn.onclick = function () {
        //         if(obj.style.display=="none"){
        //             obj.style.display="block";
        //         }else{
        //             obj.style.display="none";
        //         }
        //     }
        // }
	</script>
</body>
</html>
