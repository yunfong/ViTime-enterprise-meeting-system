<?php

require_once SERVICE_DIR.'users/CmpAdmin.php';
require_once SERVICE_DIR.'users/CmpUser.php';
require_once SERVICE_DIR.'meeting/Meet.php';

class CmpAdminManage{

	/**
	 * @var CI_Controller
	 */
	public $CI;

	/**
	 *
	 * @var CmpAdmin
	 */
	protected $cmp_admin_user;

	/**
	 * 实例
	 * @var CmpAdminManage
	 */
	private static $_instance;

	public function __construct(CmpAdmin $user = NULL){
		$this->cmp_admin_user = $user;
		$this->CI = &get_instance();
	}

	public static function getInstance(CmpAdmin $user = NULL){
		if(self::$_instance instanceof CmpAdminManage){
			return self::$_instance;
		}else{
			return self::$_instance = new CmpAdminManage($user);
		}
	}

	public function getUserByName($username){
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		return $this->CI->CompanyUserModel->getUserByName($username);
	}

	public function getUser(){
		if(empty($this->cmp_admin_user)){
			return $this->cmp_admin_user = UserSession::getUser();
		}else{
			return $this->cmp_admin_user;
		}
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

		if($user['status'] == 3){
			return "您的帐号还未激活，请前往邮箱，查收邮件激活您的帐号";
		}

		if($user['priority'] != 1){
			return "您没有权限登录";
		}

		$this->payMonth();

		$this->cmp_admin_user = new CmpAdmin($user);
		$this->cmp_admin_user->company = $company;
		UserSession::setUser($this->cmp_admin_user);
		return true;
	}

	/**
	 * 注册
	 * @param array $postData
	 */
	public function register($postData){
		$username = $postData['username'];
		$password = $postData['password'];
		$mobile = $postData['mobile'];
		$email = $postData['email'];
		$companyName = $postData['company_name'];
		$companyMark = $postData['company_mark'];

		//加载数据库访问模型
		$this->CI->load->model('company/Company_model','CompanyModel');
		$company = $this->CI->CompanyModel->getCompanyByMark($companyMark);
		if(!empty($company) && $company['id'] > 0){
			return "{$companyMark} 企业标识已经存在";
		}
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$user = $this->CI->CompanyUserModel->getUserByName($username);
		if(!empty($user)){
			return "该用户名已经被注册";
		}

		//开启事务
		$this->CI->db->trans_begin();
		$companyId = $this->CI->CompanyModel->newCompany($companyName,$companyMark);
		if(empty($companyId) || $companyId <=0 ){
			$this->CI->db->trans_rollback();
			return "注册企业失败";
		}


		$admin = new CmpAdmin();
		$admin->username = $username;
		$admin->password = make_password($username, $password);
		$admin->company_id = $companyId;
		$admin->mobile = $mobile;
		$admin->email = $email;
		$admin->create_time = time();
		$admin->priority = 1;
		$admin->status = 3;

		$user_id = $this->CI->CompanyUserModel->newUser($admin);
		if($user_id > 0){
			$this->CI->db->trans_commit();
			return true;
		}else{
			$this->CI->db->trans_rollback();
			return "注册失败";
		}

	}

	/**
	 * 登出
	 */
	public function logout(){
		$this->cmp_admin_user = null;
		UserSession::setUser(null);
	}

	/**
	 * 新增企业普通员工
	 * @param array $postData
	 * @return string | boolean
	 */
	public function addCmpUser($postData){
		$vMoney = $this->getUserMoney() ;
		if(empty($vMoney) || floatval($vMoney) < USER_MONTH_PAY){
			return "账户余额：￥{$vMoney} ,不足￥{USER_MONTH_PAY}，无法新增用户，请先充值";
		}

		$name = $postData['name'];
		$username = $postData['username'];
		$password = $postData['password'];
		$mobile = $postData['mobile'];
		$email = $postData['email'];

		//加载数据库访问模型
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$user = $this->CI->CompanyUserModel->getUserByName($username);
		if(!empty($user)){
			$user = null;
			return "该用户名已经被注册";
		}

		$user = new CmpUser();
		$user->name = $name;
		$user->username = $username;
		$user->password = make_password($username, $password);
		$company_id = $this->getUser()->company_id;
		$user->company_id = $company_id;
		$user->mobile = $mobile;
		$user->email = $email;
		$user->create_time = time();
		$user->priority = 2;

		if(empty($company_id)){
			return "数据错误，注册失败";
		}
		$this->CI->db->trans_begin();
		$rs = $this->chargeback(USER_MONTH_PAY);
		if(!$rs){
			$this->CI->db->trans_rollback();
			return "扣费不成功，注册失败";
		}
		$this->CI->load->model('user/Pay_records_model','PayRecord');
		$record = array(
			'member_id'=>$this->getUser()->id,
			'company_id'=>$user->company_id,
			'r_money'=>USER_MONTH_PAY,
			'pay_type'=>2,
			'note'=>'添加用户：'.$user->username,
			'month_uid'=>$user->id
		);
		$this->CI->PayRecord->newRecord($record);

		$vMoney = $this->getUserMoney();
		if(empty($vMoney) || floatval($vMoney) < 0){
			$this->CI->db->trans_rollback();
			return "账户余额不足，无法新增用户，请先充值";
		}
		$user_id = $this->CI->CompanyUserModel->newUser($user);
		if($user_id > 0){
			$this->CI->db->trans_commit();
			return true;
		}else{
			return "注册失败";
		}
	}

	/**
	 * 更新企业管理员
	 * @param array $postData
	 */
	public function updateCmpUser($postData){
		$user_id = $postData['user_id'];
		$name = $postData['name'];
		$company_id = $this->getUser()->company_id;
		$username = $postData['username'];
		$password = $postData['password'];
		$mobile = $postData['mobile'];
		$email = $postData['email'];
		$status = $postData['status'];

		//加载数据库访问模型
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');

		//判断是否存在该用户
		$cmpUser = $this->CI->CompanyUserModel->getUserById($user_id,$company_id);
		if(empty($cmpUser)){
			return "参数错误";
		}

		//更改帐号时需要判断是否已经存在
		if($username != $cmpUser['username']){
			$user = $this->CI->CompanyUserModel->getUserByName($username);
			if(!empty($user) && $user['id'] != $cmpUser['id']){
				return "用户名：{$username} 已经被注册";
			}
		}

		//存储结果
		$rs = 1;

		$user = new CmpUser();
		$user->name = $name;
		$user->username = $username;
		if(!empty($password)){
			$user->password = make_password($username, $password);
		}
		$user->mobile = $mobile;
		$user->email = $email;
		$user->status = intval($status);

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
				return "更新管理员资料失败";
			}
		}
		return $rs === 1;
	}

	/**
	 * 删除企业管理员
	 * @param CmpUser $user
	 */
	public function deleteCmpUser($user_id){
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$cmpId = $this->getUser()->company_id;

		$user = $this->CI->CompanyUserModel->getUserById($user_id,$cmpId);
		if(empty($user) || $user['status'] == 0){
			return '用户不存在';
		}
		$rs = $this->CI->CompanyUserModel->deleteUser($user_id,$cmpId);
		if($rs == 1){
			return true;
		}else{
			return "未知原因导致失败";
		}
	}

	/**
	 * 读取用户信息
	 * @param int $user_id
	 */
	public function getUserInfo($user_id){
		if(empty($user_id)){
			return array();
		}
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$cmpId = $this->getUser()->company_id;

		$user = $this->CI->CompanyUserModel->getUserById($user_id,$cmpId);
		$this->CI->load->model('company/Company_model','CompanyModel');
		$user['company'] = $this->CI->CompanyModel->get($user['company_id']);
		return $user;
	}

	public function getCompanyInfo($cmp_id){
		$this->CI->load->model('company/Company_model','CompanyModel');
		return $this->CI->CompanyModel->get($cmp_id);
	}

	/**
	 * 企业普通员工列表
	 * @param int $page
	 * @param int $limit
	 */
	public function listCmpUser($page = 1,$limit = 10){
		$cmpId = $this->getUser()->company_id;
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$userlist = $this->CI->CompanyUserModel->getCompanyUserListNotAdmin($cmpId,$page,$limit);
		return $userlist;
	}

	/**
	 * 读取企业所有用户
	 * @param int $company_id
	 * @param string $cols
	 * @param int $limit
	 */
	public function listAllUser($cols='*',$limit = 50){
		$cmpId = $this->getUser()->company_id;
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		return $this->CI->CompanyUserModel->getAllUser($cols,$cmpId,$limit);
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
	 * 更新企业管理员
	 * @param array $postData
	 */
	public function updateSelfInfo($postData){
		$user_id = $this->getUser()->id;
		$name = $postData['name'];
		$company_id = $this->getUser()->company_id;
		$mobile = $postData['mobile'];
		$email = $postData['email'];
		$company_name = $postData['company_name'];

		//加载数据库访问模型
		$this->CI->load->model('company/Company_model','CompanyModel');
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');

		//判断是否存在该用户
		$cmpUser = $this->CI->CompanyUserModel->getUserById($user_id,$company_id);
		if(empty($cmpUser)){
			return "参数错误";
		}

		$company = $this->CI->CompanyModel->get($company_id);

		//开启事务
		$this->CI->db->trans_begin();
		if($company['company_name'] != $company_name){
			$company = array('company_name'=>$company_name);
			$where = array('id'=>$company_id);
			$rs = $this->CI->CompanyModel->update($company,$where);
			if($rs != 1){
				$this->CI->db->trans_rollback();
				return "更新企业资料失败";
			}
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
				$rs = 1;
			}else{
				$this->CI->db->trans_rollback();
				return "更新员工资料失败";
			}
		}
		$this->CI->db->trans_commit();
		return $rs === 1;
	}

	/**
	 * 重新加载用户资料
	 */
	public function reloadUserInfo(){
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$user = $this->CI->CompanyUserModel->getUser($this->getUser()->username,$this->getUser()->company_id);
		$this->cmp_admin_user = new CmpAdmin($user);

		$this->CI->load->model('company/Company_model','CompanyModel');
		$company = $this->CI->CompanyModel->get($user['company_id']);
		$this->cmp_admin_user->company = $company;
		UserSession::setUser($this->cmp_admin_user);
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

	/**
	 * 发送邮件
	 * 1.读取邮件内容，组成格式：
	 * 企业会议邮件内容：
	 	 主题：测试会议
		开始时间：2012-06-25 10:00:00
		时长：100分钟
		参会人员：laoliu3,laoliu2,laoliu5
	公共会议邮件内容：
		主题：测试公共会议
		开始时间：2012-06-25 14:00:00
		时长：30分钟
		参会密码：123
	 * @param int $meetid
	 */
	public function sendMeetingMail($meetid){

	}

	/**
	 * 读取用户的余额
	 */
	public function getUserMoney(){
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$user = $this->CI->CompanyUserModel->find($this->getUser()->id);
		return $user['v_money'];
	}
	
	/**
	 * 是否试用期
	 */
	public function isOnTry($company_id = null){
		$this->CI->load->model('company/Company_model','CompanyModel');
		if(empty($company_id)){
			$company_id = $this->getUser()->company_id;
		}
		$company = $this->CI->CompanyModel->get($company_id);
		return $company['onTry'] == 1;
	}
	
	/**
	 * 是否有试用资格
	 */
	public function hasTryStatus(){
		if($this->getUser()->isCmpAdmin()){
			$this->CI->load->model('company/Company_model','CompanyModel');
			$company = $this->CI->CompanyModel->get($this->getUser()->company_id);
			return !($company['onTry'] > 0);
		}
		return false;
	}
	
	/**
	 * 试用
	 */
	public function toTry(){
		if($this->getUser()->isCmpAdmin()){
			$this->CI->load->model('company/Company_model','CompanyModel');
			$rs = $this->CI->CompanyModel->update(array('onTry'=>1,'tryDate'=>date('Y-m-d',strtotime('+3 days'))),array('id'=>$this->getUser()->company_id));
			if($rs >= 1){
				return true;
			}
		}
		return false;
	}
	
	public function outTry(){
		if($this->getUser()->company_id){
			$this->CI->load->model('company/Company_model','CompanyModel');
			$company = $this->CI->CompanyModel->get($this->getUser()->company_id);
			if($company['onTry'] ==1){
				if(strtotime($company['tryDate']) <= strtotime(date('Y-m-d'))){
					$this->CI->CompanyModel->update(array('onTry'=>2),array('id'=>$this->getUser()->company_id));
				}
			}
		}
	}
	
	/**
	 * 扣除用户费用
	 */
	public function chargeback($money){
		if($this->isOnTry()){
			return true;
		}
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		return $this->CI->CompanyUserModel->chargeback($this->getUser()->id,$money);
	}

	public function payMonth(){
		if($this->isOnTry()){
			return true;
		}
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		$userlist = $this->CI->CompanyUserModel->getAllUser('*',$this->getUser()->company_id);
		if(empty($userlist)){
			return true;
		}
		if($this->getUserMoney() < USER_MONTH_PAY){
			return false;
		}
		$this->CI->db->trans_begin();

		$this->CI->load->model('user/Member_monthpay_model','MonthPay');
		$this->CI->load->model('user/Pay_records_model','PayRecord');
		$this->CI->load->model('user/Member_monthpay_model','MonthPay');

		$admin = $this->getUser();
		$admin = $admin->toArray();
		foreach($userlist as $user){
			$monthPay = $this->CI->MonthPay->getUserNowMonthPay($user['id']);
			if(!empty($monthPay)){
				continue;
			}
			if($this->getUserMoney() < USER_MONTH_PAY){
				$this->CI->db->trans_rollback();
				return false;
			}
			$rs = $this->CI->CompanyUserModel->chargeback($admin['id'],USER_MONTH_PAY);
			if($rs){
				$start_date = date('Y-m-d H:i:s');
				$end_date = date('Y-m-d H:i:s',strtotime('+1 month'));
				$result = $this->CI->MonthPay->newMonthPay($user['id'],$start_date,$end_date);
				if(empty($result) || $result < 0){
					$this->CI->db->trans_rollback();
					return false;
				}
				$record = array(
					'member_id'=>$admin['id'],
					'company_id'=>$user['company_id'],
					'r_money'=>USER_MONTH_PAY,
					'pay_type'=>2,
					'note'=>"为用户 {$user['username']}支付月租",
					'month_uid'=>$user['id']
				);
				$this->CI->PayRecord->newRecord($record);

			}
		}
		$this->CI->db->trans_commit();
	}

	/**
	 * 激活注册用户
	 * @param unknown_type $id
	 */
	public function activeUser($id){
		$this->CI->load->model('company/Company_user_model','CompanyUserModel');
		return $this->CI->CompanyUserModel->updateUser($id,array('status'=>1));
	}
	
	/**
	 * 读取充值记录
	 * @param int $page 页码
	 */
	public function getRechargeHistory($page = 1){
		$page = intval($page);
		$page = max(1,$page);
		$this->CI->load->model('company/company_recharge','CompanyRecharge');
		return $this->CI->CompanyRecharge->getRechargeList($this->getUser()->id,$page);
		
	}
	
	/**
	 * 读取消费记录
	 * @param int $page
	 */
	public function getPayHistory($page = 1){
		$page = intval($page);
		$page = max(1,$page);
		$this->CI->load->model('user/Pay_records_model','PayRecord');
		return $this->CI->PayRecord->getPayList($this->getUser()->id,$page);
	}
}

?>