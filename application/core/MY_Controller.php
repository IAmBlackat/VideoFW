<?php

class MY_Controller extends CI_Controller {
	function __construct($isAdmin=false) {
		parent::__construct();
    if($isAdmin){
      $this->_adminConstruct();
    }else{
      $this->_frontendConstruct();
    }
	}
  private function _adminConstruct(){
		$this->load->library('layout');
		//set layout
		$this->layout->setTemplate("layout/admin.php");
		$this->layout->setSkin(base_url().'themes/admin/');
		$skin = $this->layout->getSkin();
		// Site global resources
		$this->layout->js($skin.'js/jquery-1.9.1.min.js');
    $this->layout->js($skin.'js/admin.js');
		$this->layout->css($skin.'css/style.css');
		$this->layout->css($skin.'css/layout.css');
		$this->lang->load('en', 'admin');
		$currentUser = userdata("username");
		$action = $this->uri->segment(2);
		$controller = $this->uri->segment(1);
		if($controller=="import_data"){
			$currentUser = "admin";
		}
    
		if($currentUser=='admin'){
			if($action=='login'){
				redirect(site_url('admin'));
			}
		}else{
			if($action!='login'){
				redirect(site_url('admin/login'));
			}
		}
  }
  private function _frontendConstruct(){
    $this->load->model('Config_model', NULL, TRUE);
    $config_model = Config_model::getInstance();
    $listGenreId = $this->Config_model->getValue("left_nav_genre");
		$this->load->library('layout');
    if($listGenreId){
      $this->load->model('Genre_model', NULL, TRUE);
      $genre_model = Genre_model::getInstance();
      $genreList = $genre_model->getRange('id IN('.$listGenreId.')', 0, 100);
      $extraData = $this->layout->getExtraData();
      $extraData['left_nav_genre'] = $genreList;

      $this->layout->setExtraData($extraData);
    }
		//set layout
		$this->layout->setTemplate("layout/frontend.php");
		$this->layout->setSkin(base_url().'themes/frontend/');
		$skin = $this->layout->getSkin();
		// Site global resources
    $this->layout->js($skin.'js/jquery-2.1.3.js');
		$this->layout->js($skin.'js/bootstrap.js');
    $this->layout->js($skin.'js/slick.min.js');
    $this->layout->js($skin.'js/fe.js');
		$this->layout->css($skin.'css/style.css');
  }
  public function setCacheHtml($name, $html, $time=NULL){
    if($time==NULL){
      $time = DEFAULT_CACHE_TIME_MINUTE * 60;
    }
    $this->load->driver('cache');
    $this->cache->file->save($name, $html, $time);
  }
  public function getCacheHtml($name){
    $this->load->driver('cache');
    $html = $this->cache->file->get($name);
    return $html;
  }
}
