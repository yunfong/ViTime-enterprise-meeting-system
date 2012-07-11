<?php

/**
 * 企业数据表
 */
class Meeting_model extends CU_Model{
	
	protected $_name = 'vitime_meeting';
	
	protected $_primary = 'id';
	
	/**
	 * @param $id
	 */
	public function get($id){
		if(empty($id)){
			return array();
		}
		return $this->find($id);
		
	}
	/**
	 * 新增会议
	 * @param unknown_type $companyName
	 * @param unknown_type $companyMark
	 */
	public function newMeeting($data){
		
		return $this->insert($data);
	}
	
	/**
	 * 读取会议列表
	 * @param int $company_id
	 * @param int $page
	 * @param int $limit
	 */
	public function getCompanyMeetingList($user_id,$company_id,$page = 1,$limit = 10){
		if(empty($company_id)){
			return array();
		}
		$page = max(intval($page),1);
		$offset = ($page - 1)*$limit;
		$where = array('company_id'=>$company_id,'type'=>1,'state !='=>'0');
		return $this->selectByPage('*',$where,"(user_id={$user_id}) desc,start_time desc",$limit,$offset);
	}
	
	/**
	 * 读取公共会议列表
	 * @param int $page
	 * @param int $limit
	 */
	public function getPublicMeetingList($user_id=0,$page = 1,$limit = 10){
		$page = max(intval($page),1);
		$offset = ($page - 1)*$limit;
		$where = array('type'=>0);
		if(!empty($user_id)){
			$order = "(user_id={$user_id}) desc,";
		}
		return $this->selectByPage('*',$where,"{$order}start_time desc",$limit,$offset);
	}
	
	/**
	 * 读取会议记录
	 * @param int $user_id
	 * @param int $page
	 * @param int $limit
	 */
	public function getMeetingRecord($user_id,$page = 1,$limit = 10){
		$page = max(intval($page),1);
		$offset = ($page - 1)*$limit;
		$total = $this->where(array('user_id'=>$user_id,'state !='=>'0'))->count_all_results('vitime_meeting');
		if($total == 0){
			return array();
		}
		
		$sql = "select M.*,PR.r_money from vitime_meeting AS M left join vitime_pay_records AS PR on M.id=PR.meet_id where M.user_id={$user_id} order by M.id desc limit {$offset},{$limit}";
		$result = $this->db->query($sql);
		if($result === false){
			return array();
		}
		
		$pagn = array(
			'data' => $result->result_array(),
			'total'=>$total,
			'totalpage'=>empty($limit)?0:ceil($total/$limit),
			'count'=> $result->num_rows(),
			'page'=>empty($limit)?1:round($offset / $limit) + 1,
			'limit'=>$limit
		);
		$result->free_result();
		return $pagn;
	}
	
}