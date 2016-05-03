<?php
/*
 * 公共函数库
 * @author    Leo
 * @filename functions.php
 * @Created  2015/12/29 10:02
 */

/**
    获取所有前端会员信息
 * 
 *  */
function getAllFrontUser($f_corpid){
    if(empty($f_corpid)) return '公司ID不能为空';
    $do = M('front_user');
    $ulist = $do->where("f_corpid = '".$f_corpid."'")->getField('f_id,f_userid,f_avatar,depart_path');
    $tmp_user = array();
     $i = 0;
    foreach ($ulist as $key=>$user){
       
        $tmp_user[$i]['f_id']   = $user['f_userid'];
        $tmp_user[$i]['db_uid'] = $user['f_id'];
        $tmp_user[$i]['f_pic']  = $user['f_avatar'];
        $tmp_dp = explode(",",rtrim($user['depart_path'],','));
        $tmp_info = array();
        foreach ($tmp_dp as $v){
            $tmp_info[] = intval($v);
        }
        
        $tmp_user[$i]['f_dpinfo'] = $tmp_info;
        $i++;
        
    }
    //var_dump(json_encode($tmp_user));exit;
    return json_encode($tmp_user);
}

function getAllCategory($f_corpid,$module){
    if(empty($f_corpid)) return '公司ID不能为空';
    $do = M('category');
  // echo $do->where(array("f_corpid"=>$f_corpid,"module"=>$module))->buildsql();
    $ulist = $do->where(array("f_corpid"=>$f_corpid,"module"=>$module))->select();
    $tmp_cate = array();
    $i = 0;
    foreach ($ulist as $key=>$user){
        $tmp_cate[$i]['id'] = $user['id'];
        //$tmp_user[$i]['f_name'] = $user['f_name'];
        $tmp_cate[$i]['name'] = $user['name'];
        $i++;
    }
    return ($tmp_cate);
}
/**
    获取所有部门
 * 
 *  */
function getAllDepartmant($f_corpid){
    if(empty($f_corpid)) return '公司ID不能为空';
    $do = M('departmant');
    $dplist = $do->where("f_corpid = '".$f_corpid."'")->getField('f_wxdid as f_id,f_pid,f_title,f_level,path');
    //转换数组
    $nlist = array();
    foreach($dplist as $key=>$row){
            if($row["f_level"]==1){
                    $c_arr = getChildNode(1,$dplist);
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
    
    return json_encode($nlist);
    
}

/**
    获取所有部门 处理子部门数组
 * 
 *  */
function getChildNode($n,$dplist) {
	
	$result = array();
	foreach($dplist as $key=>$row){
		if($row['f_pid']==$n){
			$c_arr = getChildNode($row["f_id"],$dplist);
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


/**
    新增CURL调用操作
 *  @param string $url 调用的URL地址
 * 
 *  */
function getUrlResult($url,$postData="",$headers=array()){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	if($postData){
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	}
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT,15);
	$returnValue = curl_exec($ch);
	
	curl_close($ch);
	return $returnValue;
}

/**
 * 根据内容生成简介
 * @param        $content
 * @param int    $length
 * @param string $charset
 * @param bool   $suffix
 * @return string
 */
function intro($content, $length = 300, $charset = "utf-8", $suffix = false) {
	if ($length) {
		$intro = trim(trimEOL(strip_tags($content)));
		// 删除实体
		$intro = preg_replace("/&([a-z]{1,});/", '', $intro);
		return msubstr($intro, 0, $length, $charset, $suffix);
	} else {
		return '';
	}
}

/**
 * 删除代码中的换行符
 * @param      $string
 * @param bool $js
 * @return mixed
 */
function trimEOL($string, $js = false) {
	$string = str_replace(array(chr(10), chr(13)), array('', ''), $string);
	return $js ? str_replace("'", "\'", $string) : $string;
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str     需要转换的字符串
 * @param string $start   开始位置
 * @param string $length  截取长度
 * @param string $charset 编码格式
 * @param string $suffix  截断显示字符
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
	if (function_exists("mb_substr"))
		$slice = mb_substr($str, $start, $length, $charset);
	elseif (function_exists('iconv_substr')) {
		$slice = iconv_substr($str, $start, $length, $charset);
		if (false === $slice) {
			$slice = '';
		}
	} else {
		$re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("", array_slice($match[0], $start, $length));
	}
	return $suffix ? $slice . '...' : $slice;
}

/**
 * 根据标签id获取所有子标签
 * @param int $id
 * @return mixed
 */
function getTag($id = 0,$f_corpid) {
	$do    = M('tag');
	$where = $id ? "pid=" . $id : "";
        if(empty($where)){
            $where = " f_corpid = '".$f_corpid."'";
        }else{
            $where .= " and f_corpid = '".$f_corpid."' ";
        }
        
	return $do->where($where)->field('id,tagname,pid')->select();
}

/**
 * 根据标签id获取标签信息
 * @param $id
 * @return mixed
 */
function getTagInfo($id) {
	$do = M('tag');
	return $do->find($id);
}

/**
 * 生成分类树
 * @param $data
 * @param $pid
 * @param $module
 * @return array
 */
function getCateTree($data, $pid, $module = "all") {
	$res = array();
	foreach ($data as $k => $v) {
		if ($module == $v['module'] || $module == "all") {
			if ($v['pid'] == $pid) {
				$v['additionalParameters']['children'] = getCateTree($data, $v['id'], $module);
				$v['type']                             = "folder";
				$v["name"]                             = $v["name"] . "<span data-id='" . $v['id'] . "'></span>";
				if (!$v['additionalParameters']['children']) {
					$v['type'] = "item";
				}
				$res[] = $v;
			}
		}
	}
	return $res;
}


/**
 * 生成分类数组
 * @param $data
 * @param $pid
 * @param $module
 * @return array
 */
function getCateArr($data, $pid, $module = "") {
	$res = array();
	foreach ($data as $k => $v) {
		if ($module == $v['module'] || !$module) {
			if ($v['pid'] == $pid) {
				$v['children'] = getCateArr($data, $v['id'], $module);
				$res[]         = $v;
			}
		}
	}
	return $res;
}

/**
 * 根据模块id与分类id 获取分类及子分类
 * @param string $module
 * @param int    $pid
 * @return array
 */
function getCate($module = "", $pid = 0) {
	$data = D('category')->order("module desc,sort desc")->select();
	return getCateArr($data, $pid, $module);
}

/**
 * 根据分类id获取分类信息
 * @param $id
 * @return mixed
 */
function getCateInfo($id) {
	$do = M('category');
	return $do->find($id);
}

/**
 * 根据分类id获取所有子分类id
 * @param $id
 * @return mixed
 */
function getCateChild($id) {
	$do = M('category');
	//level_2
	$list = $do->where('pid='.$id)->getField('id',true);
	foreach($list as $k=>$v){
		//level_3
		$res =  $do->where('pid='.$v)->getField('id',true);
		if($res){
			$list = array_merge($list,$res);
		}
	}
	//level_1
	$list[] = $id;
	return $list;
}


/**
 * 判断分类id是否属于另一个分类id的子类
 * @param $id
 * @param $pid
 * @return bool
 */
function isCateChild($id,$pid){
	$do = M('category');
	$list = $do->where('pid='.$pid)->getField('id',true);
	if(in_array($id,$list)){
		return true;
	}
	foreach($list as $v){
		$res = $do->where('pid='.$v)->getField('id',true);
		if(in_array($id,$res)){
			return true;
		}
	}
	return false;
}

/**
 * 生成节点数组
 * @param $data
 * @param $pid
 * @return array
 */
function getNodeArr($data, $pid = 0) {
	$res = array();
	foreach ($data as $k => $v) {
		if ($v['pid'] == $pid) {
			$v['children'] = getNodeArr($data, $v['id']);
			$res[]         = $v;
		}
	}
	return $res;
}

/**
 * 根据分类id生成面包屑
 * @param $id
 */
function cateBread($id) {
	$a = getCateInfo($id);
	if ($a['pid'] > 0) {
		$b = getCateInfo($a['pid']);
		if ($b['pid'] > 0) {
			$c = getCateInfo($b['pid']);
		}
	}
	echo $c['name'] . "&gt; " . $b['name'] . " &gt;" . $a['name'];
}

/**
 * 根据出生日期计算年龄
 * @param $birth
 * @return int
 */
function age($birth) {
	$age = array();
	$now = date('Ymd');
	//分解当前日期为年月日
	$nowyear  = (int)($now / 10000);
	$nowmonth = (int)(($now % 10000) / 100);
	$nowday   = $now % 100;


	//分解出生日期为年月日
	$birthyear  = (int)($birth / 10000);
	$birthmonth = (int)(($birth % 10000) / 100);
	$birthday   = $birth % 100;

	$year = $nowyear - $birthyear;
	if ($birthmonth > $nowmonth) {
		$year--;
	} else if ($birthmonth == $nowmonth) {
		if ($birthday == 29 && $birthmonth = 2) {
			if ($nowyear % 400 == 0 || (($nowyear % 4 == 0) && ($nowyear % 100 != 0))) {
				if ($birthday > $nowday) {
					$year--;
				}
			}
		}
	}

	return $year;
}


/**
 * 时间转换函数
 * @param $time
 * @return bool|string
 */
function tranTime($time) {
	$rtime = date("Y-m-d H:i", $time);

	$htime = date("H:i", $time);

	$time = time() - $time;

	if ($time < 60) {
		$str = '刚刚';
	} elseif ($time < 60 * 60) {
		$min = floor($time / 60);
		$str = $min . '分钟前';
	} elseif ($time < 60 * 60 * 24) {
		$h   = floor($time / (60 * 60));
		$str = $h . '小时前 ' ;
	} elseif ($time < 60 * 60 * 24 * 3) {
		$d = floor($time / (60 * 60 * 24));
		if ($d == 1)
			$str = '昨天 ' . $htime;
		else
			$str = '前天 ' . $htime;

	} else {

		$str = $rtime;

	}
	return $str;
}


/**
 * 城市查询（1表示籍贯、现居地；2表示希望工作城市）
 * @param $num
 * @return mixed
 */
function city($num){
	$city=M('city')->field('id,name')->order('sort desc')->where('level='.$num)->select();
	return $city;
}

/**
 * 根据城市id获取城市信息
 * @param $id
 */
function getCityInfo($id){
	$do = M('city');
	return $do->find($id);
}

/**
 * 邮件发送函数
 * @param $address
 * @param $title
 * @param $message
 * @return bool
 * @throws Exception
 * @throws phpmailerException
 */
function sendMail($address, $title, $message) {

	vendor('PHPMailer.class#phpmailer');

	//创建mail对象
	$mail = new PHPMailer();

	$mail->IsSMTP(); //设置使用SMTP服务器发送
	$mail->Host     = C('MAIL_HOST');  //设置126邮箱服务
	$mail->SMTPAuth = C('MAIL_SMTPAUTH');     // 设置需要验证
	$mail->Username = C('MAIL_USERNAME');  // 发件人使用邮箱
	$mail->Password = C('MAIL_PASSWORD'); // 设置发件人密码

	$mail->From     = C('MAIL_FROM');// 发件人邮箱
	$mail->FromName = C('MAIL_FROM_NAME'); //发送者名称

	$mail->AddAddress($address); // 添加发送地址

	$mail->IsHTML(true); //指定支持html格式
	$mail->CharSet = "UTF-8";

	$mail->Subject = $title;
	$mail->Body    = $message;

	if ($mail->Send()) {
		return true;
	} else {
		return false;
	}
}

/**
 * 换行符转<br/>
 * @param $data
 * @return mixed
 */
 function str_br($data){
	return str_replace("\n","<br/>",$data);
}

/**
 * 根据新闻id获取标题图路径
 * @param $id
 * @return string
 */
function titleImgPath($id){
	$path = M("news")->where("id=".$id)->getField("thumb");
	$path_parts = pathinfo($path);
	$titlepath = $path_parts['dirname'].C('TITLE_PREFIX').$path_parts['basename'];
	return $titlepath;
}

/**
 * 获取默认头像路径
 * @param $id 用户ID
 * @return array
 */
function defaultPhoto($uid){
	$mod = M("user");
	$list = $mod->find($uid);
	if(empty($list['photo'])){
		$list['photo'] = "/Public/Uploads/Picture/photo/default_user_face.jpg";
	}
	return $list;
}