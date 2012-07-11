<?php

/**
 * 重置密码
 *  
 * @author kevin
 * @date 2012-6-24
 */
class Company_get_password extends CU_Model {

	protected $_name = 'vitime_getpassword' ;
	protected $_primary= 'id' ;
	
	/**
	 * 生成重置密码验证码
	 * @param string $array
	 */
	public function t_Insert($data){
		$this->clear();
		return $this->insert($data);
	}
	
	/**
	 * 检查重置密码验证码
	 * @param string $array
	 */
	public function checkCode($code,$mid){
		if(empty($code) || empty($mid))
		{
			return array();
		}
		$where = array('code'=>$code,'mid'=>$mid);
		return $this->fetchRow($where);
	}
	/**
	 * 清空超时验证码
	 * @param string $array
	 */
	public function clear($id = 0){
		if($id){
			$where = ' id = '. $id;
		}else{
			$where = ' uptime < '. (time() - 86400);
		}
		return $this->query("DELETE FROM `vitime_getpassword` WHERE $where");
		//return $this->delete($where);
	}
}