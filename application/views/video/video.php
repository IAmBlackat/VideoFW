<?php
$videoUrlArr = $video['video_url'];
$defaultIframe = '';
$selectedServerData = array();
$hasGoogleServer = FALSE;
if($videoUrlArr){
  $i = 0;
  foreach($videoUrlArr as $urlId => $urlData) {
    if ($i == 0) {
      $selectedServerData = $urlData;
    }
    $i++;
    $serverType = isset($server_type[$urlData['server_type']]) ? $server_type[$urlData['server_type']] : SERVER_TYPE_STANDARD;
    $videoUrlArr[$urlId]['server_type'] = $serverType;
    if ($urlData['server_type'] == SERVER_TYPE_STANDARD || $urlData['server_type'] == SERVER_TYPE_HD) {
      $selectedServerData = $urlData;
      $hasGoogleServer = TRUE;
    }

    $iframeSrc = '';
    if ($urlData['server_type'] == SERVER_TYPE_STANDARD || $urlData['server_type'] == SERVER_TYPE_HD) {
      $iframeSrc = base_url('embed').'/'.$urlData['id'] .'/'.$urlData['video_id']. '/' . rawurlencode($urlData['streaming_url']);
    } else {
      $iframeSrc = $urlData['iframe_url'];
    }
    $videoUrlArr[$urlId]['iframe_src'] = $iframeSrc;
  }
  if($selectedServerData['server_type'] == SERVER_TYPE_STANDARD || $selectedServerData['server_type'] == SERVER_TYPE_HD){
    $defaultIframe = base_url('embed').'/'.$selectedServerData['id'].'/'.$selectedServerData['video_id'].'/'.rawurlencode($selectedServerData['streaming_url']);
  }else{
    $defaultIframe = $selectedServerData['iframe_url'];
  }
}

$videoLink = makeLink($video['id'], $video['title'], 'video');
?>

<div>
  <div class="box-player">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="player">
          <?php if($videoUrlArr):?>
            <div class="ui-server">
              <ul>
                <?php
                foreach($videoUrlArr as $urlData):
                ?>
                <li class="<?php if($urlData['id'] == $selectedServerData['id']) echo 'active'?>"><a class="_select_server <?php echo $urlData['server_type']['alias']?>" href="#" data-id="<?php echo $urlData['id']?>" data-iframe="<?php echo $urlData['iframe_src']?>"><?php echo $urlData['server_type']['name']?></a></li>
                <?php
                endforeach;
                ?>

              </ul>
            </div><!-- /.ui-server -->
            <iframe id="_video_player_iframe" allowfullscreen='true' webkitallowfullscreen='true' mozallowfullscreen='true' marginheight='0' marginwidth='0' scrolling='no' frameborder='0' width='854' height='520' src='<?php echo $defaultIframe?>' target='_blank'></iframe>
            <div class="ui-share">
              <span>Share with love</span>
              <ul>
                <li><a id="_fbshare" data-href="http://www.facebook.com/sharer.php?u=<?php echo $videoLink?>&t=<?php echo $video['title']?>" href="#" class="fa fa-facebook-square"></a></li>
                <li><a id="_twiter_share" data-href="http://twitter.com/share?url=<?php echo $videoLink?>&text=<?php echo $video['title']?>&count=none/" href="#" class="fa fa-twitter-square"></a></li>
                <li><div class="fb-send" data-href="<?php echo $videoLink?>"></div></li>
              </ul>
            </div><!-- /.ui-share -->
            <i>If there any errors appear, please <a href="#" id="_reload_page"> reload the page </a> first. If errors reappear then report to us. Thanks and Enjoy!!</i>
            <?php if($hasGoogleServer==FALSE):?>
              <div id="logs_view" element_id="<?php echo $video['id']?>" data-type="video"></div>
            <?php endif;?>
          <?php else:?>

            <i>We lost few servers.Then, few episodes can't Watch. Please <a href="#" id="_reload_page"> reload the page </a> first. We fixing all episodes. Thanks and Enjoy!!</i>
            <div id="logs_view" element_id="<?php echo $video['id']?>" data-type="video"></div>
          <?php endif;?>

          </div><!-- /.player -->
        </div><!-- /.col-sm-12 -->
      </div><!-- /.row -->
    </div><!-- /.container -->
  </div>
</div>

<div class="vi-meta">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h3 class="vi-title"><?php echo $video['title']?></h3>
        <div class="vi-des">
          <p><?php echo getVideoDescription($video) ?></p>
        </div>
      </div><!-- /.col-sm-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</div><!-- /.vi-meta -->

<div class="container">
  <div class="row">
    <?php $this->load->view('templates/sidebar'); ?>
    <div class="wrap-content col-sm-9">
      <?php if($videoOfSeries):?>
      <div class="ui-list-episode">
        <ul class="list-episode">
          <?php foreach($videoOfSeries as $v):?>
          <!--<li>
            <span class="slabel <?php echo $v['has_sub'] ? 'sub' : 'raw'?>"><?php echo $v['has_sub'] ? 'Sub' : 'Raw'?></span>
            <a href="<?php echo makeLink($v['id'], $v['title'], 'video')?>"><?php echo $v['title']?></a><i><?php echo date("Y/m/d", strtotime($v['publish_date']));?></i>
          </li>-->
          <li>
            <span class="slabel <?php echo $v['has_sub'] ? 'sub' : 'raw'?>"><?php echo $v['has_sub'] ? 'Sub' : 'Raw'?></span>
            <a href="<?php echo makeLink($v['id'], $v['title'], 'video')?>">Episode <?php echo $v['episode'] > 9 ? $v['episode'] : '0'.$v['episode']?></a></i>
          </li>
          <?php endforeach;?>
        </ul><!-- /.list-episode -->
      </div><!-- /.list-episode -->
      <?php endif;?>

      <?php $this->load->view('video/_video_item', array('randomGenre'=> $randomGenre,'suggestSeriesList' => $suggestSeriesList)); ?>
      <div class="box-comment">
        <div class="fb-comments" data-href="<?php echo $videoLink?>" data-width="847" data-numposts="10"></div>
      </div>


    </div><!-- /.col-sm-9 -->
  </div><!-- /.row -->
</div><!-- /.container -->