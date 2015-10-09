<div class="container">
  <div class="row">
    <?php $this->load->view('templates/sidebar'); ?>

    <div class="wrap-content col-sm-9">
      <div class="section-line">
        <div class="row">
          <div class="col-sm-12">
            <div class="item-video item-feature">
              <div class="row">
                <div class="ui-thumb big-ui-thumb col-sm-5">
                  <a href="<?php echo $newestVideo ? makeLink($newestVideo['id'], $newestVideo['title'], 'video') : '#'?>">
                    <img src="<?php echo getThumbnail($series['thumbnail'], 'series') ?>" alt="<?php echo $series['title']?>" class="img-responsive">
                    <span class="fa fa-play-circle-o"></span>
                  </a>
                </div><!-- /.ui-thumb -->
                <div class="ui-meta col-sm-7">
                  <h3 class="ui-title"><a href="<?php echo $newestVideo ? makeLink($newestVideo['id'], $newestVideo['title'], 'video') : '#'?>" class="ui-ellipsis-2"><?php echo $series['title']?></a></h3>
                  <p class="ellipsis-8"><?php echo $series['description']?></p>
                </div><!-- /.ui-meta -->
              </div><!-- /.row -->
            </div><!-- /.item-video -->
          </div><!-- /.col-sm-6 -->
        </div><!-- /.row -->
      </div><!-- /.section-line -->

      <div class="ui-section-title">
        <h3>List Video</h3>
      </div><!-- /.ui-section-title -->

      <?php if($videosOfSeries):
      $dataArr = array_chunk($videosOfSeries, 4);
      foreach ($dataArr as $dataChunk):
      ?>
      <div class="section-line">
        <div class="row pm-row">
        <?php foreach ($dataChunk as $data): ?>
          <div class="col-sm-3">
            <div class="item-video">
              <div class="ui-thumb">
                <a href="<?php echo makeLink($data['id'], $data['title'], 'video')?>">
                  <img src="<?php echo getThumbnail($series['thumbnail'], 'series') ?>" alt="<?php echo $data['title']?>" class="img-responsive">
                  <span class="fa fa-play-circle-o"></span>
                </a>
                <span class="episode">Episode <?php echo $data['episode']?></span>
              </div><!-- /.ui-thumb -->
              <div class="ui-meta">
                <h3 class="ui-title"><a href="<?php echo makeLink($data['id'], $data['title'], 'video')?>" class="ui-ellipsis-2"><?php echo $data['title']?></a></h3>
              </div><!-- /.ui-meta -->
            </div><!-- /.item-video -->
          </div><!-- /.col-sm-3 -->
        <?php endforeach;?>
        </div><!-- /.row -->
      </div><!-- /.section-line -->
      <?php
      endforeach;
      endif;
      ?>
      <?php $this->load->view("templates/paging_frontend", array('total' => $total, 'max' => $max, 'offset' => $offset)); ?>

    </div>
  </div><!-- /.row -->
</div><!-- /.container -->