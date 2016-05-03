<?php
/*
 * 分类模型类
 * @author    Leo
 * @filename DemoModel.class.php
 * @Created  2014/12/28 18:20
 */
namespace Admin\Model;

use Think\Model;

class AnnouncementModel extends Model {

	//自动验证
	protected $_validate = array(
		array('f_cid', 'require', '请选择公告分类'),
		array('f_title', 'require', '请输入公告标题'),
		array('f_content', 'require', '请填写公告内容')
	);

	protected $_map = array(
        'cid' =>'f_cid', // 把表单中name映射到数据表的username字段
        'title'  =>'f_title', // 把表单中的mail映射到数据表的email字段
        'content'  =>'f_content' // 
    );

}
