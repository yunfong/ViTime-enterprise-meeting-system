<?php
/**
 * abstract Cu_Model
 * @package libraries 
 * @desc Model类的扩展，补充了一些常用操作方法。
 * @version v1.0
 * @author Gray.Liu
 * @since 2010-9-3
 * @copyright gaoomei@foxmail.com 
 *
 */
class CU_Model extends CI_Model{

	/**
	 * @var CI_DB_active_record
	 */
	protected $db;
	
	/**
	 * 表名
	 * @var string
	 */
	protected $_name;
	
	/**
	 * 主键名
	 * @var string
	 */
	protected $_primary;
	
	/**
	 * 数据表前缀
	 * @var string
	 */
	protected $_dbprefix;
	
	/**
	 * 构造函数
	 * @param array $config 连接配置
	 */
	public function __construct($config = ''){
		unset($this->db);//调试
		parent::__construct();
		$this->connect($config);
	}
	
	public function _afterConnect(){
		if(!empty($this->_dbprefix)){
			$this->db->dbprefix = $this->_dbprefix;
		}else{
			$this->db->dbprefix = config_item('sub_domain').'_';
		}
	}
	
	/**
	 * 魔术方法，用于截获未定义的方法
	 * @param string $method 方法名
	 * @param array $args 参数列表
	 */
	public function __call($method, $args)  
    {  
    	if(!method_exists($this->db,$method)){
    		show_error('Not found method:'.$method.' in db');
    	}
    	return call_user_func_array(array($this->db,$method),$args);
    }  
    
	/**
	 * 链接数据库
	 * @param mixed $config 链接参数
	 * @return void|object 
	 */
	protected function connect($config = NULL){
		return $this->load->database($config);
	}
	
	/**
	 * 查询所有数据
	 * @param int $limit 数量
	 * @param int $offset 偏移量
	 * @return object $data 返回一个active record类型的对象
	 */
	protected function all($limit = null, $offset = null){
		$data = $this->db->get($this->_name,$limit,$offset);
		return $data;
	}
	
	/**
	 * 统计
	 * @param 条件 $where
	 */
	public function count($where){
		return $this->where($where)->count_all_results($this->_name);
	}
	
	/**
	 * 分页读取数据
	 * @param string $cols 字段
	 * @param array|string $where 条件
	 * @param array|string $order 排序字段
	 * @param int $limit 数量
	 * @param int $offset 偏移量
	 * @return array array('data'=>数据,'total'=>数据总数,'totalpage'=>总页数,'count'=>本次查询结果数量)
	 */
	public function selectByPage($cols = '*',$where = NULL,$order = NULL,$limit = NULL,$offset = NULL){
		$this->db->select($cols);
		$this->where($where)->limit($limit,$offset);
		if(!empty($order)){
			$this->db->order_by($order);
		}
		$result = $this->db->get($this->_name);
		$total = $this->where($where)->count_all_results($this->_name);
		$pagn = array(
			'data' => $result->result_array(),
			'total'=>$total,
			'totalpage'=>empty($limit)?0:ceil($total/$limit),
			'count'=> $result->num_rows(),
			'page'=>empty($limit)?1:round($offset / $limit) + 1,
			'limit'=>$limit
		);
		$result->free_result();
		return $pagn;
	}
	
	/**
	 * 插入数据
	 * @param array $set 数据集合(key/value)
	 * @return int $insert_id 如果插入的表有自动增长主键，则返回增长后的值，否则返回影响记录条数
	 */
	public function insert(array $set){
		$this->db->insert($this->_name,$set);
		$insert_id = $this->db->insert_id();
		if($insert_id<=0){
			return $this->db->affected_rows();
		}else{
			return $insert_id;
		}
	}
	
	/**
	 * 更新数据
	 * @param array $set 数据集合(key/value)
	 * @param array|string $where 条件
	 * @return int $effct_rows_count 修改到的记录条数
	 */
	public function update(array $set,$where){
		$this->where($where);
		$this->db->update($this->_name,$set);
		return $this->db->affected_rows();
	}
	
	/**
	 * 删除数据
	 * @param array|string $where 条件
	 * @param int $limit 删除数量限制，默认不限制
	 * @return int $delete_count 删除的记录数
	 */
	public function delete($where,$limit = NULL){
		if(!is_null($limit)&&is_numeric($limit)){
			$this->where($where);
			if(is_numeric($limit)){
				$this->db->limit($limit);
			}
			$this->db->delete($this->_name);
			return $this->db->affected_rows();
		}
		$this->db->delete($this->_name,$where);
		return $this->db->affected_rows();
	}
	
	/**
	 * 读取一行数据
	 * @param array|string $where 条件
	 * @param string $order 排序字段
	 * @return array
	 */
	public function fetchRow($where,$order = NULL){
		$this->where($where);
		if(!empty($order)){
			$this->db->order_by($order);
		}
		return $this->db->get($this->_name)->row_array();
	}
	
	/**
	 * 批量读取数据
	 * @param array|string $where 条件
	 * @param string $order 排序字段
	 * @param int $limit 查询数量
	 * @param int $offset 查询起始偏移量
	 * @return array
	 */
	public function fetchAll($cols = '*',$where,$order,$limit = NULL,$offset = NULL){
		$this->db->select($cols);
		$this->where($where);
		if(!empty($order)){
			$this->db->order_by($order);
		}
		return $this->db->get($this->_name,$limit,$offset)->result_array();
	}
	
	/**
	 * 查询记录:该方法需要设置_primary的值
	 * @param mixed $priValue 主键值
	 * @return array
	 */
	public function find($priValue){
		if($this->_primary){
			return $this->where($this->_primary,$priValue)->get($this->_name)->row_array();
		}
		return array();
	}
	
	/**
	 * 重写where
	 * @param mixed $where
	 * return CU_Model $this->db
	 */
	protected function where(){
		$args = func_get_args();
		if(is_array($args[0])){
			foreach($args[0] as $k=>$wh){
				if(is_array($wh)){
					$this->db->where_in($k,$wh);
				}else{
					$this->db->where($k,$wh);
				}
			}
		}else{
			if(empty($args[0])){
				return $this->db;
			}
			call_user_func_array(array($this->db,'where'), $args);
		}
		return $this->db;
	}	

}
