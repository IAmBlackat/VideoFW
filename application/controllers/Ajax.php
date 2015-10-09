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
    $ret = array();
    $logsModel = null;
    $this->load->model('Logs_model', NULL, TRUE);
    $logsModel = new Logs_model();
    $elementId = isset($_POST['element_id']) ? intval($_POST['element_id']) : 0;
    $type = isset($_POST['type']) ? intval($_POST['type']) : 'video';
    $urlId = isset($_POST['url_id']) ? intval($_POST['url_id']) : 0;
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    if($status==0){
      $this->load->library('simple_html_dom');
      $this->load->file(APPPATH.'components/ImportDramaCool.php');
      $dramaCool = new ImportDramaCool();
      $videoObj = $this->Video_model->getById($elementId);
      if($videoObj){
        $updateResult = $dramaCool->updateStreamingInLog($videoObj['id'], $videoObj['original_url']);
        //$updateResult[113412] = 'abc';
        $streamingUrl = isset($updateResult[$urlId]) ? $updateResult[$urlId] : '';
        if($updateResult){
          $ret['msg'] = 'updated';
          $ret['surl'] = $streamingUrl;
        }else{
          $ret['msg'] = 'cannot updated';
        }
      }else{
        $ret['msg'] = 'file not found';
      }
    }else{
      $ret['msg'] = 'playing';
      if ($elementId) {
        $logsModel->writeLogs($elementId, $type);
      }
    }
    echo json_encode($ret);
  }


  public function deleteCache() {
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
