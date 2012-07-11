<?php
/**
 * 短信接口
 *
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-6-25
 */
class SMSWebservice {


	/**
	 * @var CI_Controller
	 */
	private $CI = null;

	private $config = array();
	/**
	 * @var SoapClient
	 */
	private $soap = null;
	/**
	 * @var SMSWebservice
	 */
	private static $_instance;

	private $username;
	private $password;

	public function __construct($config = null){
		$this->config = $config;
		$this->username = $config['username'];
		$this->password = $config['password'];
		$this->initSOAP();
		$this->CI =  &get_instance();
	}

	public static function  getInstance($config = null){
		if(!(self::$_instance instanceof SMSWebservice)){
			if(empty($config) || !is_array($config)){
				$config = config_item('sms');
			}
			self::$_instance = new self($config);
		}
		return self::$_instance;
	}

	protected function initSOAP(){
		$wsdl = $this->config['wsdl'];
		try{
			$this->soap = new SoapClient($wsdl,array('featrues'=>SOAP_USE_XSI_ARRAY_TYPE));
		}catch (Exception $e){
			$this->CI->log->write_log('ERROR',$e->getMessage());
			$this->soap = null;
		}
	}

	/**
	 * 发送短信
	 * @param int $uid 用户id
	 * @param int $mobile 接收号码可以是数组，也可以是逗号（,）分割的手机号码字符串
	 * @param string $content 内容
	 */
	public function send($uid,$mobile,$content){
		if(empty($mobile) || empty($content)){
			return $this->getResult(-2);
		}
		if(is_string($mobile) && strpos($mobile, ',')!==false){
			$mobile = explode(',', $mobile);
		}else{

		}
		if(is_array($mobile)){
			if(count(array_unique(array_filter($mobile)))>1){
				return $this->batchSend($uid, $mobile, $content);
			}else{
				$mobile = implode('', $mobile);
			}
		}

		try{
			$params = array(
				'in0'=>$this->username,
				'in1'=>$this->password,
				'in2'=>$mobile,
				'in3'=>$content,
				'in4'=>4
			);
			$rs = $this->soap->dealMsg($params);
		}catch(Exception $e){
			$this->CI->log->write_log('ERROR',$e->getMessage());
			return  $this->getResult(-10);
		}
		if($rs instanceof stdClass){
			$rs = $rs->out;
		}
		if($rs > 0){
			$this->saveSms($uid,$mobile,$rs,$content);
			return $rs;
		}
		return  $this->getResult($rs);
	}

	/**
	 * 群发送短信
	 * @param string $mobile
	 * @param string $content
	 */
	public function batchSend($uid,$mobiles,$content){
		if(empty($mobiles) || empty($content)){
			return $this->getResult(-2);
		}
		if(empty($this->soap)){
			return $this->getResult(-8);
		}
		$mobiles = (array)$mobiles;
		try{
			$params = array(
				'in0'=>$this->username,
				'in1'=>$this->password,
				'in2'=>$mobiles,
				'in3'=>$content,
				'in4'=>'',
				'in5'=>3
			);
//			$rs = $this->soap->dealBatchMsg($this->username,$this->password,$mobiles,$content,'',3);
			$rs = $this->soap->dealBatchMsg($params);
		}catch(Exception $e){
			$this->CI->log->write_log('ERROR',$e->getMessage());
			return  $this->getResult(-10);
		}
		if($rs instanceof stdClass){
			$rs = $rs->out;
		}
		if($rs > 0){
			$this->saveSms($uid,$mobiles,$content,$rs);
			return $rs;
		}
		return  $this->getResult($rs);

	}

	public function saveSms($uid,$mobiles,$content,$sid){
		$this->CI->load->model('user/Sms_send_model','SMSModel');
		if(is_array($mobiles)){
			$mobiles = implode(',', $mobiles);
		}
		$this->CI->SMSModel->newSMS($uid,$mobiles,$sid,$content,date('Y-m-d H:i:s'));
	}

	public function getResult($no){
		if($no > 0){
			return $no;
		}
		$no = strval($no);
		$rs = array(
		"0"=>"提交成功",
		"-1"=>"用户名或密码不存在",
		"-2"=>"内容为空",
		"-3"=>"内容太长（内容630字内）",
		"-4"=>"号码列表为空",
		"-5"=>"号码列表中包含不合法号码",
		"-6"=>"短信条数不足",
		"-7"=>"号码超过50000个",
		"-8"=>"接口服务未开启",
		"-10"=>"系统出错",
		"-11"=>"用户接口不存在或未激活",
		);
		return $rs[$no];
	}

}


?>