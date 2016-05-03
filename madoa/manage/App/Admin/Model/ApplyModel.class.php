<?php
/*
 *
 * @author     Never、more
 * @Created  2015/1/13
 */
namespace Admin\Model;

use Think\Model\ViewModel;
class ApplyModel extends ViewModel {
   public $viewFields = array(
     'Apply'=>array('f_id','f_corpid','f_uid','f_nextid','f_info','f_type','f_start','f_end','f_total','f_at','f_status','f_instime'),
     'Front_user'=>array('f_name'=>'uname', '_on'=>'Apply.f_uid=Front_user.f_id'),
     'Category'=>array('name'=>'ctname', '_on'=>'Apply.f_type=Category.id'),
     'Corp'=>array('f_name'=>'cpname', '_on'=>'Apply.f_corpid=Corp.f_id')
   );
 }
