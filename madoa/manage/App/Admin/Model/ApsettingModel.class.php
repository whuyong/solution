<?php
/*
 *
 * @author     Never、more
 * @Created  2015/1/13
 */
namespace Admin\Model;

use Think\Model\RelationModel;

class ApsettingModel extends RelationModel {

	//自动验证
	protected $_validate = array(
		array('f_corpid', 'require', '请填写所属部门'),
		array('f_uid', 'require', '请填写用户名'),
		array('f_type', 'require', '请选择结束时间'),
                array('f_suid', 'require', '请填写审批人')
	);

	//自动完成
	protected $_auto = array(
		array('f_lastimte', 'time', 2, 'function'),
		array('f_status', '1',1)
	);

	//关联模型
	protected $_link = array(
		'front_user' => array(//关联内容表
			'mapping_type'   => self::BELONGS_TO,
			'class_name'     => "front_user",
			"foreign_key"    => 'f_uid',
			"mapping_fields" => 'f_name,f_avatar',
			"as_fields"      => 'f_name:uname,f_avatar:f_upic'
		),
                'front_user2' => array(//关联内容表
			'mapping_type'   => self::BELONGS_TO,
			'class_name'     => "front_user",
			"foreign_key"    => 'f_suid',
			"mapping_fields" => 'f_name,f_avatar',
			"as_fields"      => 'f_name:suname,f_avatar:f_spic'
		),
                'category'  =>  array(
                        'mapping_type'   => self::BELONGS_TO,
			'class_name'     => "category",
			"foreign_key"    => 'f_type',
			"mapping_fields" => 'name',
			"as_fields"      => 'name:ctname'
                ),
                'corp'  =>  array(
                        'mapping_type'   => self::BELONGS_TO,
			'class_name'     => "corp",
			"foreign_key"    => 'f_corpid',
			"mapping_fields" => 'f_name',
			"as_fields"      => 'f_name:cpname'
                )
	);
}
