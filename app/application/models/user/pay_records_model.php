<?php

class Pay_records_model extends CU_Model{

	protected $_name = 'vitime_pay_records';
	protected $_primary = 'id';
	
	public function newRecord($data){
		return $this->insert($data);
	}
	
	public function getPayList($uid,$page = 1,$limit = 10){
		if(empty($uid)){
			return array();
		}
		$where = array('member_id'=>$uid);
		$limit = intval($limit);
		$offset = ($page - 1)*$limit;
		return $this->selectByPage('*',$where,'pay_time desc',$limit,$offset);
	}
}

?>