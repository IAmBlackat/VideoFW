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
    $sql = "SELECT video.*, series.title as series_title, series.thumbnail as series_thumbnail, series.description as series_desctiption
      FROM video LEFT JOIN series 
        ON video.series_id = series.id ".
      $whereClause . $orderBy ." LIMIT $offset,$limit";
    $query = $this->db->query($sql);
    $data = $query->result_array();
    return $data;
  }
  function getTotalFull($where=''){
    $sql = "SELECT COUNT(DISTINCT (video.id)) AS total
      FROM video LEFT JOIN series
        ON video.series_id = series.id ";
    $sql = $sql.' WHERE '.$where;
    $query = $this->db->query($sql);
    $result = $query->result_array();
    return $result[0]['total'];
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
      $sql = "SELECT v.*, vu.id as vuid, vu.streaming_url, vu.type, vu.is_part, vu.iframe_url, vu.server_type, vu.video_id ".
        " FROM " . self::TABLE_NAME . ' AS v '. 
        " LEFT JOIN video_url AS vu ON v.id=vu.video_id".
        " WHERE v." . self::TABLE_KEY . " = ?";
      $query = $this->db->query($sql, array($id));
      $datas = $query->result_array();
      if ($datas) {
        $videoUrl = array();
        foreach($datas as $d){
          if($d['vuid']){
            $videoUrl[$d['vuid']] = array(
              'id' => $d['vuid'],
              'streaming_url' => $d['streaming_url'],
              'type' => $d['type'],
              'server_type' => $d['server_type'],
              'iframe_url' => $d['iframe_url'],
              'video_id' => $d['video_id'],
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
  function getNewestVideo($totalItem=12, $videoType=VIDEO_TYPE_DRAMA){

    $sql = "SELECT series.* FROM series "
      . "INNER JOIN video "
      . "ON video.series_id=series.id WHERE series.status=".STATUS_SHOW. " AND series.type=".$videoType." GROUP BY series_id ORDER BY video.publish_date DESC LIMIT 0,$totalItem";

    $query = $this->db->query($sql);
    $datas = $query->result_array();
    $videoDatas = array();
    if($datas){
      $seriesData = array();
      $strQueryVideo = '';
      foreach($datas as $key => $data){
        $seriesData[$data['id']] = $data;
        $strQueryVideo .= " (SELECT * FROM video WHERE series_id={$data['id']} ORDER BY episode DESC LIMIT 1) ";
        if($key<count($datas)-1){
          $strQueryVideo .= "UNION ";
        }
      }
      $videoDatas = $this->db->query($strQueryVideo)->result_array();
      if($videoDatas){
        foreach($videoDatas as $k => $videoData){
          $videoDatas[$k]['series'] = $seriesData[$videoData['series_id']];
        }
      }
    }
    return $videoDatas;
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
    $result = $this->db->update(self::TABLE_NAME, $params, array(self::TABLE_KEY => $videoId));
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
