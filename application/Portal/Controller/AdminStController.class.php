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

class AdminStController extends AdminbaseController {

	private $model;

	public function __construct()
	{
		parent::__construct();
		$this->model	=	M('St');
	}

	public function index()
	{
		$data = $this->model->order('id desc')->select();

		// �õ���������
		$one_cg 	=	M('CgOne')->select();
		foreach($one_cg as $one_one) {
			$one_swap[$one_one['id']]	=	$one_one['cg_name'];
		}

		$this->assign('one_cg', $one_swap);

		// �õ���������
		$area		=	M('Area')->select();
		$this->assign('area', $area);

		// �õ�����
		$type		=	M('CgType')->select();
		foreach($type as $type_one) {
			$type_swap[$type_one['id']]	=	$type_one['type_name'];
		}
		$this->assign('type', $type_swap);

		$this->assign('data', $data);
		$this->display();
	}

	public function add(){
		// �õ���������
		$one_cg 	=	M('CgOne')->select();
		$this->assign('one_cg', $one_cg);

		// �õ���������
		$area		=	M('Area')->select();
		$this->assign('area', $area);

		// �õ���������
		$number		=	M('CgNumber')->select();
		$this->assign('number', $number);

		// �õ�����
		$type		=	M('CgType')->select();
		$this->assign('type', $type);

		// �õ�˵������ʩ
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
				$this->success("��ӳɹ���");
			} else {
				$this->error("���ʧ�ܣ�");
			}
		}
	}

	// ɾ�����·���
	public function del() {
		$id = I("get.id",0,'intval');

		if ($this->model->delete($id)!==false) {
			$this->success("ɾ���ɹ���");
		} else {
			$this->error("ɾ��ʧ�ܣ�");
		}
	}

	//  �༭
	public function edit()
	{
		$id 	= 	I("get.id",0,'intval');
		$data 	= 	$this->model->where(array('id'=>$id))->find();
		$this->assign("image",json_decode($data['image'],true));
		$this->data = $data;

		// �õ���������
		$one_cg 	=	M('CgOne')->select();
		$this->assign('one_cg', $one_cg);

		// �õ���������
		$area		=	M('Area')->select();
		$this->assign('area', $area);

		// �õ���������
		$number		=	M('CgNumber')->select();
		$this->assign('number', $number);

		// �õ�����
		$type		=	M('CgType')->select();
		$this->assign('type', $type);

		// �õ�˵������ʩ
		$facility	=	M('CgFacility')->select();
		$this->assign('facility', $facility);

		$this->display();
	}

	// ִ�б༭
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
				$this->success("�޸ĳɹ���");
			} else {
				$this->error("�޸�ʧ�ܣ�");
			}
		}
	}

	// �༭
	public function timearea()
	{
		$cg_id 	= 	I("get.id",0,'intval');
		if (!$cg_id) {
			$this->error('ȱ��id');
		}
		$this->assign('cg_id', $cg_id);

		// ��ȡ�ó��ݵ�����
		$data 	= 	M('CgTimearea')->where(array('cg_id'=>$cg_id))->order('date desc')->select();

		$this->assign('data', $data);
		$this->display();
	}

	public function add_timearea()
	{
		if (IS_POST) {
			$post = $_POST;

			// ���ó����ڸ������Ƿ��Ѵ���
			$where	=	array(
				'cg_id'	=>	$post['cg_id'],
				'date'	=>	$post['date']
			);

			$result = M('CgTimearea')->where($where)->find();

			if ($result) {
				$this->error('�������Ѿ����ڹ��ˣ���ֱ���޸�');
			} else {
				$time_page = trim($post['time_page'],',');
				$data	=	array(
					'cg_id'		=>	$post['cg_id'],
					'date'		=>	$post['date'],
					'time_page'	=>	$time_page
				);
				$add_re = M('CgTimearea')->add($data);
				if ($add_re) {
					$this->success('�ɹ�');
				} else {
					$this->error('ʧ��');
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
				$this->success('�ɹ�');
			} else {
				$this->error('ʧ��');
			}
		}
	}

	public function del_timearea()
	{
		$id = I("post.id",0,'intval');

		if (M('CgTimearea')->delete($id)!==false) {
			$this->success("ɾ���ɹ���");
		} else {
			$this->error("ɾ��ʧ�ܣ�");
		}
	}

	// �鿴����Ԥ��
	public function cgyd()
	{
		$data = M('CgReserve')->select();
		$this->assign('data', $data);

		// �õ���������
		$one_cg 	=	M('CgOne')->select();
		foreach($one_cg as $one_one) {
			$one_swap[$one_one['id']]['cg_name']	=	$one_one['cg_name'];
			$one_swap[$one_one['id']]['add_ress']	=	$one_one['add_ress'];
		}
		$this->assign('one_cg', $one_swap);

		$this->display();
	}
}
