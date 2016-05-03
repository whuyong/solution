<?php
/*
 * @author    Leo
 * @version    $Id$
 * @filename index.php
 * @Created  2014/12/22 12:59
 */

define("APP_DEBUG",true);
//定义应用路径
define("APP_PATH","./App/");
//配置应用状态home
//define('APP_STATUS','home');
//定义缓存目录
define('RUNTIME_PATH',"./Runtime/");
//define('APP_STATUS','oschina');

require("./ThinkPHP/ThinkPHP.php");