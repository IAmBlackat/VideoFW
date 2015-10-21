<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Home extends MY_Controller {

  public $Video_model = NULL;
  public $Series_model = NULL;
  public $Editor_Box_model = NULL;
  public $_config = NULL;

  function __construct() {
    parent::__construct();
    $this->load->model('Video_model', NULL, TRUE);
    $this->load->model('Series_model', NULL, TRUE);
    $this->load->model('Editor_Box_model', NULL, TRUE);
    $this->Video_model = Video_model::getInstance();
    $this->Series_model = Series_model::getInstance();
    $this->Editor_Box_model = Editor_Box_model::getInstance();
    $this->_config = $this->config->config;
  }

  /**
   * home page
   * @param string $page
   */
  public function index($page = 'index') {
    $this->output->cache(DEFAULT_CACHE_TIME_MINUTE);
    $data = array();
    //banner
    $banner = $this->Editor_Box_model->getByKey('home_slider');
    $viewData = array('data' => $banner);
    $data['block_banner'] = $this->load->view("home/_banner", $viewData, true);

    //video moi nhat
    //$newestVideo = $this->Video_model->getRangeFull('video.status='.STATUS_SHOW, 0, 1, 'video.publish_date DESC');
    //$newestVideo = $this->Video_model->getNewestVideo(1,VIDEO_TYPE_DRAMA);
    $data['newestVideo'] = array();//$newestVideo;

    $dramaList = $this->Video_model->getNewestVideo(12,VIDEO_TYPE_DRAMA);
    $viewData = array('datas' => $dramaList, 'title' => 'Recent Added Drama', 'link' => base_url().'drama-list.html');
    $data['block_drama'] = $this->load->view("home/_block_item", $viewData, true);

    $dramaList = $this->Video_model->getNewestVideo(12,VIDEO_TYPE_SHOW);
    //$dramaList = $this->Series_model->getRangeFull('series.status='.STATUS_SHOW. ' AND series.type='.VIDEO_TYPE_SHOW, 0, 12, 'video.publish_date DESC');
    $viewData = array('datas' => $dramaList, 'title' => 'Recent Added Show', 'link' => base_url().'show-list.html');
    $data['block_show'] = $this->load->view("home/_block_item", $viewData, true);
    
    //$dramaList = $this->Series_model->getRangeFull('series.status='.STATUS_SHOW. ' AND series.type='.VIDEO_TYPE_MOVIE, 0, 12, 'video.publish_date DESC');
    $dramaList = $this->Video_model->getNewestVideo(12,VIDEO_TYPE_MOVIE);
    $viewData = array('datas' => $dramaList, 'title' => 'Recent Added Movie', 'link' => base_url().'movies-list.html');
    $data['block_movie'] = $this->load->view("home/_block_item", $viewData, true);
    
    //echo "<pre>"; print_r($newestVideo); die();
    $this->layout->view('home/' . $page, $data);
  }

}
