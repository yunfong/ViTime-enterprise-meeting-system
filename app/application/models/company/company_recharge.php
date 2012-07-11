<?php

/**
 * 重置密码
 *  
 * @author kevin
 * @date 2012-6-24
 */
class Company_recharge extends CU_Model {

	protected $_name = 'vitime_recharge' ;
	protected $_primary= 'id' ;
	
	/**
	 * 插入充值记录
	 * @param array $data
	 */
	public function insertRecharge($data){
		return $this->insert($data);
	}
	
	/**
	 * 获取充值记录
	 * @param int $id
	 */
	public function getRecharge($id){
		if(empty($id))
		{
			return array();
		}
		$where = array('id'=>$id);
		return $this->fetchRow($where);
	}
	/**
	 * 更新充值记录
	 * @param array $data
	 * @param int $id
	 */
	public function updateRecharge($id,$data){
		if(empty($id) || empty($data)){
			return 0;
		}
		$where = array('id'=>$id);
		return $this->update($data, $where);
	}
	
	public function getRechargeList($uid,$page = 1,$limit = 10){
		if(empty($uid)){
			return array();
		}
		$where = array('mid'=>$uid);
		$limit = intval($limit);
		$offset = ($page - 1)*$limit;
		return $this->selectByPage('*',$where,'uptime desc',$limit,$offset);
	}
}