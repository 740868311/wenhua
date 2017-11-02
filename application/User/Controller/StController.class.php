<?php
namespace User\Controller;

use Common\Controller\MemberbaseController;

class StController extends MemberbaseController {

	function _initialize(){
		parent::_initialize();
		$this->model	=	M('St');
	}

	// ����Ԥ��
	public function index()
	{
		// �õ����û����е�����
		$where 	= 	array('user_id'=>$this->userid);
		$data 	= 	$this->model->where($where)->select();
		$this->assign('data', $data);

		$this->assign($this->user);
		$this->display();
	}

	public function add()
	{
		if (IS_POST) {
			$post = I('post.');
			// �ֻ���
			$phone 	=	$post['phone'];
			if (strlen($phone) == "11") {
				//���沿���жϳ����ǲ���11λ
				$n = preg_match_all("/13[123569]{1}\d{8}|15[1235689]\d{8}|188\d{8}/", $phone, $array);
				if (!$n) {
					echo json_encode(array('status'=>1,'info'=>'��������ȷ���ֻ���'));die;
				}
			} else {
				echo json_encode(array('status'=>1,'info'=>'���ȱ�����11λ'));die;
			}

			// ��������
			$type	=	$post['type'];


			$data['user_id']	=	$this->userid;
			$data['username']	=	$this->user['user_nicename'];
			$data['phone']		=	$phone;
			$data['sex']		=	(int)$post['sex'];
			$data['team_name']	=	htmlspecialchars($post['team_name']);
			$data['add_time']	=	date('Y-m-d H:i:s', time());
			$data['thumb']		=	$post['thumb'];
			$data['one_id']		=	(int)$post['one_id'];
			$data['type_ids']	=	$type;
			$data['introduce']	=	htmlspecialchars($post['introduce']);
			$data['require']	=	htmlspecialchars($post['require']);
//			$data['area_ids']	=	$area_ids;
//			$data['cilck']	=	$area_ids;



		}
	}
}
