<?php

class Series_model extends CI_Model {

  const TABLE_NAME = 'series';
  const TABLE_KEY = 'id';
  
  static protected $_instance = NULL;
  static protected $_assoc_columns = array(
    'title',
    'description',
    'country',
    'thumbnail',
    'release_date',
    'status',
    'is_complete',
    'original_url',
    'type'
  );

  function __construct() {
    parent::__construct();
  }

  /**
   *
   * @return Series_model
   */
  static public function getInstance() {
    if (self::$_instance === NULL) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  function getRange($where = '', $offset = 0, $itemPerPage = 0, $orderBy = "id DESC") {
    $offset = intval($offset);
    $limit = intval($itemPerPage);
    $whereClause = "";
    if (!empty($where)) {
      $whereClause = " WHERE " . $where;
    }
    $limitClause = $itemPerPage ? " LIMIT $offset,$limit" : "";
    $sql = "SELECT * FROM " . self::TABLE_NAME . $whereClause . " ORDER BY " . $orderBy . $limitClause;
    $query = $this->db->query($sql);
    $data = $query->result_array();
    return $data;
  }
  function listSeriesByGenre($genreId, $offset=0, $itemPerPage=0, $isRandom=FALSE, $exceptId=0){
    $data = array();
    if($genreId){
      $sql = "SELECT series.* FROM series
              INNER JOIN series_genre
              ON series.id=series_genre.series_id
            WHERE series_genre.genre_id={$genreId} AND series.id <> $exceptId";
      if($isRandom){
        $sql = $sql." ORDER BY RAND() ";
      }
      if($itemPerPage){
        $sql = $sql." LIMIT $offset, $itemPerPage";
      }
      $query = $this->db->query($sql);
      $data = $query->result_array();
    }
    return $data;
  }
  function getTotalSeriesByGenre($genreId) {
    $total = 0;
    if($genreId){
      $sql = "SELECT count(DISTINCT(series.id)) as total FROM series
                INNER JOIN series_genre
                ON series.id=series_genre.series_id
              WHERE series_genre.genre_id={$genreId}";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      $total = $result[0]['total'];
    }
    return $total;
  }
  function getRangeFull($where = '', $offset = 0, $itemPerPage = 15, $orderBy = "id DESC") {
    $offset = intval($offset);
    $limit = intval($itemPerPage);
    $whereClause = "";
    if (!empty($where)) {
      $whereClause = " WHERE " . $where;
    }
    $sql = "SELECT series.*, video.title  AS video_title, video.episode AS video_episode, video.id AS video_id FROM series "
      . "INNER JOIN video "
      . "ON video.series_id=series.id " . $whereClause . " GROUP BY series.id ORDER BY " . $orderBy . " LIMIT $offset,$limit";
    $query = $this->db->query($sql);
    $data = $query->result_array();
    return $data;
  }
  function getAllShort() {
    $sql = "SELECT id, title FROM " . self::TABLE_NAME ;
    $query = $this->db->query($sql);
    $datas = $query->result_array();
    $ret = array();
    foreach($datas as $data){
      $ret[$data['id']] = $data['title'];
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
      $sql = "SELECT * FROM " . self::TABLE_NAME . 
        " WHERE " . self::TABLE_NAME.'.'.self::TABLE_KEY . " = ?";
      
      $query = $this->db->query($sql, array($id));
      $datas = $query->result_array();
      if ($datas) {
        $data = $datas[0];
      }
    } catch (Exception $ex) {
      
    }
    return $data;
  }
  function getByIdFull($id) {
    $data = array();
    try {
      $sql = "SELECT s.*, g.id AS genre_id, g.name AS genre_name ".
        " FROM " . self::TABLE_NAME .' AS s'. 
        " LEFT JOIN series_genre AS sg ON s.id = sg.series_id".
        " LEFT JOIN genre AS g ON g.id = sg.genre_id ".
        " WHERE s.".self::TABLE_KEY . " = ?";
      
      $query = $this->db->query($sql, array($id));
      $datas = $query->result_array();
      if ($datas) {
        $genre = array();
        foreach($datas as $d){
          if($d['genre_id']){
            $genre[$d['genre_id']] = $d['genre_name'];
          }
        }
        $data = $datas[0];
        $data['genre'] = $genre;
      }
    } catch (Exception $ex) {
      
    }
    return $data;
  }
  function listSeriesByFirstChar($char, $offset = 0, $itemPerPage = 15){
    $datas = array();
    $limitClause = "";
    if($itemPerPage){
      $limit = intval($itemPerPage);
      $limitClause = " LIMIT $offset, $limit";
    }

    $alphas = range('A', 'Z');
    $strAlpha = implode("','", $alphas);
    $sql = '';


    if($char){
      if($char=='other'){
        $sql = "SELECT *, 'Other' AS firstchar FROM series WHERE LEFT(title, 1) NOT IN ('{$strAlpha}') ORDER BY release_date DESC {$limitClause}";
      }else{
        $sql = "SELECT *, UPPER(LEFT(title, 1)) AS firstchar  FROM series WHERE LEFT(title, 1) = '{$char}' ORDER BY release_date DESC ".$limitClause;
      }
    }
    try {
      $query = $this->db->query($sql);
      $datas = $query->result_array();
    } catch (Exception $ex) {

    }
    return $datas;
  }
  function totalSeriesByFirstChar($char){
    $total = 0;

    $alphas = range('A', 'Z');
    $strAlpha = implode("','", $alphas);
    $sql = '';


    if($char){
      if($char=='other'){
        $sql = "SELECT COUNT(1) AS total FROM series WHERE LEFT(title, 1) NOT IN ('{$strAlpha}')";
      }else{
        $sql = "SELECT COUNT(1) AS total  FROM series WHERE LEFT(title, 1) = '{$char}'";
      }
    }
    try {
      $query = $this->db->query($sql);
      $result = $query->result_array();
      $total = $result[0]['total'];
    } catch (Exception $ex) {

    }
    return $total;
  }
  function listSeriesGroupByFirstChar($limit=NULL){
    $ret = array();
    $limitClause = "";
    if($limit){
      $limit = intval($limit);
      $limitClause = " LIMIT {$limit}";
    }

    $alphas = range('A', 'Z');
    $strAlpha = implode("','", $alphas);
    $sql = '';
    foreach($alphas as $key => $char){
      if($key != 0 && $key < count($alphas)){
        $sql .= " UNION (SELECT *, UPPER(LEFT(title, 1)) AS firstchar FROM series WHERE LEFT(title, 1) = '{$char}' ORDER BY release_date DESC {$limitClause})";
      }else{
        $sql .= " (SELECT *, UPPER(LEFT(title, 1)) AS firstchar FROM series WHERE LEFT(title, 1) = '{$char}' ORDER BY release_date DESC {$limitClause})";
      }
    }
    $sql .= " UNION (SELECT *, 'Other' AS firstchar FROM series WHERE LEFT(title, 1) NOT IN ('{$strAlpha}') ORDER BY release_date DESC {$limitClause})";

    try {
      $query = $this->db->query($sql);
      $datas = $query->result_array();
      if ($datas) {
        foreach($datas as $data){
          $ret[$data['firstchar']][] = $data;
        }
      }
    } catch (Exception $ex) {

    }
    return $ret;
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
  function getGenreIdBySeriesId($seriesId){
    $data = array();
    try {
      $sql = "SELECT genre_id FROM series_genre WHERE series_id = ?";
      $query = $this->db->query($sql, array($seriesId));
      $datas = $query->result_array();
      if ($datas) {
        foreach($datas as $d){
          $data[] = $d['genre_id'];
        }
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
