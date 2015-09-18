<?php

class Admin extends MY_Controller {

  function __construct() {
    parent::__construct(TRUE);
  }

  public function index($page = 'index') {

    if (!file_exists('application/views/admin/' . $page . '.php')) {
      show_404();
    }
    $this->layout->title('Admin');
    $this->layout->view('admin/' . $page);
  }

  public function login($page = 'login') {
    if (!file_exists('application/views/admin/' . $page . '.php')) {
      show_404();
    }
    $data = $_POST;
    if (isset($_POST['login'])) {
      if (empty($data['password'])) {
        set_flash_error($this->lang->line('admin.login.require.password'));
      }
      if (empty($data['username'])) {
        set_flash_error($this->lang->line('admin.login.require.username'));
      }
      if (!has_error()) {
        $username = $data['username'];
        $password = $data['password'];
        $this->load->model('Config_model', NULL, TRUE);
        $config_model = Config_model::getInstance();
        $configUsername = $this->Config_model->getValue("admin_username");
        $configPass = $this->Config_model->getValue("admin_password");
        if ($username == $configUsername && $password == $configPass) {
          set_userdata("username", $configUsername);
          redirect(site_url('admin'));
        } else {
          set_flash_error($this->lang->line('admin.login.wrong'));
        }
      }
    }

    $this->layout->title('Login');
    $this->layout->view('admin/' . $page, $data);
  }

  public function logout() {
    unset_userdata("username");
    redirect(site_url('admin/login'));
  }

}
