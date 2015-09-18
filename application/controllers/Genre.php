<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Genre extends MY_Controller {

  public $Genre_model = NULL;
  public $Series_model = NULL;
  public $_config = NULL;

  function __construct() {
    parent::__construct();
    $this->load->model('Genre_model', NULL, TRUE);
    $this->load->model('Series_model', NULL, TRUE);
    $this->Genre_model = Genre_model::getInstance();
    $this->Series_model = Series_model::getInstance();
    $this->_config = $this->config->config;
  }
  public function detail($page = "detail") {
    $uri = $this->uri->segment(2);
    $genreName = str_replace('.html', '', $uri);
    $genre = $this->Genre_model->getByName($genreName);
    if ($genre) {
      $pageNum = isset($_GET['p']) ? intval($_GET['p']) : 1;
      $offset = ($pageNum - 1)*ITEM_PER_PAGE_32;
      $seriesOfGenre = $this->Series_model->listSeriesByGenre($genre['id'], $offset, ITEM_PER_PAGE_32);

      $data['listObject'] = $seriesOfGenre;
      $total = $this->Series_model->getTotalSeriesByGenre($genre['id']);

      $data['total'] = $total;
      $data['max'] = ITEM_PER_PAGE_32;
      $data['offset'] = $offset;

      $data['genre'] = $genre;
      $this->layout->title("Series of genre ".$genre['name']);
      $this->layout->view('genre/' . $page, $data);
    } else {
      $this->layout->view('home/error', array());
    }
  }

}
