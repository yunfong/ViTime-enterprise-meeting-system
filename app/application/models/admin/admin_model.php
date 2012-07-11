<?php
/**
 * 对应表vitime_admin
 * 管理员模型
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-21
 */
class Admin_model extends CU_Model {

	protected $_name = 'vitime_admin';
	protected $_primary = 'id';
	
	/**
	 * 读取用户
	 * @param unknown_type $username
	 */
	public function getUserByName($username){
		if(empty($username)){
			return array();
		}
		
		return $this->fetchRow(array('username'=>$username));
	}
}

?>