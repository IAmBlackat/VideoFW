<?php
if($extra_data && isset($extra_data['left_nav_genre'])):
$genreList = $extra_data['left_nav_genre'];
?>
<div class="sidebar col-sm-3">
  <div class="widget ui-list-cate">
    <ul>
      <?php foreach($genreList as $genre):?>
        <li><a href="<?php echo makeLink($genre['id'], $genre['name'], 'genre')?>"><?php echo $genre['name'] ?></a></li>
      <?php endforeach;?>
    </ul>
  </div><!-- /.ui-list-cate -->
  <div class="widget promote-zone">
    <a href="#">
      <img src="https://unsplash.it/300/300?random" alt="video title" class="img-responsive" />
    </a>
  </div><!-- /.widget promote-zone -->
</div>
<?php endif;?>