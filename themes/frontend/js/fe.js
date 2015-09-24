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
    if($('#logs_view').size() && $('#_videojs').size()){
      videojs('_videojs').ready(function() {
        var videoPlayer = this;
        var element = $('#logs_view');
        var elementId = element.attr("element_id");
        var type = element.attr("data-type");
        var cookieName = "_refresh_"+elementId;
        videoPlayer.on('error', function() {

          $.post(BASE_URL+"ajax/logs",{element_id: elementId, type: type, status: 0}, function( data ) {
            if(data == 'updated'){

              var cookieValue = $.cookie(cookieName);
              if(cookieValue==null || cookieValue == 'no'){
                $.cookie(cookieName, "yes", { expires: 1, path: '/' } );
                var currentLocation = window.location;
                window.location = currentLocation;
              }
            }
          });

        });
        videoPlayer.on('play', function() {
          $.cookie(cookieName, "no", { expires: 1, path: '/' } );
          $.post(BASE_URL+"ajax/logs",{element_id: elementId, type: type, status: 1}, function( data ) {
          });
        });
      });

    }
  },
}
