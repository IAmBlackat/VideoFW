<div class="container">
  <div class="row">
    <?php $this->load->view('templates/sidebar'); ?>

    <div class="wrap-content col-sm-9">
      <div class="ui-section-title">
        <h3>List Series By Genre "<?php echo $genre['name']?>"</h3>
      </div><!-- /.ui-section-title -->
      <?php if($listObject):
        $dataArr = array_chunk($listObject, 4);
        foreach ($dataArr as $dataChunk):
          ?>
          <div class="section-line">
            <div class="row pm-row">
              <?php foreach ($dataChunk as $data): ?>
                <div class="col-sm-3">
                  <div class="item-video">
                    <div class="ui-thumb">
                      <a href="<?php echo makeLink($data['id'], $data['title'], 'series', $data['country'])?>">
                        <img src="<?php echo getThumbnail($data['thumbnail'], 'series') ?>" alt="<?php echo $data['title']?>" class="img-responsive" />
                        <span class="fa fa-play-circle-o"></span>
                      </a>
                    </div><!-- /.ui-thumb -->
                    <div class="ui-meta">
                      <h3 class="ui-title"><a href="<?php echo makeLink($data['id'], $data['title'], 'series', $data['country'])?>" class="ui-ellipsis-2"><?php echo $data['title']?></a></h3>
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

    </div><!-- /.col-sm-9 -->
  </div><!-- /.row -->
</div><!-- /.container -->