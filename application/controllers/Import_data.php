<?php
class Import_Data extends MY_Controller {
	public $Video_model = NULL;
  public $Config_model = NULL;
	protected  $_config = NULL;
	public $dramaCool = null;
	function __construct() {
		parent::__construct(TRUE);
		set_userdata( 'active_menu' , strtolower(get_class( $this )));
		$this->_config = $this->config->config;
		$this->load->library('simple_html_dom');
		$this->load->file(APPPATH.'components/ImportDramaCool.php');
		$this->dramaCool = new ImportDramaCool();
    $this->load->model('Config_model', NULL, TRUE);
    $this->Config_model = Config_model::getInstance();
		set_time_limit(0);
		
	}
  
	public function index($page = 'import') {
		$data = array();
    $importType = $this->_config['import_type'];
    unset($importType[IMPORT_TYPE_COUNTRY]);
    
    $importTypeSelectbox = selectBox($importType, array('Name' => 'import_type_id', 'Selected' => FALSE));
    $data['importTypeSelectbox'] = $importTypeSelectbox;
    
    $typeSelectbox = selectBox($this->_config['video_type'], array('Name' => 'type', 'Selected' => FALSE));
    $data['typeSelectbox'] = $typeSelectbox;
    
		$this->layout->title("Import Data");
		$this->layout->view('import_data/' . $page, $data);
	}
  #/Applications/XAMPP/xamppfiles/bin/php-5.5.15 /Datas/Sources/VideoFW/index.php import_data console_update_new_data 
  public function console_update_new_data(){
    
    $dramaLink = "http://www.dramacool.com/recently-added";
    $movieLink = "http://www.dramacool.com/recently-added-movie";
    $kshowLink = "http://www.dramacool.com/recently-added-kshow";
    echo "console_update_new_data:\n";
    $this->dramaCool->importHomePage($dramaLink, array('type'=>VIDEO_TYPE_DRAMA));
    $this->dramaCool->importHomePage($movieLink, array('type'=>VIDEO_TYPE_MOVIE));
    $this->dramaCool->importHomePage($kshowLink, array('type'=>VIDEO_TYPE_SHOW));
  }
  #/Applications/XAMPP/xamppfiles/bin/php-5.5.15 /Datas/Sources/VideoFW/index.php import_data console_update_video_streaming_status 
  public function console_update_video_streaming_status(){
    echo "console_update_video_streaming_status:\n";
    $updateStreamingStatus = $this->Config_model->getValue('update_streaming_status');
    if($updateStreamingStatus==1){
      $this->Video_model->updateImportStatus(0);
    }
    $this->Config_model->setValue('update_streaming_status', 0);//begin
    die();
  }
  #/Applications/XAMPP/xamppfiles/bin/php-5.5.15 /Datas/Sources/VideoFW/index.php import_data console_update_video_streaming 
  public function console_update_video_streaming(){
    echo "console_update_video_streaming:\n";
    $time1 = time();
    $whereClause = " import_status IS NULL OR import_status=0";
    $videoList = $this->Video_model->getRange($whereClause, 0, 1, 'id ASC');
    if($videoList){
      foreach($videoList as $video){
        $originalUrl = $video['original_url'];
        $this->dramaCool->updateStreaming($video['id'], $originalUrl);
        $dataStatus = array();
        $dataStatus['import_status'] = 1;
        //$this->Video_model->update($video['id'], $dataStatus);
      }
    }else{
      $this->Config_model->setValue('update_streaming_status', 1);//done
    }
    $time2 = time();
    echo "console_update_video_streaming: ".($time2 - $time1)."\n";
    die("exit");
  }
  #/Applications/XAMPP/xamppfiles/bin/php-5.5.15 /Datas/Sources/VideoFW/index.php import_data console 
  //auto scan all data in website (run first clone)
  public function console(){
    $time1 = time();
    echo "console:\n";
    $this->_importDrama();
    $this->_importShow();
    $this->_importMovies();
    $time2 = time();
    echo "\nTotal time : ".($time2-$time1); echo "s\n";
    die("exit console");
  }
  private function _importDrama(){
     $time1 = time();
    $dramaUrls = array(
      'http://www.dramacool.com/category/korean-drama',
      'http://www.dramacool.com/category/japanese-drama',
      'http://www.dramacool.com/category/taiwanese-drama',
      'http://www.dramacool.com/category/hong-kong-drama',
      'http://www.dramacool.com/category/chinese-drama'
      );
    foreach($dramaUrls as $dramaUrl){
      $this->dramaCool->importFromCountryUrl($dramaUrl, array('type'=>VIDEO_TYPE_DRAMA));
    }
    $time2 = time();
    echo "\nTotal time _importDrama : ".($time2-$time1); echo "s\n";
  }
  private function _importShow(){
    $time1 = time();
    //block ngoai le, ko co link see more
    $seriesArr = array(
      'http://www.dramacool.com/drama-detail/-finding-sugar-man.html',
      'http://www.dramacool.com/drama-detail/-my-little-television.html',
      'http://www.dramacool.com/drama-detail/1-vs-100.html',
      'http://www.dramacool.com/drama-detail/100-choice.html',
      'http://www.dramacool.com/drama-detail/100-days-of-miracles.html',
      'http://www.dramacool.com/drama-detail/100-sencond.html'
      );
    foreach($seriesArr as $seriesLink){
      $this->dramaCool->importFromSeriesUrl($seriesLink, array('type'=>VIDEO_TYPE_SHOW));
    }
    $urls = array(
      'http://www.dramacool.com/kshow',
      );
    foreach($urls as $url){
      $this->dramaCool->importFromCountryUrl($url, array('type'=>VIDEO_TYPE_SHOW));
    }
    $time2 = time();
    echo "\nTotal time _importShow : ".($time2-$time1); echo "s\n";
  }
  
  private function _importMovies(){
    $time1 = time();
    $urls = array(
      'http://www.dramacool.com/category/chinese-movies',
      'http://www.dramacool.com/category/hong-kong-movies',
      'http://www.dramacool.com/category/japanese-movies',
      'http://www.dramacool.com/category/korean-movies',
      'http://www.dramacool.com/category/taiwanese-movies'
    );
    foreach($urls as $url){
      $this->dramaCool->importFromCountryUrl($url, array('type'=>VIDEO_TYPE_MOVIE));
    }
    $time2 = time();
    echo "\nTotal time _importMovies : ".($time2-$time1); echo "s\n";
  }
	public function save(){
		$data = $_POST;
		$importType = $this->_config['import_type'];
    unset($importType[IMPORT_TYPE_COUNTRY]);
    $importTypeSelectbox = selectBox($importType, array('Name' => 'import_type_id', 'Selected' => $data['import_type_id']));
    $data['importTypeSelectbox'] = $importTypeSelectbox;
    $typeSelectbox = selectBox($this->_config['video_type'], array('Name' => 'type', 'Selected' => $data['type']));
    $data['typeSelectbox'] = $typeSelectbox;
		$message = $this->validate($data);
		if(!empty($message)){
			set_flash_error($message);
		}
		if (!has_error()) {
			$siteUrl = strtolower($data['site_url']);
			$id = 0;
			$importTypeId = $data['import_type_id'];
      if(strpos($siteUrl, "dramacool.com/")){
        if($importTypeId==IMPORT_TYPE_VIDEO){
          $ret = $this->dramaCool->importFromVideoUrl($siteUrl);
        }elseif($importTypeId==IMPORT_TYPE_SERIES){
          $ret = $this->dramaCool->importFromSeriesUrl($siteUrl);
        }elseif($importTypeId==IMPORT_TYPE_COUNTRY){
          die();
          //$ret = $this->dramaCool->importFromCountryUrl($siteUrl);
        }
        $id=$ret['id'];
      }
			if($id){
				$url = base_url() . 'admin_video/show?id='.$id;
				set_flash_message($this->lang->line('admin.import.success'));
				redirect($url);
			}else{
				set_flash_error($ret['msg']);
				$this->layout->title('Import Data');
				$this->layout->view('import_data/import', $data);
			}
		}else{
			$this->layout->title('Import Data');
			$this->layout->view('import_data/import', $data);
		}
	}
	public function validate($data){
		$message = "";
		if (empty($data['site_url'])) {
			$message .= "<li>".$this->lang->line('admin.import.require.link')."</li>";
		}
		if (empty($data['import_type_id'])) {
			$message .= "<li>".$this->lang->line('admin.import.require.import_type')."</li>";
		}
		return $message;
	}
	
}
