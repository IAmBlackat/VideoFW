<script type="text/javascript">
  jQuery(document).ready(function () {
    $("#search_video").click(function () {
      var videoName = $('#video_name').val();
      $('#table_result').html("waiting ...");
      if (videoName) {
        $.post("<?php echo base_url() ?>ajax/search_video", {keyword: videoName}, function (data) {
          if (data != '[]') {
            var html = '<table class="stats" style="width: 100%"><thead><th>Select</th><th>No.</th><th>Title</th></thead>';
            $.each(data, function (key, value) {
              html += '<tr>';
              html += '<td><input class="cb" type="checkbox" value="' + value.id + '"></td>';
              html += '<td>' + (key + 1) + '</td>';
              html += '<td>' + value.title + '</td>';
              html += '</tr>';
            });
            html += '</table>';
            $('#table_result').html(html);
          }

          $("#add_video").click(function () {
            var videoIdArr = [];
            $('input.cb[type=checkbox]').each(function () {
              if (this.checked) {
                videoIdArr.push($(this).val());
              }
            });
            if (videoIdArr.length > 0) {
              var videoIds = videoIdArr.join(",");
              var serieId = $("#series_id").val();
              $.post("<?php echo base_url() ?>ajax/add_video_to_serie", {videoIds: videoIds, serieId: serieId}, function (data) {
                var currentLocation = window.location;
                window.location = currentLocation;
              });
            }
          });

        }, "json");
      }
    });

  });
</script>
<fieldset>
  <legend><strong>Series detail</strong></legend>
  <table class="stats" style="width: 100%;border: 1px #000">

    <tbody>
      <tr>
        <td>Title</td>
        <td><?php echo empty($title) ? '' : $title ?></td>
      </tr>
      <tr>
        <td>Thumbnail</td>
        <td><img height="100" src="<?php echo getThumbnail($thumbnail, 'series') ?>"></td>
      </tr>
      <tr>
        <td>Description</td>
        <td><?php echo empty($description) ? '' : $description ?></td>
      </tr>
      <tr>
        <td>Video Type</td>
        <td><?php echo $video_type[$type] ?></td>
      </tr>
    </tbody>
  </table>
</fieldset>
<fieldset>
  <legend><strong>Videos of series</strong></legend>
  <table class="stats" style="width: 100%">
    <thead>
      <th>No.</th>
      <th>Title</th>
      <th>Created Date</th>
      <th></th>
    </thead>
    <tbody>
      <?php if (!empty($videosOfSeries)): ?>
        <?php foreach ($videosOfSeries as $n1 => $video): ?>
          <tr>
            <td><?php echo $n1 + 1 ?></td>
            <td><a href="<?php echo base_url() ?>admin_video/show?id=<?php echo $video['id'] ?>"><?php echo $video['title'] ?></a></td>
            <td><?php echo $video['created_date'] ?></td>
            <td><a href="<?php echo base_url() ?>ajax/remove_video_of_series?series_id=<?php echo $id ?>&video_id=<?php echo $video['id'] ?>">Remove</a></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</fieldset>
<fieldset>
  <legend><strong>Add video to series</strong></legend>
  <input type="text" name="video_name" id="video_name">
  <input type="button" value="Search" id="search_video">
  <input type="button" value="Add to serie" id="add_video">
  <div id="table_result"></div>

</fieldset>
<fieldset style="border:0px;text-align: left">
  <input type="hidden" id="series_id" value=<?php echo $id ?>>
  <input type="button" class="button" value="Edit" onclick="window.location = '<?php echo base_url() ?>admin_series/edit?id=<?php echo $id ?>'"/>
  <input type="button" class="button" value="List" onclick="window.location = '<?php echo base_url() ?>admin_series/index'"/>
</fieldset>
