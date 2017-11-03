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

class AdminHdController extends AdminbaseController {

	private $model;

	public function __construct()
	{
		parent::__construct();
		$this->model	=	M('Hd');
	}

	// 活动列表
	public function index()
	{
		$data = $this->model->select();
		$this->data = $data;
		$this->display();
	}

	// 添加活动
	public function add(){

		// 得到所属区域
		$area		=	M('Area')->select();
		$this->assign('area', $area);

		// 得到类型
		$type		=	M('HdType')->select();
		$this->assign('type', $type);

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

			if(strlen($post['phone']) == "11")
			{
				//上面部分判断长度是不是11位
				if (!preg_match_all("/13[123569]{1}\d{8}|15[1235689]\d{8}|188\d{8}/", $post['phone'], $array)) {
					$this->error('请输入正确的手机号');die;
				}
			}else{
				$this->error('手机号长度必须是11位');die;
			}
			$data['area_id']		=	(int)$post['area_id'];
			$data['name']			=	htmlspecialchars($post['name']);
			$data['sum']			=	(int)$post['sum'];
			$data['type_id']		=	(int)$post['type_id'];
			$data['address']		=	htmlspecialchars($post['address']);
			$data['phone']			=	$post['phone'];
			$data['date']			=	htmlspecialchars($post['date']);
			$data['time']			=	htmlspecialchars($post['time']);
			$data['remarks']		=	htmlspecialchars($post['remarks']);
			$data['hint']			=	htmlspecialchars($post['hint']);
			$data['host']			=	htmlspecialchars($post['host']);
			$data['thumb']			=	$post['thumb'];
			$data['content']		=	htmlspecialchars_decode($post['content']);
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

		// 得到所属区域
		$area		=	M('Area')->select();
		$this->assign('area', $area);

		// 得到类型
		$type		=	M('HdType')->select();
		$this->assign('type', $type);

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
			if(strlen($post['phone']) == "11")
			{
				//上面部分判断长度是不是11位
				if (!preg_match_all("/13[123569]{1}\d{8}|15[1235689]\d{8}|188\d{8}/", $post['phone'], $array)) {
					$this->error('请输入正确的手机号');die;
				}
			}else{
				$this->error('手机号长度必须是11位');die;
			}
			$data['id']				=	$post['id'];
			$data['area_id']		=	(int)$post['area_id'];
			$data['name']			=	htmlspecialchars($post['name']);
			$data['sum']			=	(int)$post['sum'];
			$data['type_id']		=	(int)$post['type_id'];
			$data['address']		=	htmlspecialchars($post['address']);
			$data['phone']			=	$post['phone'];
			$data['date']			=	htmlspecialchars($post['date']);
			$data['time']			=	htmlspecialchars($post['time']);
			$data['remarks']		=	htmlspecialchars($post['remarks']);
			$data['hint']			=	htmlspecialchars($post['hint']);
			$data['host']			=	htmlspecialchars($post['host']);
			$data['thumb']			=	$post['thumb'];
			$data['content']		=	htmlspecialchars_decode($post['content']);
			
			$result	= $this->model->save($data);
			if ($result) {
				$this->success("修改成功！");
			} else {
				$this->error("修改失败！");
			}
		}
	}
}
