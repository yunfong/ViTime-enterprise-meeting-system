<?php

echo makePwd('gray','123456');

function makePwd($username,$password){
	return md5(base64_encode($username).base64_encode($password).md5($password));
}