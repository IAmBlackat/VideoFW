<?php

class Editor_Box_model extends CI_Model {

  const TABLE_NAME = 'editor_box';
  const TABLE_KEY = 'id';

  static protected $_instance = NULL;
  static protected $_assoc_columns = array(
    'key',
    'name',
    'description',
    'link'
  );

  function __construct() {
    parent::__construct();
  }

  /**
   *
   * @return Editor_Box_model
   */
  static public function getInstance() {
    if (self::$_instance === NULL) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  function getByKey($key){
    $ret = array();
    try {
      $sql = "SELECT e.*, et.id AS item_id, et.item_thumbnail, et.item_link, et.item_text
              FROM editor_box e LEFT JOIN editor_box_item et
                ON e.id=et.box_id WHERE `key` = ?";
      $query = $this->db->query($sql, array($key));
      $datas = $query->result_array();
      $items = array();
      if($datas){
        foreach($datas as $data){
          $ret['id'] = $data['id'];
          $ret['key'] = $data['key'];
          $ret['name'] = $data['name'];
          $ret['description'] = $data['description'];
          $ret['link'] = $data['link'];
          $item = array();
          if($data['item_id']){
            $item['id'] = $data['item_id'];
            $item['item_thumbnail'] = $data['item_thumbnail'];
            $item['item_link'] = $data['item_link'];
            $item['item_text'] = $data['item_text'];
            $items[$item['id']] = $item;
          }
        }
        $ret['items'] = $items;
      }
    } catch (Exception $ex) {
    }
    return $ret;
  }
  function getRange($where = '', $offset = 0, $itemPerPage = 15, $order='')
  {
    $orderBy = $order ? ' ORDER BY ' . $order : '';
    $offset = intval($offset);
    $limit = intval($itemPerPage);
    $whereClause = "";
    if (!empty($where)) {
      $whereClause = " WHERE " . $where;
    }
    $sql = "SELECT * FROM " . self::TABLE_NAME . $whereClause . $orderBy . " LIMIT $offset,$limit";
    $query = $this->db->query($sql);
    $data = $query->result_array();
    return $data;
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

  function delete($id) {
    return $this->db->delete(self::TABLE_NAME, array(self::TABLE_KEY => $id));
  }
  function getAll() {
    $sql = "SELECT * FROM " . self::TABLE_NAME ;
    $query = $this->db->query($sql);
    $datas = $query->result_array();
    $genres = array();
    foreach($datas as $data){
      $genres[$data['id']] = $data['name'];
    }
    return $genres;
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
