<?php 
   require_once("./inc/config.inc.php");
   require_once("./inc/userinfo.php");
$op_list=getCommonDataInfo(array("f_uid"=>$user_info['f_id']),"operate_union","","","",array("f_id"=>"desc"),true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=480,target-densitydpi=high-dpi,user-scalable=no" />
<title>申请列表</title>
<link rel="stylesheet" href="includes/weui.min.css"/>
<link rel="stylesheet" href="includes/style.css"/>
</head>
<body>
<div class="container">
	<div class="page">
		
		<div class="top_title">
			<h1 class="page_title" >我所管辖的人员请假申请列表</h1>
		</div>

		<div class="bd">

			<div class="weui_cells weui_cells_access">
				<div class="weui_cell weui_top">
					<div class="weui_cell_hd"></div>
					<div class="weui_cell_bd weui_cell_primary">
						姓名
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						部门
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						天数
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						状态
					</div>
					<div class="weui_cell_ft">操作</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_hd"></div>
					<div class="weui_cell_bd weui_cell_primary">
						AAA
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						技术
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						2天
					</div>
					<div class="weui_cell_bd weui_cell_primary" style="color:#EB6100">
						未审核
					</div>
					<div class="weui_cell_ft">处理</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_hd"></div>
					<div class="weui_cell_bd weui_cell_primary">
						AAA
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						技术
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						2天
					</div>
					<div class="weui_cell_bd weui_cell_primary" style="color:#EB6100">
						未审核
					</div>
					<div class="weui_cell_ft">处理</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_hd"></div>
					<div class="weui_cell_bd weui_cell_primary">
						AAA
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						技术
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						2天
					</div>
					<div class="weui_cell_bd weui_cell_primary" style="color:#999999">
						已审核
					</div>
					<div class="weui_cell_ft">处理</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_hd"></div>
					<div class="weui_cell_bd weui_cell_primary">
						AAA
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						技术
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						2天
					</div>
					<div class="weui_cell_bd weui_cell_primary" style="color:#999999">
						已审核
					</div>
					<div class="weui_cell_ft">处理</div>
				</div>
			</div>

		</div>
	</div>
</div>
<script src="js/zepto.min.js"></script>
<script>
document.body.addEventListener('touchstart', function () {
});
</script>
</body>
</html>