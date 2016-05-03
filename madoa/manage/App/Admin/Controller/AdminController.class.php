<?php
/*
 * Rbac 用户管理(管理员)
 * @author     Leo
 * @Created  2015/1/2
 */
namespace Admin\Controller;

class AdminController extends CommonController {

	public function index() {
                $f_corpid = session('admin.f_corpid');
                if($f_corpid == 1){
                    $this->display('super_index');
                }else{
                    $this->display();
                }
		
	}

	/**
	 * 查询数据
	 */
	public function  lists() {
		$do = D("admin");
                
                $f_corpid = session('admin.f_corpid');  
		/* 初始化分页查询条件 */
		$pageSize       = I('iDisplayLength');    //每页记录数
		$maxRows        = $do->count();           //总记录数
		$page           = I("iDisplayStart");     //当前起始索引
		$field          = array("userid", "username", "alias", "logintime", "loginip"); //字段
		$order          = I('iSortCol_0') != null ? $field[intval(I('iSortCol_0'))] . " " . I("sSortDir_0") : "id asc";//排序
		$where          = I('sSearch') != "" ? "username like '%" . I('sSearch') . "%'" : ""; //搜索用户名
		
                //1为超级管理员
                if($f_corpid != '1'){
                    if(empty($where)){
                        $where = "f_corpid = '".$f_corpid."' ";
                    }else{
                        $where = " and f_corpid = '".$f_corpid."' ";
                    }
                }
                
                $DisplayRecords = $do->where($where)->count();
		$data           = $do->where($where)->order($order)->limit($page, $pageSize)->relation(true)->join('LEFT JOIN tb_corp ON tb_admin.f_corpid = tb_corp.f_id')->select(); //获取数据
                //var_dump($data);
		/* 返回给客户端数据 */
		$list["sEcho"]                = I("sEcho");         //来自客户端 sEcho 的没有变化的复制品
		$list["iTotalRecords"]        = $maxRows;           //总记录数
		$list["iTotalDisplayRecords"] = $DisplayRecords;    //过滤之后，实际的行数
		$list["aaData"]               = $data;              //表中数据
               
		$this->ajaxReturn($list);
	}

	/**
	 * 添加用户页面
	 */
	public function  add() {
		//获取所有角色列表
                $where = "f_corpid = '".session('admin.f_corpid')."' ";
		$role = M("role")->field("id,remark")->where($where)->select();
		$this->assign("role", $role);
                
                //获取所有经销商列表
		$corp = M("corp")->field("f_id,f_name")->select();
		$this->assign("corp", $corp);
                
		$this->display("edit");
	}

	/**
	 * 执行添加操作
	 */
	public function insert() {
		$do = D("admin");
                
		if ($do->create()) {
                        $do->f_corpid = I('f_corpid') ? I('f_corpid') : session('admin.f_corpid');
			if ($user_id = $do->add()) {
				//添加角色_用户关联表数据
				$data['user_id'] = $user_id;
				$data['role_id'] = I("role_id");
				M("role_user")->add($data);
				$this->success("用户添加成功!", U("Admin/index"));
			} else {
				$this->error("用户添加失败！");
			}
		} else {
			$this->error($do->getError());
		}
	}

	/**
	 * 删除用户
	 */
	public function  delete() {
		$do       = D("admin");
		$username = D("admin")->where("userid=" . I("uid", 0))->getField("username");
		if ($username == C("RBAC_SUPERADMIN")) {
			$this->error("超级管理员无法删除");
			exit;
		}
		if ($do->delete(I("uid", 0))) {
			//删除角色_用户关联表数据
			M("role_user")->where("user_id=" . I("uid"))->delete();
			$this->success("删除成功");
		} else {
			$this->error("删除失败");
		}
	}

	/**
	 * 编辑页面
	 */
	public function edit() {
		//获取用户昵称与所属角色
		//$alias   = D("admin")->where("userid=" . I("uid", 0))->getField("alias");
                $data_arr = D("admin")->where("userid=" . I("uid", 0))->find();
                $alias      = $data_arr['alias'];
                $f_corpid   = $data_arr['f_corpid'];
                //var_dump($alias);
		$role_id = D("role_user")->where("user_id=" . I("uid", 0))->getField("role_id");
		//获取所有角色列表
                 $where = "f_corpid = '".session('admin.f_corpid')."' ";
		$role = M("role")->field("id,remark")->where($where)->select();
		
                $this->assign("role", $role);
		$this->assign("role_id", $role_id);
		$this->assign("alias", $alias);
                $this->assign("f_corpid",$f_corpid);
                //获取所有经销商列表
		$corp = M("corp")->field("f_id,f_name")->select();
		$this->assign("corp", $corp);
                
		$this->display("edit");
	}

	/**
	 * 执行更新操作
	 */
	public function update() {
		$do = D("admin");
		if ($do->create()) {
			//修改用户昵称
                        $data = array('alias'=>I('alias'),'f_corpid'=>I('f_corpid'));
			$do->where("userid=" . I("userid"))->setField($data);
                        //$do->where("userid=" . I("userid"))->setField("alias", I("alias"));
			//修改角色_用户关联表数据
			M("role_user")->where("user_id=" . I("userid"))->setField("role_id", I("role_id"));
			$this->success("用户信息修改成功", U("Admin/index"));

		} else {
			$this->error($do->getError());
		}
	}
        
        
        public function test(){
            echo 'success';
        }
}