<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"E:\WWW\game/application/user\view\sales\index.html";i:1545364165;s:50:"E:\WWW\game\application\user\view\common\head.html";i:1545371954;}*/ ?>
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
		<div class="main" style="margin-top: 20px;">
			<div><a href="<?php echo url('user/sales/add_sales'); ?>"  class="add-member" >添加业务员</a></div>
			<form action="<?php echo url('user/sales/index'); ?>" method="post">
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-bordered form-table">
								<tbody>
								<tr>
									<td class="search-data" width="10%">编号</td>
									<td><input type="text" name="uid" class="form-control"/></td>
									<td class="search-data" width="10%">姓名</td>
									<td><input type="text"  name="username" class="form-control"/></td>
									<td class="search-data" width="10%">联系方式</td>
									<td><input type="text" name="tel" class="form-control"/></td>
<!-- 									<td class="search-data" width="10%">等级</td>
									<td><input type="text" class="form-control"/></td> -->
								</tr>
								<tr>
	<!-- 								<td class="search-data" width="10%">总入金</td>
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
		<!-- 							<td class="search-data" width="10%">时间段</td>
									<td>
										<input type="text" class="form-control"/>
									</td> -->
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
			<div class="row margin-left">
					<div class="table-title-wrap title-des">业务员列表</div>
				<div class="title-total">总人数：<span class="title-count">11</span>人</div>


			</div>
			<div class="row">
				<div class="col-sm-12">
					<table class="table table-hover table-spacing" style="margin-top: 20px;">
						<thead>
						<tr class="table-title">
							<td>编号</td>
							<td>账户号</td>
                          	<td>真实姓名</td>
							<td>联系方式</td>
                          	<td>财富值</td>
							<!--<td>时间</td>-->
							<td>提成点位</td>
                          	<td>总提成</td>
							<td>当日提成</td>
						<td>下级代理</td>
				<!-- 			<td>状态</td> -->
							<td>操作</td>
						</tr>
						</thead>
						<tbody>
				<?php if(is_array($user_data) || $user_data instanceof \think\Collection || $user_data instanceof \think\Paginator): $i = 0; $__LIST__ = $user_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
						<tr class="table-data">
							<td><?php echo $v['uid']; ?></td>
							<td><?php echo $v['username']; ?></td>
                          	<td><?php echo $v['realname']; ?></td>
							<td><?php echo $v['tel']; ?></td>
                          	<td><?php echo $v['gold']; ?></td>
							<!--<td><?php echo date("Y-m-d",$v['reg_time']); ?></td>-->
							<td class="recharge-green"><?php echo $v['commission']*100; ?>%</td>
                          	<td><?php echo round($v['money']/100*$v['commission'],2); ?></td>
							<td><?php echo round($v['today_money']/100*$v['commission'],2); ?></td>
							<td><!--<a href="#" class="company-detail">企业（<?php echo $v['business_num']; ?>）</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
								<a href="#" class="company-detail">个人（<?php echo $v['person_num']; ?>）</a>

							</a>&nbsp;&nbsp;&nbsp;<a href="#" class="company-detail">玩家（<?php echo $v['player_num']; ?>）</a>

							</td>
							
<!-- 							<td>工商银行 6212260000000000</td>
							<td class="untreated">未提现</td> -->
							<td>
								<a href="#" class="a-underline adjust">调级</a>&nbsp;&nbsp;&nbsp;
								<a href="<?php echo url('user/sales/edit_sales'); ?>?uid=<?php echo $v['uid']; ?>" class="a-underline edit">编辑</a>&nbsp;&nbsp;&nbsp;
								<a href="#example" class="a-underline" onclick="dongjie(<?php echo $v['uid']; ?>,$(this));">
											<?php if($v['is_lock'] ==0){?>
												冻结
											<?php }else{?> 
												已冻结 
											<?php };?></a>&nbsp;&nbsp;&nbsp;
                              <a href="#" class="a-underline rake-back">返佣比</a>

                              <a href="#" class="detail a-underline" value="<?php echo $v['uid']; ?>" style="margin-left:10px ">明细</a>
                              <?php if($admin_data['type'] == 100){?>
                              <a href="#" class="add_gold a-underline" value="<?php echo $v['uid']; ?>" style="margin-left:10px ">添加金币</a>
                              <?php };?>
							</td>
						</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>

						</tbody>
					</table>
                  <?php echo $user_data->render(); ?>
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
							<div class="rackBack margin-left-5"><input type="text" class="fanyongbi_value form-control"/></div>
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

			<!--个人、企业代理商弹层 start-->
			<div class="modal fade myModal"  tabindex="-1" role="dialog" id="company-dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="elastic-dialog">
							<div class="next-agent"><span class="next-agent_name"></span>的下级代理商  <span class="close-dialog-img" class="close" data-dismiss="modal" aria-label="Close"><img src="/public/static/assets/img/close-img.png"/></span></div>
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
												<!-- 		<td width="25%">下级代理</td> -->
														<td width="20%">业绩</td>
														<td width="10%">备注</td>
													<!-- 	<td width="15%">操作</td> -->
													</tr>
													</thead>
													<tbody id="company_agent">
													<tr class="table-data">
													<!-- 	<td>hyzf_1</td>
														<td>张三</td>
														<td>15600018901</td> -->
													<!-- 	<td>企业（10）&nbsp;个人（10）</td> -->
													<!-- 	<td>总入金（10000） 总提成（88888）</td>
														<td>信息</td> -->
												<!-- 		<td><a href="#" class="a-underline">调级</a>&nbsp;&nbsp;&nbsp;<a href="#" class="a-underline">冻结</a></td> -->
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
													<!-- 	<td width="25%">下级代理</td> -->
														<td width="20%">业绩</td>
														<td width="10%">备注</td>
													<!-- 	<td width="15%">操作</td> -->
													</tr>
													</thead>
													<tbody id="person_agent">
		<!-- 											<tr class="table-data">
														<td>hyzf_1</td>
														<td>张三</td>
														<td>15600018901</td>
													 	<td>企业（10）&nbsp;个人（10）</td> -->
											<!-- 			<td>总入金（10000） 总提成（88888）</td>
														<td>信息</td> -->
												<!-- 		<td><a href="#" class="a-underline">调级</a>&nbsp;&nbsp;&nbsp;<a href="#" class="a-underline">冻结</a></td> -->
													<!-- </tr>  -->
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
	<script src="/public/static/assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script src="/public/static/assets/vendor/js/main.js"></script>

</body>

<script type="text/javascript">
	$('.detail').click(function(){

		var uid = $(this).attr('value');
		var username = $(this).parent().parent().find('td:eq(2)').html();
		$('.next-agent_name').text(username);



		$.post('<?php echo url("user/sales/detail"); ?>',{uid:uid},function(data){


			var company_agent = '';
			$.each(data.company_agent,function(key,item){
				company_agent+='<tr class="table-data"><td>'+item.uid+'</td><td>'+item.username+'</td><td>'+item.tel+'</td><td>总入金（'+item.recharge/100+'） 总提成（'+Math.round(item.money/100*item.commission)+'）</td><td>'+item.realname+'</td></tr>';

			});

			$('#company_agent').html(company_agent);

			var person_agent = '';
			$.each(data.person_agent,function(key,item1){
				person_agent+='<tr class="table-data"><td>'+item1.uid+'</td><td>'+item1.username+'</td><td>'+item1.tel+'</td><td>总入金（'+item1.recharge/100+'） 总提成（'+Math.round(item1.money/100*item1.commission)+'）</td><td>'+item1.realname+'</td></tr>';

			});

			$('#person_agent').html(person_agent);


		});

			

		$('#company-dialog').modal();


	})


</script>


<script type="text/javascript">
		function dongjie(uid,obj){
			
	    	var mymessage=confirm("你确定要操作吗?");
		    if(mymessage==true)
		    {   
	
		    	$.ajax({
		    		'url':'<?php echo url("user/agent/dongjie"); ?>?uid='+uid,
		    		'type':'json',
		    		success:function(data){

		    			if(data.code=='0000'){
		    				obj.text('已冻结');
		    				alert(data.msg);

		    			}else if(data.code=='1111'){
		    				obj.text('冻结');
		    				alert(data.msg);

		    			}else{
		    				alert('服务器错误');
		    			}
		    		}

		    	})

			}
	    
		}

</script>


</html>
