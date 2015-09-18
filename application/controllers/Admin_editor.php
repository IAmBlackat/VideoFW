<?php

class Admin_editor extends MY_Controller {

  public $Editor_Box_model = NULL;
  public $Editor_Box_Item_model = NULL;
  protected $_config = NULL;

  function __construct() {
    parent::__construct(true);
    $this->load->model('Editor_Box_model', NULL, TRUE);
    $this->load->model('Editor_Box_Item_model', NULL, TRUE);
    $this->Editor_Box_model = Editor_Box_model::getInstance();
    $this->Editor_Box_Item_model = Editor_Box_Item_model::getInstance();
    $this->_config = $this->config->config;
    set_userdata('active_menu', strtolower(get_class($this)));
  }

  public function index($page = 'index') {
    if (!file_exists('application/views/admin_editor/' . $page . '.php')) {
      show_404();
    }
    $offset = isset($_GET['o']) ? intval($_GET['o']) : 0;
    $this->layout->title('List box');

    $whereClause = "1";
    $total = $this->Editor_Box_model->getTotal($whereClause);
    $listObject = $this->Editor_Box_model->getRange($whereClause, $offset, ITEM_PER_PAGE);
    $uri = "admin_editor/index";
    $data['listObject'] = $listObject;
    $data['total'] = $total;
    $data['max'] = ITEM_PER_PAGE;
    $data['offset'] = $offset;
    $data['uri'] = $uri . '?o=';
    $this->layout->view('admin_editor/' . $page, $data);
  }
  public function list_box_item($page = 'list_box_item') {
    if (!file_exists('application/views/admin_editor/' . $page . '.php')) {
      show_404();
    }
    $offset = isset($_GET['o']) ? intval($_GET['o']) : 0;
    $this->layout->title('List box Item');

    $whereClause = "1";
    $total = $this->Editor_Box_Item_model->getTotal($whereClause);
    $listObject = $this->Editor_Box_Item_model->getRangeFull($whereClause, $offset, ITEM_PER_PAGE);
    $uri = "admin_editor/list_box_item";
    $data['listObject'] = $listObject;
    $data['total'] = $total;
    $data['max'] = ITEM_PER_PAGE;
    $data['offset'] = $offset;
    $data['uri'] = $uri . '?o=';
    $this->layout->view('admin_editor/' . $page, $data);
  }
  public function create($page = 'create') {
    if (!file_exists('application/views/admin_editor/' . $page . '.php')) {
      show_404();
    }
    $data = array();
    $this->layout->title('Create box');
    $this->layout->view('admin_editor/' . $page, $data);
  }
  public function create_box_item($page = 'create_box_item') {
    if (!file_exists('application/views/admin_editor/' . $page . '.php')) {
      show_404();
    }
    $data = array();
    $listBox = $this->Editor_Box_model->getAll();
    $boxSelectbox = selectBox($listBox, array('Name' => 'box_id','Selected' => FALSE, 'MainOption' => TRUE));
    $data['boxSelectbox'] = $boxSelectbox;
    $this->layout->title('Create box item');
    $this->layout->view('admin_editor/' . $page, $data);
  }
  public function validate($data, $isUpdate = FALSE) {
    $message = "";
    if (empty($data['name'])) {
      $message .= "<li>" . $this->lang->line('admin.box.require.name') . "</li>";
    }
    if (empty($data['key'])) {
      $message .= "<li>" . $this->lang->line('admin.box.require.key') . "</li>";
    }
    return $message;
  }
  public function validate_box_item($data, $isUpdate = FALSE) {
    $message = "";
    if (empty($data['item_text'])) {
      $message .= "<li>" . $this->lang->line('admin.box_item.require.text') . "</li>";
    }
    if (empty($data['item_link'])) {
      $message .= "<li>" . $this->lang->line('admin.box_item.require.link') . "</li>";
    }
    if (isset($data['box_id']) && $data['box_id'] == -1) {
      $message .= "<li>" . $this->lang->line('admin.box_item.require.box') . "</li>";
    }
    if (!$isUpdate) {
      if (empty($_FILES) || empty($_FILES['item_thumbnail']['tmp_name'])) {
        $message .= "<li>" . $this->lang->line('admin.box_item.require.thumbnail') . "</li>";
      }
    }
    return $message;
  }

  public function save() {
    $data = $_POST;
    $message = $this->validate($data);
    if (!empty ($data['key'])) {
      $value = $this->Editor_Box_model->getByKey($data['key']);
      if (!empty($value)) {
        $message .= "<li>" . sprintf($this->lang->line('admin.config.exist.key'), $data['key']) . "</li>";
        set_flash_error($message);
      }
    }
    if (!empty($message)) {
      set_flash_error($message);
    }
    if (!has_error()) {
      $this->Editor_Box_model->insert($data);
      $url = base_url() . 'admin_editor/index';
      set_flash_message($this->lang->line('admin.box.created'));
      redirect($url);
    } else {
      $this->layout->title('Create box');
      $this->layout->view('admin_editor/create', $data);
    }
  }
  public function save_box_item() {
    $data = $_POST;
    $message = $this->validate_box_item($data);
    $listBox = $this->Editor_Box_model->getAll();
    $boxSelectbox = selectBox($listBox, array('Name' => 'box_id','MainOption' => TRUE, 'Selected' => $data['box_id']));
    $data['boxSelectbox'] = $boxSelectbox;

    if (!empty($message)) {
      set_flash_error($message);
    }
    if (!has_error()) {
      $result = upload_image($_FILES, EDITOR_IMAGE_THUMBNAIL_PATH);
      if (!empty($result)) {
        if ($result['error'] != 0) {
          set_flash_error($result['message']);
          $this->layout->title('Create box item');
          $this->layout->view('admin_series/create_box_item', $data);
        } else {
          $data['item_thumbnail'] = $result['fileName'];
          $this->Editor_Box_Item_model->insert($data);
          $url = base_url() . 'admin_editor/list_box_item';
          set_flash_message($this->lang->line('admin.box.created'));
          redirect($url);
        }
      }
    } else {
      $this->layout->title('Create box item');
      $this->layout->view('admin_editor/create_box_item', $data);
    }
  }

  public function edit($page = 'edit') {
    if (!file_exists('application/views/admin_editor/' . $page . '.php')) {
      show_404();
    }
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $this->layout->title('Edit box');
    $data = $this->Editor_Box_model->getById($id);
    $this->layout->view('admin_editor/' . $page, $data);
  }
  public function edit_box_item($page = 'edit_box_item') {
    if (!file_exists('application/views/admin_editor/' . $page . '.php')) {
      show_404();
    }
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $data = $this->Editor_Box_Item_model->getById($id);
    $listBox = $this->Editor_Box_model->getAll();
    $boxSelectbox = selectBox($listBox, array('Name' => 'box_id','Selected' => $data['box_id'], 'MainOption' => TRUE));
    $data['boxSelectbox'] = $boxSelectbox;
    $this->layout->title('Edit box item');
    $this->layout->view('admin_editor/' . $page, $data);
  }

  public function update() {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $data = $_POST;
    $data['id'] = $id;
    $obj = $this->Editor_Box_model->getById($id);
    $message = $this->validate($data, TRUE);
    if (!empty ($data['key'])) {
      $dataDb = $this->Editor_Box_model->getByKey($data['key']);
      if (!empty($dataDb) && ($dataDb['id']!=$id)) {
        $message .= "<li>" . sprintf($this->lang->line('admin.config.exist.key'), $data['key']) . "</li>";
        set_flash_error($message);
      }
    }
    if (!empty($message)) {
      set_flash_error($message);
    }

    if (!has_error()) {
      $id = $this->Editor_Box_model->update($id, $data);
      $url = base_url() . 'admin_editor/index';
      set_flash_message($this->lang->line('admin.box.updated'));
      redirect($url);
    } else {
      $this->layout->title('Edit box');
      $this->layout->view('admin_editor/edit', $data);
    }
  }
  public function update_box_item() {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $data = $_POST;
    $data['id'] = $id;
    $obj = $this->Editor_Box_Item_model->getById($id);
    $data['item_thumbnail'] = $obj['item_thumbnail'];
    $originalThumb = $obj['item_thumbnail'];
    $message = $this->validate_box_item($data, TRUE);
    if (!empty($message)) {
      set_flash_error($message);
    }
    $listBox = $this->Editor_Box_model->getAll();
    $boxSelectbox = selectBox($listBox, array('Name' => 'box_id','MainOption' => TRUE, 'Selected' => $data['box_id']));
    $data['boxSelectbox'] = $boxSelectbox;


    if (!has_error()) {
      $isUpload = FALSE;
      $canUpdate = FALSE;
      if (!empty($_FILES) && !empty($_FILES['item_thumbnail']['tmp_name'])) {
        $result = upload_image($_FILES, EDITOR_IMAGE_THUMBNAIL_PATH);
        if ($result['error'] != 0) {
          $this->layout->title('Edit box item');
          $this->layout->view('admin_editor/edit_box_item', $data);
        } else {
          $data['item_thumbnail'] = $result['fileName'];
          $canUpdate = TRUE;
          $isUpload = TRUE;
        }
      } else {
        $canUpdate = TRUE;
      }
      if ($canUpdate) {
        $id = $this->Editor_Box_Item_model->update($id, $data);
        if($isUpload && file_exists(EDITOR_IMAGE_THUMBNAIL_PATH.$originalThumb)){
          unlink(EDITOR_IMAGE_THUMBNAIL_PATH.$originalThumb);
        }
        $url = base_url() . 'admin_editor/list_box_item';
        set_flash_message($this->lang->line('admin.box.updated'));
        redirect($url);
      }
    } else {
      $this->layout->title('Edit box item');
      $this->layout->view('admin_editor/list_box_item', $data);
    }
  }

  public function delete() {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $data = $this->Editor_Box_model->delete($id);
    $url = base_url() . 'admin_editor/index';
    set_flash_message($this->lang->line('admin.content.deleted'));
    redirect($url);
  }
  public function delete_box_item() {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $data = $this->Editor_Box_Item_model->getById($id);
    if ($data) {
      $originalThumb = $data['item_thumbnail'];
      if(file_exists(EDITOR_IMAGE_THUMBNAIL_PATH.$originalThumb)){
        unlink(EDITOR_IMAGE_THUMBNAIL_PATH.$originalThumb);
      }
    }
    $data = $this->Editor_Box_Item_model->delete($id);
    $url = base_url() . 'admin_editor/list_box_item';
    set_flash_message($this->lang->line('admin.content.deleted'));
    redirect($url);
  }

  public function show($page = 'show') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $data = $this->Editor_Box_model->getById($id);
    $itemOfbox = $this->Editor_Box_Item_model->getRange("box_id=" . $id, 0, 100);
    $data['itemOfbox'] = $itemOfbox;
    $this->layout->title('Show box');
    $this->layout->view('admin_editor/' . $page, $data);
  }

}
