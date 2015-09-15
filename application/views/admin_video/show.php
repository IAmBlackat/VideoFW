<fieldset>
	<legend><strong>Video detail</strong></legend>
	<table class="stats a" style="width: 100%;border: 1px #000">
		<tbody>
			<tr>
				<td>Title</td>
				<td><?php echo empty($title) ? '' : $title ?></td>
			</tr>
      <tr>
				<td>Episode</td>
				<td><?php echo empty($episode) ? '' : $episode ?></td>
			</tr>
      <tr>
				<td>Image (of Series)</td>
        <td>
          <?php if($series):?>
          <img width="100" src="<?php echo getThumbnail($series['thumbnail'], 'series') ?>">
          <?php endif;?>
        </td>
			</tr>
      <tr>
				<td>Genre (of Series)</td>
        <td>
          <?php if($series):?>
          <?php if($series['genre']) echo implode(", ", $series['genre']) ?>
          <?php endif;?>
        </td>
			</tr>
			<tr>
				<td>Series</td>
				<td>
          <?php if($series) :  ?>
          <a href="<?php echo base_url().'admin_series/show?id='.$series['id']?>"><?php echo $series['title']?></a>
          <?php endif;?>
        </td>
			</tr>
			<tr>
				<td>Status</td>
        <td><?php echo getStrStatus($status) ?></td>
			</tr>
      <tr>
				<td>Has Sub</td>
        <td><?php echo $has_sub ? 'YES' : 'NO (RAW)'  ?></td>
			</tr>
      <tr>
				<td>Publish date</td>
        <td><?php echo $publish_date ?></td>
			</tr>
      <tr>
				<td>Original URL</td>
				<td><?php echo empty($original_url) ? '' : $original_url ?></td>
			</tr>
      <tr>
				<td>Format</td>
				<td>
          <?php 
          $defaultUrl = '';
          if($video_url){
            foreach($video_url as $vurl){
              if($vurl['type'] == VIDEO_TYPE_360) echo ' 360p ';
              if($vurl['type'] == VIDEO_TYPE_480) echo ' 480p ';
              if($vurl['type'] == VIDEO_TYPE_720) echo ' 720p ';
              $defaultUrl = base64_decode($vurl['streaming_url']);
            }
          }
        ?>
        </td>
			</tr>
      <tr>
				<td>Preview</td>
				<td>
          <?php if($defaultUrl):?>
          <video class="video-js vjs-default-skin" controls preload="true" width="727px" height="450px"
                data-setup="{}">
             <source src="<?php echo $defaultUrl?>" type='video/mp4' />
          </video>
          <?php else: ?>
          NO Streaming URL
          <?php endif;?>
        </td>
			</tr>
		</tbody>
	</table>
</fieldset>
<fieldset style="border:0px;text-align: left">
<input type="button" class="button" value="Edit" onclick="window.location='<?php echo base_url() ?>admin_video/edit?id=<?php echo $id?>'"/>
<input type="button" class="button" value="List" onclick="window.location='<?php echo base_url() ?>admin_video/index'"/>

</fieldset>
