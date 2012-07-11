<?php

class Welcome extends CI_Controller{
	
	public function index(){
//		$this->load->helper('url');
//		redirect('/index.html','location','301');
		$this->load->view('index.php');
	}
/* *
	 *
	 * @see CU_Controller::_has_permissions_do()
	 */
	protected function _has_permissions_do() {
		// TODO Auto-generated method stub
		
	}
	
	public function mail(){
	
	}
	
	public function check_mobile(){
		$postData['mobile'] = '14709387654';
		if(strlen(trim($postData['mobile']))!=11 || !preg_match("/13[123569]{1}\d{8}|15\d{9}|18\d{9}|14\d{9}/",$postData['mobile'])){
			echo "手机号码格式不正确";
		}
	}
	
	public function check_email(){
		$this->load->config('email.php',true);
		$config = config_item('email');
	}

}