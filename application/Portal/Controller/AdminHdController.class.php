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

	// 活动列表
	public function index()
	{

		$this->display();
	}

	// 添加活动
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

	// 执行添加
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

	// 删除活动
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
}
