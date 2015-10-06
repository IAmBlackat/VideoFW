<?php
$videoUrlArr = $video['video_url'];
$defaultIframe = '';
if($videoUrlArr):
  $selectedServer = 0;
  $i = 0;
  $selectedServerData = array();
  $firstUrlId = 0;
  foreach($videoUrlArr as $urlId => $urlData):
    if($i==0){
      $selectedServerData = $urlData;
    }
    $i++;
    if($urlData['server_type']==SERVER_TYPE_STANDARD || $urlData['server_type'] == SERVER_TYPE_HD){
      $selectedServerData = $urlData;
    }
    $serverType = isset($server_type[$urlData['server_type']]) ? $server_type[$urlData['server_type']] : SERVER_TYPE_STANDARD;
    $iframeSrc = '';
    if($urlData['iframe_url']){
      $iframeSrc = $urlData['iframe_url'];
    }else{
      $iframeSrc = base_url('embed').'/'.rawurlencode($urlData['streaming_url']);
    }
?>
  <div class="_select_server" data-id="<?php echo $urlData['id']?>" data-iframe="<?php echo $iframeSrc?>"><?php echo $serverType['name']?></div>
<?php
  endforeach;
  if($selectedServerData['iframe_url']){
    $defaultIframe = $selectedServerData['iframe_url'];

  }else{
    $defaultIframe = base_url('embed').'/'.rawurlencode($selectedServerData['streaming_url']);
  }
endif
?>
<?php if($defaultIframe):?>
<div id="_video_player">
  <iframe allowfullscreen='true' webkitallowfullscreen='true' mozallowfullscreen='true' marginheight='0' marginwidth='0' scrolling='no' frameborder='0' width='727' height='450' src='<?php echo $defaultIframe?>' target='_blank'></iframe>
</div>
<?php endif;?>

<!--<div id="logs_view" element_id="<?php echo $video['id']?>" data-type="video"></div> -->
<div class="container">
  <div class="row">
    <?php $this->load->view('templates/sidebar'); ?>
    <div class="wrap-content col-sm-9">
      <?php if($videoOfSeries):?>
      <div class="ui-list-episode">
        <ul class="list-episode">
          <?php foreach($videoOfSeries as $v):?>
          <li>
            <span class="slabel <?php echo $v['has_sub'] ? 'sub' : 'raw'?>"><?php echo $v['has_sub'] ? 'Sub' : 'Raw'?></span>
            <a href="<?php echo makeLink($v['id'], $v['title'], 'video')?>"><?php echo $v['title']?></a><i><?php echo date("Y/m/d", strtotime($v['publish_date']));?></i>
          </li>
          <?php endforeach;?>
        </ul><!-- /.list-episode -->
      </div><!-- /.list-episode -->
      <?php endif;?>

      <?php $this->load->view('video/_video_item', array('randomGenre'=> $randomGenre,'suggestSeriesList' => $suggestSeriesList)); ?>


    </div><!-- /.col-sm-9 -->
  </div><!-- /.row -->
</div><!-- /.container -->