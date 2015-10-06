<script src="<?php echo $theme_path ?>js/jquery-2.1.3.js"></script>
<script src="<?php echo $theme_path ?>js/bootstrap.js"></script>
<script src="<?php echo $theme_path ?>js/slick.min.js"></script>
<script src="<?php echo $theme_path ?>js/fe.js"></script>
<link href="<?php echo $theme_path ?>css/style.css" rel="stylesheet">

<link href="<?php echo $theme_path ?>css/video-js.css" rel="stylesheet">
<script src="<?php echo $theme_path ?>js/video-js.js"></script>
<script src="<?php echo $theme_path ?>js/jquery.cookie.js"></script>
<link href="<?php echo $theme_path ?>css/video-js-resolutions.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $theme_path ?>js/video-js-resolutions.js"></script>
<script>
  videojs.options.flash.swf = "<?php echo $theme_path?>/swf/video-js.swf";
</script>

          <?php
          if($data):
            $streamingUrl = base64_decode($data);
          ?>
            <video id="_videojs" class="video-js vjs-default-skin" controls="" autoplay preload="" width="854" height="520" data-setup="{}">
              <source src="<?php echo $streamingUrl?>" type="video/mp4" data-res="360">
              <source src="<?php echo $streamingUrl?>" type="video/mp4" data-res="480" data-default:"true">
            </video>
          <?php else:?>
            Video will update as soon as possible. Thank you!
          <?php endif;?>
          <div id="_updating_streaming_msg" class="hidden">Try Loading video ...</div>

