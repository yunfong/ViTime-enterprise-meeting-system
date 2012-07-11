<?php

/**
 * 会议记录
 */
class Meetinguserlog_model extends CU_Model{
	
	protected $_name = 'vitime_meeting_userlog';
	
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
	 * 新增记录
	 * @param $meet_id
	 * @param $user_id
	 * @param $date
	 */
	public function newLog($meet_id,$user_id,$date){
		if(empty($meet_id) || empty($user_id)){
			return 0;
		}
		$data = array(
			'meet_id'=>$meet_id,
			'user_id'=>$user_id,
			'date'=>$date
		);
		return $this->insert($data);
	}
	
	/**
	 * 读取会议用户
	 * @param int $meeting_id
	 */
	public function getMeetingUserList($meeting_id){
		if(empty($meeting_id)){
			return array();
		}
		$where = "meet_id = ".$this->db->escape($meeting_id);
		
		$sql = "select L.meet_id,M.* from {$this->_name} as L left join vitime_member as M on L.user_id=M.id where  {$where} order by L.id desc ";
		$query = $this->db->query($sql);
		return $query->result_array();	
	}
	
	public function getMeetByMUid($meet_id,$uid){
		$where = array('meet_id'=>intval($meet_id),'user_id'=>$uid);
		return $this->fetchRow($where);
	}
	
	
}