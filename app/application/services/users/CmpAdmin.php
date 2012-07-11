<?php

require_once (SERVICE_DIR.'users/IUser.php');
/**
 * 企业管理员用户
 *  
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-20
 */
class CmpAdmin implements IUser {
	
	private $user_data = array();
	
	public function __construct($db_row = array()){
		$this->user_data = $db_row;
	}
	
	public function __get($key){
		return $this->user_data[$key];
	}
	
	public function __set($key,$value){
		$this->user_data[$key] = $value;
	}
	
	/* (non-PHPdoc)
	 * @see IUser::isAdmin()
	 */
	public function isSysAdmin() {
		return false;		
	}

	/* 
	 * @see IUser::isCmpUser()
	 */
	public function isCmpAdmin() {
		return true;
		
	}

	/* 是否普通用户
	 * @see IUser::isUser()
	 */
	public function isCmpUser() {
		return false;
		
	}

	/* 转为数组
	 * @see IUser::toArray()
	 */
	public function toArray() {
		return $this->user_data;		
	}


}

?>