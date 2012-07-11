<?php

require_once SERVICE_DIR.'users/CmpUser.php';

class CmpUserManage {

	/**
	 * @var CI_Controller
	 */
	public $CI;

	/**
	 * @var CmpUser
	 */
	protected $cmp_user;

	/**
	 * 实例
	 * @var CmpUserManage
	 */
	private static $_instance;

	public function __construct(CmpUser $user = NULL){
		$this->cmp_user = $user;
		$this->CI = &get_instance();
	}

	/**
	 *
	 * @param CmpUser $user
	 * @return CmpUserManage
	 */
	public static function getInstance(CmpUser $user = NULL){
		if(self::$_instance instanceof CmpUserManage){
			return self::$_instance;
		}else{
			return self::$_instance = new CmpUserManage($user);
		}
	}

	public function getUser(){
		if(empty($this->cmp_user)){
			return $this->cmp_user = UserSession::getUser();
		}else{
			return $this->cmp_user;
		}
	}
	/**
	 * 支付通知
	 * @param array $data
	 * @param int $id
	 */
	public function payment_notify($id,$data){
		$this->CI->load->model('company/company_recharge','CompanyRecharge');
		$this->CI->CompanyRecharge->updateRecharge($id,$data);
	}
	/**
	 * 更新支付记录
	 * @param array $data
	 * @param int $id
	 */
	public function updateRecharge($id,$data){
		$this->CI->load->model('company/company_recharge','CompanyRecharge');
		$this->CI->CompanyRecharge->updateRecharge($id,$data);
	}
	/**
	 * 获取支付记录
	 * @param int $rid
	 */
	public function getRecharge($rid){
		$this->CI->load->model('company/company_recharge','CompanyRecharge');
		return $this->CI->CompanyRecharge->getRecharge($rid);
	}
	/**
	 * 修改用户余额
	 * @param int $user_id
	 * @param int $money
	 */
	public function updateUserMoney($user_id,$money){
		$this->CI->load->model('company/Company_user_model','CompanyRecharge');
		return $this->CI->CompanyRecharge->updateUserMoney($user_id,$money);
	}
	/**
	 * 支付
	 * @param int $rid
	 */
	public function payment($rid){
		$this->CI->load->model('company/company_recharge','CompanyRecharge');
		$recharge = $this->CI->CompanyRecharge->getRecharge($rid);
		if($recharge['way'] == 'alipay'){
			$out_trade_no = $recharge['id'];
			$total_fee    = $recharge['money'];
			require_once SERVICE_DIR.'alipay_wy/alipayto.php';
		}
	}
	/**
	 * 充值
	 * @param array $postData
	 */
	public function recharge($postData){
		$user = $this->getUser();
		$money = intval($postData['money']);
		$way = $postData['way'];
		$oid = intval($postData['oid']);
		$money = $postData['money'];
		$this->CI->load->model('company/company_recharge','CompanyRecharge');
		$data = array( 'mid' =>$user->id,'money'=>$money, 'way'=>$way ,'uptime'=>time());
		return $this->CI->CompanyRecharge->insertRecharge($data);
	}
	/**
	 * 检查重置密码验证码
	 * @param string $code
	 * @param int $mid
	 */
	public function checkCode($code,$mid){
		$this->CI->load->model('company/Company_get_password','CompanyGetPassword');
		if(!$this->CI->CompanyGetPassword->checkCode($code,$mid)){
			showmessage("错误，该链接已过期");
		}
	}
	/**
	 * 重置密码
	 * @param array $postData
	 */
	public function setPassword($postData){
		$newpassword = $postData['newpassword'];
		$newpassword2 = $postData['newpassword2'];
		if( $newpassword != $newpassword2 ){
			return "两次密码不一致";
		}
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$user = $this->CI->CompanyUserModel->getUserById($postData['mid']);
		if(empty($user)){
			return "用户不存在";
		}
		$data = array('password' => make_password($user['username'],$newpassword) );
		$result = $this->CI->CompanyUserModel->updateUser($postData['mid'],$data);
		return true;
	}
	/**
	 * 找回密码
	 * @param array $postData
	 */
	public function getPassword($postData){
		$username = $postData['username'];
		$email = $postData['email'];

		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$user = $this->CI->CompanyUserModel->getUserByName($username);

		if(empty($user)){
			return "用户不存在";
		}

		if(empty($user['status']) ||$user['status'] < 1){
			return "您的帐号已经被删除或锁定";
		}

		if($user['email'] != $email){
			return "Email错误";
		}
		$code = random(8);
		$data = array('mid'=>$user['id'],'code'=>$code,'uptime'=>time());
		$this->CI->load->model('company/Company_get_password','CompanyGetPassword');
		$this->CI->CompanyGetPassword->t_Insert($data);
		$title = '【微泰】找回密码通知信';
		$message = $user['username'].',您好, 谢谢您加入微泰官网会员， 请点击以下链接进行密码修改：<a href="http://'.$_SERVER['HTTP_HOST'].'/members/set_password?code='.$code.'&mid='.$user['id'].'">http://'.$_SERVER['HTTP_HOST'].'/members/set_password?code='.$code.'&mid='.$user['id'].'</a>';
// 		sendmail($email,$title,$message);
		return phpmailer_send($email,$title,$message);
// 		return true;
	}
	/**
	 * 登录
	 * @param array $postData
	 */
	public function login($postData){
		$username = $postData['username'];
		$password = $postData['password'];
		$companyMark = $postData['company_mark'];

		//加载数据库访问模型
		$this->CI->load->model('company/Company_model','CompanyModel');
		$company = $this->CI->CompanyModel->getCompanyByMark($companyMark);
		if(empty($company)){
			return "【{$companyMark}】企业不存在";
		}

		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$user = $this->CI->CompanyUserModel->getUser($username,$company['id']);

		if(empty($user)){
			return "用户不存在";
		}


		if(make_password($username, $password) != $user['password']){
			return "用户名或密码不对";
		}

		if(empty($user['status']) ||$user['status'] < 1){
			return "您的帐号已经被删除或锁定，不允许登录";
		}
		
		$cmpAdmin = $this->CI->CompanyUserModel->getCompanyAdmin($user['company_id']);
		if(empty($cmpAdmin) || empty($cmpAdmin['status']) ||$cmpAdmin['status'] < 1){
			return "该企业已经注销";
		}


		$this->cmp_user = new CmpUser($user);
		$this->cmp_user->company = $company;

		$this->CI->load->model('user/Member_monthpay_model','MonthPay');
		$monthPay = $this->CI->MonthPay->getUserNowMonthPay($user['id']);
		if(empty($monthPay) && !CmpAdminManage::getInstance()->isOnTry($user['company_id'])){
			if(!$this->payMonth($this->cmp_user)){
				return "扣取月租失败，您已被限制登录，请通知企业管理员充值。";
			}
		}

		UserSession::setUser($this->cmp_user);
		return true;
	}

	public function changePassword($oldPwd,$newPwd){
		$user = $this->getUser();
		if(make_password($user->username, $oldPwd)!= $user->password){
			return "旧密码不正确";
		}

		$newPwd = make_password($user->username, $newPwd);
		if($newPwd == $user->password){
			return "密码没有改动，请改动后再提交";
		}
		
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$where = array('id'=>$user->id);
		$rs = $this->CI->CompanyUserModel->update(array('password'=>$newPwd),$where);
		if($rs == 1){
			$user->password = $newPwd;
			UserSession::setUser($user);
			return true;
		}
		return "修改密码失败";
	}

	/**
	 * 登出
	 */
	public function logout(){
		$this->cmp_user = null;
		UserSession::setUser(null);
	}

	/**
	 * 更新企业管理员
	 * @param array $postData
	 */
	public function updateSelfInfo($postData){
		$user_id = $this->getUser()->id;
		$name = $postData['name'];
		$company_id = $this->getUser()->company_id;
		$mobile = $postData['mobile'];
		$email = $postData['email'];

		//加载数据库访问模型
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');

		//判断是否存在该用户
		$cmpUser = $this->CI->CompanyUserModel->getUserById($user_id,$company_id);
		if(empty($cmpUser)){
			return "参数错误";
		}

		//存储结果
		$rs = 1;

		$user = new CmpUser();
		$user->name = $name;
		$user->mobile = $mobile;
		$user->email = $email;

		 //检查是否有更改
		$userArr = $user->toArray();
		$isModify = false;
		foreach($userArr as $k=>$field){
			if($cmpUser[$k] != $field){
				$isModify = true;
				break;
			}
		}

		if($isModify){
			$where = array('id'=>$user_id,'company_id'=>$company_id);
			$rs = $this->CI->CompanyUserModel->update($user->toArray(),$where);
			if($rs == 1){
				return true;
			}else{
				return "更新员工资料失败";
			}
		}
		return $rs === 1;
	}

	public function reloadUserInfo(){
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$user = $this->CI->CompanyUserModel->getUser($this->getUser()->username,$this->getUser()->company_id);
		$this->cmp_user = new CmpUser($user);

		$this->CI->load->model('company/Company_model','CompanyModel');
		$company = $this->CI->CompanyModel->get($user['company_id']);
		$this->cmp_user->company = $company;
		UserSession::setUser($this->cmp_user);
	}

/**
	 * 读取用户邮箱帐号设置
	 */
	public function getUserEmailSetting(){
		$uid = $this->getUser()->id;
		$this->CI->load->model('company/Company_emailsetting_model','CompanyEmailSettingModel');
		return  $this->CI->CompanyEmailSettingModel->getSettingByUid($uid);
	}

	/**
	 * 更新用户邮箱帐号设置
	 * @param array $postData
	 */
	public function updateUserEmailSetting($postData){
		$uid = $this->getUser()->id;
		$this->CI->load->model('company/Company_emailsetting_model','CompanyEmailSettingModel');
		return $this->CI->CompanyEmailSettingModel->updateSetting($uid,$postData['email'],$postData['password'],$postData['smtp'],$postData['port'],$postData['is_ssl']);
	}

	public function payMonth($userInfo){
		if(CmpAdminManage::getInstance()->isOnTry($userInfo->company_id)){
			return true;
		}
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$admin = $this->CI->CompanyUserModel->getCompanyAdmin($userInfo->company_id);
		if(empty($admin)){
			return false;
		}
		if($admin['v_money'] < USER_MONTH_PAY){
			return false;
		}

		$this->CI->db->trans_begin();
		$rs = $this->CI->CompanyUserModel->chargeback($admin['id'],USER_MONTH_PAY);
		if($rs){
			$this->CI->load->model('user/Member_monthpay_model','MonthPay');
			$start_date = date('Y-m-d H:i:s');
			$end_date = date('Y-m-d H:i:s',strtotime('+1 month'));
			$result = $this->CI->MonthPay->newMonthPay($userInfo->id,$start_date,$end_date);
			if(empty($result) || $result < 0){
				$this->CI->db->trans_rollback();
				return false;
			}
			$this->CI->load->model('user/Pay_records_model','PayRecord');

			$record = array(
				'member_id'=>$admin['id'],
				'company_id'=>$userInfo->company_id,
				'r_money'=>USER_MONTH_PAY,
				'pay_type'=>2,
				'note'=>"为用户 {$userInfo->username}支付月租",
				'month_uid'=>$userInfo->id
			);
			$this->CI->PayRecord->newRecord($record);
			$this->CI->db->trans_commit();
		}

		return $rs;
	}
}

?>