<?php

class CU_Input extends CI_Input {

	/**
	 * 是否post提交
	 */
	public function is_post(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			return true;
		}
		return false;
	}
}

?>