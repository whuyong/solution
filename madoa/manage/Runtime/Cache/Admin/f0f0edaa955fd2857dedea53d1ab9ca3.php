<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html >
	<head>
		<meta charset="utf-8"/>
		<title>Madsolution 后台</title>
		<meta name="keywords" content=""/>
		<meta name="description" content=""/>
		<script src="/madoa/manage/Public/Js/global.js" type="text/javascript"></script>
		<script src="/madoa/manage/Public/Js/libs/seajs/2.3.0/sea.js" type="text/javascript"></script>
		<script src="/madoa/manage/Public/Js/config.js" type="text/javascript"></script>
		<link rel="stylesheet" href="/madoa/manage/Public/Js/admin/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/madoa/manage/Public/Js/admin/css/font-awesome.min.css"/>
		<link rel="stylesheet" href="/madoa/manage/Public/Js/admin/css/ace.min.css"/>
		<link rel="stylesheet" href="/madoa/manage/Public/Js/admin/css/ace-rtl.min.css"/>
		<link rel="stylesheet" href="/madoa/manage/Public/Js/admin/css/ace-skins.min.css"/>
	</head>

	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<i class=""></i>
									<span class="red">Madsolution</span>
									<span class="white">网站后台登陆</span>
								</h1>
								<!--<h4 class="blue">&copy; 周伯通网站后台管理系统</h4>-->
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="icon-coffee green"></i>
												Please Enter Your Information
											</h4>

											<div class="space-6"></div>
											<form method="post" action="<?php echo U('Login/login');?>">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" placeholder="用户名" name="username" />
															<i class="icon-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="密码" name="password" />
															<i class="icon-lock"></i>
														</span>
													</label>

													<div class="space"></div>

													<div class="clearfix">


														<button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="icon-key"></i>
															登陆
														</button>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

										</div><!-- /widget-main -->
										<div class="toolbar clearfix">
											<div>
												<a  class="forgot-password-link">
													<i class="icon-arrow-left"></i>
												</a>
											</div>
											<div>
												<a  class="user-signup-link">
													<i class="icon-arrow-right"></i>
												</a>
											</div>
										</div>
									</div><!-- /widget-body -->
								</div><!-- /login-box -->
							</div><!-- /position-relative -->
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div>
		</div><!-- /.main-container -->
		<!-- inline scripts related to this page -->
</body>
</html>