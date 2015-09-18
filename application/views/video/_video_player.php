<link href="<?php echo $theme_path ?>css/video-js.css" rel="stylesheet">
<script src="<?php echo $theme_path ?>js/video-js.js"></script>
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
            <video class="video-js vjs-default-skin" controls="" preload="true" width="640" height="264" data-setup="{}">
              <source src="<?php echo $streamingUrl?>" type="video/mp4">
            </video>
          <?php else:?>
            Comming soon
          <?php endif;?>


        </div><!-- /.player -->
      </div><!-- /.col-sm-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</div>

