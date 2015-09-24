<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class ajax extends MY_Controller {

  public $_config = NULL;
  public $Video_model = NULL;

  function __construct() {
    parent::__construct();
    $this->load->model('Video_model', NULL, TRUE);
    $this->Video_model = Video_model::getInstance();
    $this->_config = $this->config->config;
  }

  public function index($page = 'index') {
    die("file not found");
  }

  public function show($page = "show") {
    
  }

  //dung cho admin
  public function search_video() {
    $keyword = isset($_POST['keyword']) ? strip_tags($_POST['keyword']) : '';
    $data = array();
    if ($keyword) {
      $where = "title LIKE '%" . $keyword . "%'";
      $rs = $this->Video_model->getRange($where, 0, 100);
      if (!empty($rs)) {
        foreach ($rs as $key => $r) {
          $tmp = array();
          $tmp['id'] = $r['id'];
          $tmp['title'] = $r['title'];
          $tmp['link'] = "/admin_video/show?id=" . $tmp['id'];
          $data[] = $tmp;
        }
      }
    }
    echo json_encode($data);
  }

  public function add_video_to_serie() {
    $videoIds = isset($_POST['videoIds']) ? $_POST['videoIds'] : 0;
    $serieId = isset($_POST['serieId']) ? intval($_POST['serieId']) : 0;
    $data = array();
    if (!empty($videoIds) && !empty($serieId)) {
      $arrVideoIds = explode(",", $videoIds);
      $this->Video_model->addVideoToSerie($arrVideoIds, $serieId);
    }
  }

  function remove_video_of_series() {
    $videoId = isset($_GET['video_id']) ? intval($_GET['video_id']) : 0;
    $seriesId = isset($_GET['series_id']) ? intval($_GET['series_id']) : 0;
    if ($videoId && $seriesId) {
      $result = $this->Video_model->removeSeriesOfVideo($videoId);
      $url = base_url() . 'admin_series/show?id=' . $seriesId;
      redirect($url);
    }
  }

  public function logs() {
    $strReturn = '';
    $logsModel = null;
    $this->load->model('Logs_model', NULL, TRUE);
    $logsModel = new Logs_model();
    $elementId = isset($_POST['element_id']) ? intval($_POST['element_id']) : 0;
    $type = isset($_POST['type']) ? intval($_POST['type']) : 'video';
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    if($status==0){
      $this->load->library('simple_html_dom');
      $this->load->file(APPPATH.'components/ImportDramaCool.php');
      $dramaCool = new ImportDramaCool();
      $videoObj = $this->Video_model->getById($elementId);
      if($videoObj){
        $r = $dramaCool->updateStreaming($videoObj['id'], $videoObj['original_url'], false);
        if($r){
          $strReturn = 'updated';
        }
      }
    }else{
      $strReturn = 'playing';
    }
    if ($elementId) {
      $logsModel->writeLogs($elementId, $type);
    }
    echo $strReturn;
  }

  public function load_sidebar() {
    $this->load->driver('cache');
    //$this->cache->file->save('foo', 'bar', 1);
    $foo = $this->cache->get('foo');
    echo $foo;
    $data['html'] = "";
    $data['errorCode'] = 0;
    $this->load->file(APPPATH . 'business/video_business.php');
    $_bus = NewsBusiness::getInstance();
    $genreId = isset($_POST['genre_id']) ? intval($_POST['genre_id']) : 0;
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $genreType = isset($_POST['genre_type']) ? $_POST['genre_type'] : '';
    $showType = isset($_POST['show_type']) ? intval($_POST['show_type']) : "item"; //item or link
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : ITEM_PER_PAGE;
    switch ($type) {
      case 'newest':
        $data['errorCode'] = 1;
        $cacheName = "_newest.";
        if (!empty($genreId)) {
          $cacheName .= 'gid.' . $genreId;
          $newest = $_bus->listNewsByGenre($genreId, $offset, $length); //lay trang tiep theo de suggest
          $title = "CÙNG CHUYÊN MỤC";
          if ($genreType) {
            $cacheName .= '.gtype.' . $genreType;
            $title = "CHUYÊN MỤC " . mb_strtoupper($this->_config['genre'][$genreId]);
          }
        } else {
          $cacheName .= 'gid.home';
          $title = "MỚI NHẤT";
          $newest = $_bus->getNewstNews();
        }
        if ($showType == 'link') {
          $cacheName .= '.link';
          $title = "CÓ THỂ BẠN QUAN TÂM";

          $html = $this->getCacheHtml($cacheName); //lay trong cache file
          if (empty($html)) {
            $data['cached'] = false;
            $html = $this->load->view("blocks/suggest/newest_link", array('datas' => $newest, 'title' => $title), true);
            //set lai cache file
            $this->setCacheHtml($cacheName, $html);
          }
        } else {
          $cacheName .= '.item';
          $html = $this->getCacheHtml($cacheName); //lay trong cache file
          if (empty($html)) {
            $data['cached'] = false;
            $html = $this->load->view("blocks/suggest/newest", array('datas' => $newest, 'title' => $title), true);
            //set lai cache file
            $this->setCacheHtml($cacheName, $html);
          }
        }
        $data['html'] = $html;
        break;
      case 'topview':
        $topview = $_bus->getTopViewNews($offset, $length);
        $data['errorCode'] = 1;
        $data['cached'] = true;
        $cacheName = "_topview";
        $html = $this->getCacheHtml($cacheName); //lay trong cache file
        if (empty($html)) {
          $data['cached'] = false;
          $html = $this->load->view("blocks/suggest/topview", array('datas' => $topview), true);
          //set lai cache file
          $this->setCacheHtml($cacheName, $html);
        }
        $data['html'] = $html;
        break;
      case 'facebook-fan':
        $data['errorCode'] = 1;
        $html = $this->load->view("blocks/suggest/facebook", array(), true);
        $data['html'] = $html;
        break;
    }
    echo json_encode($data);
  }

  public function deleteConfig() {
    $acc = isset($_GET['acc']) ? $_GET['acc'] : "";
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    if ($acc == 'khuongpham') {
      $this->load->driver('cache');
      echo "cache Info:<br>";
      echo "<pre>";
      print_r($this->cache->file->cache_info());
      if ($action == 'clean') {
        $rs = $this->cache->file->clean();
        echo $rs ? "OK" : "Failr";
      }
    }
  }

}
