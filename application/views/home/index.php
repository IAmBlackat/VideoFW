<div class="container">
  <?php echo $block_banner?>
  <div class="row">
    <?php $this->load->view('templates/sidebar'); ?>
    <div class="col-sm-9 wrap-content">
      <?php if ($newestVideo): ?>
        <div class="section-line">
          <div class="row">
            <div class="col-sm-12">
              <div class="item-video item-feature">
                <div class="row">
                  <div class="ui-thumb big-ui-thumb col-sm-5">
                    <a href="<?php echo makeLink($newestVideo[0]['id'], $newestVideo[0]['title']) ?>">
                      <img src="<?php echo getThumbnail($newestVideo[0]['series_thumbnail'], 'series') ?>" alt="<?php echo $newestVideo[0]['title'] ?> " class="img-responsive" />
                      <span class="fa fa-play-circle-o"></span>
                    </a>
                  </div><!-- /.ui-thumb -->
                  <div class="ui-meta col-sm-7">
                    <h3 class="ui-title"><a href="<?php echo makeLink($newestVideo[0]['id'], $newestVideo[0]['title']) ?>" class="ui-ellipsis-2"><?php echo $newestVideo[0]['title'] ?></a></h3>
                    <p><?php echo getVideoDescription($newestVideo[0]) ?></p>
                  </div><!-- /.ui-meta -->
                </div><!-- /.row -->
              </div><!-- /.item-video -->
            </div><!-- /.col-sm-6 -->
          </div><!-- /.row -->
        </div><!-- /.section-line -->
      <?php endif; ?>
      <div class="section-line">
        <?php echo $block_drama?>
        <?php echo $block_show?>
        <?php echo $block_movie?>
      </div><!-- /.col-sm-9 -->
    </div><!-- /.row -->
  </div>
</div>