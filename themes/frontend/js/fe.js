jQuery(document).ready(function () {
  $('#_btnSearch').click(function () {
    $('#_frmSearch').submit();
  });
  if ($('#_filterByGenre').size()) {
    var url = BASE_URL+'series-list.html';
    $("#_filterByGenre").on("change", function(){
      var genre = $('#_filterByGenre').val();
    });
    return false;
  }

  Video.logVideo();

});
var Video = {
  logVideo:function(){
    if($('#logs_view').size()){
      var element = $('#logs_view');
      var elementId = element.attr("element_id");
      var type = element.attr("data-type");
      var cookieName = "_refresh_"+elementId;
      if($('#_videojs').size()){
        videojs('_videojs').ready(function() {
          var videoPlayer = this;
          var cookieValue = $.cookie(cookieName);
          videoPlayer.on('error', function() {
            if(cookieValue==null){
              $('#_updating_streaming_msg').removeClass("hidden");
            }
            $.post(BASE_URL+"ajax/logs",{element_id: elementId, type: type, status: 0}, function( data ) {
              if(data == 'updated'){
                if(cookieValue==null){
                  $.cookie(cookieName, "yes", { expires: 1, path: '/' } );
                  var currentLocation = window.location;
                  window.location = currentLocation;
                }
              }
            });

          });
          videoPlayer.on('play', function() {
            $('#_updating_streaming_msg').addClass("hidden");
            $.removeCookie(cookieName, { path: '/' });
            $.post(BASE_URL+"ajax/logs",{element_id: elementId, type: type, status: 1}, function( data ) {
            });
          });
        });
      }else{
        $.post(BASE_URL+"ajax/logs",{element_id: elementId, type: type, status: 0}, function( data ) {
          if(data == 'updated'){
            if(cookieValue==null){
              $.cookie(cookieName, "yes", { expires: 1, path: '/' } );
              var currentLocation = window.location;
              window.location = currentLocation;
            }
          }
        });

      }


    }
  },
}
