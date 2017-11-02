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

class CgAdminController extends AdminbaseController {

	private $model;

	public function __construct()
	{
		parent::__construct();
		$this->model	=	M('Cg');
	}

	public function index()
	{
		$data = $this->model->order('id desc')->select();

		// 得到所属场馆
		$one_cg 	=	M('CgOne')->select();
		foreach($one_cg as $one_one) {
			$one_swap[$one_one['id']]	=	$one_one['cg_name'];
		}

		$this->assign('one_cg', $one_swap);

		// 得到所属区域
		$area		=	M('Area')->select();
		$this->assign('area', $area);

		// 得到类型
		$type		=	M('CgType')->select();
		foreach($type as $type_one) {
			$type_swap[$type_one['id']]	=	$type_one['type_name'];
		}
		$this->assign('type', $type_swap);

		$this->assign('data', $data);
		$this->display();
	}

	public function add(){
		// 得到所属场馆
		$one_cg 	=	M('CgOne')->select();
		$this->assign('one_cg', $one_cg);

		// 得到所属区域
		$area		=	M('Area')->select();
		$this->assign('area', $area);

		// 得到人数条件
		$number		=	M('CgNumber')->select();
		$this->assign('number', $number);

		// 得到类型
		$type		=	M('CgType')->select();
		$this->assign('type', $type);

		// 得到说所有设施
		$facility	=	M('CgFacility')->select();
		$this->assign('facility', $facility);

		$this->display("add");
	}

	public function add_post()
	{
		if (IS_POST) {
			if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
				foreach ($_POST['photos_url'] as $key=>$url){
					$photourl=sp_asset_relative_url($url);
					$_POST['image'][]=array("url"=>$photourl,"alt"=>$_POST['photos_alt'][$key]);
				}
			}
			$_POST['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
			$post = $_POST;

			$data['one_id']			=	(int)$post['one_id'];
			$data['area_id']		=	(int)$post['area_id'];
			$data['name']			=	htmlspecialchars($post['name']);
			$data['number_id']		=	(int)$post['number_id'];
			$data['type_id']		=	(int)$post['type_id'];
//			$data['address']		=	htmlspecialchars($post['address']);
			$data['facility_ids']	=	implode(',', $post['facility']);
			$data['acreage']		=	htmlspecialchars($post['acreage']);
			$data['number']			=	$post['number'];
			$data['cost']			=	(int)$post['cost'];
			$data['remarks']		=	htmlspecialchars($post['remarks']);
			$data['time_page']		=	implode(',', $post['time_page']);
			$data['image']			=	json_encode($post['image']);
			$data['thumb']			=	$post['thumb'];
			$data['add_time']		= 	date('Y-m-d H:i:s', time());

			$result	= $this->model->add($data);
			if ($result) {
				$this->success("添加成功！");
			} else {
				$this->error("添加失败！");
			}
		}
	}

	// 删除文章分类
	public function del() {
		$id = I("get.id",0,'intval');

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
		$this->assign("image",json_decode($data['image'],true));
		$this->data = $data;

		// 得到所属场馆
		$one_cg 	=	M('CgOne')->select();
		$this->assign('one_cg', $one_cg);

		// 得到所属区域
		$area		=	M('Area')->select();
		$this->assign('area', $area);

		// 得到人数条件
		$number		=	M('CgNumber')->select();
		$this->assign('number', $number);

		// 得到类型
		$type		=	M('CgType')->select();
		$this->assign('type', $type);

		// 得到说所有设施
		$facility	=	M('CgFacility')->select();
		$this->assign('facility', $facility);

		$this->display();
	}

	// 执行编辑
	public function edit_post()
	{
		if (IS_POST) {
			if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
				foreach ($_POST['photos_url'] as $key=>$url){
					$photourl=sp_asset_relative_url($url);
					$_POST['image'][]=array("url"=>$photourl,"alt"=>$_POST['photos_alt'][$key]);
				}
			}
			$_POST['thumb'] = sp_asset_relative_url($_POST['thumb']);
			$post = $_POST;

			$data['id']				=	$post['id'];
			$data['one_id']			=	(int)$post['one_id'];
			$data['area_id']		=	(int)$post['area_id'];
			$data['name']			=	htmlspecialchars($post['name']);
			$data['number_id']		=	(int)$post['number_id'];
//			$data['address']		=	htmlspecialchars($post['address']);
			$data['type_id']		=	(int)$post['type_id'];
			$data['facility_ids']	=	implode(',', $post['facility']);
			$data['acreage']		=	htmlspecialchars($post['acreage']);
			$data['number']			=	$post['number'];
			$data['cost']			=	(int)$post['cost'];
			$data['remarks']		=	htmlspecialchars($post['remarks']);
			$data['time_page']		=	implode(',', $post['time_page']);
			$data['image']			=	json_encode($post['image']);
			$data['thumb']			=	$post['thumb'];
			$data['add_time']		= 	date('Y-m-d H:i:s', time());

			$result	= $this->model->save($data);
			if ($result) {
				$this->success("修改成功！");
			} else {
				$this->error("修改失败！");
			}
		}
	}

	// 编辑
	public function timearea()
	{
		$cg_id 	= 	I("get.id",0,'intval');
		if (!$cg_id) {
			$this->error('缺少id');
		}
		$this->assign('cg_id', $cg_id);

		// 读取该场馆的排期
		$data 	= 	M('CgTimearea')->where(array('cg_id'=>$cg_id))->order('date desc')->select();

		$this->assign('data', $data);
		$this->display();
	}

	public function add_timearea()
	{
		if (IS_POST) {
			$post = $_POST;

			// 检查该场馆在该日期是否已存在
			$where	=	array(
				'cg_id'	=>	$post['cg_id'],
				'date'	=>	$post['date']
			);

			$result = M('CgTimearea')->where($where)->find();

			if ($result) {
				$this->error('该日期已经存在过了，请直接修改');
			} else {
				$time_page = trim($post['time_page'],',');
				$data	=	array(
					'cg_id'		=>	$post['cg_id'],
					'date'		=>	$post['date'],
					'time_page'	=>	$time_page
				);
				$add_re = M('CgTimearea')->add($data);
				if ($add_re) {
					$this->success('成功');
				} else {
					$this->error('失败');
				}
			}
		}
	}

	public function edit_timearea()
	{
		if (IS_POST) {
			$post = $_POST;

			$time_page = trim($post['time_page'],',');
			$data	=	array(
				'time_page'	=>	$time_page,
			);
			$where 		=	array(
				'cg_id'		=>	$post['cg_id'],
				'id'		=>	$post['id']
			);
			$add_re = M('CgTimearea')->where($where)->save($data);
			if ($add_re) {
				$this->success('成功');
			} else {
				$this->error('失败');
			}
		}
	}

	public function del_timearea()
	{
		$id = I("post.id",0,'intval');

		if (M('CgTimearea')->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}

	// 查看场馆预订
	public function cgyd()
	{
		$data = M('CgReserve')->select();
		$this->assign('data', $data);

		// 得到所属场馆
		$one_cg 	=	M('CgOne')->select();
		foreach($one_cg as $one_one) {
			$one_swap[$one_one['id']]['cg_name']	=	$one_one['cg_name'];
			$one_swap[$one_one['id']]['add_ress']	=	$one_one['add_ress'];
		}
		$this->assign('one_cg', $one_swap);

		$this->display();
	}
}
