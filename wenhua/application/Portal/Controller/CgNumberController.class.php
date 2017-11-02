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

class CgNumberController extends AdminbaseController {

	private $model;

	public function __construct()
	{
		parent::__construct();
		$this->model	=	M('CgNumber');
	}

	public function index()
	{
		$data = $this->model->select();
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
			$data['number_name']	= 	htmlspecialchars($post['number_name']);
			$data['min']			=	(int)$post['min'];
			$data['max']			=	(int)$post['max'];
			$result	= $this->model->add($data);
			if ($result) {

				$this->success('添加成功');die;
				$info	=	array(
					'status'=>	1,
					'info'	=>	'添加成功'
				);
				print_r($info);die;
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
		$count = M('Cg')->where(array("number_id" => $id))->count();

		if ($count > 0) {
			$this->error("该条件还有详细场馆在用，无法删除！");
		}

		if ($this->model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}

	//  编辑
	public function edit()
	{
		$id 	= 	I("get.id",0,'intval');
		$data 	= 	$this->model->where(array('id'=>$id))->find();
		$this->data = $data;
		$this->display();
	}

	// 执行编辑
	public function edit_post()
	{
		if (IS_POST) {
			$post = $_POST;
			$post['number_name'] = htmlspecialchars($post['number_name']);
			$post['min'] = (int)$post['min'];
			$post['max'] = (int)$post['max'];
			$result	= $this->model->save($post);
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
