<?php

class Member_monthpay_model extends CU_Model{

	protected $_name = 'vitime_member_monthpay';
	protected $_primary = 'id';
	
	public function newMonthPay($uid,$start_date,$end_date){
		if(empty($uid)){
			return false;
		}
		$time = time();
		$month = date('Ym',strtotime($start_date));
		
		$data = array(
		'member_id'=>$uid,
		'pay_time'=>date('Y-m-d H:i:s',$time),
		'month'=>$month,
		'start_date'=>$start_date,
		'end_date'=>$end_date
		);
		
		return $this->insert($data);
	}
	
	public function getUserNowMonthPay($uid){
		if(empty($uid)){
			return array();
		}
		$now = date('Y-m-d');
		$where = array('member_id'=>$uid,'start_date <='=>$now,'end_date >='=>$now);
		return $this->fetchRow($where);
	}
}

?>