<?php
/**
 * 会议
 *  
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-20
 */
class Meeting {

	protected $meeting_info = array();
	
	public function __get($key){
		return $this->meeting_info[$key];
	}
	
	public function __set($key,$value){
		$this->meeting_info[$key] = $value;
	}
	
	/**
	 * 是否公开会议
	 */
	public function isPublic(){
		return $this->meeting_info['type'] == 0;
	} 
	
	/**
	 * 是否正在会议中
	 */
	public function isMeeting(){
		return strtotime($this->meeting_info['start_time']) < time()
				&& strtotime($this->meeting_info['end_time']) > time();
	}
	
	/**
	 * 是否结束 
	 */
	public function isOver(){
		return strtotime($this->meeting_info['end_time']) < time();
	}
	
	/**
	 * 是否已经取消
	 */
	public function isCancel(){
		return $this->meeting_info['state'] == 0;
	}
	
	public function toArray(){
		return $this->meeting_info;
	}
}

?>