<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {
	/**
	 * @var CI_Loader
	 */
	public $load;

	/**
	 * @var CI_Calendar
	 */
	public $calendar;
	
	/**
	 * @var Email
	 */
	public $email;
	
	/**
	 * @var CI_Encrypt
	 */
	public $encrypt;
	
	/**
	 * @var CI_Ftp
	 */
	public $ftp;
	
	/**
	 * @var CI_Hooks
	 */
	public $hooks;
	
	/**
	 * @var CI_Image_lib
	 */
	public $image_lib;
	
	/**
	 * @var CI_Language
	 */
	public $language;
	
	/**
	 * @var CI_Log
	 */
	public $log;
	
	/**
	 * @var CI_Output
	 */
	public $output;
	
	/**
	 * @var CI_Pagination
	 */
	public $pagination;
	
	/**
	 * @var CI_Parser
	 */
	public $parser;
	
	/**
	 * @var CI_Session
	 */
	public $session;
	
	/**
	 * @var CI_Sha1
	 */
	public $sha1;
	
	/**
	 * @var CI_Table
	 */
	public $table;
	
	/**
	 * @var CI_Trackback
	 */
	public $trackback;
	
	/**
	 * @var CI_Unit_test
	 */
	public $unit;
	
	/**
	 * @var CI_Upload
	 */
	public $upload;
	
	/**
	 * @var CI_URI
	 */
	public $uri;
	
	/**
	 * @var CI_User_agent
	 */
	public $agent;
	
	/**
	 * @var CI_Validation
	 */
	public $validation;
	
	/**
	 * @var CI_Xmlrpc
	 */
	public $xmlrpc;
	
	/**
	 * @var CI_Zip
	 */
	public $zip;
	
	/**
	 * @var CI_Benchmark
	 */
	public $benchmark;
	
	/**
	 * @var CI_Cart
	 */
	public $cart;
	
	/**
	 * @var CI_Config
	 */
	public $config;
	
	/**
	 * @var CI_Input
	 */
	public $input;
	
	/**
	 * @var CI_Router
	 */
	public $router;
	
	private static $instance;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::$instance =& $this;
		
		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');

		$this->load->initialize();
		
		log_message('debug', "Controller Class Initialized");
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
}
// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */