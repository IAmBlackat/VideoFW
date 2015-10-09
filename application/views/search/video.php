<div class="container">
  <div class="row">
    <?php $this->load->view('templates/sidebar'); ?>
    <div class="wrap-content col-sm-9">
      <div class="info-search">
        <p>About <strong><?php echo $total?></strong> results</p>
      </div>
      <?php if($listObject):?>
        <?php foreach($listObject as $object):
          $detailLink = makeLink($object['id'], $object['title'], 'video');
          ?>
        <div class="ui-list-item">
          <div class="thumb">
            <a href="<?php echo $detailLink?>">
              <img src="<?php echo getThumbnail($object['series_thumbnail'], 'series') ?>" alt="<?php echo $object['title']?>" class="img-responsive">
            </a>
          </div><!-- /.thumb -->
          <div class="meta">
            <h3 class="title"><a href="<?php echo $detailLink ?>"><?php echo $object['title']?></a></h3>
            <p><?php echo $object['series_desctiption']?></p>
          </div><!-- /.meta -->
        </div><!-- /.ui-list-item -->
        <?php endforeach;?>
      <?php endif;?>

      <?php $this->load->view("templates/paging_frontend", array('total' => $total, 'max' => $max, 'offset' => $offset)); ?>

    </div><!-- /.col-sm-9 -->
  </div><!-- /.row -->
</div><!-- /.container -->