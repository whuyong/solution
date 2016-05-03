<?php
/*
 *
 * @author     Never、more
 * @Created  2015/1/13
 */
namespace Admin\Controller;

class AnnouncementController extends CommonController {

	public function index() {
		
		$this->display();
	}


	/**
	 * 查询数据
	 */
	public function  lists() {
		$do = M("announcement");
 	
		/* 初始化分页查询条件 */
		$pageSize       = I('iDisplayLength');    //每页记录数
		$maxRows        = $do->count();           //总记录数
		$page           = I("iDisplayStart");     //当前起始索引
		$field          = array("f_id","f_title","username", "f_instime", "f_hits"); //字段
		$order          = I('iSortCol_0') != null ? 't1.'.$field[intval(I('iSortCol_0'))] . " " . I("sSortDir_0") : "t1.f_instime desc";//排序
		$where          = I('sSearch') != "" ? "AND t1.f_title like '%" . I('sSearch') . "%'" : " "; //搜索用户名
		//$DisplayRecords = $do->where($where)->count();
		//$data           = $do->where($where)->order($order)->limit($page, $pageSize)->select(); //获取数据

		$csql = "SELECT
					count(t1.`f_id`) as c
				FROM
					__PREFIX__announcement t1
				LEFT JOIN __PREFIX__admin t2 ON t1.`f_uid` = t2.`userid`
				WHERE t1.`f_isDele` = 0 
					{$where}";
		$cData = $do->query($csql);
		//var_dump($cData);
		$DisplayRecords = isset($cData[0]['c']) ? $cData[0]['c'] : 0;

		$sql = "SELECT
					t1.*, t2.`username`,
				IF(t1.`f_passed`,'审核','未审核') as passType
				FROM
					__PREFIX__announcement t1
				LEFT JOIN __PREFIX__admin t2 ON t1.`f_uid` = t2.`userid`
				WHERE t1.`f_isDele` = 0 
					{$where}
					ORDER BY {$order}
					limit {$page}, {$pageSize}";
		//echo $sql;
		
		$listData = $do->query($sql);

		/* 返回给客户端数据 */
		$list["sEcho"]                = I("sEcho"); //来自客户端 sEcho 的没有变化的复制品
		$list["iTotalRecords"]        = $maxRows;   //总记录数
		$list["iTotalDisplayRecords"] = $DisplayRecords;   //过滤之后，实际的行数
		$list["aaData"]               = !empty($listData) ? $listData : null;      //表中数据

		$this->ajaxReturn($list);
	}

	/**
	 * 图片上传裁剪函数
	 */
	public function thumpUpload() {
		/*图片上传*/
		//动态修改上传文件配置信息
		$upload           = new \Think\Upload();
		$upload->rootPath = C('UPLOAD_PATH') . 'Picture/';
		$upload->exts     = array('jpg', 'gif', 'png', 'jpeg');
		$upload->autoSub  = true;
		$upload->subName  = 'news' . '/' . date('Ymd') . "/";

		$info = $upload->uploadOne($_FILES['thumb']);
		/*图片裁剪*/
		$image = new \Think\Image();
		$image -> open(C('UPLOAD_PATH')."Picture/".$info['savepath'].$info['savename']);
		$image -> thumb(728,380,\Think\Image::IMAGE_THUMB_FIXED)->save(C('UPLOAD_PATH')."Picture/".$info['savepath'].$info['savename']);
		$image -> thumb(728,289,\Think\Image::IMAGE_THUMB_NORTHWEST)->save(C('UPLOAD_PATH')."Picture/".$info['savepath']."title_".$info['savename']);
		return $info;
	}

	/**
	 * 加载新闻添加页面
	 */
	public function add() {
		//获取公告分类
		$annouCategory = $this->getAnnouCategory();
		$this->assign("annouCategory",$annouCategory);

		//echo $sql;

		//echo  C('DB_PREFIX');
		
		//获取新闻分类信息
		// $list = getCate(C('NEWS'),0);
		// $this->assign("list",$list);
		$this->display('edit');
	}

	/**
	 * 加载编辑页面
	 */
	public function edit() {
		$annouCategory = $this->getAnnouCategory();
		$this->assign("annouCategory",$annouCategory);

		//$list = getCate(C('NEWS'),$pid=0);
		$data = D('announcement')->find(I('id', 0));
		// $this->assign("list",$list);
		$this->assign('data', $data);
		$this->display('edit');
	}

	/**
	 *获取公告分类
	 *
	 */
	private function getAnnouCategory(){
		$do = M();
		$f_corpid = session('admin.f_corpid');
		$sql = "SELECT
					t1.*, t2.tagname
				FROM
					__PREFIX__category t1
				LEFT JOIN __PREFIX__tag t2 ON t1.module = t2.id
				WHERE
					t1.f_corpid = {$f_corpid}
				AND t2.f_ename = 'gonggao' ";
		$annouCategory = $do->query($sql);

		return $annouCategory;
	}

	/**
	 * 添加数据
	 */
	public function insert() {
		$do  = D('announcement');
		

		if ($do->create()) {
			$do->f_uid = session('admin.userid');
            $do->f_corpid = session('admin.f_corpid');

            $do->f_instime = date("Y-m-d H:i;s");
			$do->f_startTime = date("Y-m-d");
			$do->f_endTime = date("Y-m-d");

			$do->f_passed = 0; //未审核
                     
			//执行添加
			if ($do->add()) {
                                
				$this->success('添加分类成功!', U("index"));
			} else {
				$this->error("添加分类失败！");
			}
		} else {
			$this->error($do->getError());
		}
		//$this->error($do->getError());
	}

	/**
	 * 删除数据
	 */
	public function delete() {
		// $do = D('news');
		// //根据id查找对应新闻信息
		// $picname = $do->where("id=".I('id'))->getField("thumb");
		// if ($do->delete(I('id'))) {
		// 	//删除成功则删除对应新闻内容信息
		// 	M("news_data")->delete(I("id"));
		// 	//删除标题图片
		// 	unlink(".".$picname);
		// 	$titlepicname = pathinfo($picname);
		// 	unlink(".".$titlepicname['dirname'].C('TITLE_PREFIX').$titlepicname['basename']);
		// 	$this->success("删除成功!");
		// } else {
		// 	$this->error("删除失败!");
		// }
		$do = D("announcement");

		if ($do->create()) {
			
			$do->f_id = I('id');
			$do->f_isDele = 1;
			
			if($do->save()) {
				//更新内容表
				//M("announcement")->where('f_id=' . I('id'))->setField('f_isDele', 1);
				$this->success("删除成功", U("index"));
			} else {
				$this->error("删除失败");
			}
		} else {
			$this->error($do->getError());
		}

		//var_dump($f_id);

	}

	/**
	 * 修改数据
	 */
	public function update() {
		$do = D("announcement");
		//判断是否有内容简介，若没有从新闻内容中截取前120字作为简介
		//$_POST["introduce"] = $_POST["introduce"] ? $_POST["introduce"] : intro($_POST["content"]);
		
		if ($do->create()) {

			if($do->save()) {
				//更新内容表
				//M("news_data")->where('id=' . I('id'))->setField('content', I("content"));
				$this->success("修改成功", U("index"));
			} else {
				$this->error("修改失败");
			}
		} else {
			$this->error($do->getError());
		}


	}

	


	/**###################评论开始######################
	 * 加载显示页面
	 */
	public function comment(){
		$this->display();
	}

	/**
	 * 获取分页信息
	 */
	public function  commentList() {
		$do = M("comment");

		/* 初始化分页查询条件 */
		$pageSize       = I('iDisplayLength');    //每页记录数
		$maxRows        = $do->count();           //总记录数
		$page           = I("iDisplayStart");     //当前起始索引
		$field          = array("id", "uid","pid","puid","mid","content", "addtime", "status"); //查询字段
		$order          = I('iSortCol_0') != null ? $field[intval(I('iSortCol_0'))] . " " . I("sSortDir_0") : "addtime desc";//排序
		$where          = I('sSearch') != "" ? "content like '%" . I('sSearch') . "%'" : ""; //搜素标题title
		$DisplayRecords = $do->where($where)->count();
		$data           = $do->field($field)->where($where)->order($order)->limit($page, $pageSize)->select(); //获取数据

		/* 返回给客户端数据 */
		$list["sEcho"]                = I("sEcho"); //来自客户端 sEcho 的没有变化的复制品
		$list["iTotalRecords"]        = $maxRows;   //总记录数
		$list["iTotalDisplayRecords"] = $DisplayRecords;   //过滤之后，实际的行数
		$list["aaData"]               = $data;      //表中数据

		$this->ajaxReturn($list);
	}

	/**
	 * 执行修改
	 */
	public function update_comment(){
		//修改评论状态
		$status = I('status');
		$id = I('id');
		$mod = M('comment');
		$mod->where('id='.$id)->setField("status",$status);
	}
}