<?php

require_once (SERVICE_DIR.'users/IUser.php');

class TouristUser implements IUser {
	
	private $user_data = array();
	
	public function __construct($db_row = array()){
		$this->user_data = $db_row;
		if(empty($this->user_data)){
			if(!empty($_SESSION['tourist_name']))
			{
				$name = $_SESSION['tourist_name'];
			}else{
				$name = substr(uniqid(session_id()),-6);
				$_SESSION['tourist_name'] = $name; 
			}
			$this->user_data = array('username'=>'Guest'.$name,'name'=>'游客'.$name);
		}
	}
	
	public function __get($key){
		if(array_keys($this->user_data,$key)){
			return $this->user_data[$key];
		}
		return null;
	}
	
	public function __set($key,$value){
		$this->user_data[$key] = $value;
	}
	
	/**
	 * 
	 */
	public function isSysAdmin() {
		return false;
	}

	/**
	 * 
	 */
	public function isCmpAdmin() {
		return false;
	}

	/**
	 * 
	 */
	public function isCmpUser() {
		return false;
	}

	/**
	 * 
	 */
	public function toArray() {
		
	}


}

?>