<?php 
   require_once("./inc/config.inc.php");
   require_once("./inc/userinfo.php");
   if($aid){
	 $operate_list=getCommonDataInfo(array()"f_aid"=>$aid),"operate_union","","","",array("f_id"=>"desc"),true);
	 $apply_list=getCommonDataInfo(array("f_id"=>$aid),"apply_union","","","",array("f_id"=>"desc"),false);
   }
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
		
		<div class="weui_dialog_alert" id="op_result" style="display: none;">
			<div class="weui_mask"></div>
			<div class="weui_dialog">
				<div class="weui_dialog_hd"><strong class="weui_dialog_title">提交成功</strong></div>
				<div class="weui_dialog_bd">恭喜你，审核通过</div>
				<div class="weui_dialog_ft">
					<a href="javascript:;" class="weui_btn_dialog primary">确定</a>
				</div>
			</div>
		</div>

		<div class="top_title">
			<div class="top_info" style="padding-top:20px">
			<?php 
			if($operate_list){
				foreach($operate_list as  $k=>$v){
                                                        if($v["f_status"]=='1') {echo '<span style="font-size:26px">'.date("Y年m月d日",strtotime($v['f_instime'])).' '.$v['f_userid'].'批准</span><br />';}
							elseif($v["f_status"]=='2') { echo '<span style="font-size:26px">'.date("Y年m月d日",strtotime($v['f_instime'])).' '.$v['f_userid'].'驳回</span><br />'; $prove_flag=2; return; }
                                                        elseif($v['f_uid']==$user_info['f_id']) { echo '<span style="font-size:20px">现等待您的批准...</span>';} 
					?>
				
				
				<?php
				}
			}?>
			</div>
		</div>
		<div class="bd">
			
			<div style="height:30px;margin-top:25px;text-align:center;color:#EB6100;font-size:22px;line-height:30px;"><?php echo $apply_list['f_title']."-".$apply_list['f_userid']?>的请假申请</div>
			
			<div class="weui_cells weui_cells_access">
				<div class="weui_cell">
					<div class="weui_cell_hd">
						请假类型：
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						<p><?php echo $apply_list['cname'];?></p>
					</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_hd">
						请假时间：
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						<p><?php echo substr($apply_list['f_start'],0,10);?> 至 <?php echo substr($apply_list['f_end'],0,10);?></p>
					</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_hd">
						申请时间：
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						<p><?php echo $apply_list['f_instime'];?></p>
					</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_hd">
						请假天数：
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						<p><?php echo $apply_list['f_total'];?>天</p>
					</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_hd">
						请假原因：
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						<p><?php if($apply_list['f_info']) echo $v['f_total']; else echo "无";?></p>
					</div>
				</div>
				<!--<div class="weui_cell">
					<div class="weui_cell_hd">
						审核状态：
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						<p>待审核</p>
					</div>
					<div class="weui_cell_ft">
					</div>
				</div>-->
			</div>
			<?php if($prove_flag!=2){ ?>
			<div class="weui_cells_title">审核意见(可选)</div>
			<div class="weui_cells weui_cells_form">
				<div class="weui_cell">
					<div class="weui_cell_bd weui_cell_primary">
						<textarea class="weui_textarea" placeholder="说明" rows="2"></textarea>
						<div class="weui_textarea_counter"><span class="uinput_counter">0</span>/50</div>
					</div>
				</div>
			</div>
			<div class="weui_opr_area">
				<p class="weui_btn_area">
					<a href="javascript:javascript:$('#op_result').show();void(0);" class="weui_btn weui_btn_new">同意</a>
					<a href="javascript:javascript:$('#op_result').show();void(0);" class="weui_btn weui_btn_default">驳回</a>
				</p>
			</div>
			<?php }?>
		</div>
	</div>
</div>
<script src="js/zepto.min.js"></script>
<script>
document.body.addEventListener('touchstart', function () {
});

$(function(){
	
	var $dialog = $('#op_result');
    $dialog.find('.weui_btn_dialog').on('click', function () {
          $dialog.hide();
    });

});
</script>
</body>
</html>