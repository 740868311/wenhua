<?php
namespace Portal\Controller;
use Common\Controller\HomebaseController;
/**
 * ��ҳ
 */
class StController extends HomebaseController {

	public function __construct()
	{
		parent::__construct();
		$this->cg_model	=	M('St');
	}

	public function index() {
		$data  =   I('get.');

		// ----������ʼ----
		if (isset($data['type_id'])) {
			$type_id  =   (int)$data['type_id'];

			if ($type_id) {
				cookie('type_id', $type_id);
				$where['type_id'] =   $type_id;
			} else {
				cookie('type_id', null);
			}
		} else {
			if ((int)cookie('type_id')) {
				$where['type_id'] =   cookie('type_id');
			}
		}

		if (isset($data['facility'])) {
			$facility  		=   $data['facility'];

			if ($facility) {

				$facility_data 	= 	explode(',', $facility);

				sort($facility_data);
				$facility		=	'%'.implode('%', $facility_data).'%';
				$where['facility_ids']	=	array('like', $facility);

				cookie('facility', $facility_data);
			} else {
				cookie('facility', null);
			}
		} else {
			if (cookie('facility')) {
				$facility_data 	= 	explode(',', cookie('facility'));

				sort($facility_data);
				$facility		=	'%'.implode('%', $facility_data).'%';
				$where['facility_ids']	=	array('like', $facility);
			}
		}

		if (isset($data['one_id'])) {
			$one_id  =   $data['one_id'];

			if ($one_id) {
				cookie('one_id', $one_id);
				$where['one_id'] =   $one_id;
			} else {
				cookie('one_id', null);
			}
		} else {
			if (cookie('one_id')) {
				$where['one_id'] =   cookie('type_id');
			}
		}

		if (isset($data['area_id'])) {
			$area_id  =   $data['area_id'];

			if ($area_id) {
				cookie('area_id', $area_id);
				$where['area_id'] =   $area_id;
			} else {
				cookie('area_id', null);
			}
		} else {
			if (cookie('area_id')) {
				if (cookie('area_id') != 1) {
					$where['area_id']    =   cookie('area_id');
				}
			}
		}
		if (isset($data['number_id'])) {
			$number_id  =   $data['number_id'];

			if ($number_id) {
				cookie('number_id', $number_id);
				$where['number_id'] =   $number_id;
			} else {
				cookie('number_id', null);
			}
		} else {
			if (cookie('number_id')) {
				if (cookie('number_id') != 1) {
					$where['number_id']    =   cookie('number_id');
				}
			}
		}

		$this->assign('type_id', cookie('type_id'));
		$this->assign('one_id', cookie('one_id'));
		$this->assign('area_id', cookie('area_id'));
		$this->assign('number_id', cookie('number_id'));
		$this->assign('facility_ids', cookie('facility'));

		// �õ���������
		$one_cg 	=	M('CgOne')->select();
		foreach($one_cg as $one_one) {
			$one_swap[$one_one['id']]	=	$one_one['cg_name'];
		}
		$this->assign('one_cg', $one_swap);

		// �õ���������
		$area		=	M('Area')->select();
		foreach($area as $area_one) {
			$area_swap[$area_one['id']]	=	$area_one['area_name'];
		}
		$this->assign('area', $area_swap);

		// �õ���������
		$number		=	M('CgNumber')->select();
		$this->assign('number', $number);

		// �õ�����
		$type		=	M('CgType')->select();
		$this->assign('type', $type);

		// �õ�˵������ʩ
		$facility	=	M('CgFacility')->select();
		foreach($facility as $facility_one) {
			$facility_swap[$facility_one['id']]	=	$facility_one['facility_name'];
		}

		$this->assign('facility', $facility_swap);

		$count=$this->cg_model->where($where)->count();

		$page = $this->page($count, 20);

		$this->cg_model
			->where($where)
			->limit($page->firstRow , $page->listRows)
			->order("add_time DESC");

		$cg_data=$this->cg_model->select();

		$this->assign("page", $page->show('Admin'));
		$this->assign('cg_data', $cg_data);

		$this->display();
	}

	public function show()
	{
		$id 	= 	I("get.id",0,'intval');
		$where 	=	array('id'=>$id);
		$data	=	$this->cg_model->where($where)->find();

		// �õ���������
		$one_cg 	=	M('CgOne')->select();
		foreach($one_cg as $one_one) {
			$one_swap[$one_one['id']]	=	$one_one['cg_name'];
		}
		$this->assign('one_cg', $one_swap);

		// �õ�˵������ʩ
		$facility	=	M('CgFacility')->select();
		foreach($facility as $facility_one) {
			$facility_swap[$facility_one['id']]	=	$facility_one['facility_name'];
		}
		$this->assign('facility', $facility_swap);

		$date 	=	I('get.date');
		if (!$date) {
			$date = date('Y-m-d', time());
		}
		$this->assign('date', $date);

		$date_w	=	array(
			0	=>	'��',
			1	=>	'һ',
			2	=>	'��',
			3	=>	'��',
			4	=>	'��',
			5	=>	'��',
			6	=>	'��',
		);

		// ��ȡ����
		$timearea	=	 M('CgTimearea')->where(array('cg_id'=> $id))->select();
		foreach($timearea as $timearea_one) {
			$timearea_swap[$timearea_one['date']]	=	$timearea_one['time_page'];
		}

		// ��ȡ�Ѿ�Ԥ����ȥ������   �ս������ڿ������ⲿ����
		$yd_timearea=	M('CgReserve')->where(array('cg_id'=>$id))->select();

		foreach($yd_timearea as $yd_timearea_one) {
			$yd_timearea_swap[$yd_timearea_one['date']][$yd_timearea_one['time_page']]	=	$yd_timearea_one;
		}
		$this->assign('yd_timearea', $yd_timearea_swap);

		$this->assign('timearea', $timearea_swap);
		$this->assign('data', $data);
		$this->assign('date_w', $date_w);
		$this->display();
	}

	public function ajaxdd()
	{
		if (IS_POST) {
			$date_w	=	array(
				0	=>	'��',
				1	=>	'һ',
				2	=>	'��',
				3	=>	'��',
				4	=>	'��',
				5	=>	'��',
				6	=>	'��',
			);
			$data_post = $_POST;

			$user = sp_get_current_user();

			// �õ�����
			$res = M('cg')->where(array('id'=>$data_post['cg_id']))->find();
			$time_page = $res['time_page'];
			$time_page = explode(',', $time_page);
			$data['time_page']			=	$time_page[$data_post['page']-1];
			$data['date']				=	date('Y��m��d��', strtotime($data_post['date'])).' ��'.$date_w[date('w', strtotime($data_post['date']))];
			$data['user_nicename']		=	$user['user_nicename'];
			$data['phone']				=	$user['mobile'];
			echo json_encode($data);die;
		} else {
			$this->error();
		}
	}

	public function ajax_order()
	{
		if (IS_POST) {
			$data_post = $_POST;
			// �ж�ѡ�����ڵ�ʱ����Ƿ����
			$data['date']	=	$data_post['date'];
			$data['cg_id']	=	$data_post['cg_id'];
			$data['time_page']	=	array('like', '%'.$data_post['page'].'%');
			$res = M('CgTimearea')->where($data)->find();

			// �жϸ�ʱ����Ƿ��Ѿ���Ԥ��
			if ($res) {
				$data_reserve['cg_id']		=	$data_post['cg_id'];
				$data_reserve['date']		=	$data_post['date'];
				$data_reserve['time_page']	=	$data_post['page'];
				$reserve_res 	= 	M('CgReserve')->where($data_reserve)->find();
				if ($reserve_res) {
					// ����Ѿ���ԤԼ������error
					$this->error();
				} else {
					// �õ�one_id
					$cg_data = M('cg')->where(array('id'=>$data_post['cg_id']))->find();
					$time_page	=	$cg_data['time_page'];
					$time_page	=	explode(',', $time_page);
					$user = sp_get_current_user();
					$reserve_data['cg_id']		=	$data_post['cg_id'];
					$reserve_data['cg_name']	=	$cg_data['name'];
					$reserve_data['one_id']		=	$cg_data['one_id'];
					$reserve_data['date']		=	$data_post['date'];
					$reserve_data['time_page']	=	$data_post['page'];
					$reserve_data['time']		=	$time_page[$data_post['page']-1];
					$reserve_data['contacts']	=	$data_post['contacts'];
					$reserve_data['phone']		=	$data_post['phone'];
					$reserve_data['purpose']	=	$data_post['purpose'];
					$reserve_data['remarks']	=	$data_post['remarks'];
					$reserve_data['user_id']	=	$user['id'];
					$reserve_data['user_name']	=	$user['user_nicename'];
					$reserve_data['add_time']	=	date('Y-m-d H:i:s');
					$reserve_add = M('CgReserve')->add($reserve_data);
					if ($reserve_add) {
						$res	=	array('status'=>1, 'info'=>'�ɹ�');
						echo json_encode($res);
					}
				}
			} else {
				$this->error();
			}
			//
		} else {
			$this->error();
		}
	}
}


