<?php 
   require_once("./inc/config.inc.php");
   require_once("./inc/userinfo.php");
   $module=$_config['apply']==''? "2":$_config['apply'];
   $category=getCommonDataInfo(array("module"=>$module),"tb_category","","","","",true);
   
	$uid=1;
   $uid = $uid?$uid:$user_info['uid'];
   $days = array();
   for($i=15;$i>=1;$i--){
	   $date = date("Y-m-d",strtotime("-".$i." day"));
	   array_push($days,$date);
   }

   for($i=0;$i<=15;$i++){
	   $date = date("Y-m-d",strtotime("+".$i." day"));
	   array_push($days,$date);
   }

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=480,target-densitydpi=high-dpi,user-scalable=no" />
<title>我的请假</title>
<link rel="stylesheet" href="includes/weui.min.css"/>
<link rel="stylesheet" href="includes/style.css"/>
</head>
<body>
<div class="container">
	<div class="page">
		
		<div class="weui_dialog_confirm" id="qj_result" style="display:none;">
			<div class="weui_mask"></div>
			<div class="weui_dialog">
				<div class="weui_dialog_hd"><strong class="weui_dialog_title">提交成功</strong></div>
				<div class="weui_dialog_bd">
					请耐心等待，同时您可以撤销或查看状态！
				</div>
				<div class="weui_dialog_ft">
					
					<a href="qj_detail.php" class="weui_btn_dialog primary">确定</a>
				</div>
			</div>
		</div>

		<div class="top_title">
			<h1 class="page_title">请假信息</h1>
		</div>

		<div class="bd">
			<div style="height:30px;margin-top:25px;text-align:center;color:#EB6100;font-size:22px;line-height:30px;">填写请假信息</div>
			<div class="weui_cells">
				<div class="weui_cell weui_cell_select weui_select_after">
					<div class="weui_cell_hd">
						请假类型
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						<select class="weui_select" name="select2" id="htype">
						<?php
						if($category){
								foreach($category as $k=>$v){
									echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
								}
							}
						?>
						</select>
					</div>
				</div>
				
				<div class="weui_cell weui_cell_select weui_select_after">
					<div class="weui_cell_hd"><label for="" class="weui_label2">开始时间</label></div>
					<div class="weui_cell_bd weui_cell_primary" style="position:relative">
						<select class="weui_select" name="starttime" onchange="qjselect()">
						    <?php foreach($days as $day){?>
							<option value="<?=$day?>" <?php if($day==date("Y-m-d")){echo "selected='selected'";}?>><?=$day?></option>
							<?php }?>
						</select>
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						<select class="weui_select" name="startap" id="startap" onchange="qjselect()">
							<option value="1">上午</option>
							<option value="2">下午</option>
						</select>
					</div>
				</div>
				<div class="weui_cell weui_cell_select weui_select_after">
					<div class="weui_cell_hd"><label for="" class="weui_label2">结束时间</label></div>
					<div class="weui_cell_bd  weui_cell_primary" style="position:relative">
						<select class="weui_select" name="endtime" onchange="qjselect()">
							<?php foreach($days as $day){?>
							<option value="<?=$day?>" <?php if($day==date("Y-m-d")){echo "selected='selected'";}?>><?=$day?></option>
							<?php }?>
						</select>
					</div>
					<div class="weui_cell_bd weui_cell_primary">
						<select class="weui_select" name="endap" id="endap" onchange="qjselect()">
							<option value="1">上午</option>
							<option value="2">下午</option>
						</select>
					</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_bd weui_cell_primary">
						<p>总天数</p>
					</div>
					<div class="weui_cell_ft" id="t_days">0天</div>
				</div>
			</div>
			
			<div class="weui_cells_title" style="margin-top:20px;">添加说明(可选)</div>
			<div class="weui_cells weui_cells_form">
				<div class="weui_cell">
					<div class="weui_cell_bd weui_cell_primary">
						<textarea class="weui_textarea" placeholder="请假理由" rows="3" id="content"></textarea>
						<div class="weui_textarea_counter"><span class="uinput_counter">0</span>/100</div>
					</div>
				</div>
			</div>

			<div class="weui_btn_area">
				<a class="weui_btn weui_btn_new" id="showTooltips" onclick="return qjtj();">提交申请</a>
			</div>

		</div>
	</div>
</div>
<script src="js/zepto.min.js"></script>
<script>
document.body.addEventListener('touchstart', function () {
}); 

$(function(){
	var $dialog = $('#qj_result');
    $dialog.find('.weui_btn_dialog').on('click', function () {
          $dialog.hide();
    });

});
var qjday = "";
function qjselect(){
	var starttime = $("[name=starttime]").val();
	var endtime = $("[name=endtime]").val();
	var startap = $("[name=startap]").val();
	var endap = $("[name=endap]").val();

    if(starttime > endtime){
		alert("时间选择不正确");
		return false;
	}
    qjday = getDays(starttime,endtime)+1;
	if(startap==1 && endap==1){
		//上午到上午
		qjday = qjday-0.5;
	}
    if(startap==1 && endap==2){
		//上午到下午
	}
	if(startap==2 && endap==1 && (starttime==endtime)){
		//下午到上午
		alert("时间选择不正确");
		qjday = qjday-1;
	}
	if(startap==2 && endap==2){
		//下午到下午
		qjday = qjday-0.5;
	}
	$("#t_days").html(qjday+"天");
}

function getDays(strDateStart,strDateEnd){
   var strSeparator = "-"; //日期分隔符
   var oDate1;
   var oDate2;
   var iDays;
   oDate1= strDateStart.split(strSeparator);
   oDate2= strDateEnd.split(strSeparator);
   var strDateS = new Date(oDate1[0], oDate1[1]-1, oDate1[2]);
   var strDateE = new Date(oDate2[0], oDate2[1]-1, oDate2[2]);
   iDays = parseInt(Math.abs(strDateS - strDateE ) / 1000 / 60 / 60 /24)//把相差的毫秒数转换为天数
   return iDays ;
}

function qjtj(){
	var starttime = $("[name=starttime]").val();
	var endtime = $("[name=endtime]").val();
	var htype = $("#htype").val();
	
	var content = $("#content").val();
	if(qjday==0){
		alert("请假天数不能为空");
		return;
	}
	if(content==""){
        alert("请假理由不能为空");
		return;
	}
	$.ajax({
		type: "get",
		url: "./control/qj_chk.php",
		data:"starttime="+starttime+"&endtime="+endtime+"&qjday="+qjday+"&content="+encodeURI(content)+"&htype="+htype,
		cache:false,
		beforeSend: function(XMLHttpRequest){    
		},
		success: function(data, textStatus){
			var res=parseInt(data);
			if(res > 0) $("#qj_result").show();
			else if(res < 0) alert("提交失败");
			
		},
		complete: function(XMLHttpRequest, textStatus){
		},
		error: function(){
		//请求出错处理
		}
    });
}
</script>
</body>
</html>