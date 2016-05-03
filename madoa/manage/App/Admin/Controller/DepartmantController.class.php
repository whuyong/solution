<?php
/*
 * 部门控制器
 * @author     Leo
 * @Created  2014/12/30
 */
namespace Admin\Controller;

class DepartmantController extends CommonController {

	/**
	 * 浏览部门
	 */
	public function index() {
                $f_corpid = session('admin.f_corpid');
                
		$list = D('departmant')->where("f_corpid = '".$f_corpid."'")->order("concat(path,f_id)")->select();
                
		foreach ($list as $k => $v) {
			if ($v['f_pid'] > 0) {
				$num              = substr_count($v["path"], ",") - 1;
				$list[$k]['f_title'] = str_repeat("&nbsp;", $num * 4) . " —| " . $v['f_title'];
			}
		}
                //var_dump($list);exit;
		$this->assign('list', $list);
		$this->display();
	}

	/**
	 * 添加部门页面
	 */
	public function add() {
                $f_corpid = session('admin.f_corpid');
		$list = D('departmant')->where("f_corpid = '".$f_corpid."'")->field("f_id,f_pid,path,f_title")->order("concat(path,f_id)")->select();
		foreach ($list as $k => $v) {
			if ($v['f_pid'] > 0) {
				$num              = substr_count($v["path"], ",") - 1;
				$list[$k]['f_title'] = str_repeat("&nbsp;", $num * 3) . "—| " . $v['f_title'];
			}
			if(I('f_id') == $v['f_id']){
				$list[$k]["selected"] = "selected";
			}
		}
		$this->assign('list', $list);
		$this->display("edit");
	}

	/**
	 * 执行添加部门
	 */
	public function insert() {
		$do = D('departmant');
		if ($do->create()) {
                        $do->f_corpid = session('admin.f_corpid');
			//组合f_pid与路径
			$tmp      = explode("|", I('f_pid'));
			$do->f_pid  = $tmp[0];
			$do->path = $tmp[1] . $tmp[0] . ",";
                        if(empty($tmp[1])){
                            $do->f_level = 1;
                        }else{
                            $larr = explode(',',rtrim($tmp[1],','));
                            $do->f_level = count($larr)+1;
                        }
                        
			//执行添加
			if ($do->add()) {
				$this->success('添加部门成功!', U("index"));
			} else {
				$this->error("添加部门失败！");
			}
		} else {
			$this->error($do->getError());
		}

	}

	/**
	 * 删除部门
	 */
	public function delete() {
		if (!D("departmant")->where("f_pid=" . I('id'))->select()) {
			//执行删除
			if (D("departmant")->delete(I('id'))) {
				$this->success("部门删除成功!");
			} else {
				$this->success("部门删除失败!");
			}
		} else {
			$this->error("此部门下含有子部门,无法删除！请先删除子部门");
		}
	}

	/**
	 * 修改部门页面
	 */
	public function edit() {
                $f_corpid = session('admin.f_corpid');
		$item                = D("departmant")->find(I('f_id', 0));
		$list[0]             = D('departmant')->where("f_corpid = '".$f_corpid."'")->find($item['f_pid']);
		$list[0]["selected"] = "selected";
		if(!$list[0]['f_title']){
			$list[0]['f_title'] = "作为主部门";
		}
		$this->assign('disabled', 'disabled');
		$this->assign('item', $item);
		$this->assign('list', $list);
		$this->display("edit");
	}

	/**
	 * 执行修改
	 */
	public function update() {
		$do = D('departmant');
		if ($do->create()) {
                        $data = array(
                            "f_title"=>I("f_title"),
                            //"f_ename"=>I("f_ename")
                        );
                        
			if ($do->where('f_id=' . I('f_id'))->setField($data)) {
				$this->success('修改部门成功!', U("index"));
			} else {
				$this->error("修改部门失败！");
			}
		} else {
			$this->error($do->getError());
		}
	}
}