<?php

/**
 * 构造安全加密密码
 * @param string $username
 * @param string $password
 * @return string
 */
function make_password($username,$password){
	return md5(base64_encode($username).base64_encode($password).md5($password));
}