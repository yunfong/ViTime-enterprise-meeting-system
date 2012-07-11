<?php
session_start();
date_default_timezone_set("Asia/Shanghai");
/*
* 环境设置，默认开发环境
*/
define ( 'ENVIRONMENT', 'development' );
/*
 * 错误设置，不同环境会有不同设置 
 */

if (defined ( 'ENVIRONMENT' )) {
	switch (ENVIRONMENT) {
		case 'development' :
			error_reporting ( E_ALL^E_NOTICE );
			break;
		
		case 'testing' :
		case 'production' :
			error_reporting ( 0 );
			break;
		
		default :
			exit ( 'The application environment is not set correctly.' );
	}
}

/*** 项目目录 ***/
define ( 'PROJ_ROOT', str_replace ( "\\", "/", dirname ( dirname ( __FILE__ ) ) ) );

/*** 网站根目录 ***/
define ( 'WEBROOT', PROJ_ROOT . '/webroot' );

/*
 * Codeigniter 框架路径
 *
 */
$system_path = PROJ_ROOT . '/CodeIgniter';

/*
 * 应用程序路径
 */
$application_folder = PROJ_ROOT . '/application';

/*
 * 相关全局路径设置
 *
 */

// 针对cli模式的路径设置
if (defined ( 'STDIN' )) {
	chdir ( dirname ( __FILE__ ) );
}

//格式化框架系统路径
if (realpath ( $system_path ) !== FALSE) {
	$system_path = realpath ( $system_path ) . '/';
}
$system_path = rtrim ( $system_path, '/' ) . '/';

// 检查路径是否正确
if (! is_dir ( $system_path )) {
	exit ( "Your system folder path does not appear to be set correctly. Please open the following file and correct this: " . pathinfo ( __FILE__, PATHINFO_BASENAME ) );
}

/*
 * -------------------------------------------------------------------
 *  设置路径常量
 * -------------------------------------------------------------------
 */
// 入口文件名称
define ( 'SELF', pathinfo ( __FILE__, PATHINFO_BASENAME ) );

// PHP文件扩展名，该全局变量已经废弃
define ( 'EXT', '.php' );

// 系统框架基本路径
define ( 'BASEPATH', str_replace ( "\\", "/", $system_path ) );

// 前端控制器（该文件）路径
define ( 'FCPATH', str_replace ( SELF, '', __FILE__ ) );

// 系统框架目录名称
define ( 'SYSDIR', trim ( strrchr ( trim ( BASEPATH, '/' ), '/' ), '/' ) );

// 应用程序（application）目录路径
if (is_dir ( $application_folder )) {
	define ( 'APPPATH', $application_folder . '/' );
} else {
	if (! is_dir ( BASEPATH . $application_folder . '/' )) {
		exit ( "Your application folder path does not appear to be set correctly. Please open the following file and correct this: " . SELF );
	}
	
	define ( 'APPPATH', BASEPATH . $application_folder . '/' );
}


//服务层路径
define ( 'SERVICE_DIR', APPPATH.'/services/' );

define('SROOT','http://'.$_SERVER['HTTP_HOST'].'/');
/*
 * --------------------------------------------------------------------
 * 加载框架
 * --------------------------------------------------------------------
 *
 */
require_once BASEPATH . 'core/CodeIgniter.php';

/* End of file index.php */
/* Location: ./index.php */