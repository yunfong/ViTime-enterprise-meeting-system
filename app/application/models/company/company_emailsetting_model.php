<?php

/**
 * 企业用户邮件设置表
 */
class Company_emailsetting_model extends CU_Model{
	
	protected $_name = 'vitime_member_emailset';
	
	protected $_primary = 'id';
	
	/**
	 * 根据ID读取公司信息
	 * @param $id
	 */
	public function get($id){
		if(empty($id)){
			return array();
		}
		return $this->find($id);
		
	}
	
	/**
	 * 根据uid读取信息
	 * @param int $uid
	 */
	public function getSettingByUid($uid){
		if(empty($uid)){
			return array();
		}
		
		$setting = $this->fetchRow(array('member_id'=>$uid));
		if(empty($setting)){
			$setting = array('member_id'=>$uid,'email'=>'','password'=>'');
			$rs = $this->newSetting($uid,'','');
			if($rs > 0){
				return $setting;
			}
			return array();			
		}
		return $setting;
	}
	
	/**
	 * 新增信息
	 */
	public function newSetting($uid,$email,$password,$smtp='',$port = 25,$is_ssl = 0){
		if(empty($uid)){
			return 0;
		}
		$data = array('member_id'=>$uid,'email'=>$email,'password'=>$password,'smtp'=>$smtp,'port'=>$port,'is_ssl'=>$is_ssl);
		return $this->insert($data);
	}
	
	/**
	 * 更新
	 * @param int $uid
	 * @param string $email
	 * @param string $password
	 */
	public function updateSetting($uid,$email,$password,$smtp='',$port = 25,$is_ssl = 0){
		$is_ssl = intval($is_ssl)===1?'1':'0';
		$data = array('member_id'=>$uid,'email'=>$email,'password'=>$password,'smtp'=>$smtp,'port'=>$port,'is_ssl'=>$is_ssl);
		if(empty($uid)){
			return 0;
		}
		$setting = $this->getSettingByUid($uid);
		$isModi = false;
		foreach($data as $k=>$it){
			if($it != $setting[$k]){
				$isModi = true;
				break;
			}
		}
		if(!$isModi){
			return 1;
		}
		return $this->update($data, array('member_id'=>$uid));
	}
}