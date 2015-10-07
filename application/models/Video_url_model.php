<?php

class Video_Url_model extends CI_Model {

  const TABLE_NAME = 'video_url';
  const TABLE_KEY = 'id';
  
  const RELATED_TABLE = 'video';
  const RELATED_TABLE_KEY = 'video_id';

  static protected $_instance = NULL;
  static protected $_assoc_columns = array(
    'video_id',
    'streaming_url',
    'type',
    'server_type',
    'iframe_url',
    'is_part',
  );

  function __construct() {
    parent::__construct();
  }

  /**
   *
   * @return Video_Url_model 
   */
  static public function getInstance() {
    if (self::$_instance === NULL) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  function getRange($where = '', $offset = 0, $itemPerPage = 15) {
    $offset = intval($offset);
    $limit = intval($itemPerPage);
    $whereClause = "";
    if (!empty($where)) {
      $whereClause = " WHERE " . $where;
    }
    $sql = "SELECT * FROM " . self::TABLE_NAME . $whereClause . " LIMIT $offset,$limit";
    $query = $this->db->query($sql);
    $data = $query->result_array();
    return $data;
  }
  function getAllVideoUrlByVideoId($videoId){
    $ret = array();
    try {
      $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE video_id = ?";
      $query = $this->db->query($sql, array($videoId));
      $datas = $query->result_array();
      if($datas){
        foreach($datas as $data){
          $ret[$data['server_type']] = $data;
        }
      }
    } catch (Exception $ex) {

    }
    return $ret;
  }
  function getTotal($where = '') {
    $whereClause = "";
    if (!empty($where)) {
      $whereClause = " WHERE " . $where;
    }
    $tableKey = self::TABLE_KEY;
    $sql = "SELECT COUNT($tableKey) AS total FROM " . self::TABLE_NAME;
    $sql .= $whereClause;
    $query = $this->db->query($sql);
    $result = $query->result_array();
    return $result[0]['total'];
  }

  function getById($id) {
    $data = array();
    try {
      $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE " . self::TABLE_KEY . " = ?";
      $query = $this->db->query($sql, array($id));
      $data = $query->result_array();
      if (isset($data[0])) {
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
      return $id;
    } catch (Exception $ex) {
      return ERROR_SYSTEM;
    }
  }
  function deleteByVideoId($videoId){
    return $this->db->delete(self::TABLE_NAME, array(self::RELATED_TABLE_KEY => $videoId));
  }

  function delete($id) {
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
