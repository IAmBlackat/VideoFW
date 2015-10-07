<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Video extends MY_Controller {

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

  public function video($page = "video") {
    //$this->output->cache(15);
    $uri = $this->uri->segment(2);
    $videoId = getIdFromUri($uri);
    $video = $this->Video_model->getByIdFull($videoId);
    $data = array();
    if ($video) {
      $data['video'] = $video;
      $data['server_type'] = $this->_config['server_type'];
      $seriesId = $video['series_id'];
      $series = $this->Series_model->getByIdFull($seriesId);
      $videosOfSeries = $this->Video_model->getRange("series_id=" . $seriesId, 0 , 0 , 'episode DESC');
      $randomGenreId = $series['genre'] ? array_rand($series['genre']) : 0;
      $suggestSeriesList = $this->Series_model->listSeriesByGenre($randomGenreId,0, 8, TRUE, $seriesId);
      $data['videoOfSeries'] = $videosOfSeries;
      //echo "<pre>"; print_r($videosOfSeries); die();
      $data['suggestSeriesList'] = $suggestSeriesList;
      $data['randomGenre'] = $randomGenreId ? array('id'=>$randomGenreId, 'name'=>$series['genre'][$randomGenreId]) : array();
      $this->layout->title($video['title']);
      $metaData['description'] = getVideoDescription($video);
      $metaData['page_link'] = makeLink($video['id'], $video['title'], 'video');
      $metaData['image'] = getThumbnail($series['thumbnail'], 'series');
      $this->layout->setMeta($metaData);
      $this->layout->view('video/' . $page, $data);
    } else {
      $this->layout->view('home/nodata', array());
    }
  }
  public function embed(){
    $urlId = intval($this->uri->segment(2));
    $videoId = intval($this->uri->segment(3));
    $base64StreamingUrl = $this->uri->segment(4);
    $base64StreamingUrl = rawurldecode($base64StreamingUrl);
    $theme_path = base_url().'themes/frontend/';
    $data = $this->load->view('video/_video_player_embed', array('data'=> $base64StreamingUrl, 'theme_path' => $theme_path, 'video_id' => $videoId, 'url_id' => $urlId), TRUE);
    echo $data; die();
  }
}
