<?php
require_once(SERVICE_DIR.'company/CmpAdminManage.php');
require_once(SERVICE_DIR.'company/CmpUserManage.php');
require_once SERVICE_DIR.'meeting/MeetingManage.php';
require_once SERVICE_DIR.'sms/SMSWebservice.php';

/**
 * 企业管理后台
 *
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-21
 */
class Company extends CU_Controller{

	public function __construct(){
		parent::__construct();
		$this->_needValidLogin(true);
	}

	/**
	 * 企业后台首页，默认显示用户列表
	 */
	public function index(){
		$this->_remap('company_meeting');
	}

	public function listuser(){
		$page = $this->input->get('page',true);
		$service = CmpAdminManage::getInstance();
		$userlist = $service->listCmpUser($page);
		$userlist['page'] = intval($page);
		$this->displayHtml($userlist);
	}

	/**
	 * 添加用户
	 */
	public function adduser(){
		$vMoney = CmpAdminManage::getInstance()->getUserMoney();
		if((empty($vMoney) || floatval($vMoney) < USER_MONTH_PAY) && !CmpAdminManage::getInstance()->isOnTry()){
			$this->back("账户余额：￥{$vMoney} ,不足￥".USER_MONTH_PAY."，无法新增用户，请先充值") ;
		}
		$this->displayHtml();
	}

	public function do_adduser(){
		if(empty($_POST)){
			$this->_redirect('adduser');
		}

		$postData = $this->input->post(NULL,TRUE);
		//去掉html标签
		foreach($postData as $k=>&$v){
			$postData[$k] = trim(strip_tags($v));
		}

		if(empty($postData['username']) || empty($postData['password']) || empty($postData['mobile']) || empty($postData['email'])){
			return $this->displayHtml(array('errMsg'=>'请填写完整再提交'),'adduser');
		}
		$service = CmpAdminManage::getInstance();
		$loginMsg = $service->addCmpUser($postData);
		if($loginMsg === true){
			$_SESSION['adduser_success_data'] = $postData;
			$this->_redirect('adduser_success');
		}else{
			return $this->displayHtml(array('errMsg'=>'添加用户失败，'.$loginMsg),'adduser');
		}
	}

	public function adduser_success(){
		if(!empty($_SESSION['adduser_success_data'])){
			$userinfo = $_SESSION['adduser_success_data'];
			unset($_SESSION['adduser_success_data']);
			$this->displayHtml($userinfo);
		}else{
			$this->_redirect('adduser');
		}

	}

	public function do_delete_user(){
		if(!$this->input->is_post() && !$this->input->is_ajax_request()){
			$this->_redirect('listuser');
		}

		$user_id = $this->input->post('user_id',true);
		if(empty($user_id) || !is_numeric($user_id)){
			$errMsg = array('status'=>0,'msg'=>'参数错误');
			exit(json_encode($errMsg));
		}

		$rs = CmpAdminManage::getInstance()->deleteCmpUser($user_id);
		if($rs === true){
			$errMsg = array('status'=>1,'msg'=>'删除成功');
			exit(json_encode($errMsg));
		}else{
			$errMsg = array('status'=>0,'msg'=>'删除失败，'.$rs);
			exit(json_encode($errMsg));
		}

	}

	/**
	 * 更新用户
	 * @param int $user_id
	 */
	public function update_user($user_id = null){
		if(!$this->input->is_post()){
			if(empty($user_id)){
				$this->_redirect('listuser');
			}else{
				$companyUser = CmpAdminManage::getInstance()->getUserInfo($user_id);
				return $this->displayHtml($companyUser);
			}
			return;
		}

		if(empty($_POST)){
			$this->_redirect('listuser');
		}

		$postData = $this->input->post(NULL,TRUE);
		$errMsg = '';

		//去掉html标签
		foreach($postData as $k=>&$v){
			$postData[$k] = trim(strip_tags($v));
		}

		$errMsg = '';
		if(empty($postData['username'])){
			$errMsg .= $this->wrapErrorMsg("用户名必须填写");
		}

		if(empty($postData['mobile'])){
			$errMsg .= $this->wrapErrorMsg("手机号码必须填写");
		}

		if(empty($postData['email'])){
			$errMsg .= $this->wrapErrorMsg("邮箱必须填写");
		}

		if(!empty($errMsg)){
			$errMsg = "填写不完整：<br />{$errMsg}";
			return $this->displayHtml(array('errMsg'=>$errMsg));
		}

		$loginMsg = CmpAdminManage::getInstance()->updateCmpUser($postData);
		if($loginMsg === TRUE){
			$this->_redirect('update_user_success');
		}else{
			$companyUser = CmpAdminManage::getInstance()->getUserInfo($postData['user_id']);
			return $this->displayHtml(array_merge($companyUser,array('errMsg'=>"修改失败，$loginMsg")));
		}

	}

	public function update_user_success(){
		$this->displayHtml();
	}

	/**
	 * 企业会议列表
	 */
	public function company_meeting(){
		$page = $this->input->get('page',true);

		$meetingList = MeetingManage::getInstance()->listCmpMeeting($page);
		$this->displayHtml($meetingList);
	}

	/**
	 * 公共会议列表
	 */
	public function public_meeting(){
		$page = $this->input->get('page',true);

		$meetingList = MeetingManage::getInstance()->listPubMeeting($page);
		$this->displayHtml($meetingList);
	}

	/**
	 * 读取参与会议的人员
	 * @param int $meeting_id
	 */
	public function get_meeting_user_list($meeting_id = null){
		if(empty($meeting_id) || !is_numeric($meeting_id)){
			exit('[]');
		}
		$list = MeetingManage::getInstance()->listCmpMeetingUser($meeting_id);
		if(empty($list)){
			exit('[]');
		}else{
			exit(json_encode($list));
		}
	}

	/**
	 * 预约企业会议
	 */
	public function company_reservation(){
		$user_list = CmpAdminManage::getInstance()->listAllUser('name,username,id',0);
		$this->displayHtml(array('_action'=>'company_meeting','user_list'=>$user_list));
	}

	/**
	 * 发布会议
	 */
	public function do_company_reservation(){
		$postData = $this->input->post(NULL,TRUE);
		if(empty($postData)){
			$this->_redirect('company_reservation');
		}

		$errMsg = '';
		if(empty($postData['title'])){
			$errMsg .='会议主题必须填写&nbsp;&nbsp;';
		}

		if(empty($postData['start_time'])){
			$errMsg .="会议开始时间必须填写";
		}

		if(!empty($errMsg)){
			$postData['errMsg'] = $errMsg;
			$this->displayHtml($postData,'company_reservation');
		}else{
			$rs = MeetingManage::getInstance()->bookMeeting($postData);
			if(is_numeric($rs) || $rs > 0){
				$_SESSION['company_meeting_success'] = $rs;
				$this->_redirect('company_reservation_success');
			}else{
				$postData['errMsg'] = $rs;
				$this->displayHtml($postData,'company_reservation');
			}
		}
	}

	/**
	 * 预约会议成功
	 */
	public function company_reservation_success(){
		$meet_id = $_SESSION['company_meeting_success'];
		unset($_SESSION['company_meeting_success']);
		if(empty($meet_id)){
			$this->_redirect('company_meeting');
		}
		$meeting = MeetingManage::getInstance()->getMeetingInfo($meet_id);
		$meeting['user_list'] = MeetingManage::getInstance()->listCmpMeetingUser($meet_id);
		$this->displayHtml($meeting);
	}

/**
	 * 预约公共会议
	 */
	public function public_reservation(){
		$this->displayHtml(array('_action'=>'public_meeting'));
	}

	public function do_public_reservation(){
		$postData = $this->input->post(NULL,TRUE);
		if(empty($postData)){
			$this->_redirect('public_reservation');
		}

		$errMsg = '';
		if(empty($postData['title'])){
			$errMsg .='会议主题必须填写&nbsp;&nbsp;';
		}

		if(empty($postData['start_time'])){
			$errMsg .="会议开始时间必须填写";
		}

		if(!is_numeric($postData['usercount']) || intval($postData['usercount']) <= 0){
			$errMsg .="会议人数填写不正确";
		}

		$usercount = intval($postData['usercount']);
		$vMoney = CmpAdminManage::getInstance()->getUserMoney();
		if(($vMoney < ($usercount * floatval(USER_PUB_PAY))) && !CmpAdminManage::getInstance()->isOnTry()){
			$errMsg .="本次会议需要扣费：￥".($usercount * floatval(USER_PUB_PAY)).'，您的余额（￥'.$vMoney.'）不足，请先充值';
		}

		if(!empty($errMsg)){
			$postData['errMsg'] = $errMsg;
			$this->displayHtml($postData,'public_reservation');
		}else{

			$rs = MeetingManage::getInstance()->bookPublicMeeting($postData);
			if(is_numeric($rs) || $rs > 0){
				$_SESSION['public_meeting_success'] = $rs;
				$this->_redirect('public_reservation_success');
			}else{
				$postData['errMsg'] = $rs;
				$this->displayHtml($postData,'public_reservation');
			}
		}
	}
	/**
	 * 预约会议成功
	 */
	public function public_reservation_success(){
		$meet_id = $_SESSION['public_meeting_success'];
		unset($_SESSION['public_meeting_success']);
		if(empty($meet_id)){
			$this->_redirect('public_meeting');
		}
		$meeting = MeetingManage::getInstance()->getMeetingInfo($meet_id);
		$this->displayHtml($meeting);
	}


	/**
	 * 编辑企业会议
	 */
	public function edit_company_reservation($meet_id = null){
		if(empty($meet_id)){
			$this->_redirect('company_meeting');
		}
		$meeting = MeetingManage::getInstance()->getMeetingInfo($meet_id);
		$meeting['all_user_list'] = CmpAdminManage::getInstance()->listAllUser('name,username,id',0);
		if($meeting['state'] != 1){
			$this->displayHtml(array('errMsg'=>'该会议已经锁定，无法编辑','back_url'=>"{$this->_controller}/company_meeting"),'edit_failure');
		}else{
			$this->displayHtml($meeting);
		}

	}

	public function do_edit_company_reservation(){
		$postData = $this->input->post(NULL,TRUE);
		$meet_id = $postData['meet_id'];
		if(empty($postData) || empty($meet_id)){
			$this->_redirect('company_meeting');
		}

		$errMsg = '';
		if(empty($postData['meet_id'])){
			$errMsg .='参数错误&nbsp;&nbsp;';
		}
		if(empty($postData['title'])){
			$errMsg .='会议主题必须填写&nbsp;&nbsp;';
		}

		if(empty($postData['start_time'])){
			$errMsg .="会议开始时间必须填写";
		}

		if(!empty($errMsg)){
			$meeting = MeetingManage::getInstance()->getMeetingInfo($meet_id);
			$meeting['all_user_list'] = CmpAdminManage::getInstance()->listAllUser('name,username,id',0);
			$meeting['errMsg'] = $errMsg;
			$this->displayHtml($meeting,'edit_company_reservation');
		}else{
			$rs = MeetingManage::getInstance()->changeMeeting($postData);
			if(is_numeric($rs) || $rs > 0){
				$_SESSION['company_meeting_success'] = $rs;
				$this->_redirect('company_reservation_success');
			}else{
				$meeting = MeetingManage::getInstance()->getMeetingInfo($meet_id);
				$meeting['all_user_list'] = CmpAdminManage::getInstance()->listAllUser('name,username,id',0);
				$meeting['errMsg'] = $rs;
				$this->displayHtml($meeting,'edit_company_reservation');
			}
		}
	}

	/**
	 * 编辑
	 */
	public function edit_public_reservation($meet_id = null){
		if(empty($meet_id)){
			$this->_redirect('public_meeting');
		}
		$meeting = MeetingManage::getInstance()->getMeetingInfo($meet_id);
		if($meeting['state'] != 1){
			$this->displayHtml(array('errMsg'=>'该会议已经锁定，无法编辑','back_url'=>"{$this->_controller}/public_meeting"),'edit_failure');
		}else{
			$this->displayHtml($meeting);
		}

	}

	public function do_edit_public_reservation(){
		$postData = $this->input->post(NULL,TRUE);
		if(empty($postData)){
			$this->_redirect('public_reservation');
		}
		$meet_id = $postData['meet_id'];
		$meeting = MeetingManage::getInstance()->getMeetingInfo($meet_id);
//		if($this->_user->id != $meeting['user_id']){
//			$this->back('您没有权限修改该会议');
//		}
		$errMsg = '';
		if(empty($postData['meet_id'])){
			$errMsg .='参数错误&nbsp;&nbsp;';
		}
		if(empty($postData['title'])){
			$errMsg .='会议主题必须填写&nbsp;&nbsp;';
		}

		if(empty($postData['start_time'])){
			$errMsg .="会议开始时间必须填写";
		}

		if(!is_numeric($postData['usercount']) || intval($postData['usercount']) <= 0){
			$errMsg .="会议人数填写不正确";
		}
		$chargeMoney = 0;
		$usercount = intval($postData['usercount']);
		if(strtotime($meeting['end_time']) < time()){
			$vMoney = CmpAdminManage::getInstance()->getUserMoney();
			$chargeMoney = ($usercount * floatval(USER_PUB_PAY));
			if(($vMoney < $chargeMoney && !CmpAdminManage::getInstance()->isOnTry())){
				$errMsg .="本次会议需要扣费：￥".($usercount * floatval(USER_PUB_PAY)).'，您的余额（￥'.$vMoney.'）不足，请先充值';
			}
		}else if($usercount > intval($meeting['usercount'])){
			$usercount = $usercount - intval($meeting['usercount']);
			$vMoney = CmpAdminManage::getInstance()->getUserMoney();
			$chargeMoney = ($usercount * floatval(USER_PUB_PAY));
			if(($vMoney < $chargeMoney) && !CmpAdminManage::getInstance()->isOnTry()){
				$errMsg .="本次会议需要扣费：￥".($usercount * floatval(USER_PUB_PAY)).'，您的余额（￥'.$vMoney.'）不足，请先充值';
			}
		}

		if(!empty($errMsg)){
			$postData['errMsg'] = $errMsg;
			$this->displayHtml($postData,'edit_public_reservation');
		}else{
			$rs = MeetingManage::getInstance()->changePublicMeeting($postData,$chargeMoney);
			if(is_numeric($rs) || $rs > 0){
				$_SESSION['public_meeting_success'] = $rs;
				$this->_redirect('public_reservation_success');
			}else{
				$postData['errMsg'] = $rs;
				$postData = array_merge($meeting,$postData);
				$this->displayHtml($postData,'edit_public_reservation');
			}
		}
	}

	public function delete_meeting(){
		if(!$this->input->is_post() && !$this->input->is_ajax_request()){
			$this->_redirect('company_meeting');
		}
		$meet_id = $this->input->post('meet_id',true);
		$meet_id = trim(strip_tags($meet_id));
		if(!is_numeric($meet_id)){
			exit(json_encode(array('status'=>0,'msg'=>'参数错误')));
		}

		$rs = MeetingManage::getInstance()->cancelMeeting($meet_id);
		if($rs == 1){
			exit(json_encode(array('status'=>1,'msg'=>'会议已经成功取消')));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>'会议取消失败')));
		}
	}

	public function change_password(){
		$this->displayHtml();
	}

	public function do_change_password(){
		if(!$this->input->is_post() || empty($_POST)){
			$this->_redirect('change_password');
		}
		$postData = $this->input->post(NULL,TRUE);
		if(empty($postData['password']) || empty($postData['newpassword'])){
			$postData['errMsg'] = "密码不能为空，必须填写";
			$this->displayHtml($postData,'change_password');
		}
		$rs = CmpAdminManage::getInstance()->changePassword($postData['password'], $postData['newpassword']);
		if($rs === true){
			$_SESSION['change_password_success'] = $rs;
			$this->_redirect('change_password_success');
		}else{
			$postData['errMsg'] = $rs;
			$this->displayHtml($postData,'change_password');
		}
	}

	public function change_password_success(){
		if(empty($_SESSION['change_password_success'])){
			$this->_redirect('change_password');
		}else{
			unset($_SESSION['change_password_success']);
			$this->displayHtml();
		}
	}

/**
	 * 进入会议
	 * @param unknown_type $meet_id
	 */
	public function enter_meeting($meet_id){
		if(empty($meet_id)){
			$this->back();
		}
		$meeting = MeetingManage::getInstance()->getMeetingInfo($meet_id);
		if(empty($meeting['password'])){
			redirect('/meeting/index/'.$meet_id);
		}
		$meeting['_action'] = 'public_meeting';
		$this->displayHtml($meeting);
	}

	public function do_enter_meeting(){
		if(empty($_POST)){
			$this->back();
		}
		$meeting_id = $this->input->post('meet_id',true);
		$password = $this->input->post('password',TRUE);
		$meeting = MeetingManage::getInstance()->getMeetingInfo($meeting_id);
		if(empty($password)){
			$meeting['errMsg'] = '密码不能为空';
			$this->displayHtml($meeting,'enter_meeting');
		}else{
			if($password !=  $meeting['password']){
				$meeting['errMsg'] = '密码错误，请重新输入';
				$this->displayHtml($meeting,'enter_meeting');
			}else{
				redirect('/meeting/index/'.$meeting_id);
			}
		}

	}

	public function meeting(){
		exit('meeting');
	}

	/**
	 * 更新自己资料
	 * @param int $user_id
	 */
	public function profile(){
		if(!$this->input->is_post() || empty($_POST)){
			$companyUser = $this->_user->toArray();
			$companyUser['v_money'] = CmpAdminManage::getInstance()->getUserMoney();
			return $this->displayHtml($companyUser);
		}

		$postData = $this->input->post(NULL,TRUE);
		$errMsg = '';

		//去掉html标签
		foreach($postData as $k=>&$v){
			$postData[$k] = trim(strip_tags($v));
		}

		$errMsg = '';
		if(empty($postData['name'])){
			$errMsg .= $this->wrapErrorMsg("姓名必须填写");
		}

		if(empty($postData['mobile'])){
			$errMsg .= $this->wrapErrorMsg("手机号码必须填写");
		}

		if(empty($postData['email'])){
			$errMsg .= $this->wrapErrorMsg("邮箱必须填写");
		}

		if(empty($postData['company_name'])){
			$errMsg .= $this->wrapErrorMsg("企业名称必须填写");
		}


		if(!empty($errMsg)){
			$companyUser = $this->_user->toArray();
			$errMsg = "填写不完整：<br />{$errMsg}";
			return $this->displayHtml(array_merge($companyUser,array('errMsg'=>$errMsg)));
		}

		$loginMsg = CmpAdminManage::getInstance()->updateSelfInfo($postData);
		if($loginMsg === TRUE){
			CmpAdminManage::getInstance()->reloadUserInfo();
			$this->_redirect('update_info_success');
		}else{
			$companyUser = $this->_user->toArray();
			return $this->displayHtml(array_merge($companyUser,array('errMsg'=>"修改失败，$loginMsg")));
		}

	}

	public function update_info_success(){
		$this->displayHtml();
	}

	/**
	 * 会议记录
	 */
	public function meeting_record(){
		$page = $this->input->get('page',true);
		$data = MeetingManage::getInstance()->getMeetingRecord($page);
		$this->displayHtml($data);
	}

	/**
	 * 邮箱设置
	 */
	public function mail_setting(){
		$companyUser = $this->_user->toArray();
		$emailSetting = CmpAdminManage::getInstance()->getUserEmailSetting();
		$companyUser = array_merge($companyUser,$emailSetting);
		if(!$this->input->is_post() || empty($_POST)){
			return $this->displayHtml($companyUser);
		}
		$postData = $this->input->post(NULL,TRUE);
		$errMsg = '';

		//去掉html标签
		foreach($postData as $k=>&$v){
			$postData[$k] = trim(strip_tags($v));
		}

		$errMsg = '';

		if(empty($postData['email'])){
			$errMsg .= $this->wrapErrorMsg("邮箱帐号必须填写");
		}

		if(empty($postData['password'])){
			$errMsg .= $this->wrapErrorMsg("邮箱密码必须填写");
		}

		if(empty($postData['smtp'])){
			$errMsg .= $this->wrapErrorMsg("SMTP地址必须填写");
		}

		if(empty($postData['port'])){
			$errMsg .= $this->wrapErrorMsg("SMTP端口必须填写");
		}

		if(!empty($errMsg)){
			$errMsg = "填写不完整：<br />{$errMsg}";
			return $this->displayHtml(array_merge($companyUser,array('errMsg'=>$errMsg)));
		}

		$loginMsg = CmpAdminManage::getInstance()->updateUserEmailSetting($postData);
		if($loginMsg ==1){
			$this->_redirect('update_info_success');
		}else{
			return $this->displayHtml(array_merge($companyUser,array('errMsg'=>"修改失败，$loginMsg")));
		}
	}

	/**
	 * 支付
	 */
	public function payment(){
		$rid = intval($this->input->get('rid',true));
		$cmpUserManage = CmpUserManage::getInstance();
		$rid = $cmpUserManage->payment($rid);
	}
	/**
	 * 充值
	 */
	public function recharge(){
		$resu = array();
		$resu = array('money'=>$this->input->get('money',true));
		if($_POST){
			//获取提交参数
			$money = $this->input->post('money',true);
			$way = $this->input->post('way',true);

			//判断参数完整性
			if(empty($money) || empty($way)){
				$errMsg= "请填写完整再提交";
				$resu = array('errMsg'=>$errMsg);
			}else{
				if($money != intval($money)){
					$errMsg= "请输入整数";
					$resu = array('errMsg'=>$errMsg);
				}else{
					$cmpUserManage = CmpUserManage::getInstance();
					$postData = $this->input->post(NULL,TRUE);
					$rid = $cmpUserManage->recharge($postData);
					$resu = array('rid'=>$rid);
				}
			}
		}
		$this->displayHtml($resu);
	}

	public function sendsms(){
		if(!$this->input->is_ajax_request() || !$this->input->is_post()){
			exit('{msg:"非法",error:true}');
		}
		$meet_id = $this->input->post('meet_id',true);
		if(empty($meet_id)){
			exit('{msg:"非法",error:true}');
		}
		$meeting =  MeetingManage::getInstance()->getMeetingInfo($meet_id);
		if($meeting['user_id'] != $this->_user->id){
			exit('{msg:"参数错误",error:true}');
		}
		$meeting['user_list'] = MeetingManage::getInstance()->listCmpMeetingUser($meet_id);
		$mobiles= array();
		foreach($meeting['user_list'] as $user){
			if(!empty($user['mobile']) && is_numeric($user['mobile'])){
				$mobiles[] = trim($user['mobile']);
			}
		}
		$url = SROOT.'/meeting/index/'.$meet_id;
		$content = "会议通知：\n主题：".trim($meeting['title']).'，开始时间：'.trim($meeting['start_time']).'，时长：'.trim($meeting['time_length']).'分钟，参会网址：'.$url.'。请准时参加。';
		$rs = SMSWebservice::getInstance()->send($this->_user->id, $mobiles, $content);
		if(is_numeric($rs) && $rs >0){
			exit('{msg:"发送成功",error:false}');
		}else{
			exit('{msg:"发送失败：'.$rs.'",error:true}');
		}
	}

	/**
	 * 发送会议邮件
	 */
	public function sendmail(){
		if(!$this->input->is_ajax_request() || !$this->input->is_post()){
			exit('{msg:"非法",error:true}');
		}
		$meet_id = $this->input->post('meet_id',true);
		if(empty($meet_id)){
			exit('{msg:"非法",error:true}');
		}
		$userMailSetting = CmpAdminManage::getInstance()->getUserEmailSetting();
		if(empty($userMailSetting['email'])){
			exit('{msg:"请先设置邮箱",error:true,url:"/company/mail_setting",target:"_blank"}');
		}

		$meeting =  MeetingManage::getInstance()->getMeetingInfo($meet_id);
		if($meeting['user_id'] != $this->_user->id){
			exit('{msg:"参数错误",error:true}');
		}
		$meeting['user_list'] = MeetingManage::getInstance()->listCmpMeetingUser($meet_id);
		$username = array();
		foreach($meeting['user_list'] as $user){
			$username[] = empty($user['name'])?$user['username']:$user['name'];
		}
		$username = implode('，', $username);
		$url = SROOT.'/meeting/index/'.$meet_id;



		$this->load->library('PHPMailer',array(true));
		$mail = $this->phpmailer;
		$body = <<<MAIL
<p>会议主题：{$meeting['title']}</p>
<p>会议时间：{$meeting['start_time']}</p>
<p>会议时长：{$meeting['time_length']}</p>
<p>参会人员：{$username}</p>
<p>点击入会链接：<a href="{$url}" target="_blank">{$url}</a></p>
MAIL;
		$mail->SetLanguage('cn');
		$mail->set('CharSet','utf-8');
		$mail->IsSMTP();
		$mail->Host       = $userMailSetting['smtp']; // SMTP server
		$mail->SMTPDebug  = 0;
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Port       = $userMailSetting['port'];
		$mail->Username   = $userMailSetting['email']; // SMTP account username
		$mail->Password   = $userMailSetting['password']; // SMTP account password
		if(strpos($userMailSetting['email'], '@')===false){
			$userMailSetting['email'] .= str_replace('smtp.', '@', $userMailSetting['smtp']);
		}
		$mail->SetFrom($userMailSetting['email'], empty($this->_user->name)?$this->_user->username:$this->_user->name);
		$mail->AddReplyTo($userMailSetting['email'], empty($this->_user->name)?$this->_user->username:$this->_user->name);
		$mail->Subject    = "视频会议通知";
		$mail->AltBody    = "视频会议通知"; // optional, comment out and test

		$mail->MsgHTML($body);
		foreach($meeting['user_list'] as $user){
			if(!empty($user['email'])){
				$mail->AddAddress($user['email'], empty($user['name'])?$user['username']:$user['name']);
			}
		}
		try{
		if(!$mail->Send()) {
			$msg = urlencode("发送邮件失败，详细信息：".$mail->ErrorInfo);
			exit('{msg:"'.$msg.'",error:true}');
		} else {
		  exit('{msg:"发送邮件成功",error:false}');
		}
		}catch(Exception $e){
			$msg = urlencode("发送邮件失败，详细信息：".$mail->ErrorInfo);
			exit('{msg:"'.$msg.'",error:true}');
		}
	}

/**
	 * 发布公共会议后，发送通知邮件
	 */
	public function sendpubmail(){
		if(!$this->input->is_ajax_request() || !$this->input->is_post()){
			exit('{msg:"非法",error:true}');
		}

		$userMailSetting = CmpUserManage::getInstance()->getUserEmailSetting();
		if(empty($userMailSetting['email'])){
			exit('{msg:"请先设置邮箱",error:true,url:"/mymeeting/mail_setting",target:"_blank"}');
		}

		$postData = $this->input->post(null,true);
		if(empty($postData['receipt']) || empty($postData['content'])|| strpos($postData['receipt'], '@')===false){
			exit('{msg:"参数错误，请填写完整再发送",error:true}');
		}
		$toUsers = $postData['receipt'];
		$toUsers = str_replace(',', ';', $toUsers);
		$toUsers = explode(';', $toUsers);

		$this->load->library('PHPMailer',array(true));
		$mail = $this->phpmailer;
		$body = nl2br($postData['content']);
		$body = strip_tags($body,'<br><p>');
		$mail->SetLanguage('cn');
		$mail->set('CharSet','utf-8');
		$mail->IsSMTP();
		$mail->Host       = $userMailSetting['smtp']; // SMTP server
		$mail->SMTPDebug  = 0;
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Port       = $userMailSetting['port'];
		$mail->Username   = $userMailSetting['email']; // SMTP account username
		$mail->Password   = $userMailSetting['password']; // SMTP account password
		if(strpos($userMailSetting['email'], '@')===false){
			$userMailSetting['email'] .= str_replace('smtp.', '@', $userMailSetting['smtp']);
		}
		$mail->SetFrom($userMailSetting['email'], empty($this->_user->name)?$this->_user->username:$this->_user->name);
		$mail->AddReplyTo($userMailSetting['email'], empty($this->_user->name)?$this->_user->username:$this->_user->name);
		$mail->Subject    = "视频会议通知";
		$mail->AltBody    = "视频会议通知"; // optional, comment out and test

		$mail->MsgHTML($body);
		foreach($toUsers as $user){
			if(!empty($user)){
				$mail->AddAddress($user);
			}
		}
		try{
		if(!$mail->Send()) {
			$msg = urlencode("发送邮件失败，详细信息：".$mail->ErrorInfo);
			exit('{msg:"'.$msg.'",error:true}');
		} else {
		  exit('{msg:"发送邮件成功",error:false}');
		}
		}catch(Exception $e){
			$msg = urlencode("发送邮件失败，详细信息：".$mail->ErrorInfo);
			exit('{msg:"'.$msg.'",error:true}');
		}
	}
	
	public function to_try(){
		if(!CmpAdminManage::getInstance()->hasTryStatus()){
			showmessage("您不具备试用资格","/company/recharge");
		}
		if(CmpAdminManage::getInstance()->toTry()){
			$date = date('Y年m月d日',strtotime('+3 days'));
			showmessage("您已获得三天试用资格，在此期间可正常使用各项收费服务，三天后（{$date}）将收回试用资格");
		}else{
			$this->back("获取试用资格失败");
		}
	}
	
	/**
	 * 充值记录
	 */
	public function recharge_history(){
		$page = $this->input->get('page',true);
		$data = CmpAdminManage::getInstance()->getRechargeHistory($page);
		$this->displayHtml($data);
	}
	
	/**
	 * 消费记录
	 */
	public function expense_history(){
		$page = $this->input->get('page',true);
		$data = CmpAdminManage::getInstance()->getPayHistory($page);
		$this->displayHtml($data);
	}

	private function back($message = ''){
		if(!empty($message)){
			header('Content-Type:text/html; charset=utf-8');
			exit('<script>alert(decodeURIComponent("'.urlencode($message).'").replace("+"," "));window.location.href="/company/recharge"</script>');
		}
		exit('<script>window.history.back()</script>');
	}

	/* 是否有权限
	 */
	protected function _has_permissions_do() {
		return !empty($this->_user) && $this->_user->isCmpAdmin();
	}

}