<?php
/*
 * Rbac 角色管理
 * @author     Leo
 * @Created  2015/1/2
 */
namespace Admin\Controller;

class RoleController extends CommonController {

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
		$do = M("role");
                $f_corpid = session('admin.f_corpid');  
		/* 初始化分页查询条件 */
		$pageSize       = I('iDisplayLength');    //每页记录数
		$maxRows        = $do->count();           //总记录数
		$page           = I("iDisplayStart");     //当前起始索引
		$field          = array("id", "name", "remark", "status"); //字段
		$order          = I('iSortCol_0') != null ? $field[intval(I('iSortCol_0'))] . " " . I("sSortDir_0") : "addtime desc";//排序
		$where          = I('sSearch') != "" ? "name like '%" . I('sSearch') . "%'" : ""; //搜索角色
		 //1为超级管理员
                if($f_corpid != '1'){
                    if(empty($where)){
                        $where = "f_corpid = '".$f_corpid."' ";
                    }else{
                        $where = " and f_corpid = '".$f_corpid."' ";
                    }
                }
                $DisplayRecords = $do->where($where)->count();
		
                $data           = $do->where($where)->order($order)->limit($page, $pageSize)->join('LEFT JOIN tb_corp on tb_role.f_corpid = tb_corp.f_id')->select(); //获取数据
                //var_dump($data);
		/* 返回给客户端数据 */
		$list["sEcho"]                = I("sEcho"); //来自客户端 sEcho 的没有变化的复制品
		$list["iTotalRecords"]        = $maxRows;   //总记录数
		$list["iTotalDisplayRecords"] = $DisplayRecords;   //过滤之后，实际的行数
		$list["aaData"]               = $data;      //表中数据

		$this->ajaxReturn($list);
	}

	/**
	 * 添加角色页面
	 */
	public function add() {
                //获取所有经销商列表
		$corp = M("corp")->field("f_id,f_name")->select();
		$this->assign("corp", $corp);
                
		$this->display("edit");
	}

	/**
	 * 执行角色添加
	 */
	public function insert() {
		$do = D("role");
		if ($do->create()) {
                        $do->f_corpid = I('f_corpid') ? I('f_corpid') : session('admin.f_corpid');
			if ($do->add()) {
				$this->success("角色添加成功", U("Role/index"));
			} else {
				$this->error("角色添加失败");
			}
		} else {
			$this->error($do->getError());
		}
	}

	/**
	 * 删除角色
	 */
	public function delete() {
		$do = D("role");
		//判断该角色是否包含用户
		$num = M("role_user")->where("role_id=" . I("id", 0))->count();
		if ($num && $num > 0) {
			$this->error("该角色下含有{$num}名所属用户,暂时无法删除！");
			exit;
		}
		if ($do->delete(I('id', 0))) {
			$this->success("角色删除成功!");
		} else {
			$this->error("角色删除失败!");
		}
	}

	/**
	 * 修改页面
	 */
	public function edit() {
		$data = D("role")->find(I("id", 0));
		$this->assign("data", $data);
                
                //获取所有经销商列表
		$corp = M("corp")->field("f_id,f_name")->select();
		$this->assign("corp", $corp);
                
		$this->display("edit");
	}

	/**
	 * 修改操作
	 */
	public function update() {
		$do = D("role");
		if ($do->create()) {
                        $do->f_corpid = I('f_corpid') ? I('f_corpid') : session('admin.f_corpid');
			if ($do->save()) {
				$this->success("修改成功!", U("Role/index"));
			} else {
				$this->error("修改失败");
			}
		} else {
			$this->error($do->getError());
		}
	}

	/**
	 * 配置节点页面
	 */
	public function set() {
		if(I("post.")){
			if(I("nodeids")){
				D("role_node")->where("role_id=".I("id",0))->delete();
				foreach(I("nodeids") as $v){
					$data['role_id'] = I("id");
					$data['node_id'] = $v;
					if(!D("role_node")->add($data)){
						$this->error("节点【{$v}】配置失败:".D("role_node")->getError());
						exit;
					}
				}
				$this->success("节点配置成功",U("Role/index"));
			}
		}else{
			$data = D("role_node")->where("role_id=" . I("id", 0))->getfield("node_id", true);
			//获取节点列表
			$list = D('node')->order("sort desc")->select();
			foreach ($list as $k => $v) {
				if (in_array($v["id"], $data)) {
					$list[$k]["checked"] = "checked";
				}
			}
			$list = getNodeArr($list);
			$this->assign('list', $list);
			$this->display();
		}
	}
}