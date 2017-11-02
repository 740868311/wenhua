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

class AdminHdTypeController extends AdminbaseController {

	private $model;

	public function __construct()
	{
		parent::__construct();
		$this->model	=	M('HdType');
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
			$data['type_name'] = htmlspecialchars($post['type_name']);
			$res = $this->model->where($data)->find();
			if ($res) {
				$info	=	array(
					'status'=>	0,
					'info'	=>	'该类型已经存在'
				);
				echo json_encode($info);die;
			}
			$result	= $this->model->add($data);
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
		$count = M('St')->where(array("type_id" =>array('like', '%'.$id.'%')))->count();

		if ($count > 0) {
			$this->error("该类型下还有活动，无法删除！");
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
			$post['type_name'] = htmlspecialchars($post['type_name']);
			$result	= $this->model->save($post);
			if ($result) {
				$info	=	array(
					'status'=>	1,
					'info'	=>	'编辑成功'
				);
				print_r(json_encode($info));
			} else {
				$info	=	array(
					'status'=>	0,
					'info'	=>	'编辑失败'
				);
				echo json_encode($info);
			}
		}
	}
}
