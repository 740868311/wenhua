<?php
namespace User\Controller;

use Common\Controller\MemberbaseController;

class CgController extends MemberbaseController {

	function _initialize(){
		parent::_initialize();
		$this->model	=	M('CgReserve');
	}

	// 场馆预定
	public function index() {

		$data  =   I('REQUEST.');

		// ----条件开始----
		if (isset($data['reserve_one_id'])) {
			$one_id  =   $data['reserve_one_id'];

			if ($one_id) {
				cookie('reserve_one_id', $one_id);
				$where['one_id'] =   $one_id;
			} else {
				cookie('reserve_one_id', null);
			}
		} else {
			if (cookie('reserve_one_id')) {
				$where['one_id'] =   cookie('reserve_one_id');
			}
		}

		if (isset($data['start'])) {
			$start  =   $data['start'];

			if ($start) {
				cookie('start', $start);
				$where['date'] =   array('gte',$start);
			} else {
				cookie('start', null);
			}
		} else {
			if (cookie('start')) {
				$where['date'] =   array('gte', cookie('start'));
			}
		}
		$this->start = cookie('start');

		if (isset($data['end'])) {
			$start  =   $data['end'];

			if ($start) {
				cookie('end', $start);
				$where['date'] =   array('lt',$start);
			} else {
				cookie('end', null);
			}
		} else {
			if (cookie('end')) {
				$where['date'] =   array('lt', cookie('end'));
			}
		}

		$this->end = cookie('end');

		if (isset($data['status']) && ($data['status'] == 1)) {
			$map['date']		=	array('lt', date('Y-m-d', time()));
			$map['status']		=	2;
			$map['_logic']		=	'or';
			$where['_complex']	=	$map;
		} else {
			$where['date']	=	array('egt', date('Y-m-d', time()));
			$where['status']=	1;
		}

		$this->status = (int)$data['status'];

		$this->assign('reserve_one_id', cookie('reserve_one_id'));

		// 得到所属场馆
		$one_cg 	=	M('CgOne')->select();
		foreach($one_cg as $one_one) {
			$one_swap[$one_one['id']]['cg_name']	=	$one_one['cg_name'];
			$one_swap[$one_one['id']]['add_ress']	=	$one_one['add_ress'];
		}
		$this->assign('one_cg', $one_swap);

		$where['user_id']	=	$this->userid;
		$count=$this->model->where($where)->count();

		$page = $this->page($count, 5);

		$this->model
			->where($where)
			->limit($page->firstRow , $page->listRows)
			->order("add_time DESC");

		$cg_data=$this->model->select();

//		echo $this->model->_sql();die;
		$this->assign("page", $page->show('Admin'));
		$this->assign('cg_data', $cg_data);

		$this->assign($this->user);
		$this->display();
	}

	public function td()
	{
		$id = (int)$_GET['id'];

		$where['user_id']	=	$this->userid;
		$where['id']		=	$id;
		$where['status']	=	1;

		$res = $this->model->where($where)->find();
		if ($res) {
			$save_res = $this->model->save(array('id'=>$id, 'status'=>2));
		}
		$this->redirect('user/Cg/index');
	}
}
