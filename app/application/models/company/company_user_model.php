<?php

/**
 * 企业用户表
 *  
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-21
 */
class Company_user_model extends CU_Model {

	protected $_name = 'vitime_member' ;
	protected $_primary= 'id' ;
	
	/**
	 * 读取用户信息
	 * @param string $username
	 * @param int $companyId
	 * @return array
	 */
	public function getUser($username,$companyId){
		if(empty($username) || empty($companyId))
		{
			return array();
		}
		$where = array('username'=>$username,'company_id'=>$companyId);
		return $this->fetchRow($where);
	}
	
	/**
	 * @param int $user_id
	 * @param int $cmp_id
	 */
	public function getUserById($user_id,$cmp_id=0){
		if(empty($user_id))
		{
			return array();
		}
		$where = array('id'=>$user_id);
		if($cmp_id){
			$where['company_id'] = intval($cmp_id);
		}
		return $this->fetchRow($where);
	}
	
	/**
	 * 读取用户信息
	 * @param string $username
	 */
	public function getUserByName($username){
		if(empty($username)){
			return array();
		}
		$where = array('username'=>$username);
		return $this->fetchRow($where);
	}
	
	/**
	 * 新增用户
	 * @param IUser $user
	 * @return int 
	 */
	public function newUser(IUser $user){
		$data = $user->toArray();
		if(empty($data)){
			return 0;
		}
		return $this->insert($data);
	}
	
	/**
	 * 读取企业用户列表
	 * @param int $cmpId 企业id
	 */
	public function getUserListByCmpId($cmpId,$page = 1, $limit = 10){
		if(empty($cmpId)){
			return array();
		}
		$page = max(intval($page),1);
		$offset = ($page - 1)*$limit;
		
		$where = array('status'=>'1');
		return $this->selectByPage('*', $where,'id desc',$limit,$offset);
	}
	
	/**
	 * 读取用户列表，排除管理员
	 * @param int $cmpId
	 * @param int $page
	 * @param int $limit
	 */
	public function getCompanyUserListNotAdmin($cmpId,$page = 1, $limit = 10){
		if(empty($cmpId)){
			return array();
		}
		$page = max(intval($page),1);
		$offset = ($page - 1)*$limit;
		
		$where = array('company_id'=>$cmpId,'status'=>'1','priority'=>2);
		return $this->selectByPage('*', $where,'id desc',$limit,$offset);
	}
	
	
	/**
	 * 列出所有企业管理员
	 * @param int $page
	 * @param int $limit
	 * @return array
	 */
	public function getCmpAdminList($page = 1 ,$limit = 10){
		$page = max(intval($page),1);
		$offset = ($page - 1)*$limit;
		$where = array('M.status'=>1,'M.priority'=>1);
		$total = $this->where($where)->count_all_results($this->_name.' AS M');
		if($total == 0){
			return array();
		}
		
		$result = $this->db->select("M.*,C.company_name,C.company_mark,C.onTry,C.tryDate ",FALSE)
			->join('vitime_company AS C', "M.company_id=C.id",'LEFT')
			->where($where)->order_by('M.id','desc')
			->get($this->_name.' AS M',$limit,$offset);
//		$sql = "SELECT M.*,C.company_name,C.company_mark from {$this->_name} as M 
//				left join vitime_company as C on M.company_id=C.id where {$where}
//				order by M.id desc limit {$offset},{$limit}";
		
//		$this->db->select($sql, FALSE); 
//		$result = $this->db->get($this->_name);
		$pagn = array(
			'data' => $result->result_array(),
			'total'=>$total,
			'totalpage'=>ceil($total/$limit),
			'count'=> $result->num_rows(),
			'page'=>$page,
			'limit'=>$limit
		);
		$result->free_result();
		return $pagn;
	}
	
	/**
	 * 读取企业所有用户
	 * @param string $cols
	 * @param int $company_id
	 * @param int $limit
	 */
	public function getAllUser($cols='*',$company_id,$limit = 0){
		if(empty($company_id)){
			return array();
		}
		$where = array('company_id'=>$company_id,'status'=>1);
		if(!is_numeric($limit) || $limit == 0){
			$limit = null;
		}
		return $this->fetchAll($cols, $where,'id desc',$limit);
	}
	/**
	 * 修改用户
	 * @param int $user_id
	 * @param int $data
	 */
	public function updateUser($user_id,$data){
		if(empty($user_id) || empty($data)){
			return 0;
		}
		$where = array('id'=>$user_id);
		return $this->update($data, $where);
	}
	/**
	 * 修改用户余额
	 * @param int $user_id
	 * @param int $money
	 */
	public function updateUserMoney($user_id,$money){
		if(empty($user_id) || empty($money)){
			return 0;
		}
		if($money>=0)
			$money = '+'.$money;
		$sql = "Update vitime_member Set v_money = v_money $money Where id = $user_id";
		return $this->query($sql);
	}
	/**
	 * 删除用户
	 * @param int $user_id
	 * @param int $cmpId
	 */
	public function deleteUser($user_id,$cmpId){
		if(empty($user_id) || empty($cmpId)){
			return 0;
		}
		
		$data = array('status'=>0);
		$where = array('id'=>$user_id,'company_id'=>$cmpId);
		return $this->update($data, $where);
	}
	
	/**
	 * 扣除费用
	 * @param int $uid
	 */
	public function chargeback($uid,$vMoney){
		if(empty($uid) || !is_numeric($uid) || !is_numeric($vMoney) || floatval($vMoney)<0){
			return false;
		}
		
		$sql = "update {$this->_name} set v_money = v_money - {$vMoney} where id={$uid}";
		return $this->db->query($sql);
	}
	
	/**
	 * 获取企业管理员
	 * @param int $cmp_id
	 */
	public function getCompanyAdmin($cmp_id){
		if(empty($cmp_id)){
			return array();
		}
		return $this->fetchRow(array('company_id'=>$cmp_id,'priority'=>1));
	}
}

?>