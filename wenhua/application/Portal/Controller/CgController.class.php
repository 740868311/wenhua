<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\AdminbaseController;

class CgController extends AdminbaseController {

	public function index()
	{
		$data = M('CgOne')->select();

		$this->assign('data', $data);
		$this->display();
	}

	public function add(){
		$this->display("add");
	}

	public function add_post()
	{
		if (IS_POST) {
			$post = $_POST;
			$data['cg_name'] = htmlspecialchars($post['cg_name']);
			$result	= M('CgOne')->add($data);
			if ($result) {
				$info	=	array(
					'status'=>	1,
					'info'	=>	'添加成功'
				);
				print_r(json_encode($info));
			} else {
				$info	=	array(
					'status'=>	0,
					'info'	=>	'添加失败'
				);
				echo json_encode($info);
			}
		}
	}

	// 删除文章分类
	public function del() {
		$id = I("get.id",0,'intval');
		$count = M('Cg')->where(array("one_id" => $id))->count();

		if ($count > 0) {
			$this->error("该菜单下还有详细场馆，无法删除！");
		}

		if (M('CgOne')->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}

	//  编辑
	public function edit()
	{
		$id 	= 	I("get.id",0,'intval');
		$data 	= 	M('CgOne')->where(array('id'=>$id))->find();
		$this->data = $data;
		$this->display();
	}

	// 执行编辑
	public function edit_post()
	{
		if (IS_POST) {
			$post = $_POST;
			$post['cg_name'] = htmlspecialchars($post['cg_name']);
			$result	= M('CgOne')->save($post);
			if ($result) {
				$info	=	array(
					'status'=>	1,
					'info'	=>	'添加成功'
				);
				print_r(json_encode($info));
			} else {
				$info	=	array(
					'status'=>	0,
					'info'	=>	'添加失败'
				);
				echo json_encode($info);
			}
		}
	}
}
