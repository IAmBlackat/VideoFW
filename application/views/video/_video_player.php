<link href="<?php echo $theme_path ?>css/video-js.css" rel="stylesheet">
<script src="<?php echo $theme_path ?>js/video-js.js"></script>
<script src="<?php echo $theme_path ?>js/jquery.cookie.js"></script>
<link href="<?php echo $theme_path ?>css/video-js-resolutions.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $theme_path ?>js/video-js-resolutions.js"></script>
<script>
  videojs.options.flash.swf = "<?php echo $theme_path?>/swf/video-js.swf";
</script>
<div class="box-player">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="player">
          <?php
          if($data):
            $tmpStreamingUrlArr = array_pop($data);
            $streamingUrl = base64_decode($tmpStreamingUrlArr['streaming_url']);
          ?>
            <video id="_videojs" class="video-js vjs-default-skin" controls="" autoplay preload="" width="854" height="520" data-setup="{}">
              <source src="<?php echo $streamingUrl?>" type="video/mp4" data-res="360">
              <source src="<?php echo $streamingUrl?>" type="video/mp4" data-res="480" data-default:"true">
            </video>
          <?php else:?>
            Video will update as soon as possible. Thank you!
          <?php endif;?>
          <div id="_updating_streaming_msg" class="hidden">Try Loading video ...</div>


        </div><!-- /.player -->
      </div><!-- /.col-sm-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</div>
<div id="logs_view" element_id="<?php echo $video_id?>" data-type="video"></div>
