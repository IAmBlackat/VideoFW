<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Search extends MY_Controller {

  public $Video_model = NULL;
  public $Series_model = NULL;
  public $_config = NULL;

  function __construct() {
    parent::__construct();
    $this->load->model('Video_model', NULL, TRUE);
    $this->load->model('Series_model', NULL, TRUE);
    $this->Video_model = Video_model::getInstance();
    $this->Series_model = Series_model::getInstance();
    $this->_config = $this->config->config;
  }

  public function index($page = "index") {
    //$this->output->cache(15);
    $data = array();
    if(isset($_GET['keyword']) && strlen($_GET['keyword']) > 2){
      $keyword = filterText($_GET['keyword']);
      $whereClause = "title LIKE '%" . $keyword . "%'";
      $pageNum = isset($_GET['p']) ? intval($_GET['p']) : 1;
      $offset = ($pageNum - 1) * ITEM_PER_PAGE;
      $total = $this->Series_model->getTotal($whereClause);
      $listObject = $this->Series_model->getRange($whereClause, $offset, ITEM_PER_PAGE);
      $data['keyword'] = $keyword;
      $data['total'] = $total;
      $data['max'] = ITEM_PER_PAGE;
      $data['offset'] = $offset;
      if ($listObject) {
        $data['listObject'] = $listObject;
        $this->layout->title('Search result for '.$keyword);
        $metaData['page_link'] = makeLink(0, $keyword, 'search');
        $this->layout->setMeta($metaData);
        $this->layout->view('search/' . $page, $data);
      } else {
        $this->layout->view('home/nodata', array());
      }
    }else{
      redirect('');
    }


  }
}
