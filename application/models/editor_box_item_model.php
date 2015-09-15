<?php

class Editor_Box_Item_model extends CI_Model {

  const TABLE_NAME = 'editor_box_item';
  const TABLE_KEY = 'id';

  static protected $_instance = NULL;
  static protected $_assoc_columns = array(
    'box_id',
    'item_text',
    'item_link',
    'item_thumbnail'
  );

  function __construct() {
    parent::__construct();
  }

  /**
   *
   * @return Editor_Box_Item_model
   */
  static public function getInstance() {
    if (self::$_instance === NULL) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  function getRange($where = '', $offset = 0, $itemPerPage = 15, $order='') {
    $orderBy = $order ? ' ORDER BY '.$order : '';
    $offset = intval($offset);
    $limit = intval($itemPerPage);
    $whereClause = "";
    if (!empty($where)) {
      $whereClause = " WHERE " . $where;
    }
    $sql = "SELECT * FROM " . self::TABLE_NAME . $whereClause .$orderBy. " LIMIT $offset,$limit";
    $query = $this->db->query($sql);
    $data = $query->result_array();
    return $data;
  }
  function getRangeFull($where = '', $offset = 0, $itemPerPage = 15, $order='') {
    $orderBy = $order ? ' ORDER BY '.$order : '';
    $offset = intval($offset);
    $limit = intval($itemPerPage);
    $whereClause = "";
    if (!empty($where)) {
      $whereClause = " WHERE " . $where;
    }
    $sql = "SELECT editor_box_item.*, editor_box.name as box_name, editor_box.id as box_id
      FROM editor_box_item LEFT JOIN editor_box
        ON editor_box_item.box_id = editor_box.id ".
      $whereClause . $orderBy ." LIMIT $offset,$limit";
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
  function getByIdFull($id) {
    $data = array();
    try {
      $sql = "SELECT v.*, vu.id as vuid, vu.streaming_url, vu.type, vu.is_part ".
        " FROM " . self::TABLE_NAME . ' AS v '. 
        " LEFT JOIN video_url AS vu ON v.id=vu.video_id".
        " WHERE v." . self::TABLE_KEY . " = ?";
      $query = $this->db->query($sql, array($id));
      $datas = $query->result_array();
      if ($datas) {
        $videoUrl = array();
        foreach($datas as $d){
          if($d['streaming_url']){
            $videoUrl[$d['vuid']] = array(
              'id' => $d['vuid'],
              'streaming_url' => $d['streaming_url'],
              'type' => $d['type'],
              'is_part' => $d['is_part']
              );
          }
        }
        $data = $datas[0];
        $data['video_url'] = $videoUrl;
      }
    } catch (Exception $ex) {
      
    }
    return $data;
  }
  function getByTitle($title){
    $title = strtolower($title);
    $data = array();
    try {
      $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE LOWER(title) = ?";
      $query = $this->db->query($sql, array($title));
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
  function updateImportStatus($importStatus=0){
    $query = "UPDATE ".self::TABLE_NAME." SET import_status={$importStatus}";
    $this->db->query($query);
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
  function addVideoToSerie($arrVideoIds, $serieId){
		if($arrVideoIds){
			$params['series_id'] = $serieId;
			foreach($arrVideoIds as $videoId){
        $this->db->update(self::TABLE_NAME, $params, array(self::TABLE_KEY => $videoId));
			}
		}
	}
  function removeSeriesOfVideo($videoId){
    $params['series_id'] = 0;
		$this->db->update(self::TABLE_NAME, $params, array(self::TABLE_KEY => $videoId));
		return $result;
  }
  function getVideoByOriginalUrl($originalUrl=""){
		$data = array();
		if(!empty($originalUrl)){
			$originalUrl = stripslashes($originalUrl);
			$whereClause = ' WHERE original_url="'.$originalUrl.'"';
			$sql = "SELECT * FROM ".self::TABLE_NAME.$whereClause;
			$query = $this->db->query($sql);
			$data = $query->result_array();
			if(isset($data[0])){
				$data = $data[0];
			}
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
