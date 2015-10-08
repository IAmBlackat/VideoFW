<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=154538277958987";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="nav-alphabet">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <ul class="nav-az list-inline">
          <li><a href="<?php echo makeLink(0, '', 'list_series_all')?>">ALL</a></li>
          <li><a href="<?php echo makeLink(0, 'Other', 'list_series_by_char')?>">#</a></li>
          <?php
          $alphas = range('A', 'Z');
          foreach($alphas as $alpha):
          ?>
          <li><a href="<?php echo makeLink(0, $alpha, 'list_series_by_char')?>"><?php echo $alpha?></a></li>
          <?php endforeach;?>
        </ul>
      </div><!-- /.col-sm-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</div>

<header>
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <h1 class="logo"><a href="/"><img src="<?php echo $theme_path ?>images/logo.png" alt="Solid Tube"></a></h1>
      </div><!-- /.col-sm-3 -->
      <div class="col-sm-9">
        <div class="top-banner-728x90 center-block">
          <a href="/"><img src="<?php echo $theme_path ?>images/top_banner.jpg" alt="Drama list" class="img-responsive"></a>
        </div><!-- /.top-banner-728x90 -->
      </div><!-- /.col-sm-9 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</header>