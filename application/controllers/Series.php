<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Series extends MY_Controller {

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

  public function list_all_series($page = "list_all_series") {
    //$this->output->cache(15);
    /*
    $listGenre = $this->Genre_model->getAll();
    $listGenre = array_merge(array('--Select Genre--'), $listGenre);
    $genreSelectbox = selectBox($listGenre, array('Name' => 'genre', 'Class'=>'frm-select', 'ID' => '_filterByGenre', 'Selected' => FALSE));
    $data['genreSelectbox'] = $genreSelectbox;

    $statusArr = array("-1" => '--Select Status',
      SERIES_STATUS_ONGOING => 'On Going',
      SERIES_STATUS_COMPLETE => 'Completed');
    $statusSelectbox = selectBox($statusArr, array('Name' => 'status', 'ID' => '_filterByStatus', 'Class'=>'frm-select'));
    $data['statusSelectbox'] = $statusSelectbox;

    $years = range(2000, date('Y', time()));
    $yearArr['-1'] = '--Select year--';
    for($year=2015; $year > 2000; $year--){
      $yearArr[$year] = $year;
    }
    $yearSelectbox = selectBox($yearArr, array('Name' => 'year', 'ID' => '_filterByYear', 'Class'=>'frm-select','Selected' => FALSE));
    $data['yearSelectbox'] = $yearSelectbox;

    $typeArr = array('-1' => '--Select type--',
      VIDEO_TYPE_DRAMA		=> 'Drama',
      VIDEO_TYPE_MOVIE	=> 'Movie',
      VIDEO_TYPE_SHOW	=> 'Show',
      );
    $typeSelectbox = selectBox($typeArr, array('Name' => 'type', 'ID' => '_filterByType', 'Class'=>'frm-select','Selected' => FALSE));
    $data['typeSelectbox'] = $typeSelectbox;
*/
    $listAllSeries = $this->Series_model->listSeriesGroupByFirstChar(6);
    if ($listAllSeries) {
      $data['listAllSeries'] = $listAllSeries;
      $this->layout->title("List all series");
      $metaData['page_link'] = base_url()."series-list.html";
      $this->layout->setMeta($metaData);
      $this->layout->view('series/' . $page, $data);
    } else {
      $this->layout->view('home/nodata', array());
    }
  }
  public function list_series_by_firstchar($page = "list_series_by_firstchar") {
    $pageNum = isset($_GET['p']) ? intval($_GET['p']) : 1;
    $uri = $this->uri->segment(2);
    $char = getCharFromUri($uri);
    $total = $this->Series_model->totalSeriesByFirstChar($char);
    $offset = ($pageNum - 1) * ITEM_PER_PAGE_32;
    $listObject = $this->Series_model->listSeriesByFirstChar($char, $offset, ITEM_PER_PAGE_32);
    $data['total'] = $total;
    $data['max'] = ITEM_PER_PAGE_32;
    $data['offset'] = $offset;
    $char = ucfirst($char);
    $data['char'] = $char;
    if ($listObject) {
      $data['listObject'] = $listObject;
      $this->layout->title("List all series by first char ".$char);
      $metaData['page_link'] = rtrim(base_url(), '/'). $_SERVER['REDIRECT_URL'];
      $this->layout->setMeta($metaData);
      $this->layout->view('series/' . $page, $data);
    } else {
      $this->layout->view('home/nodata', array());
    }
  }
  public function series_detail($page = "series_detail") {
    $pageNum = isset($_GET['p']) ? intval($_GET['p']) : 1;
    $offset = ($pageNum - 1)*ITEM_PER_PAGE_8;
    $uri = $this->uri->segment(2);
    $seriesId = getIdFromUri($uri);

    $series = $this->Series_model->getById($seriesId);
    $videosOfSeries = $this->Video_model->getRange("series_id=" . $seriesId, $offset, ITEM_PER_PAGE_8);
    $data['videosOfSeries'] = $videosOfSeries;
    $total = $this->Video_model->getTotal("series_id=" . $seriesId);

    $newestVideoOfSeries = $this->Video_model->getNewVideoOfSeries($seriesId, 1);

    $data['total'] = $total;
    $data['max'] = ITEM_PER_PAGE_8;
    $data['offset'] = $offset;
    $data['newestVideo'] = isset($newestVideoOfSeries[0]) ? $newestVideoOfSeries[0]: array();
    if ($videosOfSeries) {
      $data['series'] = $series;
      $data['videosOfSeries'] = $videosOfSeries;
      $this->layout->title("Series ".$series['title']);
      $metaData['page_link'] = rtrim(base_url(), '/'). $_SERVER['REDIRECT_URL'];
      $this->layout->setMeta($metaData);
      $this->layout->view('series/' . $page, $data);
    } else {
      $this->layout->view('home/nodata', array());
    }
  }

}
