<?php 
   require_once("./inc/config.inc.php");
   require_once("./inc/userinfo.php");
   $apply_list=getCommonDataInfo(array("f_uid"=>$user_info['f_id']),"apply_union","","","",array("f_instime"=>"desc"),true);
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=480,target-densitydpi=high-dpi,user-scalable=no" />
<title>请假汇总</title>
<link rel="stylesheet" href="includes/weui.min.css"/>
<link rel="stylesheet" href="includes/style.css"/>
</head>
<body>
<div class="container">
	<div class="page">

		<div class="top_title">
			<div style="line-height:70px;font-size:20px;color:#09BB07;position:absolute;left:30px;top:12px">
				<img src="<?php echo $user_info['f_avatar'];?>" alt="" class="avatar_t" />
			</div>
			<div style="line-height:26px;font-size:22px;color:white;position:absolute;left:120px;top:36px">
				<span><?php echo $user_info['f_name'];?>的请假历史记录</span>
			</div>
		</div>
	
		<div class="bd">
			<div class="weui_cells">
			<?php if($apply_list){
				foreach($apply_list as $k=>$v){
			?>
				<div class="weui_cell" style="padding:0px 15px">
					<div class="weui_cell_bd weui_cell_primary">
						<div class="weui_cells" style="margin-top:0px">
							<div class="weui_cell">
								<div class="weui_cell_hd">
									请假时间：
								</div>
								<div class="weui_cell_bd weui_cell_primary">
									<?php echo $v['f_instime']?>
								</div>
								<div class="weui_cell_ft"><font color="#000000">类型：<?php echo $v['cname']?></font></div>
							</div>

							<div class="weui_cell">
								<div class="weui_cell_hd">
									请假日期：
								</div>
								<div class="weui_cell_bd weui_cell_primary">
									<?php echo substr($v['f_start'],0,10);?> 至 <?php echo substr($v['f_end'],0,10);?>
								</div>
								<div class="weui_cell_ft"><font color="#000000">天数：<?php echo ($v['f_total']/8);?>天</font></div>
							</div>

							<div class="weui_cell">
								<div class="weui_cell_hd">
									请假说明：
								</div>
								<div class="weui_cell_bd weui_cell_primary">
									<?php if($v['f_info']) echo $v['f_total']; else echo "无";?>
								</div>
								<div class="weui_cell_ft"></div>
							</div>
						</div>
					</div>
				</div>
				<?php 
					$op_list=getCommonDataInfo(array("f_aid"=>$v['f_id']),"operate_union","","","",array("f_level"=>"asc"),true);
					if($op_list ){
						foreach($op_list as $ok=>$ov){
							?>
				<div class="weui_cell">
					<div class="weui_cell_hd"><img src="<?php echo $ov['f_avatar']; ?>" alt="" class="avatar_b" style="margin-right:5px;display:block"></div>
					<div class="weui_cell_bd weui_cell_primary">
						<p><?php echo $ov['f_uname']; ?></p>
					</div>
					<div class="weui_cell_ft"><font color="#3CC51F"><?php if($ov['f_status']==0) echo "未审核"; elseif($ov['f_status']==1) echo "审核通过"; else echo "未通过";?></font></div>
				</div>
				
				<?php 	}
					}
					?>
			</div>
			<?php
					
				}
			}?>
			

			<div class="weui_opr_area">
				<p class="weui_btn_area">
					<a href="javascript:history.back();" class="weui_btn weui_btn_default">返回</a>
				</p>
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