<?php
if($extra_data && isset($extra_data['left_nav_genre'])):
$genreList = $extra_data['left_nav_genre'];
?>
<div class="sidebar col-sm-3">
  <div class="widget ui-fanpage">
    <div class="fb-page" data-href="https://www.facebook.com/KDramaShare" data-width="300" data-height="70" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/KDramaShare"><a href="https://www.facebook.com/KDramaShare">Dramalist.net</a></blockquote></div></div>
  </div><!-- /.widget ui-fanpage -->
  <div class="widget ui-list-cate">
    <h2 class="widget-title">Top Genre</h2>
    <ul>
      <?php foreach($genreList as $genre):?>
        <li><a href="<?php echo makeLink($genre['id'], $genre['name'], 'genre')?>"><?php echo $genre['name'] ?></a></li>
      <?php endforeach;?>
    </ul>
  </div><!-- /.ui-list-cate -->
  <div class="widget promote-zone">
    <a href="/">
      <img src="<?php echo $theme_path ?>images/right_banner.jpg" alt="Drama list" class="img-responsive" />
    </a>
  </div><!-- /.widget promote-zone -->
</div>
<?php endif;?>