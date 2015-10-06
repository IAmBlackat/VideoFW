<?php

class Video_model extends CI_Model {

  const TABLE_NAME = 'video';
  const TABLE_KEY = 'id';

  static protected $_instance = NULL;
  static protected $_assoc_columns = array(
    'title',
    'description',
    'tags',
    'thumbnail',
    'series_id',
    'original_url',
    'status',
    'publish_date',
    'has_sub',
    'episode',
    'import_status',
  );

  function __construct() {
    parent::__construct();
  }

  /**
   *
   * @return Video_model 
   */
  static public function getInstance() {
    if (self::$_instance === NULL) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  function getRange($where = '', $offset = 0, $itemPerPage = 0, $order='') {
    $orderBy = $order ? ' ORDER BY '.$order : '';
    $offset = intval($offset);
    $limit = intval($itemPerPage);
    $whereClause = "";
    if (!empty($where)) {
      $whereClause = " WHERE " . $where;
    }
    $limitClause = $itemPerPage ? " LIMIT $offset,$limit" : "";
    $sql = "SELECT * FROM " . self::TABLE_NAME . $whereClause .$orderBy.$limitClause;
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
    $sql = "SELECT video.*, series.title as series_title, series.thumbnail as series_thumbnail 
      FROM video LEFT JOIN series 
        ON video.series_id = series.id ".
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
  function getNewVideoOfSeries($seriesId, $limit=1){
    $data = array();
    try {
      $sql = "SELECT * FROM " . self::TABLE_NAME ." WHERE series_id={$seriesId} ORDER BY episode DESC LIMIT {$limit}";
      $query = $this->db->query($sql);
      $data = $query->result_array();
    } catch (Exception $ex) {

    }
    return $data;
  }
  function getByIdFull($id) {
    $data = array();
    try {
      $sql = "SELECT v.*, vu.id as vuid, vu.streaming_url, vu.type, vu.is_part, vu.iframe_url, vu.server_type ".
        " FROM " . self::TABLE_NAME . ' AS v '. 
        " LEFT JOIN video_url AS vu ON v.id=vu.video_id".
        " WHERE v." . self::TABLE_KEY . " = ?";
      $query = $this->db->query($sql, array($id));
      $datas = $query->result_array();
      if ($datas) {
        $videoUrl = array();
        foreach($datas as $d){
          $videoUrl[$d['vuid']] = array(
            'id' => $d['vuid'],
            'streaming_url' => $d['streaming_url'],
            'type' => $d['type'],
            'server_type' => $d['server_type'],
            'iframe_url' => $d['iframe_url'],
            'is_part' => $d['is_part']
            );
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
