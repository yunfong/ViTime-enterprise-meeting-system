<?php
/**
 * user interface 
 * 
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-20
 */
interface IUser {
	public function isSysAdmin();
	public function isCmpAdmin();
	public function isCmpUser();
	
	public function toArray();
}

?>