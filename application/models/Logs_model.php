<?php

class Logs_model extends CI_Model {
	const TABLE_NAME = 'logs';
	const TABLE_KEY = 'id';
	static protected $_instance = NULL;
	static protected $_assoc_columns = array(
			'element_id',
			'value',
      'type'
	);

	function __construct() {
		parent::__construct();
	}
	/**
	 *
	 * @return Content_model
	 */
	static public function getInstance() {
		if (self::$_instance === NULL) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	function getRange($where='', $offset=0, $itemPerPage=15, $orderBy="id DESC") {
		$offset = intval($offset);
		$limit = intval($itemPerPage);
		$whereClause = "";
		if(!empty($where)){
			$whereClause = " WHERE ".$where;
		}
		$sql = "SELECT * FROM ".self::TABLE_NAME.$whereClause." ORDER BY ".$orderBy." LIMIT $offset,$limit";
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
			return SUCCESSFULL;
		} catch (Exception $ex) {
			return ERROR_SYSTEM;
		}
	}
	function delete($id){
		return $this->db->delete(self::TABLE_NAME, array(self::TABLE_KEY => $id));
	}
	function deleteByElementId($elementId){
		$sql = "DELETE FROM " . self::TABLE_NAME . " WHERE element_id=".$elementId;
		$query = $this->db->query($sql);
	}
	function getLogsByVideo($elementId){		
		$whereClause = " WHERE element_id=".$elementId;
		$sql = "SELECT id, value FROM ".self::TABLE_NAME.$whereClause;
		$query = $this->db->query($sql);
		$data = $query->result_array();
		if(isset($data[0])){
			$data = $data[0];
		}
		return $data;
	}
	function writeLogs($elementId){
		$logs = $this->getLogsByVideo($elementId);
		if($logs){
			$logs['value'] = $logs['value']+1;
			$this->update($logs['id'], $logs);
		}else{
			$params['value'] = 1;
			$params['element_id'] = $elementId;
			$this->insert($params);
		}
	}
	function getTopVideo($limit=15){
		$data = array();
		try {
			$sql = "SELECT n.* FROM video n inner join " . self::TABLE_NAME . " l ON n.id=l.element_id ORDER BY l.value DESC LIMIT 0,$limit";
			$query = $this->db->query($sql);
			$data = $query->result_array();
		} catch (Exception $ex) {
		}
		return $data;
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