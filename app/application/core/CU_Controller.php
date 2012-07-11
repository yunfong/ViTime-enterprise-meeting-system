<?php
require_once SERVICE_DIR.'UserSession.php';
require_once(SERVICE_DIR.'company/CmpAdminManage.php');
require_once(SERVICE_DIR.'company/CmpUserManage.php');
require_once SERVICE_DIR.'meeting/MeetingManage.php';
require_once SERVICE_DIR.'sms/SMSWebservice.php';
/**
 * 控制器
 *
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-21
 */
abstract class CU_Controller extends CI_Controller {

	/**
	 * 是否需要验证登录状态
	 * @var boolean
	 */
	protected $_valid_login_status = false;

	/**
	 * 与相反的方法名称集合
	 * @var array
	 */
	protected $_valid_login_exclude = array();

	protected $_action;
	protected $_controller;
	/**
	 * @var IUser
	 */
	protected $_user;
	protected $_is_login = false;

	public function __construct(){
		parent::__construct();
		$this->_user = UserSession::getUser();
		$this->_user->v_money = CmpAdminManage::getInstance()->getUserMoney();
		$this->load->vars('login_user',$this->_user);
		$this->_is_login = UserSession::isLogin();
		$this->load->vars('is_login',$this->_is_login);
		$this->_controller = get_class($this);
		CmpAdminManage::getInstance()->outTry();
	}

	/**
	 * 设置是否需要验证登录
	 * @param boolean $need
	 */
	public function _needValidLogin($need){
		$this->_valid_login_status = $need;
	}

	/**
	 * 添加排除对象
	 * @param unknown_type $action
	 */
	public function _addNotValidAction($action){
		$this->_valid_login_exclude[] = $action;
	}

	/**
	 * 调用方法前检查权限
	 * @param string $method
	 * @param array $params
	 */
	public function _remap($method,$params = array()){
		$this->_action = $method;
		$self = $this;

		if(!method_exists($self, $method)){
			show_404();
		}
		$this->load->vars('_action',$this->_action);
		$this->load->vars('_controller',$this->_controller);

		if(!$this->_validLogin($method)){
//			show_error(array('请先登录','to_url'=>array('')),200,'操作错误');
			$this->_showError('该操作需要登录，请前往登录',array('url'=>'/members/login','label'=>'前往登录'));
		}

		if(!$this->_has_permissions_do()){
			return $this->_displayNoAuth();
		}

		call_user_func_array(array($self, $method), $params);
//		$this->$mmethod($params);
	}

	public function _showError($msg,$to_url = array()){
		show_error(array($msg,'to_url'=>$to_url),200,'操作错误');
	}

	/**
	 * 验证登录
	 * @param unknown_type $method
	 */
	protected function _validLogin($method){
		/**
		 * 如果需要验证登录，并且不在排除范围，并且未登录，则判断为无权限
		 */
		if($this->_valid_login_status === TRUE && !in_array(strtolower($method), array_map('strtolower', $this->_valid_login_exclude))){
			if($this->_is_login !== TRUE){
				return false;
			}
		}

		/**
		 * 如果不需要验证登录，但是在排除范围，并且未登录，则判断为无权限
		 */
		if($this->_valid_login_status === FALSE && in_array(strtolower($method), array_map('strtolower', $this->_valid_login_exclude))){
			if($this->_is_login !== TRUE){
				return false;
			}
		}
		return true;
	}

	/**
	 * 是否有权限做这件事情
	 */
	protected abstract function _has_permissions_do();

	/**
	 * 显示页面
	 * @param array $data 显示的数据
	 * @param string $template_dir 模版目录 默认当前控制器名称
	 * @param string $script 需要渲染的脚本
	 * @param boolean $renderHF 是否显示公共头部
	 *
	 */
	public function displayHtml($data = array(),$script='',$template_dir = '',$renderHF = true){
		if(!is_array($data)){
			$data =  array($data);
		}
		$this->load->vars($data);
		if(empty($template_dir)){
			$template_dir = strtolower($this->_controller);
		}

		if(empty($script)){
			$script = strtolower($this->_action);
			if(empty($script)){
				$script = 'index';
			}
		}

		$template_dir = $template_dir.'/'.$script.'.php';
		if($renderHF){
			$this->load->view('/header.php');
			$this->load->view($template_dir);
			$this->load->view('/footer.php');
		}else{
			$this->load->view($template_dir);
		}
	}

	/**
	 * 无权限操作
	 */
	protected function _displayNoAuth(){
		show_error('您没有权限执行该操作',403,'发生了权限错误');
	}

	protected function _redirect($action,$controller='',$params = array()){
		if(empty($controller)){
			$controller = strtolower($this->_controller);
		}
		if(is_array($params)){
			$params = implode('/', $params);
		}else{
			$params = trim($params,'/');
		}

		$uri = '/'.$controller.'/'.$action.'/'.$params;
		redirect($uri);
	}

	protected function wrapErrorMsg($msg){
		return "<span style='padding-left:19px;'>{$msg}</span><br />";
	}
}

?>