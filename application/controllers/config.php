<?php

class Config extends MY_Controller
{

  public $Config_model = NULL;

  function __construct()
  {
    parent::__construct(TRUE);
    $this->load->model('Config_model', NULL, TRUE);
    $this->Config_model = Config_model::getInstance();
    set_userdata('active_menu', strtolower(get_class($this)));
  }

  public function index($page = 'index')
  {

    if (!file_exists('application/views/config/' . $page . '.php')) {
      show_404();
    }
    $this->layout->title('List config');
    $total = $this->Config_model->getTotal("");

    $listObject = $this->Config_model->getRange("", 0, $total);
    $data['listObject'] = $listObject;
    $data['total'] = $total;
    $this->layout->view('config/' . $page, $data);
  }

  public function create($page = 'create')
  {
    if (!file_exists('application/views/config/' . $page . '.php')) {
      show_404();
    }
    $this->layout->title('Create Config');
    $data = array();
    $this->layout->view('config/' . $page, $data);
  }

  public function validate($data)
  {
    $message = "";
    if (empty($data['key'])) {
      $message .= "<li>" . $this->lang->line('admin.config.require.key') . "</li>";
    }
    if ($data['value'] == '' || $data['value'] == NULL) {
      $message .= "<li>" . $this->lang->line('admin.config.require.value') . "</li>";
    }
    return $message;
  }

  public function save()
  {
    $data = $_POST;
    $data['status'] = isset($data['is_active']) ? STATUS_SHOW : 0;
    $message = $this->validate($data);
    if (!empty ($data['key'])) {
      $value = $this->Config_model->getValue($data['key']);
      if (!empty($value)) {
        $message .= "<li>" . sprintf($this->lang->line('admin.config.exist.key'), $data['key']) . "</li>";
        set_flash_error($message);
      }
    }
    if (!empty($message)) {
      set_flash_error($message);
    }
    if (!has_error()) {
      $id = $this->Config_model->insert($data);
      $url = base_url() . 'config/index';
      set_flash_message($this->lang->line('admin.config.created'));
      redirect($url);
    } else {
      $this->layout->title('Create Config');
      $this->layout->view('config/create', $data);
    }
  }

  public function edit($page = 'edit')
  {
    if (!file_exists('application/views/config/' . $page . '.php')) {
      show_404();
    }
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $this->layout->title('Edit Config');
    $data = $this->Config_model->getById($id);
    $data['is_active'] = $data['status'] == STATUS_SHOW ? STATUS_SHOW : 0;
    $this->layout->view('config/' . $page, $data);
  }

  public function update()
  {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $data = $_POST;
    $data['id'] = $id;
    $data['status'] = isset($data['is_active']) ? STATUS_SHOW : 0;
    $message = $this->validate($data);
    if (!empty($message)) {
      set_flash_error($message);
    }
    if (!has_error()) {
      $result = $this->Config_model->update($id, $data);
      set_flash_message($this->lang->line('admin.config.updated'));
      $url = base_url() . 'config/index';
      redirect($url);
    } else {
      $this->layout->title('Edit Config');
      $this->layout->view('config/edit', $data);
    }
  }

  public function delete()
  {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $data = $this->Config_model->delete($id);
    $url = base_url() . 'config/index';
    set_flash_message($this->lang->line('admin.config.deleted'));
    redirect($url);
  }

  public function show($page = 'show')
  {
    $this->layout->title('Show Config');
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $data = $this->Config_model->getById($id);
    $this->layout->view('config/' . $page, $data);
  }

}
