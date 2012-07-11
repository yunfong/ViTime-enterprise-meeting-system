<?php

/**
 * 提示
 * @param string $message
 * @param string $url_forward
 */
function showmessage($message, $url_forward='', $second=1, $values=array()) {
	ob_clean();
	if($url_forward) {
		$message = "<a href=\"$url_forward\">$message</a><script>setTimeout(\"window.location.href ='$url_forward';\", ".($second*1000).");</script>";
	}
	include SERVICE_DIR.'../views/header.php';
	include SERVICE_DIR.'../views/showmessage.php';
	include SERVICE_DIR.'../views/footer.php';
	exit();
}

/**
 * 发送邮件
 * @param string $toemail
 * @param string $subject
 * @param string $message
 * @param string $from
 * @return int
 */
function sendmail($toemail, $subject, $message, $from='') {
	//邮件模板
	include SERVICE_DIR.'../views/sendmail.php';
	$message = ob_get_contents();
	ob_clean();
	$mail=array
	(
		'mailsend' => 2,
		'maildelimiter' => 1,
		'mailusername' => 1,
		'server' => 'smtp.qq.com',
		'port' => 25,
		'auth' => 1,
		'from' => 'liaokaiufo@vip.qq.com',
		'auth_username' => 'liaokaiufo@vip.qq.com',
		'auth_password' => 'b654123',
		'charset'=>'utf-8'
	);

	//邮件头的分隔符
	$maildelimiter = $mail['maildelimiter'] == 1 ? "\r\n" : ($mail['maildelimiter'] == 2 ? "\r" : "\n");
	//收件人地址中包含用户名
	$mailusername = isset($mail['mailusername']) ? $mail['mailusername'] : 1;
	//端口
	$mail['port'] = $mail['port'] ? $mail['port'] : 25;
	$mail['mailsend'] = $mail['mailsend'] ? $mail['mailsend'] : 1;

	//发信者
	$email_from = empty($from) ? $mail['from'] : $from;

	$email_to = preg_match('/^(.+?) \<(.+?)\>$/',$toemail, $mats) ? ($mailusername ? '=?'.$mail['charset'].'?B?'.base64_encode($mats[1])."?= <$mats[2]>" : $mats[2]) : $toemail;;

	$email_subject = $subject;
	$email_message = chunk_split(base64_encode(str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $message))))));

	$headers = "From: $email_from{$maildelimiter}X-Priority: 3{$maildelimiter}X-Mailer: UCENTER_HOME ".X_VER."{$maildelimiter}MIME-Version: 1.0{$maildelimiter}Content-type: text/html; charset=$_SC[charset]{$maildelimiter}Content-Transfer-Encoding: base64{$maildelimiter}";

	if($mail['mailsend'] == 1) {
		if(function_exists('mail') && @mail($email_to, $email_subject, $email_message, $headers)) {
			return true;
		}
		return false;

	} elseif($mail['mailsend'] == 2) {

		if(!$fp = fsockopen($mail['server'], $mail['port'], $errno, $errstr, 30)) {
			runlog('SMTP', "($mail[server]:$mail[port]) CONNECT - Unable to connect to the SMTP server", 0);
			return false;
		}
	 	stream_set_blocking($fp, true);

		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != '220') {
			runlog('SMTP', "$mail[server]:$mail[port] CONNECT - $lastmessage", 0);
			return false;
		}

		fputs($fp, ($mail['auth'] ? 'EHLO' : 'HELO')." uchome\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 220 && substr($lastmessage, 0, 3) != 250) {
			runlog('SMTP', "($mail[server]:$mail[port]) HELO/EHLO - $lastmessage", 0);
			return false;
		}

		while(1) {
			if(substr($lastmessage, 3, 1) != '-' || empty($lastmessage)) {
	 			break;
	 		}
	 		$lastmessage = fgets($fp, 512);
		}

		if($mail['auth']) {
			fputs($fp, "AUTH LOGIN\r\n");
			$lastmessage = fgets($fp, 512);
			if(substr($lastmessage, 0, 3) != 334) {
				runlog('SMTP', "($mail[server]:$mail[port]) AUTH LOGIN - $lastmessage", 0);
				return false;
			}

			fputs($fp, base64_encode($mail['auth_username'])."\r\n");
			$lastmessage = fgets($fp, 512);
			if(substr($lastmessage, 0, 3) != 334) {
				runlog('SMTP', "($mail[server]:$mail[port]) USERNAME - $lastmessage", 0);
				return false;
			}

			fputs($fp, base64_encode($mail['auth_password'])."\r\n");
			$lastmessage = fgets($fp, 512);
			if(substr($lastmessage, 0, 3) != 235) {
				runlog('SMTP', "($mail[server]:$mail[port]) PASSWORD - $lastmessage", 0);
				return false;
			}

			$email_from = $mail['from'];
		}

		fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 250) {
			fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
			$lastmessage = fgets($fp, 512);
			if(substr($lastmessage, 0, 3) != 250) {
				runlog('SMTP', "($mail[server]:$mail[port]) MAIL FROM - $lastmessage", 0);
				return false;
			}
		}

		fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $toemail).">\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 250) {
			fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $toemail).">\r\n");
			$lastmessage = fgets($fp, 512);
			runlog('SMTP', "($mail[server]:$mail[port]) RCPT TO - $lastmessage", 0);
			return false;
		}

		fputs($fp, "DATA\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 354) {
			runlog('SMTP', "($mail[server]:$mail[port]) DATA - $lastmessage", 0);
			return false;
		}

		$headers .= 'Message-ID: <'.gmdate('YmdHs').'.'.substr(md5($email_message.microtime()), 0, 6).rand(100000, 999999).'@'.$_SERVER['HTTP_HOST'].">{$maildelimiter}";

		fputs($fp, "Date: ".gmdate('r')."\r\n");
		fputs($fp, "To: ".$email_to."\r\n");
		fputs($fp, "Subject: ".$email_subject."\r\n");
		fputs($fp, $headers."\r\n");
		fputs($fp, "\r\n\r\n");
		fputs($fp, "$email_message\r\n.\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 250) {
			runlog('SMTP', "($mail[server]:$mail[port]) END - $lastmessage", 0);
		}
		fputs($fp, "QUIT\r\n");

		return true;

	}
}

function phpmailer_send($toemail, $subject, $message, $from=''){
	$ci = get_instance();
	$ci->load->config('email.php',true);
	$config = config_item('email');
	$ci->load->library('PHPMailer',array(true));
	$mail = $ci->phpmailer;
	$mail->SetLanguage('cn');
	$mail->set('CharSet',$config['charset']);
	$mail->IsSMTP();
	$mail->Host       = $config['smtp_host']; // SMTP server
	$mail->SMTPDebug  = 0;
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = $config['smtp_port'];
	$mail->Username   = $config['smtp_user']; // SMTP account username
	$mail->Password   = $config['smtp_pass']; // SMTP account password

	$mail->SetFrom(!empty($from)?$from:$config['smtp_from'],$config['smtp_title']);
	$mail->AddReplyTo($config['smtp_from'],$config['smtp_title']);
	$mail->Subject    = $subject;
	$mail->AltBody    = $subject; // optional, comment out and test
	$body = $ci->load->view('sendmail.php',array('message'=>$message),true);
	$mail->MsgHTML($body);
	$mail->AddAddress($toemail, "");

	$ci->load->library('log');
	$ci->config->set_item('log_threshold', 1);
	try{

		if(!$mail->Send()) {
			$msg = urlencode("发送邮件失败，详细信息：".$mail->ErrorInfo);
			$ci->log->write_log('ERROR',$msg.PHP_EOL);
			return $msg;
		} else {
			$ci->log->write_log('INFO',"发送邮件激活邮件成功：{$toemail}");
			return true;
		}
	}catch(Exception $e){
		$msg = urlencode("发送邮件失败，详细信息：".$mail->ErrorInfo);
		$ci->log->write_log('ERROR',$msg.PHP_EOL);
		return $msg;
	}
}

/**
 * 写运行日志
 * @param string $file
 * @param string $log
 */
function runlog($file, $log, $halt=0) {
	global $_SGLOBAL, $_SERVER;

	$nowurl = $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
	$log = date('Y-m-d H:i:s')."\t$type\t".getonlineip()."\t$_SGLOBAL[supe_uid]\t{$nowurl}\t".str_replace(array("\r", "\n"), array(' ', ' '), trim($log))."\n";
	$yearmonth = date('Ym');
	$logdir = './data/log/';
	if(!is_dir($logdir)) mkdir($logdir, 0777);
	$logfile = $logdir.$yearmonth.'_'.$file.'.php';
	if(@filesize($logfile) > 2048000) {
		$dir = opendir($logdir);
		$length = strlen($file);
		$maxid = $id = 0;
		while($entry = readdir($dir)) {
			if(strexists($entry, $yearmonth.'_'.$file)) {
				$id = intval(substr($entry, $length + 8, -4));
				$id > $maxid && $maxid = $id;
			}
		}
		closedir($dir);
		$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.php';
		@rename($logfile, $logfilebak);
	}
	if($fp = @fopen($logfile, 'a')) {
		@flock($fp, 2);
		fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>', "\r", "\n"), '', $log)."\n");
		fclose($fp);
	}
	if($halt) exit();
}
/**
 * 获取在线IP
 * @param int $format
 * @return string
 */
function getonlineip($format=0) {
	global $_SGLOBAL;

	if(empty($_SGLOBAL['onlineip'])) {
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
		$_SGLOBAL['onlineip'] = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
	}
	if($format) {
		$ips = explode('.', $_SGLOBAL['onlineip']);
		for($i=0;$i<3;$i++) {
			$ips[$i] = intval($ips[$i]);
		}
		return sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
	} else {
		return $_SGLOBAL['onlineip'];
	}
}

/**
 * 产生随机字符
 * @param int $length
 * @return string
 */
function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' ? mt_srand((double)microtime() * 1000000) : mt_srand();
	$seed = base_convert(md5(print_r($_SERVER, 1).microtime()), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed[mt_rand(0, $max)];
	}
	return $hash;
}
