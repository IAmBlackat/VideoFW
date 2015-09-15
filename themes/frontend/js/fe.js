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
});
