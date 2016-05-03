<?php

//数据库获取部门信息
$ulist = array(
	array("f_id"=>'chenkui',"f_pic"=>"http://shp.qpic.cn/bizmp/jwZAYNYpOVTxPYMrQ3gSq33d4u88CdWEZG4DWOu5et6mcleLiaBXvNg/","f_dpinfo"=>array(1,2,3)),
	array("f_id"=>'xuxinghua',"f_pic"=>"http://shp.qpic.cn/bizmp/jwZAYNYpOVRHiboibMkDyiaULia4Oem4M1gvhibMQwZ7R8OXoWuzgQw44HA/","f_dpinfo"=>array(1,2)),
	array("f_id"=>'chunlei',"f_pic"=>"http://shp.qpic.cn/bizmp/jwZAYNYpOVRHiboibMkDyiaULia4Oem4M1gvfwJzjUxZ1Wqrvulx1iaVFNQ/","f_dpinfo"=>array(1))
);

echo json_encode($ulist);
exit;

?>