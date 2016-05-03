<?php
/*
 * 分类控制器
 * @author     Leo
 * @Created  2014/12/30
 */
namespace Admin\Controller;

class CategoryController extends CommonController {

	/**
	 * 浏览分类
	 */
	public function index() {
		$do = D('category');
                $f_corpid = session('admin.f_corpid');
		//获取所有模块
		$module = $do->where("module>0 and f_corpid = '".$f_corpid."'")->distinct(true)->field('module')->select();
		
                foreach ($module as $k => $v) {
			if ($v['module'] > 0) {
				$tag = getTagInfo($v['module']);
				$module[$k]['name'] = $tag['tagname'];
				$module[$k]['num'] = $do->where("module=".$v["module"])->count();
			}

		}
                //var_dump($module);exit;
		$this->assign('module', $module);
		$this->assign('mid', I("mid"));
		$this->display();
	}

	/**
	 * 获取分类数据返回给客户端
	 */
	public function lists() {
                $f_corpid = session('admin.f_corpid');
		$list = D("category")->where("f_corpid = '".$f_corpid."'")->order("module desc,sort desc")->select();
		$data = getCateTree($list, 0, I("mid", "all"));
		$this->ajaxReturn($data);
	}

	/**
	 * 添加分类页面
	 */
	public function add() {
                $f_corpid = session('admin.f_corpid');
		$list = D('category')->where("f_corpid = '".$f_corpid."'")->order("module desc,sort desc")->select();
		foreach ($list as $k => $v) {
			if (I('id') == $v['id']) {
				$list[$k]["selected"] = "selected";
			}
		}
                
                //获取分类标签ID
                $tarr   = D("tag")->where("f_corpid = '".$f_corpid."' and f_ename = 'cate' ")->find();
                $tagid = $tarr['id'];
		$module = getTag($tagid,$f_corpid);
                
		//如果有id,当前操作为添加子分类
		if(I('id')){
			$item['module'] = D("category")->where("id=".I('id'))->getfield("module");
			$this->assign('item', $item);

		}
		$this->assign('module', $module);
		$this->assign('list', $list);
		$this->display("edit");
	}

	/**
	 * 执行添加分类
	 */
	public function insert() {
		$do = D('category');
                
		if ($do->create()) {
                        $do->f_corpid = session('admin.f_corpid');
                     
			//执行添加
			if ($do->add()) {
                                
				$this->success('添加分类成功!', U("index"));
			} else {
				$this->error("添加分类失败！");
			}
		} else {
			$this->error($do->getError());
		}

	}

	/**
	 * 删除分类
	 */
	public function delete() {
		if (!D("category")->where("pid=" . I('id'))->select()) {
			//执行删除
			if (D("category")->delete(I('id'))) {
				$this->success("分类删除成功!");
			} else {
				$this->success("分类删除失败!");
			}
		} else {
			$this->error("此分类下含有子分类,无法删除！请先删除子分类");
		}
	}

	/**
	 * 修改分类页面
	 */
	public function edit() {
                $f_corpid = session('admin.f_corpid');
                
		$item                = D("category")->where("f_corpid = '".$f_corpid."'")->find(I('id', 0));
		$list[0]             = D('category')->where("f_corpid = '".$f_corpid."'")->find($item['pid']);
		$list[0]["selected"] = "selected";

		if (!$list[0]['name']) {
			$list[0]['name'] = "作为主分类";
		}
		//获取模块
                $tarr   = D("tag")->where("f_corpid = '".$f_corpid."' and f_ename = 'cate' ")->find();
                $tagid = $tarr['id'];
		$module = getTag($tagid,$f_corpid);
		$this->assign('module', $module);
		$this->assign('moduleDisabled', "disabled");
		$this->assign('disabled', 'disabled');
		$this->assign('item', $item);
		$this->assign('list', $list);
		$this->display("edit");
	}

	/**
	 * 执行修改
	 */
	public function update() {
		$do = D('category');
		//只修改分类名称与排序
		if ($do->create()) {
			$data = array('name'=>I("name"),"sort"=>I("sort"));
			if ($do->where('id=' . I('id'))->setField($data)) {
				$this->success('修改分类成功!', U("index"));
			} else {
				$this->error("修改分类失败！");
			}
		} else {
			$this->error($do->getError());
		}
	}
}