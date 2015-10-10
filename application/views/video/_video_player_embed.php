<script src="<?php echo $theme_path ?>js/jquery-2.1.3.js"></script>
<script src="<?php echo $theme_path ?>js/jquery.cookie.js"></script>

<link href="<?php echo $theme_path ?>css/video-js.css" rel="stylesheet">
<script src="<?php echo $theme_path ?>js/video-js.js"></script>
<link href="<?php echo $theme_path ?>css/video-js-resolutions.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $theme_path ?>js/video-js-resolutions.js"></script>

<link href="<?php echo $theme_path ?>css/videojs.watermark.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $theme_path ?>js/videojs.watermark.js"></script>
<script>
  videojs.options.flash.swf = "<?php echo $theme_path?>/swf/video-js.swf";
</script>

  <?php
  if($data):
    $streamingUrl = base64_decode($data);
  ?>
    <video id="_videojs" class="video-js vjs-default-skin" controls="" autoplay preload="" width="854" height="520" data-setup="{}">
      <source src="<?php echo $streamingUrl?>" type="video/mp4" data-res="360">
    </video>
  <?php else:?>
    Video will update as soon as possible. Thank you!
  <?php endif;?>
  <div id="_updating_streaming_msg" class="hidden">Try Loading video ...</div>


<script type="text/javascript">
  var BASE_URL = "<?php echo base_url() ?>";
  var video = videojs('_videojs');
  video.watermark({
    file: '<?php echo $theme_path ?>images/watermark.png',
    xpos: 100,
    ypos: 0,
    //opacity: 0.8,
    className: 'vjs-watermark'
  });

  jQuery(document).ready(function () {
    var elementId = <?php echo $video_id?>;
    var urlId = <?php echo $url_id?>;
    var type = 'video';
    var cookieName = "_refresh_"+elementId;
    if($('#_videojs').size()){
      videojs('_videojs').ready(function() {
        var videoPlayer = this;
        var cookieValue = $.cookie(cookieName);
        videoPlayer.on('error', function() {
          if(cookieValue==null){
            $('#_updating_streaming_msg').removeClass("hidden");
            $.post(BASE_URL+"ajax/logs",{element_id: elementId, url_id: urlId, type: type, status: 0}, function( data ) {
              var jData = JSON.parse(data);
              if(jData.msg == 'updated'){
                var surl = atob(jData.surl);
                if(surl){
                  $.removeCookie(cookieName, { path: '/' });
                  videoPlayer.src({"type":"video/mp4", "src":surl})
                }else{
                  $.cookie(cookieName, "yes", { expires: 1, path: '/' } );
                }
              }
            });

          }
        });
        videoPlayer.on('playing', function() {
          console.debug("playing");
          $('#_updating_streaming_msg').addClass("hidden");
          $.removeCookie(cookieName, { path: '/' });
          $.post(BASE_URL+"ajax/logs",{element_id: elementId, type: type, url_id: urlId, status: 1}, function( data ) {
          });
        });
      });
    }



  });
</script>

<div id="logs_view" element_id="<?php echo $video_id?>" data-type="video"></div>

