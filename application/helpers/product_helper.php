<?php

define('IMAGE_WIDTH', 268);
define('IMAGE_HEIGHT', 154);

/**
 * resize image
 * 
 * @param String $imgpath: input file path
 * @param String $file_path: output file path
 * @param int $max_width: width resize
 * @param int $max_height : height resize
 */
function resize_image_gd2($imgpath, $file_path = NULL, $max_width = IMAGE_WIDTH, $max_height = IMAGE_HEIGHT) {
  if ($max_width == 0 || $max_height == 0)
    die();
  //Process image
  $source_image = imagecreatefromstring(file_get_contents($imgpath));
  list($width, $height) = getimagesize($imgpath);
  $rw = $width / $max_width;
  $rh = $height / $max_height;
  $ratio = $rw > $rh ? $rw : $rh;
  if ($ratio == 0)
    die();
  $newwidth = $width / $ratio;
  $newheight = $height / $ratio;
  if ($width < $max_width && $height < $max_height) {
    $newwidth = $width;
    $newheight = $height;
  }
  $newheight = max($newheight, 1);
  $newwidth = max($newwidth, 1);
  $new_image = imagecreatetruecolor($newwidth, $newheight);
  imagecopyresized($new_image, $source_image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
  $path_info = pathinfo($imgpath); //thong tin cua file anh cu
  $exten = $path_info['extension']; //phan mo rong cua hinh anh cu
  if (empty($file_path)) {
    header("Content-type: image/jpeg");
    $stream = imagejpeg($new_image);
    die();
  } else {
    if ($exten == 'jpg')
      imagejpeg($new_image, $file_path);
    if ($exten == 'gif')
      imagegif($new_image, $file_path);
    if ($exten == 'png')
      imagepng($new_image, $file_path);
  }
}

function resize_image_IM($imgpath, $file_path = NULL, $max_width = IMAGE_WIDTH, $max_height = IMAGE_HEIGHT) {
  $size = $max_width . "x" . $max_height;
  $cmd = "convert " . $imgpath . " -resize " . $size . "^ -gravity center -extent " . $size . " " . $file_path;
  echo shell_exec($cmd);
}

/**
 * get image info, support jpg, png, gif
 * 
 * @param FILE $file
 */
function image_get_info($file) {
  if (!is_file($file)) {
    return FALSE;
  }
  $details = FALSE;
  $data = @getimagesize($file);
  $file_size = @filesize($file);

  if (isset($data) && is_array($data)) {
    $extensions = array('1' => 'gif', '2' => 'jpg', '3' => 'png');
    $extension = array_key_exists($data[2], $extensions) ? $extensions[$data[2]] : '';
    $details = array('width' => $data[0],
      'height' => $data[1],
      'extension' => $extension,
      'file_size' => $file_size,
      'mime_type' => $data['mime']);
  }
  return $details;
}

/**
 * create dir and sub dir 
 * 
 * @param string $dir
 * @param int $mode
 * @param bool $recursive
 * @return bool
 */
function mkdirs($dir, $mode = 0777, $recursive = true) {
  if (is_null($dir) || $dir === "") {
    return FALSE;
  }
  if (is_dir($dir) || $dir === "/") {
    return TRUE;
  }
  if (mkdirs(dirname($dir), $mode, $recursive)) {
    return mkdir($dir, $mode);
  }
  return FALSE;
}

/**
 * upload 1 file
 * 
 * @param file $_FILES: global file
 * @param string $dirUpload: duong dan se luu file
 * @return json: kieu du lieu tra ve
 */
function upload_image($__FILES, $dirUpload = '') {
  $ci = & get_instance();
  $data = array();
  $_FILES = $__FILES;
  foreach ($_FILES as $fileId => $file) {
    $fileName = uniqid() . '_' . time();
    if ($file["error"] > 0) {
      if ($file["error"] == 1) {
        $data['error'] = 1;
        $data['message'] = $ci->lang->line('admin.content.file.toobig');
      }
    } else {
      $info = image_get_info($file["tmp_name"]);
      if (is_array($info)) {//neu la file duoc support
        if (!file_exists($dirUpload)) {
          mkdirs($dirUpload);
        }
        $uploadFile = $dirUpload . '/' . $fileName . '.' . $info["extension"];
        if (move_uploaded_file($file["tmp_name"], $uploadFile)) {
          $fileNameReturn = $fileName . '.' . $info["extension"];
          $data['error'] = 0;
          $data['fileName'] = $fileNameReturn;
          $data['message'] = "";
          //resize_image_gd2($uploadFile, $uploadFile);
        }
      } else {
        $data['error'] = 10;
        $data['message'] = $ci->lang->line('admin.content.file.notsuport');
      }
    }
  }
  return $data;
}

/**
 * method: get selectBox
 * input: 
 *         arrayOpt array can tao selectbox
 *        params = array('ID' => '', 'Name' => '', 'Class' => '', 'MainOption' => '', 'FieldKey' => '', 'FieldValue' => '', 'Javascript' => '', 'Selected' => '')
 * output: selectBox
 * author: annnl
 * modified:
 */
function selectBox($arrayOpt, $params) {
  $id = isset($params['ID']) ? $params['ID'] : 'select';
  $class = isset($params['Class']) ? $params['Class'] : 'inputal';
  $onChange = isset($params['Javascript']) ? 'onChange="' . $params['Javascript'] . '"' : '';
  $more = '';
  if (isset($params['multiple'])) {
    $more .= " multiple='multiple' ";
  }
  if (isset($params['size'])) {
    $more .= " size='" . $params['size'] . "' ";
  }
  $selection = '<select  name="' . $params['Name'] . '" id="' . $id . '" class="' . $class . '" ' . $onChange . $more . '>';
  if (isset($params['MainOption'])) {
    if (is_bool($params['MainOption']) && $params['MainOption'] == TRUE) {
      $params['MainOption'] = DEFAULT_TEXT_SELECTBOX;
    } else {
      $params['MainOption'] = $params['MainOption'];
    }
    $selection.= '<option value="-1">' . $params['MainOption'] . '</option>';
  }
  foreach ($arrayOpt as $key => $value) {
    $optKey = isset($params['FieldKey']) ? $value[$params['FieldKey']] : $key;
    $optValue = isset($params['FieldValue']) ? $value[$params['FieldValue']] : $value;
    $checked = 'selected="selected"';
    if (!isset($params['multiple'])) {  //neu khong phai multiple
      //xu ly cho viec disable mot field nao do
      $disable = FALSE;
      if (isset($params['disable'])) {
        if (is_array($params['disable']) && !empty($params['disable'])) {
          foreach ($params['disable'] as $disableId) {
            if (is_int($disableId)) {
              if ($optKey == $disableId) {
                $disable = TRUE;
              }
            } else {
              if ($optValue == $disableId) {
                $disable = TRUE;
              }
            }
          }
        }
      }
      $strDisable = '';
      if ($disable) {
        $strDisable = ' disabled="disabled" ';
      }
      if (isset($params['Selected']) && ($optKey == $params['Selected'] || (!is_int($params['Selected']) && $optValue == $params['Selected'] && !empty($params['Selected'])))) {
        $selection .= '<option  value="' . $optKey . '" ' . $checked . $strDisable . '>' . $optValue . '</option>';
      } else {
        $selection .= '<option value="' . $optKey . '" ' . $strDisable . ' >' . $optValue . '</option>';
      }
    } else {
      $selected = FALSE;
      if (isset($params['Selected'])) {
        if (is_array($params['Selected']) && !empty($params['Selected'])) {
          foreach ($params['Selected'] as $selectedId) {
            if ($optKey == $selectedId) {
              $selected = TRUE;
            }
          }
        }
      }
      if ($selected == TRUE) {
        $selection .= '<option value="' . $optKey . '" ' . $checked . '>' . $optValue . '</option>';
      } else {
        $selection .= '<option value="' . $optKey . '" >' . $optValue . '</option>';
      }
    }
  }

  $selection .= '</select>';
  return $selection;
}

/**
 * lay content tu key va render ra view
 * 
 * @param mixed $key
 */
function renderContent($key, $get_by = 'content') {
  $contentModel = new Content_model();
  $contentObj = $contentModel->getby_key($key);
  $str = '';
  if (isset($contentObj[$get_by])) {
    $str = $contentObj[$get_by];
  }
  $html = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
  return $html;
}

/**
 * lay page tu key va render ra view
 * 
 * @param mixed $key
 */
function renderPage($key, $get_by = 'content') {
  $contentModel = new Page_model();
  $contentObj = $contentModel->getby_key($key);
  $str = '';
  if (isset($contentObj[$get_by])) {
    $str = $contentObj[$get_by];
  }
  $html = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
  return $html;
}

function getThumbnail($thumbnail, $type = 'series') {
  $thumbnailUrl = '';
  switch ($type) {
    case 'series':
      $thumbnailUrl = base_url() . SERIE_IMAGE_THUMBNAIL_PATH . $thumbnail;
      break;
    case 'editor':
      $thumbnailUrl = base_url() . EDITOR_IMAGE_THUMBNAIL_PATH . $thumbnail;
      break;
    default:
      break;
  }
  return $thumbnailUrl;
}

function stripViet($strInput, $replaceSpace = '', $code = "utf-8", $stripSpace = false) {
  $stripped_str = $strInput;
  $vietU = array();
  $vietL = array();

  if (strtolower($code) == "utf-8") {
    $i = 0;
    $vietU[$i++] = array('A', array("/Á/", "/À/", "/Ả/", "/Ã/", "/Ạ/", "/Ă/", "/Ắ/", "/Ằ/", "/Ẳ/", "/Ẵ/", "/Ặ/", "/Â/", "/Ấ/", "/Ầ/", "/Ẩ/", "/Ẫ/", "/Ậ/"));
    $vietU[$i++] = array('O', array("/Ó/", "/Ò/", "/Ỏ/", "/Õ/", "/Ọ/", "/Ô/", "/Ố/", "/Ồ/", "/Ổ/", "/Ộ/", "/Ơ/", "/Ớ/", "/Ờ/", "/Ớ/", "/Ở/", "/Ỡ/", "/Ợ/"));
    $vietU[$i++] = array('E', array("/É/", "/È/", "/Ẻ/", "/Ẽ/", "/Ẹ/", "/Ê/", "/Ế/", "/Ề/", "/Ể/", "/Ễ/", "/Ệ/"));
    $vietU[$i++] = array('U', array("/Ú/", "/Ù/", "/Ủ/", "/Ũ/", "/Ụ/", "/Ư/", "/Ứ/", "/Ừ/", "/Ử/", "/Ữ/", "/Ự/"));
    $vietU[$i++] = array('I', array("/Í/", "/Ì/", "/Ỉ/", "/Ĩ/", "/Ị/"));
    $vietU[$i++] = array('Y', array("/Ý/", "/Ỳ/", "/Ỷ/", "/Ỹ/", "/Ỵ/"));
    $vietU[$i++] = array('D', array("/Đ/"));
    $i = 0;
    $vietL[$i++] = array('a', array("/á/", "/à/", "/ả/", "/ã/", "/ạ/", "/ă/", "/ắ/", "/ằ/", "/ẳ/", "/ẵ/", "/ặ/", "/â/", "/ấ/", "/ầ/", "/ẩ/", "/ẫ/", "/ậ/"));
    $vietL[$i++] = array('o', array("/ó/", "/ò/", "/ỏ/", "/õ/", "/ọ/", "/ô/", "/ố/", "/ồ/", "/ổ/", "/ỗ/", "/ộ/", "/ơ/", "/ớ/", "/ờ/", "/ở/", "/ỡ/", "/ợ/"));
    $vietL[$i++] = array('e', array("/é/", "/è/", "/ẻ/", "/ẽ/", "/ẹ/", "/ê/", "/ế/", "/ề/", "/ể/", "/ễ/", "/ệ/"));
    $vietL[$i++] = array('u', array("/ú/", "/ù/", "/ủ/", "/ũ/", "/ụ/", "/ư/", "/ứ/", "/ừ/", "/ử/", "/ữ/", "/ự/"));
    $vietL[$i++] = array('i', array("/í/", "/ì/", "/ỉ/", "/ĩ/", "/ị/"));
    $vietL[$i++] = array('y', array("/ý/", "/ỳ/", "/ỷ/", "/ỹ/", "/ỵ/"));
    $vietL[$i++] = array('d', array("/đ/"));
  }
  for ($i = 0; $i < count($vietL); $i++) {
    $stripped_str = preg_replace($vietL[$i][1], $vietL[$i][0], $stripped_str);
    $stripped_str = preg_replace($vietU[$i][1], $vietU[$i][0], $stripped_str);
  }
  if ($stripSpace) {
    $stripped_str = str_replace(' ', '', $stripped_str);
  }
  if ($replaceSpace) {
    return $stripped_str = preg_replace(array('[^[^a-zA-Z0-9]+|[^a-zA-Z0-9]+$]', '[[^a-zA-Z0-9\-]+]'), array('', $replaceSpace), $stripped_str);
  }
  return $stripped_str;
}

function buildQueryString($params = array(), $reset = false) {
  $ret = '';
  if (is_array($params)) {
    if ($reset) {
      $ret = http_build_query($params);
    } else {
      $query_data = array();
      parse_str($_SERVER['QUERY_STRING'], $query_data);
      foreach ($params as $pKey => $pVal) {
        unset($query_data[$pKey]);
      }
      foreach ($params as $pKey => $pVal) {
        if ($pVal !== NULL)
          $query_data[$pKey] = $pVal;
      }
      $ret = http_build_query($query_data);
    }
  }
  if($ret){
    $ret = '?'.$ret;
  }
  return $ret;
}

function subString($string, $length = 30) {
  $str = substr($string, 0, $length);
  $str = $str . '...';
  return $str;
}

function getImage($type = 'content', $dbFileName, $size = '55x55') {
  $imagePath = "";
  if ($dbFileName) {
    $imagePath = IMAGE_URL . "/" . $type . "/" . $size . '/' . $dbFileName;
  } else {
    $imagePath = IMAGE_URL . "/default.jpg";
  }
  return $imagePath;
}

function makeLink($id, $title, $type='video', $country=NULL) {
  $link = '';
  $alias = stripViet(str_replace('-', '', $title), '-');
  switch ($type) {
    case 'video':
      $prefix = 'video';
      $link = $prefix . '/' . $alias . '-' . $id . '.html';
      break;
    case 'series':
    case 'country':
      $CI =& get_instance();
      $config = $CI->config->config;
      $contryAlias = isset($config['countries_alias'][$country]) ? $config['countries_alias'][$country] : 'other-drama';
      $prefix = stripViet($contryAlias, '-');
      $link = $prefix . '/' . $alias . '-' . $id . '.html';
      break;
    case 'genre':
      $prefix = 'genre';
      $link = $prefix . '/' . $alias . '.html';
      break;
    case 'search':
      $prefix = 'search';
      $link = $prefix . '?keyword=' . $title;
      break;
    case 'list_series_by_char':
      $link = 'series-list/char-star-'.$title.'.html';
      break;
    case 'list_series_all':
      $link = 'series-list.html';
      break;
    default:
      break;
  }
  return base_url() . $link;
}

/**
 * lay cac thuoc tinh meta tu html
 * @param string  $html
 * @return array $rmetas html meta
 */
function getMetaFromHtml($html) {
  $doc = new DomDocument();
  @$doc->loadHTML($html);
  $xpath = new DOMXPath($doc);
  $query = '//*/meta';
  $metas = $xpath->query($query);
  $rmetas = array();
  foreach ($metas as $meta) {
    $property = $meta->getAttribute('itemprop');
    if ($meta->getAttribute('property')) {
      $property = $meta->getAttribute('property');
    }
    $content = $meta->getAttribute('content');
    //if(!empty($property) && preg_match('#^og:#', $property)) {
    $rmetas[$property] = $content;
    //}
  }
  $image = $rmetas['og:image'];
  $fileExt = strtolower(substr($image, strrpos($image, '.') + 1));
  $file = "";
  if (in_array($fileExt, array('jpg', 'png', 'gif'))) {
    $file = $image;
  }
  $rmetas['og:image'] = $file;
  return $rmetas;
}

/**
 * download image tu ben ngoai va luu vao he thong
 * @param unknown $siteImage
 * @return string
 */
function saveImageFromSite($siteImage, $dirUpload = SERIE_IMAGE_THUMBNAIL_PATH) {
  //tao file luu vao he thong
  $fileName = uniqid() . '_' . time() . ".jpg";
  $fileFull = $dirUpload . $fileName;
  $fileContent = file_get_contents($siteImage);
  $result = file_put_contents($fileFull, $fileContent);
  return $fileName;
}

function filterText($string, $isStripTag = false) {
  //$string = trim(str_replace("'", "\\'", $string));
  //$string = trim(strip_quotes($string));
  $string = trim(strip_slashes($string));
  return $isStripTag ? strip_tags($string) : $string;
}

function trimtext($text) {
  $text = trim($text);
  return $text;
}

function getFileContent($url, $option = array()) {
  try {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    //curl_setopt($ch, CURLOPT_PROXY, "proxy.tma.com.vn:8080");
    //curl_setopt($ch, CURLOPT_PROXY, "111.11.255.11");
    //curl_setopt($ch, CURLOPT_PROXYPORT, "80");

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($ch);
    if (curl_errno($ch)) {
      echo "\nURL: " . $url . " Error is : " . curl_error($ch) . "\n";
    }
    curl_close($ch);
    return $content;
  } catch (Exception $e) {
    print_r($e);
    return false;
  }
}

function format2DateTime($time) {
  $ret = time();
  $iTime = intval($time);
  $tmpTimeStamp = 0;
  if (strpos($time, 'week')) {
    $tmpTimeStamp = $iTime * 7 * 24 * 60 * 60;
  } elseif (strpos($time, 'day')) {
    $tmpTimeStamp = $iTime * 24 * 60 * 60;
  } elseif (strpos($time, 'hour')) {
    $tmpTimeStamp = $iTime * 60 * 60;
  } else {
    $ret = strtotime($time);
  }
  $ret = $ret - $tmpTimeStamp;
  $dateTime = date('Y/m/d H:i:s', $ret);
  return $dateTime;
}

function getStrStatus($status) {
  $strStatus = 'Hide';
  switch ($status) {
    case STATUS_WAIT_FOR_APPROVE:
      $strStatus = 'Wait for approve';
      break;
    case STATUS_SHOW:
      $strStatus = 'Show';
      break;
    default:
      break;
  }
  return $strStatus;
}

function getStringBetween($string, $start, $end, $includeTag = true) {
  $string = " " . $string;
  $ini = strpos($string, $start);
  if ($ini == 0)
    return "";
  $ini += strlen($start);
  $len = strpos($string, $end, $ini) - $ini;
  $newString = substr($string, $ini, $len);
  $newString = $includeTag ? $start . $newString . $end : $newString;
  return $newString;
}

function getVideoDescription($videoInfo) {
  if ($videoInfo['description']) {
    $string = $videoInfo['description'];
  } else {
    $string = 'Video ' . $videoInfo['title'];
    if ($videoInfo['has_sub']) {
      $string .= ' english sub is released';
    } else {
      $string .= ' Raw is released';
    }
  }
  return $string. '. '.EXTRA_DESCRIPTION;
}

function getIdFromUri($uri) {
  $uri = str_replace('.html', '', $uri);
  $lastnum = 0;
  if (preg_match_all('/\d+/', $uri, $numbers)){
    $lastnum = end($numbers[0]);
  }
  return intval($lastnum);
}
function getCharFromUri($uri) {
  $uri = strtolower($uri);
  $uri = str_replace('.html', '', $uri);
  $char = str_replace('char-star-', '', $uri);
  return $char;
}
function _debug($data){
  if(isset($_GET['debug']) && $_GET['debug']=='true'){
    echo $data;
  }
}