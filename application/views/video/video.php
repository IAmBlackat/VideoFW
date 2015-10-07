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
  <div class="box-player">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="player">
            <div class="ui-server">
              <ul>
                <li class="active"><a href="#">Sever 1</a></li>
                <li><a href="#">Sever 2</a></li>
                <li><a href="#">Sever 3</a></li>
                <li><a href="#">Sever 4</a></li>
              </ul>
            </div><!-- /.ui-server -->
            <iframe allowfullscreen='true' webkitallowfullscreen='true' mozallowfullscreen='true' marginheight='0' marginwidth='0' scrolling='no' frameborder='0' width='854' height='520' src='<?php echo $defaultIframe?>' target='_blank'></iframe>
            <div class="ui-share">
              <span>Share with love</span>
              <ul>
                <li><a href="#" class="fa fa-facebook-square"></a></li>
                <li><a href="#" class="fa fa-twitter-square"></a></li>
              </ul>
            </div><!-- /.ui-share -->
          </div><!-- /.player -->
        </div><!-- /.col-sm-12 -->
      </div><!-- /.row -->
    </div><!-- /.container -->
  </div>
</div>
<?php endif;?>

<div class="vi-meta">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h3 class="vi-title">A Look at Myself Episode 6</h3>
        <div class="vi-des">
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto laboriosam neque dicta totam sint saepe, doloribus nesciunt, dolore eaque obcaecati molestias blanditiis natus deserunt labore animi nobis, earum suscipit voluptatem?</p>
        </div>
      </div><!-- /.col-sm-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</div><!-- /.vi-meta -->

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