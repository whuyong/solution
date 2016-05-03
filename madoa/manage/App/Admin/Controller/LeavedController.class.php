<?php
/*
 *
 * @author     Never、more
 * @Created  2015/1/13
 */
namespace Admin\Controller;

class LeavedController extends CommonController {

	public function index() {
		$this->display();
	}
        
        public function tlist() {
		$this->display();
	}
        
        public function approve(){
             
                $this->display();
        }
        
       
        
	/**
	 * 查询数据
	 */
        public function  lists() {
            $dm=D("apply");
            $maxRows        = $dm->count(); 
            $pageSize       = I('iDisplayLength');
            $page           = I("iDisplayStart"); 
            $field          =array("f_id","f_uid","f_instime");
            $order          = I('iSortCol_0') != null ? $field[intval(I('iSortCol_0'))] . " " . I("sSortDir_0") : "f_instime desc";
            $where          = I('sSearch') != "" ? " Front_user.f_name like '%" . I('sSearch') . "%'" : "";
            $DisplayRecords = $dm->where($where)->count();
            $data           =$dm->field()->where($where)->order($order)->limit($page, $pageSize)->select();
            //echo $dm->field()->where($where)->order($order)->limit($page, $pageSize)->buildsql();
            
                 $list["sEcho"]                = I("sEcho"); //来自客户端 sEcho 的没有变化的复制品
		$list["iTotalRecords"]        = $maxRows;   //总记录数
		$list["iTotalDisplayRecords"] = $DisplayRecords;   //过滤之后，实际的行数
                if(!$data) $data="";
		$list["aaData"]               = $data;      //表中数据
		
		//$list = '';
		$this->ajaxReturn($list);
        }
	

	/**
	 * 加载新闻添加页面
	 */
	public function add() {
		//获取新闻分类信息
		$list = getCate(C('NEWS'),0);
		$this->assign("list",$list);
		$this->display('edit');
	}

	/**
	 * 加载请假修改页面
	 */
	
        public function editapp() {
		//获取信息分类信息和新闻内容信息
		$data = D('apply')->relation(true)->find(I('id', 0));
		$this->assign('data', $data);
		$this->display('editapp'); 
	}
         public function addapprove(){
                $f_corpid   =   session('admin.f_corpid');
                $m          =   C('apply');
                $dplist     =   getAllDepartmant($f_corpid);
                
                $plist      =   getAllCategory($f_corpid,$m);
                $ulist      =   getAllFrontUser($f_corpid);
                $this->assign('ulist',$ulist);
                $this->assign('plist',$plist);
                $this->assign('dlist',$dplist);
                $this->display('approve_add'); 
        }
        public function provedit(){
                $f_corpid   =   session('admin.f_corpid');
                $m          =   C('apply');
                $dplist     =   getAllDepartmant($f_corpid);
                $plist      =   getAllCategory($f_corpid,$m);
                $ulist      =   getAllFrontUser($f_corpid);
                $dm=D("apsetting");
                $the_list=$dm->relation(true)->where("f_id=".I("id"))->find();
                $this->assign('thelist',json_encode($the_list));
                $this->assign('data',($the_list));
                $this->assign('ulist',$ulist);
                $this->assign('plist',$plist);
                $this->assign('dlist',$dplist);
                $this->display('approve_edit'); 
        }
        public function update_prov(){
            $do=D("apsetting");
            $data["f_status"]=I("f_status");
            $prov=  json_decode(html_entity_decode(I("f_prov")),true);
           if($prov[0]['dbuid']) $data["f_suid"]=$prov[0]['dbuid'];
           
            if( $do->where("f_id=" . I("f_id"))->save($data)){
                $this->success('修改成功!', U("approve"));
            }else{
                $this->error($do->geterror());
            }
        }
        public function provdelete(){
            $do = D('apsetting');
		//根据id查找对应新闻信息
		//$picname = $do->where("f_id=".I('id'))->getField("thumb");
		if ($do->delete(I('id'))) {
			//删除成功则删除对应新闻内容信息
			/*M("news_data")->delete(I("id"));
			//删除标题图片
			unlink(".".$picname);
			$titlepicname = pathinfo($picname);
			unlink(".".$titlepicname['dirname'].C('TITLE_PREFIX').$titlepicname['basename']);*/
			$this->success("删除成功!");
		} else {
			$this->error("删除失败!");
		}
        }
        /**
	 * 添加审批数据
	 */
        public function insert_prov() {
                $usr = session('admin');
                $user_dept=I('user_and_dep');
                $user_dept=(json_decode(html_entity_decode ($user_dept),true));
               
		$do         = M('apsetting');
		$types      =   I("applytype");
                $f_prov    =   I("f_prov");
                
                $status     =   I("f_status");
                if(is_array($user_dept)){
                    foreach($user_dept as $uvl){
                         if(is_array($types)){
                            foreach($types as $k => $tvl){
                                if(is_array($f_prov)){
                                    foreach($f_prov as $k=>$pvl){
                                        $p_tmp                    =json_decode(html_entity_decode ($pvl),true);
                                        $data["f_corpid"]       =       $usr['f_corpid'];
                                        $data["f_uid"]          =       $uvl['dbuid'];
                                        $data["f_type"]         =       $tvl;
                                        $data["f_suid"]         =       $p_tmp[0]['dbuid'];
                                        $data["f_status"]       =       $status;
                                       $pk= $do->where(array("f_uid"=>$data["f_uid"],"f_type"=>$data["f_type"]))->count();
                                        $data["f_level"]        =       $pk+1;
                                        if ($id = $do->add($data)) {
                                            
                                        }else{
                                            
                                        }
                                    }
                                }else{
                                    $this->error("请选择审批人员");
                                    return false;
                                }
                                
                            }
                        }else{
                                $this->error("请选择审批类型");
                                return false;
                            }
                    }
                }else{
                     $this->error("请选择应用范围");
                     return false;
                }
                $this->success('添加成功！', U("approve"));
	}
	/**
	 * 添加数据
	 
	public function insert() {
		$do                 = D('news');
		//判断是否有内容简介，若没有从新闻内容中截取前120字作为简介
		$_POST["introduce"] = $_POST["introduce"] ? $_POST["introduce"] : intro($_POST["content"]);
		//判断是否有文件上传
		if($_FILES['thumb']['name']) {
			//若有文件上传调用上传文件函数
			$info = $this->thumpUpload();
			if ($info) {
				//上传成功则将保存路径封装
				$_POST['thumb'] =  "/Uploads/Picture/". $info['savepath'] . $info['savename'];
				if (!$do->create()) {
					$this->error($do->getError());
				} else {
					if ($id = $do->add()) {
						//内容表中添加数据
						$data['id']      = $id;
						$data['content'] = I('content');
						M('news_data')->add($data);
						$this->success('添加成功！', U("index"));
					} else {
						$this->error($do->getError());
					}
				}
			} else {
				$this->error("上传图片错误！");
			}
		}else{
			$this->error("请上传标题图片");
		}
	}
*/
	/**
	 * 删除数据
	 */
	public function delete() {
		$do = D('apply');
		//根据id查找对应新闻信息
		//$picname = $do->where("f_id=".I('id'))->getField("thumb");
		if ($do->delete(I('id'))) {
			//删除成功则删除对应新闻内容信息
			/*M("news_data")->delete(I("id"));
			//删除标题图片
			unlink(".".$picname);
			$titlepicname = pathinfo($picname);
			unlink(".".$titlepicname['dirname'].C('TITLE_PREFIX').$titlepicname['basename']);*/
			$this->success("删除成功!");
		} else {
			$this->error("删除失败!");
		}

	}
        /*
         修改请假数据
         */
        public function updateapp(){
            $do=D("apply");
            $data["f_start"]=I("f_start");
            $data["f_end"]=I("f_end");
            $data["f_total"]=(intval(strtotime($data['f_end']))-intval(strtotime($data['f_start'])))/3600/24;
            if( $do->where("f_id=" . I("f_id"))->save($data)){
                $this->success('修改成功!', U("index"));
            }else{
                $this->error($do->geterror());
            }
        }
      
        
	/**
	 * 修改数据
	 
	public function update() {
		$do                 = D("news");
		//判断是否有内容简介，若没有从新闻内容中截取前120字作为简介
		$_POST["introduce"] = $_POST["introduce"] ? $_POST["introduce"] : intro($_POST["content"]);
		//获取修改前的图片保存路径
		$oldpicname         = $do->where("id=" . I('id'))->getField("thumb");
		if ($_FILES['thumb']['name']) {
			$info = $this->thumpUpload();
			if ($info) {
				//若有文件上传删除原图片
				unlink(".".$oldpicname);
				//拼装标题图路径
				$titlepicname = pathinfo($oldpicname);
				unlink(".".$titlepicname['dirname'].C('TITLE_PREFIX').$titlepicname['basename']);
				//将新上传的图片保存路径封装
				$_POST['thumb'] = ltrim(C('UPLOAD_PATH'),".") . "Picture/" . $info['savepath'] . $info['savename'];
			} else {
				$this->error("上传图片失败!");
				return;
			}
		}else{
			//若无文件上传则保留原图
			$_POST['thumb'] = $oldpicname;
		}
			if ($do->create()) {
				if ($do->save()) {
					//更新内容表
					M("news_data")->where('id=' . I('id'))->setField('content', I("content"));
					$this->success("修改成功", U("index"));
				} else {
					$this->error("修改失败");
				}
			} else {
				$this->error($do->getError());
			}


	}*/
        /**
	 * 获取审核数据
	 */
        public function provelists(){
            $dm=D("apsetting");
            $maxRows        = $dm->relation(true)->count(); 
            $pageSize       = I('iDisplayLength');
            $page           = I("iDisplayStart"); 
            $field          =array("f_id","cpname","ctname","suname","f_level","f_instime");
            $where          = I('sSearch') != "" ? " tb_front_user.f_userid like '%" . I('sSearch') . "%'" : "";
            $order_tb       = $where =='' ? "":"tb_apsetting."; 
            $join           = $where !='' ? "LEFT JOIN __FRONT_USER__ ON __APSETTING__.f_uid=__FRONT_USER__.f_id" : "";
            $order          = I('iSortCol_0') != null ? $order_tb.$field[intval(I('iSortCol_0'))] . " " . I("sSortDir_0") : $order_tb."f_instime desc";
            
            $DisplayRecords = $dm->relation(true)->join($join)->where($where)->count();
          
            $data           =$dm->relation(true)->join($join)->where($where)->order($order)->limit($page, $pageSize)->select();
          //echo   $dm->relation(true)->join($join)->where($where)->order($order)->limit($page, $pageSize)->buildsql();
           $list["sEcho"]                = I("sEcho"); //来自客户端 sEcho 的没有变化的复制品
		$list["iTotalRecords"]        = $maxRows;   //总记录数
		$list["iTotalDisplayRecords"] = $DisplayRecords;   //过滤之后，实际的行数
                if(!$data) $data="";
		$list["aaData"]               = $data;      //表中数据
		
		//$list = '';
		$this->ajaxReturn($list);
        }
}