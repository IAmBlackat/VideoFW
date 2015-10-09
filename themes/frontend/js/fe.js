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
  Video.selectServer();
  $('#_fbshare').click(function(){
    var url = $(this).attr("data-href");
    window.open(url, "share", "height=600,width=600,resizable=1" )
    return false;
  });
  $('#_twiter_share').click(function(){
    var url = $(this).attr("data-href");
    window.open(url, "tweet", "height=600,width=600,resizable=1" )
    return false;
  });
  $('#_reload_page').click(function(){
    var currentLocation = window.location;
    window.location = currentLocation;
    return false;
  });


});
var Video = {
  selectServer:function(){
    $('._select_server').click(function(){
      var iframeUrl = $(this).attr('data-iframe');
      $('#_video_player_iframe').attr('src', iframeUrl);
      $('._select_server').each(function(){
        $(this).parent().removeClass("active");
      });
      $(this).parent().addClass("active");
      return false;
    });
  },
  logVideo:function(){
    if($('#logs_view').size()){
      var element = $('#logs_view');
      var elementId = element.attr("element_id");
      var type = element.attr("data-type");
      //var cookieName = "_refresh_"+elementId;
      $.post(BASE_URL+"ajax/logs",{element_id: elementId, type: type, status: 0}, function( data ) {
        var jData = JSON.parse(data);
        if(jData.msg == 'updated'){
          if(jData.urlId==0){
            //$.cookie(cookieName, "yes", { expires: 1, path: '/' } );
            var currentLocation = window.location;
            window.location = currentLocation;
          }
        }
      });
    }
  },
}
