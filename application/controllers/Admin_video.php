<?php

class Admin_Video extends MY_Controller {

  public $Video_model = NULL;
  public $Series_model = NULL;
  protected $_config;

  function __construct() {
    parent::__construct(TRUE);
    $this->load->model('Video_model', NULL, TRUE);
    $this->load->model('Series_model', NULL, TRUE);
    $this->Video_model = Video_model::getInstance();
    $this->Series_model = Series_model::getInstance();
    $this->_config = $this->config->config;
    set_userdata('active_menu', strtolower(get_class($this)));
  }

  public function index($page = 'index') {
    if (!file_exists('application/views/admin_video/' . $page . '.php')) {
      show_404();
    }
    $data = array();
    $temp = isset($_GET['rand']) ? $_GET['rand'] : '';
    if (isset($_POST['status'])) {
      $data = $_POST;
      set_userdata('admin_search_video_params', $data);
    } else if (!empty($temp)) {
      unset_userdata('admin_search_video_params');
    }
    $params = userdata('admin_search_video_params');
    //echo "<pre>"; print_r($params);
    $data['params'] = $params;
    $selectedStatus = isset($params['status']) ? intval($params['status']) : -1;
    $statusSelectbox = selectBox($this->_config['status'], array('Name' => 'status', 'Selected' => $selectedStatus, 'MainOption' => TRUE));
    $data['statusSelectbox'] = $statusSelectbox;
    $uri = "admin_video/index";
    $whereClause = "1";
    if (isset($params['keyword']) && !empty($params['keyword'])) {
      $keywork = addslashes($params['keyword']);
      $whereClause .= " AND title LIKE '%{$keywork}%' ";
    }
    if (isset($params['status']) && $params['status'] != -1) {
      $whereClause .= " AND status ='{$selectedStatus}' ";
    }
        
    $this->layout->title('List video');
    $total = $this->Video_model->getTotal($whereClause);
    $offset = isset($_GET['o']) ? intval($_GET['o']) : 0;
    $listObject = $this->Video_model->getRange($whereClause, $offset, ITEM_PER_PAGE, 'series_id ASC, episode DESC');
    $data['listObject'] = $listObject;
    $data['total'] = $total;
    $data['max'] = ITEM_PER_PAGE;
    $data['offset'] = $offset;
    $data['uri'] = "admin_video/index/?o=";
    $this->layout->view('admin_video/' . $page, $data);
  }

  public function create($page = 'create') {
    die('Closed');
  }

  public function validate($data, $isUpdate = FALSE) {
    $message = "";
    if (empty($data['title'])) {
      $message .= "<li>" . $this->lang->line('admin.video.require.title') . "</li>";
    }
    return $message;
  }
  public function edit($page = 'edit') {
    if (!file_exists('application/views/admin_video/' . $page . '.php')) {
      show_404();
    }
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $data = $this->Video_model->getById($id);
    $statusSelectbox = selectBox($this->_config['status'], array('Name' => 'status', 'Selected' => $data['status']));
    $data['statusSelectbox'] = $statusSelectbox;
    $allSeries = $this->Series_model->getAllShort();
    $seriesSelectbox = selectBox($allSeries, array('Name' => 'series_id', 'Selected' => $data['series_id']));
    $data['seriesSelectbox'] = $seriesSelectbox;
    $this->layout->title('Edit video');
    $this->layout->view('admin_video/' . $page, $data);
  }

  public function update() {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $data = $_POST;
    $data['id'] = $id;
    if(isset($data['has_sub'])){
      $data['has_sub']  = 1;
    }else{
      $data['has_sub']  = 0;
    }
    $message = $this->validate($data, true);
    if (!empty($message)) {
      set_flash_error($message);
    }
    $statusSelectbox = selectBox($this->_config['status'], array('Name' => 'status', 'Selected' => $data['status']));
    $data['statusSelectbox'] = $statusSelectbox;
    $allSeries = $this->Series_model->getAllShort();
    $seriesSelectbox = selectBox($allSeries, array('Name' => 'series_id', 'Selected' => $data['series_id']));
    $data['seriesSelectbox'] = $seriesSelectbox;
    if (!has_error()) {
      $result = $this->Video_model->update($id, $data);
      set_flash_message($this->lang->line('admin.video.updated'));
      $url = base_url() . 'admin_video/index';
      redirect($url);
    } else {
      $this->layout->title('Edit video');
      $this->layout->view('admin_video/edit', $data);
    }
  }

  public function delete() {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $this->_deleteImage($id);
    $data = $this->Video_model->delete($id);
    $url = base_url() . 'admin_video/index';
    set_flash_message($this->lang->line('admin.video.deleted'));
    redirect($url);
  }

  public function show($page = 'show') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $data = $this->Video_model->getByIdFull($id);
    //echo "<pre>"; print_r($data); die();
    if($data){
      $seriesId = $data['series_id'];
      $series = $this->Series_model->getByIdFull($seriesId);
      $data['series'] = $series;
    }
    $this->layout->title('Show video');
    $this->layout->view('admin_video/' . $page, $data);
  }
  public function update_status(){
    $ids = isset($_POST['ids']) ? $_POST['ids'] : 0;
    $command = isset($_POST['command']) ? strval($_POST['command']) : '';
    if($ids & $command){
      $idArr = explode(",", $ids);
      if($command == 'approve'){
        foreach($idArr as $id){
          $id = intval($id);
          $params = array();
          $params['status'] = STATUS_SHOW;
          $this->Video_model->update($id, $params);
        }
      }elseif($command=='hide'){
        foreach($idArr as $id){
          $id = intval($id);
          $params = array();
          $params['status'] = STATUS_HIDE;
          $this->Video_model->update($id, $params);
        }
      }
    }
  }
}
