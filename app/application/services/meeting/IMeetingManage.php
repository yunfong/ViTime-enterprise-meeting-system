<?php
/**
 * 会议操作接口
 *  
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-20
 */
interface IMeetingManage {
	
	public function listCmpMeeting();
	
	public function listPubMeeting();
	
	public function bookMeeting($data);
	
	public function cancelMeeting($meeting_id);
	
	public function changeMeeting($meeting_id);
	
	public function viewMeeting($meeting_id);
	
	
}

?>