<?php
/**
 * 短信发送
 *  
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-6-25
 */
class Sms_send_model extends CU_Model{
	protected $_name = 'vitime_send_sms';
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
	 * 新增发送短信
	 * @param string $mobile
	 * @param string $content
	 * @param string $sendtime
	 */
	public function newSms($uid,$mobile,$sid,$content,$sendtime){
		if(is_null($uid)){
			return 0;
		}
		$data = array(
			'member_id'=>$uid,
			'mobile'=>$mobile,
			'sid'=>$sid,
			'contents'=>$content,
			'sendtime'=>$sendtime
		);
		
		return $this->insert($data);
	}
}

?>