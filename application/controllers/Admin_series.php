<?php

class Admin_series extends MY_Controller {

  public $Video_model = NULL;
  public $Series_model = NULL;
  public $Genre_model = NULL;
  public $Series_Genre_model = NULL;
  protected $_config = NULL;

  function __construct() {
    parent::__construct(true);
    $this->load->model('Video_model', NULL, TRUE);
    $this->load->model('Series_model', NULL, TRUE);
    $this->load->model('Genre_model', NULL, TRUE);
    $this->load->model('Series_Genre_model', NULL, TRUE);
    $this->Video_model = Video_model::getInstance();
    $this->Series_model = Series_model::getInstance();
    $this->Genre_model = Genre_model::getInstance();
    $this->Series_Genre_model = Series_Genre_model::getInstance();
    $this->_config = $this->config->config;
    set_userdata('active_menu', strtolower(get_class($this)));
  }

  public function index($page = 'index') {
    if (!file_exists('application/views/admin_series/' . $page . '.php')) {
      show_404();
    }
    $temp = isset($_GET['rand']) ? $_GET['rand'] : '';
    if (isset($_POST['status'])) {
      $data = $_POST;
      set_userdata('admin_search_series_params', $data);
    } else if (!empty($temp)) {
      unset_userdata('admin_search_series_params');
    }
    $params = userdata('admin_search_series_params');
    //echo "<pre>"; print_r($params);
    $data['params'] = $params;
    $selectedStatus = isset($params['status']) ? intval($params['status']) : -1;
    $selectedCountry = isset($params['country']) ? intval($params['country']) : -1;
    $selectedType = isset($params['type']) ? intval($params['type']) : -1;

    $statusSelectbox = selectBox($this->_config['status'], array('Name' => 'status', 'Selected' => $selectedStatus, 'MainOption' => TRUE));
    $data['statusSelectbox'] = $statusSelectbox;
    $countrySelectbox = selectBox($this->_config['countries'], array('Name' => 'country', 'Selected' => $selectedCountry, 'MainOption' => TRUE));
    $data['countrySelectbox'] = $countrySelectbox;
    $typeSelectbox = selectBox($this->_config['video_type'], array('Name' => 'type', 'Selected' => $selectedType, 'MainOption' => TRUE));
    $data['typeSelectbox'] = $typeSelectbox;
    $offset = isset($_GET['o']) ? intval($_GET['o']) : 0;
    $this->layout->title('List series');

    $whereClause = "1";
    if (isset($params['keyword']) && !empty($params['keyword'])) {
      $keywork = addslashes($params['keyword']);
      $whereClause .= " AND title LIKE '%{$keywork}%' ";
    }
    if (isset($params['status']) && $params['status'] != -1) {
      $whereClause .= " AND status ='{$selectedStatus}' ";
    }
    if (isset($params['country']) && !empty($params['country']) && $params['country'] != -1) {
      $whereClause .= " AND country ='{$selectedCountry}' ";
    }
    if (isset($params['type']) && !empty($params['type']) && $params['type'] != -1) {
      $whereClause .= " AND type ='{$selectedType}' ";
    }
    $total = $this->Series_model->getTotal($whereClause);
    $listObject = $this->Series_model->getRange($whereClause, $offset, ITEM_PER_PAGE);
    $uri = "admin_series/index";
    $data['countries'] = $this->_config['countries'];
    $data['video_type'] = $this->_config['video_type'];
    $data['listObject'] = $listObject;
    $data['total'] = $total;
    $data['max'] = ITEM_PER_PAGE;
    $data['offset'] = $offset;
    $data['uri'] = $uri . '?o=';
    $this->layout->view('admin_series/' . $page, $data);
  }

  public function create($page = 'create') {
    if (!file_exists('application/views/admin_series/' . $page . '.php')) {
      show_404();
    }
    $data = array();
    $listGenre = $this->Genre_model->getAll();
    $genreSelectbox = selectBox($listGenre, array('Name' => 'genre[]', 'multiple' => 'multiple', 'size' => 10, 'Selected' => FALSE));
    $data['genreSelectbox'] = $genreSelectbox;
    $statusSelectbox = selectBox($this->_config['status'], array('Name' => 'status', 'Selected' => FALSE));
    $data['statusSelectbox'] = $statusSelectbox;
    $countrySelectbox = selectBox($this->_config['countries'], array('Name' => 'country', 'Selected' => FALSE));
    $data['countrySelectbox'] = $countrySelectbox;
    $typeSelectbox = selectBox($this->_config['video_type'], array('Name' => 'type', 'Selected' => FALSE));
    $data['typeSelectbox'] = $typeSelectbox;

    $this->layout->title('Create serie');
    $this->layout->view('admin_series/' . $page, $data);
  }

  public function validate($data, $isUpdate = FALSE) {
    $message = "";
    if (empty($data['title'])) {
      $message .= "<li>" . $this->lang->line('admin.series.require.title') . "</li>";
    }
    if (empty($data['description'])) {
      $message .= "<li>" . $this->lang->line('admin.series.require.description') . "</li>";
    }
    if (empty($data['genre'])) {
      $message .= "<li>" . $this->lang->line('admin.series.require.genre') . "</li>";
    }
    if (!$isUpdate) {
      if (empty($_FILES) || empty($_FILES['thumbnail']['tmp_name'])) {
        $message .= "<li>" . $this->lang->line('admin.series.require.thumbnail') . "</li>";
      }
    }
    return $message;
  }

  public function save() {
    $data = $_POST;
    $message = $this->validate($data);
    if (!empty($message)) {
      set_flash_error($message);
    }
    $data['is_complete'] = isset($data['is_complete']) ? SERIES_STATUS_COMPLETE : SERIES_STATUS_ONGOING;
    $listGenre = $this->Genre_model->getAll();
    $defaultGenre = isset($data['genre']) ? $data['genre'] : array();
    $genreSelectbox = selectBox($listGenre, array('Name' => 'genre[]', 'multiple' => 'multiple', 'size' => 10, 'Selected' => $defaultGenre));
    $data['genreSelectbox'] = $genreSelectbox;
    $statusSelectbox = selectBox($this->_config['status'], array('Name' => 'status', 'Selected' => $data['status']));
    $data['statusSelectbox'] = $statusSelectbox;
    $countrySelectbox = selectBox($this->_config['countries'], array('Name' => 'country', 'Selected' => $data['country']));
    $data['countrySelectbox'] = $countrySelectbox;
    $typeSelectbox = selectBox($this->_config['video_type'], array('Name' => 'type', 'Selected' => $data['type']));
    $data['typeSelectbox'] = $typeSelectbox;
    if (!has_error()) {


      $result = upload_image($_FILES, SERIE_IMAGE_THUMBNAIL_PATH);
      if (!empty($result)) {
        if ($result['error'] != 0) {
          set_flash_error($result['message']);
          $this->layout->title('Create series');
          $this->layout->view('admin_series/create', $data);
        } else {
          $data['thumbnail'] = $result['fileName'];
          $id = $this->Series_model->insert($data);
          if ($data['genre']) {
            foreach ($data['genre'] as $genreId) {
              $seriesGenreData = array();
              $seriesGenreData['genre_id'] = $genreId;
              $seriesGenreData['series_id'] = $id;
              $this->Series_Genre_model->insert($seriesGenreData);
            }
          }
          $url = base_url() . 'admin_series/index';
          set_flash_message($this->lang->line('admin.series.created'));
          redirect($url);
        }
      }
    } else {
      $this->layout->title('Create series');
      $this->layout->view('admin_series/create', $data);
    }
  }

  public function edit($page = 'edit') {
    if (!file_exists('application/views/admin_series/' . $page . '.php')) {
      show_404();
    }
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $this->layout->title('Edit serie');
    $data = $this->Series_model->getByIdFull($id);
    $listGenre = $this->Genre_model->getAll();
    $genreIdListBySeriesId = $this->Series_model->getGenreIdBySeriesId($id);
    $genreSelectbox = selectBox($listGenre, array('Name' => 'genre[]', 'multiple' => 'multiple', 'size' => 10, 'Selected' => $genreIdListBySeriesId));
    $data['genreSelectbox'] = $genreSelectbox;
    $statusSelectbox = selectBox($this->_config['status'], array('Name' => 'status', 'Selected' => $data['status']));
    $data['statusSelectbox'] = $statusSelectbox;
    $countrySelectbox = selectBox($this->_config['countries'], array('Name' => 'country', 'Selected' => $data['country']));
    $data['countrySelectbox'] = $countrySelectbox;
    $typeSelectbox = selectBox($this->_config['video_type'], array('Name' => 'type', 'Selected' => $data['type']));
    $data['typeSelectbox'] = $typeSelectbox;
    $this->layout->view('admin_series/' . $page, $data);
  }

  public function update() {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $data = $_POST;
    $data['id'] = $id;
    $data['is_complete'] = isset($data['is_complete']) ? SERIES_STATUS_COMPLETE : SERIES_STATUS_ONGOING;
    $obj = $this->Series_model->getById($id);
    $data['thumbnail'] = $obj['thumbnail'];
    $message = $this->validate($data, TRUE);
    if (!empty($message)) {
      set_flash_error($message);
    }
    $listGenre = $this->Genre_model->getAll();
    $defaultGenre = isset($data['genre']) ? $data['genre'] : array();
    $genreSelectbox = selectBox($listGenre, array('Name' => 'genre[]', 'multiple' => 'multiple', 'size' => 10, 'Selected' => $defaultGenre));
    $data['genreSelectbox'] = $genreSelectbox;
    $statusSelectbox = selectBox($this->_config['status'], array('Name' => 'status', 'Selected' => $data['status']));
    $data['statusSelectbox'] = $statusSelectbox;
    $countrySelectbox = selectBox($this->_config['countries'], array('Name' => 'country', 'Selected' => $data['country']));
    $data['countrySelectbox'] = $countrySelectbox;
    $typeSelectbox = selectBox($this->_config['video_type'], array('Name' => 'type', 'Selected' => $data['type']));
    $data['typeSelectbox'] = $typeSelectbox;

    if (!has_error()) {
      $canUpdate = FALSE;
      if (!empty($_FILES) && !empty($_FILES['thumbnail']['tmp_name'])) {
        $result = upload_image($_FILES, SERIE_IMAGE_THUMBNAIL_PATH);
        if ($result['error'] != 0) {
          $this->layout->title('Edit video');
          $this->layout->view('admin_series/edit', $data);
        } else {
          $data['thumbnail'] = $result['fileName'];
          $this->_deleteImage($id);
          $canUpdate = TRUE;
        }
      } else {
        $canUpdate = TRUE;
      }
      if ($canUpdate) {
        $id = $this->Series_model->update($id, $data);
        $this->Series_Genre_model->deleteBySeriesId($id);
        if ($data['genre']) {
          foreach ($data['genre'] as $genreId) {
            $seriesGenreData = array();
            $seriesGenreData['genre_id'] = $genreId;
            $seriesGenreData['series_id'] = $id;
            $this->Series_Genre_model->insert($seriesGenreData);
          }
        }
        $url = base_url() . 'admin_series/index';
        set_flash_message($this->lang->line('admin.series.updated'));
        redirect($url);
      }
    } else {
      $this->layout->title('Edit series');
      $this->layout->view('admin_series/edit', $data);
    }
  }

  public function delete() {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $this->_deleteImage($id);
    $data = $this->Series_model->delete($id);
    $this->Series_Genre_model->deleteBySeriesId($id);
    $url = base_url() . 'admin_series/index';
    set_flash_message($this->lang->line('admin.content.deleted'));
    redirect($url);
  }

  public function show($page = 'show') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $data = $this->Series_model->getById($id);
    $videosOfSeries = $this->Video_model->getRange("series_id=" . $id, 0, 100);
    $data['videosOfSeries'] = $videosOfSeries;
    $this->layout->title('Show series');
    $data['video_type'] = $this->_config['video_type'];
    $this->layout->view('admin_series/' . $page, $data);
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
          $this->Series_model->update($id, $params);
        }
      }elseif($command=='hide'){
        foreach($idArr as $id){
          $id = intval($id);
          $params = array();
          $params['status'] = STATUS_HIDE;
          $this->Series_model->update($id, $params);
        }
      }
    }
  }


  public function _deleteImage($id) {
    $data = $this->Series_model->getById($id);
    if ($data) {
      $imagePath = $data['thumbnail'];
      if (file_exists(SERIE_IMAGE_THUMBNAIL_PATH . $imagePath)) {
        unlink(SERIE_IMAGE_THUMBNAIL_PATH . $imagePath);
      }
    }
  }

}
