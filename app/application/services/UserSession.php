<?php
require_once SERVICE_DIR.'users/SysAdmin.php';
require_once SERVICE_DIR.'users/CmpAdmin.php';
require_once SERVICE_DIR.'users/CmpUser.php';
require_once SERVICE_DIR.'users/TouristUser.php';
/**
 * 用户会话管理
 *  
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-21
 */
class UserSession {
	
	const USER_TAG = 'log_user';
	private static $_user;
	
	/**
	 * 从session读取登录用户信息
	 * @return IUser user
	 */
	public static function getUser(){
		if(is_null(self::$_user)){
			self::$_user = unserialize($_SESSION[self::USER_TAG]);
			if(empty(self::$_user) || !(self::$_user instanceof IUser) || intval(self::$_user->id)<=0){
				self::setUser(new TouristUser());
				self::$_user = unserialize($_SESSION[self::USER_TAG]);
			}
		}		
		return self::$_user;
	}
	
	/**
	 * 存储登录用户信息到session
	 * @param IUser $user
	 * @return void
	 */
	public static function setUser(IUser $user = null){
		$_SESSION[self::USER_TAG] = serialize($user);
		self::$_user = $user;
	}
	
	/**
	 * 是否已经登录
	 * @return boolean
	 */
	public static function isLogin(){
		$user = self::getUser();
		if(!empty($user) && intval($user->id)>0){
			return true;
		}else{
			return false;
		}
	}

}

?>