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
    $this->output->cache(DEFAULT_CACHE_TIME_MINUTE);
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
    $cacheName = "list_series_by_firstchar.".$char.'.p.'.$pageNum;
    $html = $this->getCacheHtml($cacheName);
    if(empty($html)){
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
        $html = $this->layout->view('series/' . $page, $data, true);
        $this->setCacheHtml($cacheName, $html);
      } else {
        $this->layout->view('home/nodata', array());
      }
    }
    if($html) echo $html;
  }
  public function series_detail($page = "series_detail") {
    $pageNum = isset($_GET['p']) ? intval($_GET['p']) : 1;
    $uri = $this->uri->segment(2);
    $seriesId = getIdFromUri($uri);
    $cacheName = "series_detail.".$seriesId.'.p.'.$pageNum;
    $html = $this->getCacheHtml($cacheName);
    if(empty($html)){
      $series = $this->Series_model->getById($seriesId);
      if ($series) {
        $offset = ($pageNum - 1)*ITEM_PER_PAGE_8;
        $videosOfSeries = $this->Video_model->getRange("series_id=" . $seriesId, $offset, ITEM_PER_PAGE_8, 'episode DESC');
        $data['videosOfSeries'] = $videosOfSeries;
        $total = $this->Video_model->getTotal("series_id=" . $seriesId);

        $newestVideoOfSeries = $this->Video_model->getNewVideoOfSeries($seriesId, 1);

        $data['total'] = $total;
        $data['max'] = ITEM_PER_PAGE_8;
        $data['offset'] = $offset;
        $data['newestVideo'] = isset($newestVideoOfSeries[0]) ? $newestVideoOfSeries[0]: array();
        $this->layout->title("Series ".$series['title']);
        $metaData['page_link'] = rtrim(base_url(), '/'). $_SERVER['REDIRECT_URL'];
        $this->layout->setMeta($metaData);
        $data['series'] = $series;
        $data['videosOfSeries'] = $videosOfSeries;
        $html = $this->layout->view('series/' . $page, $data, true);
        $this->setCacheHtml($cacheName, $html);
      } else {
        $this->layout->view('home/nodata', array());
      }
    }
    if($html) echo $html;

  }
  public function list_series_by_type($page = "list_series_by_type") {
    $pageNum = isset($_GET['p']) ? intval($_GET['p']) : 1;
    $uri = strtolower($this->uri->segment(1));
    $type = VIDEO_TYPE_DRAMA;
    $title = "Drama";
    if($uri == 'movies-list.html'){
      $type = VIDEO_TYPE_MOVIE;
      $title = "Movies";
    }elseif($uri == 'show-list.html'){
      $type = VIDEO_TYPE_SHOW;
      $title = "Show";
    }
    $cacheName = "list_series_by_type.".$type.'.p.'.$pageNum;
    $html = $this->getCacheHtml($cacheName);
    if(empty($html)){
      $whereClause = " type = {$type}";
      $total = $this->Series_model->getTotal($whereClause);
      $offset = ($pageNum - 1) * ITEM_PER_PAGE_32;
      $listObject = $this->Series_model->getRange($whereClause, $offset, ITEM_PER_PAGE_32);
      $data['total'] = $total;
      $data['max'] = ITEM_PER_PAGE_32;
      $data['offset'] = $offset;
      $title = "List all ".$title;
      $data['title'] = $title;
      $this->layout->title($title);
      $metaData['page_link'] = rtrim(base_url(), '/'). $_SERVER['REDIRECT_URL'];
      $this->layout->setMeta($metaData);
      if ($listObject) {
        $data['listObject'] = $listObject;
        $html = $this->layout->view('series/' . $page, $data, true);
        $this->setCacheHtml($cacheName, $html);
      } else {
        $this->layout->view('home/nodata', array());
      }
    }
    if($html) echo $html;

  }

}
