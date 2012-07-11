<?php
/**
 * 扩展Controller类，主要做了几个插入点，实际应用中还会加入登录信息，验证方法等等
 *  
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-27
 */
class MY_Controller extends CI_Controller{
	
	/**
	 * 控制器名称
	 * @var string
	 */
	protected $_controller;
	/**
	 * 执行的方法名称
	 * @var string
	 */
	protected $_action;
	/**
	 * 渲染视图名称，如：index => index.php
	 * @var string
	 */
	protected $_render_action;
	/**
	 * 是否取消视图渲染 
	 * @var bool
	 */
	protected $_no_render = false;
	
	/**
	 * 不允许子类重写构造方法，用_init方法代替
	 */
	public final function __construct(){
		parent::__construct();
		$this->_controller = get_class($this);
		$this->_action = $this->router->fetch_method();
		$this->_init();
	}
	
	/**
	 * 初始化，用于代替子类的构造方法
	 */
	protected function _init(){}
	
	/**
	 * 分发action前的操作，比如验证action执行权限
	 */
	protected function _prePostDispatch(){
	}
	
	/**
	 * 分发action后操作，如自动载入视图
	 */
	protected function _postDispatch(){
		$this->_renderView();
	}
	
	/**
	 * 重写
	 * @param string $method
	 * @param array $params
	 */
	public final function _remap($method,$params = array()){
		$self = $this;
		if(!method_exists($self, $method)){
			show_404();
		}
		$this->_action = $method;
		$this->_render_action = $method;
		
		$this->_prePostDispatch();
		call_user_func_array(array($self, $method), $params);
		$this->_postDispatch();
	}
	
	/**
	 * 加载view视图，约定视图命名为（小写）：controller/action.php
	 * @param string $view_path
	 */
	protected function _renderView($view_path = null){
		//不渲染视图
		if(empty($view_path) && $this->_no_render){
			return;
		}
		
		//@TODO 加载公用头部
		
		//加载内容部分
		if(!empty($view_path)){
			$this->_renderScript($view_path);
		}else{
			$this->_renderScript(strtolower($this->_controller).'/'.$this->_render_action.'.php');
		}
		
		//@TODO 加载公用尾部
	}
	
	/**
	 * 渲染渲染某个action对应的视图
	 * @param string $action
	 */
	protected function _renderAction($action){
		$this->_render_action = $action;
	}
	
	/**
	 * 不渲染视图
	 * @param bool $flag
	 */
	protected function _setNoRender($flag = true){
		$this->_no_render = $flag;
	}
	
	/**
	 * 加载视图脚本
	 * @param unknown_type $script_path
	 */
	protected function _renderScript($script_path){
		$this->load->view($script_path);
	}
	
	
}