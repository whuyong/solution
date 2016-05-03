<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="cn">
<head>
	<meta charset="utf-8"/>
	<title>后台</title>
	<meta name="keywords" content=""/>
	<meta name="description" content=""/>
	<script>
		wolf = {};
		wolf.seajsBase = "/madoa/manage/Public/Js/";
	</script>
	<script src="/madoa/manage/Public/Js/global.js" type="text/javascript"></script>
	<script src="/madoa/manage/Public/Js/libs/seajs/2.3.0/sea.js" type="text/javascript"></script>
	<!--<script src="/madoa/manage/Public/Js/libs/seajs/seajs-combo.js" type="text/javascript"></script>-->
	<script src="/madoa/manage/Public/Js/config.js" type="text/javascript"></script>
	<link rel="stylesheet" href="/madoa/manage/Public/Js/admin/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="/madoa/manage/Public/Js/admin/css/font-awesome.min.css"/>
	<link rel="stylesheet" href="/madoa/manage/Public/Js/admin/css/ace.min.css"/>
	<link rel="stylesheet" href="/madoa/manage/Public/Js/admin/css/ace-rtl.min.css"/>
	<link rel="stylesheet" href="/madoa/manage/Public/Js/admin/css/ace-skins.min.css"/>
</head>
<body>
<div class="navbar navbar-default" id="navbar">
	<script type="text/javascript">
		seajs.use(["ace-extra"],function(ace){
			try {
				ace.settings.check('navbar', 'fixed')
			} catch (e) {
			}
		})
	</script>

	<div class="navbar-container" id="navbar-container">
		<div class="navbar-header pull-left">
			<a href="<?php echo U('Index/index');?>" class="navbar-brand">
				<small>
					<i class="icon-home"></i>
					madsolution后台管理系统
				</small>
			</a><!-- /.brand -->
		</div>
		<!-- /.navbar-header -->
		<div class="navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">
				<li class="light-blue">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">

								<span class="user-info">
									<small>欢迎光临,</small>
									<?php echo ($user["alias"]); ?>
								</span>

						<i class="icon-caret-down"></i>
					</a>

					<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li>
							<a href="<?php echo U('setting/inbox');?>">
								<i class="icon-cog"></i>
								修改密码
							</a>
						</li>

						<li class="divider"></li>

						<li>
							<a href="<?php echo U('Login/logout');?>">
								<i class="icon-off"></i>
								退出
							</a>
						</li>
					</ul>
				</li>
			</ul>
			<!-- /.ace-nav -->
		</div>
		<!-- /.navbar-header -->
	</div>
	<!-- /.container -->
</div>

<div class="main-container" id="main-container">
<script type="text/javascript">
	seajs.use(["ace-extra"], function (ace) {
		try {
			ace.settings.check('main-container', 'fixed')
		} catch (e) {
		}
	})
</script>

<div class="main-container-inner">
<a class="menu-toggler" id="menu-toggler" href="#">
	<span class="menu-text"></span>
</a>

<div class="sidebar" id="sidebar">
<script type="text/javascript">
	seajs.use(["ace-extra"], function (ace) {
		try {
			ace.settings.check('sidebar', 'fixed')
		} catch (e) {
		}
	})
</script>
<div class="sidebar-shortcuts" id="sidebar-shortcuts">
	<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
		<button class="btn btn-success">
			<i class="icon-signal"></i>
		</button>

		<button class="btn btn-info">
			<i class="icon-pencil"></i>
		</button>

		<button class="btn btn-warning home"  title="后台首页">
			<i class="icon-home"></i>
		</button>

		<button class="btn btn-danger clear" title="清空缓存">
			<i class="icon-cog"></i>

		</button>
	</div>

	<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
		<span class="btn btn-success"></span>

		<span class="btn btn-info"></span>

		<span class="btn btn-warning"></span>

		<span class="btn btn-danger"></span>
	</div>
	<script>
		seajs.use(['$'],function($){
			$('button.home').click(function(){
				window.open("<?php echo U('Admin/Index/index');?>");
			})
			$('button.clear').click(function(){
				$.ajax({
					url : "<?php echo U('Setting/clearCache');?>",
					success : function (data) {
						if ( data.status > 0 ) {
							bootbox.alert(data.info, function () {

							});
						} else {
							bootbox.alert({
								message : data.info,
								title : "提示信息"
							});
							return false;
						}
					}
				})
			})

		})
	</script>
</div>
<!-- #sidebar-shortcuts -->
<span id="dataphp"
      data-controller="<?php echo (CONTROLLER_NAME); ?>"
      data-action="<?php echo (ACTION_NAME); ?>"></span>
<ul class="nav nav-list">



<li>
	<a href="javascript:void(0)" class="dropdown-toggle">
		<i class="icon-user"></i>
		<span class="menu-text"> 前端会员管理</span>
		<b class="arrow icon-angle-down"></b>
	</a>
		<ul class="submenu">
				<li class="frontuser">
					<a href="<?php echo U('FrontUser/index');?>">
						<i class="icon-leaf"></i>
						会员管理
					</a>
				</li>
				<li class="frontrole">
					<a href="<?php echo U('FrontRole/index');?>">
						<i class="icon-home"></i>
						角色管理
					</a>
				</li>
				<li class="frontnode">
					<a href="<?php echo U('FrontNode/index');?>">
						<i class="icon-laptop"></i>
						节点管理
					</a>
				</li>
				
				
				<li>
					<a href="javascript:void(0)" class="dropdown-toggle">
						<i class="icon-double-angle-right"></i>
						部门管理
						<b class="arrow icon-angle-down"></b>
					</a>
					<ul class="submenu" id="departmant">
						<li class="index">
							<a href="<?php echo U('Departmant/index');?>">
								<i class="icon-leaf"></i>
								浏览部门
							</a>
						</li>
						<li class="add">
							<a href="<?php echo U('Departmant/add');?>">
								<i class="icon-leaf"></i>
								添加部门
							</a>
						</li>
					</ul>
				</li>
				
				
			</ul>

</li>


<li id="catetag">
	<a href="#" class="dropdown-toggle">
		<i class="icon-list"></i>
		<span class="menu-text"> 分类/标签</span>

		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu">
		<li>
			<a href="javascript:void(0)" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				分类管理
				<b class="arrow icon-angle-down"></b>
			</a>
			<ul class="submenu" id="category">
				<li class="index">
					<a href="<?php echo U('Category/index');?>">
						<i class="icon-leaf"></i>
						浏览分类
					</a>
				</li>
				<li class="add">
					<a href="<?php echo U('Category/add');?>">
						<i class="icon-leaf"></i>
						添加分类
					</a>
				</li>
			</ul>
		</li>
		<li>
			<a href="javascript:void(0)" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				标签管理
				<b class="arrow icon-angle-down"></b>
			</a>
			<ul class="submenu" id="tag">
				<li class="index">
					<a href="<?php echo U('Tag/index');?>">
						<i class="icon-leaf"></i>
						浏览标签
					</a>
				</li>
				<li class="add">
					<a href="<?php echo U('Tag/add');?>">
						<i class="icon-leaf"></i>
						添加标签
					</a>
				</li>
			</ul>
		</li>
	</ul>
</li>

<!-- 通讯录管理 start -->
<li>
	<a href="#" class="dropdown-toggle">
		<i class="icon-edit"></i>
		<span class="menu-text"> 签到管理</span>
		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu" id="check">
		<li class="index">
			<a href="<?php echo U('Check/index');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				签到记录
			</a>
		</li>
		<li class="rule add">
			<a href="<?php echo U('Check/rule');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				打卡规则
			</a>
		</li>
		
	</ul>
</li>
<!-- 通讯录管理 end -->

<!-- 移动办公管理 start -->
<li>
	<a href="#" class="dropdown-toggle">
		<i class="icon-check"></i>
		<span class="menu-text"> 请假管理</span>
		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu" id="leaved">
		<li class="index editapp">
			<a href="<?php echo U('Leaved/index');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				请假记录
			</a>
		</li>
		<!--<li class="tlist add">
			<a href="<?php echo U('Leaved/tlist');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				请假类型
			</a>
		</li>-->
		<li class="approve addapprove provedit">
			<a href="<?php echo U('Leaved/approve');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				审批设置
			</a>
		</li>
		
	</ul>
</li>
<!-- 移动办公管理 end -->

<!-- 公告管理 start -->
<li>
	<a href="#" class="dropdown-toggle ">
		<i class="icon-volume-up"></i>
		<span class="menu-text"> 公告管理 </span>

		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu" id="announcement">
		<li class="index">
			<a href="<?php echo U('Announcement/index');?>" >
				<i class="icon-double-angle-right"></i>
				公告信息
				<b class="arrow icon-angle-down"></b>
			</a>
		</li>
		<li class="add">
			<a href="<?php echo U('Announcement/add');?>" >
				<i class="icon-double-angle-right"></i>
				发布公告
				<b class="arrow icon-angle-down"></b>
			</a>
		</li>
		<!--<li class="comment">
			<a href="<?php echo U('Announcement/comment');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				评论列表
				<b class="arrow icon-angle-down"></b>
			</a>
		</li>-->

	</ul>
</li>


<!-- 公告管理 end -->


<li>
	<a href="#" class="dropdown-toggle">
		<i class="icon-signal"></i>
		<span class="menu-text"> 业绩目标追踪</span>
		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu" id="kpi">
		<li class="index">
			<a href="<?php echo U('Kpi/index');?>">
				<i class="icon-double-angle-right"></i>
				考核列表
			</a>
		</li>
		<li class="add">
			<a href="<?php echo U('Kpi/add');?>">
				<i class="icon-double-angle-right"></i>
				添加考核规则
			</a>
		</li>
	</ul>
</li>


<li id="logistics">
	<a href="#" class="dropdown-toggle">
		<i class="icon-map-marker"></i>
		<span class="menu-text"> 物流管理</span>
		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu">
		<li class="index">
			<a href="<?php echo U('logistics/index');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				物流列表
				<b class="arrow icon-angle-down"></b>
			</a>
		</li>
	</ul>
</li>

<li id="stock">
	<a href="#" class="dropdown-toggle">
		<i class="icon-inbox"></i>
		<span class="menu-text"> 库存管理</span>

		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu" >
		<li class="index">
			<a href="<?php echo U('stock/index');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				库存列表
				<b class="arrow icon-angle-down"></b>
			</a>
		</li>
		
	</ul>
</li>

<li id="finance">
	<a href="#" class="dropdown-toggle">
		<i class="icon-gift"></i>
		<span class="menu-text"> 应收账款</span>

		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu">
		<li class="index">
			<a href="<?php echo U('finance/index');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				账款记录
				<b class="arrow icon-angle-down"></b>
			</a>
		</li>
	</ul>
</li>

<li id="intro">
	<a href="#" class="dropdown-toggle">
		<i class="icon-desktop"></i>
		<span class="menu-text"> 经销商介绍</span>

		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu">
		<li class="edit">
			<a href="<?php echo U('intro/edit');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				公司介绍
				<b class="arrow icon-angle-down"></b>
			</a>
		</li>
	</ul>
</li>

<li id="userman">
	<a href="#" class="dropdown-toggle">
		<i class="icon-heart"></i>
		<span class="menu-text"> 销售名片夹</span>

		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu">
		<li>
			<a href="javascript:void(0)" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>

				用户组
				<b class="arrow icon-angle-down"></b>
			</a>
			<ul class="submenu" id="user">
				<li class="index">
					<a href="<?php echo U('User/index');?>">
						<i class="icon-leaf"></i>
						用户列表
					</a>
				</li>
				<li class="add">
					<a href="<?php echo U('User/add');?>">
						<i class="icon-leaf"></i>
						添加用户
					</a>
				</li>
			</ul>
		</li>
		
	</ul>
</li>







<li id="product">
	<a href="javascript:void(0)" class="dropdown-toggle">
		<i class="icon-shopping-cart"></i>
		<span class="menu-text"> 产品管理</span>

		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu">
		<li class="index edit">
			<a href="<?php echo U('product/index');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				产品列表
				<b class="arrow icon-angle-down"></b>
			</a>
		</li>
	</ul>
</li>




<li id="custom">
	<a href="javascript:void(0)" class="dropdown-toggle">
		<i class="icon-book"></i>
		<span class="menu-text"> 员工客户管理</span>

		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu">
		<li class="index edit">
			<a href="<?php echo U('custom/index');?>" class="dropdown-toggle">
				<i class="icon-double-angle-right"></i>
				客户列表
				<b class="arrow icon-angle-down"></b>
			</a>
		</li>
	</ul>
</li>


<li id="outdata">
	<a href="#" class="dropdown-toggle">
		<i class="icon-globe"></i>
		<span class="menu-text"> DT数据管理</span>

		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu" >
		<li class="product">
			<a href="<?php echo U('Outdata/product');?>">
				<i class="icon-double-angle-right"></i>
				产品主数据
			</a>
		</li>
		<li class="custom">
			<a href="<?php echo U('Outdata/custom');?>">
				<i class="icon-double-angle-right"></i>
				客户主数据
			</a>
		</li>
		<li class="sales">
			<a href="<?php echo U('Outdata/sales');?>">
				<i class="icon-double-angle-right"></i>
				销售订单数据
			</a>
		</li>
		<li class="send">
			<a href="<?php echo U('Outdata/send');?>">
				<i class="icon-double-angle-right"></i>
				发货单数据
			</a>
		</li>
		<li class="instore">
			<a href="<?php echo U('Outdata/instore');?>">
				<i class="icon-double-angle-right"></i>
				进货数据
			</a>
		</li>

		<li class="stoke">
			<a href="<?php echo U('Outdata/stoke');?>">
				<i class="icon-double-angle-right"></i>
				库存数据
			</a>
		</li>

		<li class="finance">
			<a href="<?php echo U('Outdata/finance');?>">
				<i class="icon-double-angle-right"></i>
				应收账款
			</a>
		</li>


		<li class="adminlist">
			<a href="<?php echo U('Outdata/adminlist');?>">
				<i class="icon-double-angle-right"></i>
				经销商列表
			</a>
		</li>

		<li class="typelist">
			<a href="<?php echo U('Outdata/typelist');?>">
				<i class="icon-double-angle-right"></i>
				组织架构表
			</a>
		</li>
	</ul>
</li>

<?php if(($user["f_corpid"]) == "1"): ?><li>
	<a href="#" class="dropdown-toggle">
		<i class="icon-wrench"></i>
		<span class="menu-text"> 经销商管理</span>
		<b class="arrow icon-angle-down"></b>
	</a>
	<ul class="submenu" id="dealer">
		<li class="index">
			<a href="<?php echo U('Dealer/index');?>">
				<i class="icon-double-angle-right"></i>
				经销商列表
			</a>
		</li>
		<li class="add">
			<a href="<?php echo U('Dealer/add');?>">
				<i class="icon-double-angle-right"></i>
				添加经销商
			</a>
		</li>
	</ul>
</li><?php endif; ?>

<li>
	<a href="javascript:void(0)" class="dropdown-toggle">
		<i class="icon-cogs"></i>
		<span class="menu-text"> 权限管理</span>

		<b class="arrow icon-angle-down"></b>
	</a>
		<ul class="submenu">
				<li class="admin">
					<a href="<?php echo U('Admin/index');?>">
						<i class="icon-leaf"></i>
						后台管理员
					</a>
				</li>
				<li class="role">
					<a href="<?php echo U('Role/index');?>">
						<i class="icon-home"></i>
						角色管理
					</a>
				</li>
				<li class="node">
					<a href="<?php echo U('Node/index');?>">
						<i class="icon-laptop"></i>
						节点管理
					</a>
				</li>

			</ul>

</li>

</ul>
<!-- /.nav-list -->

<div class="sidebar-collapse" id="sidebar-collapse">
	<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
</div>

<script type="text/javascript">
	seajs.use(["ace-extra"], function (ace) {
		try {
			ace.settings.check('sidebar', 'collapsed')
		} catch (e) {
		}
	})
	//左侧菜单栏 open+active
	seajs.use(['$'], function ($) {
		var controller = $("#dataphp").attr("data-controller").toLocaleLowerCase();
		var action = $("#dataphp").attr("data-action").toLocaleLowerCase();
		(function () {
			if ( action ) {
				$("#" + controller).find("." + action).addClass("active").siblings().removeClass("active");
				$("#" + controller).find("." + action).parents("li").addClass("active open").siblings().removeClass("active open");
			}
			//管理组特别处理
			var rbac = ['admin', 'role', 'node','frontuser','frontrole','frontnode'];
			if ( rbac.toString().indexOf(controller) > -1 ) {
				$("."+controller).addClass("active").siblings().removeClass('active');
				$("."+controller).parents("li").addClass("active open").siblings().removeClass("active open");
			}
		})();
	})
</script>
</div>
<!--右侧内容 start-->
<div class="main-content">
	<!--面包屑 start-->
	<div class="breadcrumbs" id="breadcrumbs">
		<script type="text/javascript">
			seajs.use(["ace-extra"], function (ace) {
				try {
					ace.settings.check('breadcrumbs', 'fixed')
				} catch (e) {
				}
			})
		</script>

		<ul class="breadcrumb">
			<li>
				<i class="icon-home home-icon"></i>
				<a href="<?php echo U('Index/index');?>">首页</a>
			</li>
		</ul>
		<!--面包屑 end-->
	</div>

	<div class="page-content">
		<div class="page-header">
			<h1>欢迎来到madsolution后台管理系统</h1>
		</div>
		<!-- /.page-header -->

		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
			<div class="col-xs-12">
				<div class="well">
					<h3 class="green smaller lighter">Personal </h3>
					<ul class="list-unstyled spaced2">
						<li>
							<i class="icon-group "></i>
							用户名：<?php echo ($user["alias"]); ?>
						</li>

						<li>
							<i class="icon-dashboard"></i>
							登录时间：<?php echo date("Y-m-d H:i:s",$user[logintime]);?>
						</li>

						<li>
							<i class="icon-download"></i>
							登录IP：<?php echo ($user["loginip"]); ?>
						</li>

					</ul>
				</div>
				<div class="well">
					<h3 class="green smaller lighter">System </h3>
					<ul class="list-unstyled spaced2">
						<li>
							<i class="icon-desktop "></i>
							当前操作系统：<?php echo (PHP_OS); ?>
						</li>

						<li>
							<i class="icon-flag"></i>
							当前运行环境：<?php echo ($_SERVER['SERVER_SOFTWARE']); ?>
						</li>

						<li>
							<i class="icon-credit-card"></i>
							框架版本：<?php echo (THINK_VERSION); ?>
						</li>

					</ul>
				</div>
			</div>
		</div>
		<!-- PAGE CONTENT END -->
	</div>
</div>
</div>
<!-- page specific plugin scripts  加载当前页面js -->
<!-- TopButton start-->
<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
	<i class="icon-double-angle-up icon-only bigger-110"></i>
</a>
<!-- TopButton end-->
</div>
<!-- /.main-container -->

<!-- basic scripts 基本js-->
<script>
	seajs.use(['admin-common','typeahead-bs2','bootstrap-min','ace-elements','ace-min'])
</script>
<!-- inline scripts related to this page -->
</body>
</html>