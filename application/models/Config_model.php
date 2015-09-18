<?php

class Config_model extends CI_Model {
	const TABLE_NAME = 'config';
	const TABLE_KEY = 'id';
	static protected $_instance = NULL;
	static protected $_assoc_columns = array('key',
		'value',
		'status',
	);

	function __construct() {
		parent::__construct();
	}
	/**
	 *
	 * @return Config_model 
	 */
	static public function getInstance() {
		if (self::$_instance === NULL) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	function getRange($where='', $offset=0, $itemPerPage=15) {
		$offset = intval($offset);
		$limit = intval($itemPerPage);
		$whereClause = "";
		if(!empty($where)){
			$whereClause = " WHERE ".$where;
		}
		$sql = "SELECT * FROM ".self::TABLE_NAME.$whereClause." LIMIT $offset,$limit";
		$query = $this->db->query($sql);
        $data = $query->result_array();
		return $data;
	}
	function getTotal($where=''){
		$whereClause = "";
		if(!empty($where)){
			$whereClause = " WHERE ".$where;
		}
		$tableKey = self::TABLE_KEY;
		$sql = "SELECT COUNT($tableKey) AS total FROM ".self::TABLE_NAME;
        $sql .= $whereClause;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result[0]['total'];
	}
	function getById($id){
		$data = array();
		try {
			$sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE " . self::TABLE_KEY . " = ?";
			$query = $this->db->query($sql, array($id));
			$data = $query->result_array();
			if(isset($data[0])){
				$data = $data[0];
			}
		} catch (Exception $ex) {
		}
		return $data;
	}
  function getByKey($key){
		$data = array();
		try {
			$sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE `key` = ?";
			$query = $this->db->query($sql, array($key));
			$data = $query->result_array();
			if(isset($data[0])){
				$data = $data[0];
			}
		} catch (Exception $ex) {
		}
		return $data;
	}
	function getValue($key){
		$value = "";
		try {
			$sql = "SELECT value FROM " . self::TABLE_NAME . " WHERE `key` = ?";
			$query = $this->db->query($sql, array($key));
			$data = $query->result_array();
			if(isset($data[0]) && isset($data[0]['value'])){
				$value = $data[0]['value'];
			}
		} catch (Exception $ex) {
		}
		return $value;
	}
  function setValue($key, $value){
    $dbData = $this->getByKey($key);
    $params = array('key' => $key, 'value' => $value, 'status' => STATUS_SHOW);
    if($dbData!=""){
      $this->db->update(self::TABLE_NAME, $params, array(self::TABLE_KEY => $dbData['id']));
    }else{
      $this->db->insert(self::TABLE_NAME, $params);
    }
  }
	function insert($params) {
		try {
			$params = $this->filterProps($params);
			$this->db->insert(self::TABLE_NAME, $params);
			return $this->db->insert_id();
		} catch (Exception $ex) {
			return ERROR_SYSTEM;
		}
	}

	function update($id, $params) {
		try {
			$params = $this->filterProps($params);
			$this->db->update(self::TABLE_NAME, $params, array(self::TABLE_KEY => $id));
			return $id;
		} catch (Exception $ex) {
			return ERROR_SYSTEM;
		}
	}
	function delete($id){
		return $this->db->delete(self::TABLE_NAME, array(self::TABLE_KEY => $id)); 
	}
	
	private function filterProps($param) {
		if (!is_array($param))
			throw new Exception("Invalid input");
		$result = array();
		foreach (self::$_assoc_columns as $column)
			if (isset($param[$column]))
				$result[$column] = $param[$column];
		return $result;
	}

}