<?php

/**
 * 企业数据表
 */
class Company_model extends CU_Model{
	
	protected $_name = 'vitime_company';
	
	protected $_primary = 'id';
	
	/**
	 * 根据ID读取公司信息
	 * @param $id
	 */
	public function get($id){
		if(empty($id)){
			return array();
		}
		return $this->find($id);
		
	}
	
	/**
	 * 根据mark标识读取企业信息
	 * @param string $mark
	 */
	public function getCompanyByMark($mark){
		if(empty($mark)){
			return array();
		}
		
		return $this->fetchRow(array('company_mark'=>$mark));
	}
	
	/**
	 * 新增公司
	 * @param unknown_type $companyName
	 * @param unknown_type $companyMark
	 */
	public function newCompany($companyName,$companyMark){
		if(empty($companyName)){
			return 0;
		}
		$data = array('company_name'=>$companyName,'company_mark'=>$companyMark);
		return $this->insert($data);
	}	
}