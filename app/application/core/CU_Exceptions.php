<?php
/**
 * 错误处理
 *  
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-22
 */
class CU_Exceptions extends CI_Exceptions {

	/**
	 * 常规错误处理
	 *
	 * @access	private
	 * @param	string	the heading
	 * @param	string	the message
	 * @param	string	the template name
	 * @param 	int		the status code
	 * @return	string
	 */
	function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		set_status_header($status_code);
		
		if(isset($message['to_url']) && is_array($message['to_url'])){
			$to_url = $message['to_url'];
			unset($message['to_url']);
		}

		$message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include(APPPATH.'errors/'.$template.'.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}

?>