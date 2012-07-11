<?php
require_once SERVICE_DIR.'company/CmpUserManage.php';
require_once SERVICE_DIR.'company/CmpAdminManage.php';
require_once SERVICE_DIR.'admin/AdminManage.php';
require_once SERVICE_DIR.'UserSession.php';
/**
 * 管理登录界面
 *
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-21
 */
class Members extends CI_Controller{

	const COOKIE_USER_NAME_TAG = 'l_u_name';
	const COOKIE_COMPANY_TAG = 'l_u_company_mark';
	const ADMIN_LOGIN_TEMPLATE = 'admin_login.php';
	const USER_LOGIN_TEMPLATE = 'login.php';
	const USER_REG_TEMPLATE = 'register.php';
	const CONTROLLER_NAME = '/members' ;
	const GET_PASSWORD_TEMPLATE = 'get_password.php';
	const SET_PASSWORD_TEMPLATE = 'set_password.php';

	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$not_skip = array('logout','payment_return','payment_notify');
		//如果已经登录则跳转到对应首页，如果没有登录则显示登录框
		if(UserSession::isLogin() && !in_array($this->router->fetch_method(),$not_skip)){
			$user = UserSession::getUser();
			if($user->isSysAdmin()){
				redirect('/admin', 'location');
			}else if($user->isCmpAdmin()){
				redirect('/company', 'location');
			}else{
				redirect('/mymeeting', 'location');
			}
		}
	}
	/**
	 * 支付通知
	 */
	public function payment_notify(){
		require_once(SERVICE_DIR.'alipay_wy/alipay.config.php');
		require_once(SERVICE_DIR.'alipay_wy/lib/alipay_notify.class.php');
		$alipayNotify = new AlipayNotify($aliapy_config);
		$verify_result = $alipayNotify->verifyReturn();
		if($verify_result) {
			$out_trade_no	= $_GET['out_trade_no'];	//获取订单号
			$trade_no		= $_GET['trade_no'];		//获取支付宝交易号
			$total_fee		= $_GET['total_fee'];		//获取总价格

			$data = array('status'=>$_GET['trade_status'],'trade_no'=>$trade_no);
			$cmpUserManage = CmpUserManage::getInstance();
			$cmpUserManage->updateRecharge($out_trade_no,$data);
			if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
				//判断是否处理
				$Recharge = CmpUserManage::getRecharge($out_trade_no);
				if($Recharge['isdeal'] == 0){
					//增加用户余额
					CmpUserManage::updateUserMoney($Recharge['mid'],$total_fee);
					$cmpUserManage->updateRecharge($out_trade_no,array('isdeal'=>1));
				}
			}
			echo "success";
		}
		else {
			echo "fail";
		}
	}
	/**
	 * 支付结果
	 */
	public function payment_return(){
		require_once(SERVICE_DIR.'alipay_wy/alipay.config.php');
		require_once(SERVICE_DIR.'alipay_wy/lib/alipay_notify.class.php');
		$alipayNotify = new AlipayNotify($aliapy_config);
		$verify_result = $alipayNotify->verifyReturn();
		if($verify_result) {
			$out_trade_no	= $_GET['out_trade_no'];	//获取订单号
			$trade_no		= $_GET['trade_no'];		//获取支付宝交易号
			$total_fee		= $_GET['total_fee'];		//获取总价格

			$data = array('status'=>$_GET['trade_status'],'trade_no'=>$trade_no);
			$cmpUserManage = CmpUserManage::getInstance();
			$cmpUserManage->updateRecharge($out_trade_no,$data);
			if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
				showmessage('支付成功','/members');
			}
			else {
				showmessage('未知异常','/members');
			}
		}
		else {
			showmessage('验证失败','/members');
		}
	}
	/**
	 * 普通用户登录界面
	 */
	public function index(){
		$this->login();
	}
	/**
	 * 取回密码
	 */
	public function get_password(){
		if($_POST){
			//获取提交参数
			$username = $this->input->post('username',true);
			$email = $this->input->post('email',true);

			//判断参数完整性
			if(empty($username) || empty($email)){
				$errMsg= "请填写完整再提交";
				return $this->displayLoginHtml(array('errMsg'=>$errMsg),self::GET_PASSWORD_TEMPLATE);
			}else{

				$cmpUserManage = CmpUserManage::getInstance();
				$postData = $this->input->post(NULL,TRUE);
				$getPasswordMsg = $cmpUserManage->getPassword($postData);
				if($getPasswordMsg === true){
					showmessage("修改密码的邮件已经发送到你的邮箱 $email ，你需要点击邮件里的确认链接来完成密码修改");
				}else{
					return $this->displayLoginHtml(array('errMsg'=>"{$getPasswordMsg}"),self::GET_PASSWORD_TEMPLATE);
				}
			}
		}
		$this->displayLoginHtml(array(),self::GET_PASSWORD_TEMPLATE);
	}
	/**
	 * 重置密码
	 */
	public function set_password(){
		$cmpUserManage = CmpUserManage::getInstance();
		if($_POST){
			//获取提交参数
			$code = $this->input->post('code',true);
			$mid = $this->input->post('mid',true);
			$newpassword = $this->input->post('newpassword',true);
			$newpassword2 = $this->input->post('newpassword2',true);
			//判断参数完整性
			if(empty($newpassword) || empty($newpassword2)){
				$errMsg= "请填写完整再提交";
				$this->displayLoginHtml(array('errMsg'=>$errMsg),self::SET_PASSWORD_TEMPLATE);
			}else{
				$cmpUserManage->checkCode($code,$mid);
				$postData = $this->input->post(NULL,TRUE);
				$getPasswordMsg = $cmpUserManage->setPassword($postData);
				if($getPasswordMsg === true){
					showmessage("修改密码成功!",'/members/login');
				}else{
					$this->displayLoginHtml(array('errMsg'=>"{$loginMsg}"),self::SET_PASSWORD_TEMPLATE);
				}
			}
		}
		$code = $this->input->get('code',TRUE);
		$mid = $this->input->get('mid',TRUE);
		$cmpUserManage->checkCode($code,$mid);
		$this->displayLoginHtml(array('code'=>$code,'mid'=>$mid),self::SET_PASSWORD_TEMPLATE);
	}
	public function login(){
		//读取cookie中的用户名
		$cookie_username = @$_COOKIE[self::COOKIE_USER_NAME_TAG];
		$cookie_username = $this->encrypt->decode($cookie_username);

		$company_mark = @$_COOKIE[self::COOKIE_COMPANY_TAG];
		$company_mark = $this->encrypt->decode($company_mark);
		$this->displayLoginHtml(array('username'=>$cookie_username,'company_mark'=>$company_mark),self::USER_LOGIN_TEMPLATE);
	}
	/**
	 * 普通用户登录处理
	 */
	public function do_login(){
		if(empty($_POST)){
			redirect(self::CONTROLLER_NAME,'location');
		}

		//获取提交参数
		$username = $this->input->post('username',true);
		$password = $this->input->post('password',true);
		$companyMark = $this->input->post('company_mark',true);

		//判断参数完整性
		if(empty($username) || empty($password) || empty($companyMark)){
			$errMsg= "请填写完整再提交";
			$this->displayLoginHtml(array('errMsg'=>$errMsg),self::USER_LOGIN_TEMPLATE);
		}else{
			$user = CmpAdminManage::getInstance()->getUserByName($username);
			if($user['priority'] == 1){
				return $this->do_admin_login();
			}
			$cmpUserManage = CmpUserManage::getInstance();
			$postData = $this->input->post(NULL,TRUE);
			$loginMsg = $cmpUserManage->login($postData);
			if($loginMsg === true){
				//保存用户名到cookies中
				if($this->input->post('keepme') == '1'){
					$cookie_username = $this->encrypt->encode($postData['username']);
					$this->input->set_cookie(self::COOKIE_USER_NAME_TAG,$cookie_username,86400*365);
				}
				redirect('/mymeeting', 'location');
			}else{
				$this->displayLoginHtml(array('errMsg'=>"登录失败，{$loginMsg}"),self::USER_LOGIN_TEMPLATE);
			}
		}
	}

	/**
	 * 管理员登录界面
	 *
	 */
	public function admin_login(){

		//读取cookie中的用户名
		$cookie_username = @$_COOKIE[self::COOKIE_USER_NAME_TAG];
		$cookie_username = $this->encrypt->decode($cookie_username);

		$company_mark = @$_COOKIE[self::COOKIE_COMPANY_TAG];
		$company_mark = $this->encrypt->decode($company_mark);
		$this->displayLoginHtml(array('username'=>$cookie_username,'company_mark'=>$company_mark),self::ADMIN_LOGIN_TEMPLATE);
	}

	/**
	 *
	 * 登录处理
	 */
	public function do_admin_login(){
		if(empty($_POST)){
			redirect(self::CONTROLLER_NAME.'/admin_login','location');
		}

		//获取提交参数
		$username = $this->input->post('username',true);
		$password = $this->input->post('password',true);
		$companyMark = $this->input->post('company_mark',true);
		$userType = $this->input->post('user_type',true);

		//判断参数完整性
		if(empty($username) || empty($password) || (empty($companyMark) && $userType=='company') || empty($userType)){
			$errMsg= "请填写完整再提交";
			$this->displayLoginHtml(array('errMsg'=>$errMsg));
		}else{
			//执行登录操作
			if($userType == 'company'){
				$this->cmpAdminLogin();
			}else{
				$this->adminLogin();
			}
		}
	}

	/**
	 * 企业管理员注册
	 */
	public function register(){
		$this->displayLoginHtml(array(),self::USER_REG_TEMPLATE,false);
	}

	public function do_register(){
		if(empty($_POST)){
			redirect(self::CONTROLLER_NAME.'/register','location');
		}

		$postData = $this->input->post(NULL,TRUE);
		$errMsg = '';

		//去掉html标签
		foreach($postData as $k=>&$v){
			$postData[$k] = trim(strip_tags($v));
		}

		if(empty($postData['verifycode']) || (strtolower($postData['verifycode']) != $_SESSION['verify_code'])){
			return $this->displayLoginHtml(array('errMsg'=>'验证码不正确'),self::USER_REG_TEMPLATE,false);
		}



		$errMsg = '';
		if(empty($postData['username'])){
			$errMsg .= $this->wrapErrorMsg("用户名必须填写");
		}

		if(empty($postData['password'])){
			$errMsg .= $this->wrapErrorMsg("密码必须填写");
		}

		if(empty($postData['mobile'])){
			$errMsg .= $this->wrapErrorMsg("手机号码必须填写");
		}

		if(!preg_match("/1[3458]{1}\d{9}/",$postData['mobile'])){
			$errMsg .= $this->wrapErrorMsg("手机号码格式不正确");
		}

		if(empty($postData['email'])){
			$errMsg .= $this->wrapErrorMsg("邮箱必须填写");
		}
		$this->load->helper('email');
		if (!valid_email($postData['email'])){
			$errMsg .= $this->wrapErrorMsg("邮箱格式不正确");
		}

		if(empty($postData['company_name'])){
			$errMsg .= $this->wrapErrorMsg("公司名称必须填写");
		}

		if(empty($postData['company_mark'])){
			$errMsg .= $this->wrapErrorMsg("企业标识必须填写");
		}

		if(!empty($errMsg)){
			$errMsg = "填写不完整：<br />{$errMsg}";
			return $this->displayLoginHtml(array('errMsg'=>$errMsg),self::USER_REG_TEMPLATE,false);
		}

		$cmpAdminManager = CmpAdminManage::getInstance();
		$loginMsg = $cmpAdminManager->register($postData);
		if($loginMsg === TRUE){
			$_SESSION['register_success_id'] = $postData['username'];
			redirect(self::CONTROLLER_NAME.'/register_success', 'location');
		}else{
			return $this->displayLoginHtml(array('errMsg'=>"注册失败，$loginMsg"),self::USER_REG_TEMPLATE,false);
		}
	}

	/**
	 * 登录成功页面
	 */
	public function register_success(){
		$register_id = $_SESSION['register_success_id'];
		if(empty($register_id)){
			redirect('/members','location');
		}

		$this->load->view('/header.php');
		$this->load->view(self::CONTROLLER_NAME.'/register_success.php',array('register_id'=>$register_id));
		$this->load->view('/footer.php');
	}

	/**
	 * 发送注册激活邮件
	 */
	public function send_register_mail(){
		ignore_user_abort(true);
		$register_id = $_SESSION['register_success_id'];
		if(empty($register_id)){
			exit(0);
		}
		unset($_SESSION['register_success_id']);

		$user = CmpAdminManage::getInstance()->getUserByName($register_id);
		if(empty($user)){
			exit(0);
		}
		$company = CmpAdminManage::getInstance()->getCompanyInfo($user['company_id']);

		$user['url'] = SROOT.'members/active_user/'.md5($user['id'].config_item('encryption_key')).$user['id'];
		$user['company_name'] = $company['company_name'];
		$user['company_mark'] = $company['company_mark'];
		$body = $this->load->view('/mail_template/register.php',$user,true);
		$this->load->config('email.php',true);
		$config = config_item('email');
		$this->load->library('PHPMailer',array(true));
		$mail = $this->phpmailer;
		$mail->SetLanguage('cn');
		$mail->set('CharSet',$config['charset']);
		$mail->IsSMTP();
		$mail->Host       = $config['smtp_host']; // SMTP server
		$mail->SMTPDebug  = 0;
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Port       = $config['smtp_port'];
		$mail->Username   = $config['smtp_user']; // SMTP account username
		$mail->Password   = $config['smtp_pass']; // SMTP account password

		$mail->SetFrom($config['smtp_from'],$config['smtp_title']);
		$mail->AddReplyTo($config['smtp_from'],$config['smtp_title']);
		$mail->Subject    = "请激活您的账号 - 微泰移动视频会议系统";
		$mail->AltBody    = "请激活您的账号 - 微泰移动视频会议系统"; // optional, comment out and test
		$mail->MsgHTML($body);
		$mail->AddAddress($user['email'], empty($user['name'])?$user['username']:$user['name']);

		$this->load->library('log');
		$this->config->set_item('log_threshold', 1);
		try{

			if(!$mail->Send()) {
				$msg = urlencode("发送邮件失败，详细信息：".$mail->ErrorInfo);
				$this->log->write_log('ERROR',$msg.PHP_EOL.'Uid:'.$user['uid']);
				exit(0);
			} else {
				$this->log->write_log('INFO',"发送邮件激活邮件成功：{$user['email']}");
			  exit(1);
			}
		}catch(Exception $e){
			$msg = urlencode("发送邮件失败，详细信息：".$mail->ErrorInfo);
			$this->log->write_log('ERROR',$msg.PHP_EOL.'Uid:'.$user['id']);
			exit(0);
		}
	}

	public function active_user($crypt){
		$uid = substr($crypt, 32);
		$crypt = substr($crypt, 0,-2);
		if(empty($uid) || empty($crypt) || strlen($crypt)!=32){
			exit("error:");
		}
		if(md5($uid.config_item('encryption_key')) != $crypt){
			exit("error");
		}
		$user = CmpAdminManage::getInstance()->getUserInfo($uid);
		$data = array();
		if($user['status'] == 3){
			$rs = CmpAdminManage::getInstance()->activeUser($user['id']);
			if($rs == 1){
				$company = CmpAdminManage::getInstance()->getCompanyInfo($user['company_id']);
				$user['company_name'] = $company['company_name'];
				$user['company_mark'] = $company['company_mark'];
				$data = $user;
			}else{
				$data = array('success'=>false);
			}
		}else {
			redirect('/members/admin_login');
		}
		$this->displayLoginHtml($data,'active_user.php');
	}

	public function ajax_login(){
		if(empty($_POST)){
			exit('{error:true}');
		}

		//获取提交参数
		$username = $this->input->post('username',true);
		$password = $this->input->post('password',true);
		$companyMark = $this->input->post('company_mark',true);

		//判断参数完整性
		if(empty($username) || empty($password) || empty($companyMark)){
			exit('{errMsg:"请填写完整再提交"}');
		}

		$user = CmpAdminManage::getInstance()->getUserByName($username);
		if(empty($user)){
			exit('{errMsg:"用户不存在"}');
		}
		$login = false;
		$postData = array('username'=>$username,'password'=>$password,'company_mark'=>$companyMark);
		if($user['priority'] == 1){
			$login = CmpAdminManage::getInstance()->login($postData);
		}else{
			$login = CmpUserManage::getInstance()->login($postData);
		}
		if($login===true){
			exit('{success:true}');
		}else{
			exit('{success:false,errMsg:"'.$login.'"}');
		}
	}

	public function logout(){
		unset($_SESSION);
		$user = UserSession::getUser();
		if(!empty($user)){
			if($user->isSysAdmin()){
				AdminManage::getInstance()->logout();
			}elseif ($user->isCmpAdmin()){
				CmpAdminManage::getInstance()->logout();
			}else{
				CmpUserManage::getInstance()->logout();
			}
		}
		session_destroy();
		redirect('/','location');
	}

	/**
	 * 系统管理员登录
	 */
	private function adminLogin(){
		$adminManager = AdminManage::getInstance();
		$postData = $this->input->post(NULL,true);
		$loginMsg = $adminManager->login($postData);
		if($loginMsg === true){
			//保存用户名到cookies中
			if($this->input->post('keepme') == '1'){
				$cookie_username = $this->encrypt->encode($postData['username']);
				$this->input->set_cookie(self::COOKIE_USER_NAME_TAG,$cookie_username,86400*365);
			}
			redirect('/admin', 'location');
		}else{
			$this->displayLoginHtml(array('errMsg'=>"登录失败，{$loginMsg}"));
		}
	}

	/**
	 * 企业管理员登录
	 */
	private function cmpAdminLogin(){
		$cmpManage = CmpAdminManage::getInstance();
		$postData = $this->input->post(NULL,true);
		$loginMsg = $cmpManage->login($postData);
		if($loginMsg === true){
			//保存用户名到cookies中
			if($this->input->post('keepme') == '1'){
				$cookie_username = $this->encrypt->encode($postData['username']);
				$this->input->set_cookie(self::COOKIE_USER_NAME_TAG,$cookie_username,86400*365);
			}
			redirect('/company', 'location');
		}else{
			$this->displayLoginHtml(array('errMsg'=>"登录失败，{$loginMsg}"));
		}
	}

	/**
	 * 输出页面
	 * @param array $data
	 * @param string $template
	 */
	private function displayLoginHtml($data,$template = '',$renderTopAndBottom = true){

		$postData = @$this->input->post(null,true);
		if(!is_array($postData)){
			$postData = array($postData);
		}
		$data = @array_merge($postData,$data);
		if(empty($template)){
			$template = self::ADMIN_LOGIN_TEMPLATE;
		}
		if($renderTopAndBottom){
			$this->load->view(self::CONTROLLER_NAME.'/login_header.php');
			$this->load->view(self::CONTROLLER_NAME.'/'.$template,$data);
			$this->load->view('/footer.php');
		}else{
			$this->load->view(self::CONTROLLER_NAME.'/'.$template,$data);
		}
	}

	private function wrapErrorMsg($msg){
		return "<span style='padding-left:19px;'>{$msg}</span><br />";
	}
}

?>