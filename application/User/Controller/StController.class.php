<?php
namespace User\Controller;

use Common\Controller\MemberbaseController;

class StController extends MemberbaseController {

	function _initialize(){
		parent::_initialize();
		$this->model	=	M('St');
	}

	// 场馆预定
	public function index()
	{
		// 得到该用户所有的社团
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
			// 手机号
			$phone 	=	$post['phone'];
			if (strlen($phone) == "11") {
				//上面部分判断长度是不是11位
				$n = preg_match_all("/13[123569]{1}\d{8}|15[1235689]\d{8}|188\d{8}/", $phone, $array);
				if (!$n) {
					echo json_encode(array('status'=>1,'info'=>'请输入正确的手机号'));die;
				}
			} else {
				echo json_encode(array('status'=>1,'info'=>'长度必须是11位'));die;
			}

			// 社团类型
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
