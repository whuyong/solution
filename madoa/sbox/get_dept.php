<?php

//数据库获取部门信息
$dplist = array(
	array("f_id"=>1,"f_pid"=>0,"f_title"=>"madhouse","f_level"=>1,"path"=>"0,"),
	array("f_id"=>2,"f_pid"=>1,"f_title"=>"madsolution","f_level"=>2,"path"=>"0,1,"),
	array("f_id"=>3,"f_pid"=>2,"f_title"=>"frontend","f_level"=>3,"path"=>"0,1,2,"),
	array("f_id"=>4,"f_pid"=>1,"f_title"=>"brand","f_level"=>2,"path"=>"0,1"),
	array("f_id"=>5,"f_pid"=>4,"f_title"=>"PM","f_level"=>3,"path"=>"0,1,4,"),
	array("f_id"=>6,"f_pid"=>4,"f_title"=>"BD","f_level"=>3,"path"=>"0,1,4,"),
	array("f_id"=>7,"f_pid"=>1,"f_title"=>"market","f_level"=>2,"path"=>"0,1,")
);

//转换数组
$nlist = array();
foreach($dplist as $key=>$row){
	if($row["f_level"]==1){
		$c_arr = getChildNode(1);
		if(count($c_arr) > 0){
			$row["hasC"] = 1;
			$row["cNode"] = $c_arr;
		}else {
			$row["hasC"] = 0;
		}
		$nlist[] = $row;
		break;
	}
}

function getChildNode($n) {
	global $dplist;
	$result = array();
	foreach($dplist as $key=>$row){
		if($row['f_pid']==$n){
			$c_arr = getChildNode($row["f_id"]);
			if(count($c_arr) > 0){
				$row["hasC"] = 1;
				$row["cNode"] = $c_arr;
			}else {
				$row["hasC"] = 0;
			}
			$result[] = $row;
		}
	}
	Return $result;
}

echo json_encode($nlist);
exit;

?>