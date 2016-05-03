<?php
/**
 * 职位收藏
 * @author    Frank
 * @version   $Id$
 * @filename  FavoriteController.class.php
 * @Created   2015/1/12 8:59
 */
namespace User\Controller;

use Think\Controller;

class FavoriteController extends CommonController {
	/**
	 * 职位收藏列表
	 * @author    Frank
	 * @Created   2015/1/12
	 */
	public function index() {
		$jid  = D('collect')->where('uid=' . session('user.id'))->field('jid')->select();  //获取用户收藏职位id
		$job  = D('job');
		$com  = D('company');
		$edu  = getTag(C('EID'));      //获取学历列表
		$year = getTag(C('YEARID'));   //获取工作年限列表

		//验证职位收藏有效性,过滤已经失效职位.
		foreach ($jid as $k => $v) {
			if (!empty($job->where('id=' . $jid[$k]['jid'] . ' AND status=1')->select()[0])) {
				$result[] = $jid[$k]['jid'];
			}
		}

		//遍历获取有效企业信息及职位信息
		for ($i = 0; $i < count($result); $i++) {
			//获取所有职位信息
			$jobs[$i] = $job->where('id=' . $result[$i])->find();
			//转换职位工作地点成城市名
			$jobs[$i]['city'] = getCityInfo($jobs[$i])['name'];
			//转换薪资值
			$jobs[$i]['payid'] = $jobs[$i]['payid'] * 1000;

			//转换学历
			foreach ($edu as $k => $v) {
				if ($jobs[$i]['eid'] == $v['id']) {
					$jobs[$i]['edu'] = $v['tagname'];
				}
			}
			//转换工作年限
			foreach ($year as $k => $v) {
				if ($jobs[$i]['year'] == $v['id']) {
					$jobs[$i]['workyear'] = $v['tagname'];
				}
			}
			//加入唯一标识符(一个大随机数)
			$jobs[$i]['rand']  = rand(100000001, 999999999);
			//获取相关企业所有信息
			$coms[$i] = $com->where('id=' . $jobs[$i]['comid'])->field('cname,logo')->find();
			//添加企业名称
			$jobs[$i]['cname'] = $coms[$i]['cname'];
			//添加企业logo
			$jobs[$i]['logo']  = $coms[$i]['logo'];
			//转换时间显示
			$jobs[$i]['edittime'] = tranTime($jobs[$i]['edittime']);
		}
		$this->assign('job', $jobs);
		$this->display('Job/favorite');
	}

	/**
	 * 删除收藏
	 * @author    Frank
	 * @Created   2015/1/12
	 */
	public function deleteItem() {
		$jid    = $_POST['jid'];
		$uid    = session('user.id');
		$resule = D('collect')->where('uid=' . $uid . ' AND jid=' . $jid)->delete();
		if ($resule || $resule > 0) {
			$this->ajaxReturn(true);
		} else {
			$this->ajaxReturn(false);
		}
	}

}