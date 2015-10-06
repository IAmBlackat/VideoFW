<?php

/**
 * CodeIgnighter layout support library
 *  with Twig like inheritance blocks
 *
 * v 1.0
 *
 *
 * @author Constantin Bosneaga
 * @email  constantin@bosneaga.com
 * @url    http://a32.me/
 */
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Layout {

  private $obj;
  private $layout_view;
  private $title = '';
  private $css_list = array(), $js_list = array();
  private $block_list, $block_new, $block_replace = false;
  private $skinUrl = '';
  private $metaData = array();
  private $extraData = array();
  function Layout() {
    $this->obj = & get_instance();
    $this->layout_view = "";
    // Grab layout from called controller
    if (isset($this->obj->layout_view))
      $this->layout_view = $this->obj->layout_view;
  }

  function setTemplate($layoutDir) {
    $this->layout_view = $layoutDir;
  }
  public function setExtraData($data){
    $this->extraData = $data;
  }
  public function getExtraData(){
    return $this->extraData;
  }
  function setSkin($skinUrl) {
    $this->skinUrl = $skinUrl;
  }

  function getSkin() {
    return $this->skinUrl;
  }

  function view($view, $data = null, $return = false) {

    $data['extra_data'] = $this->extraData;
    // Render template
    $data['theme_path'] = $this->getSkin();
    $data['content_for_layout'] = $this->obj->load->view($view, $data, true);
    $data['title_for_layout'] = !empty($this->title) ? DEFAULT_TITLE .' - '.$this->title : DEFAULT_TITLE;
    $data['description_for_layout'] = isset($this->metaData['description']) ? $this->metaData['description'] : DEFAULT_DESCRIPTION;
    $data['keyword_for_layout'] = isset($this->metaData['keyword_for_layout']) ? $this->metaData['keyword_for_layout'] : DEFAULT_KEYWORD;

    $urlForLayout = base_url();
    if(isset($this->metaData['page_link'])){
      $urlForLayout = $this->metaData['page_link'];
    }elseif(isset($_SERVER['REDIRECT_URL'])){
      $urlForLayout = rtrim(base_url(), '/').$_SERVER['REDIRECT_URL'];
    }
    $data['url_for_layout'] = $urlForLayout;

    $data['image_for_layout'] = isset($this->metaData['image']) ? $this->metaData['image'] : base_url() . UPLOAD_PATH . 'image.jpg';;
    // Render resources
    $data['js_for_layout'] = '';
    foreach ($this->js_list as $v)
      $data['js_for_layout'] .= sprintf('<script type="text/javascript" src="%s"></script>', $v);

    $data['css_for_layout'] = '';
    foreach ($this->css_list as $v)
      $data['css_for_layout'] .= sprintf('<link rel="stylesheet" type="text/css"  href="%s" />', $v);
    // Render template
    $this->block_replace = true;
    $output = $this->obj->load->view($this->layout_view, $data, $return);
    return $output;
  }

  /**
   * Set page title
   *
   * @param $title
   */
  function title($title) {
    $this->title = $title;
  }

  function setMeta($metaArr) {
    $this->metaData = $metaArr;
  }

  /**
   * Adds Javascript resource to current page
   * @param $item
   */
  function js($item) {
    $this->js_list[] = $item;
  }

  /**
   * Adds CSS resource to current page
   * @param $item
   */
  function css($item) {
    $this->css_list[] = $item;
  }

  /**
   * Twig like template inheritance
   *
   * @param string $name
   */
  function block($name = '') {
    if ($name != '') {
      $this->block_new = $name;
      ob_start();
    } else {
      if ($this->block_replace) {
        // If block was overriden in template, replace it in layout
        if (!empty($this->block_list[$this->block_new])) {
          ob_end_clean();
          echo $this->block_list[$this->block_new];
        }
      } else {
        $this->block_list[$this->block_new] = ob_get_clean();
      }
    }
  }

}
