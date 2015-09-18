<div class="container">
  <div class="row">
    <?php $this->load->view('templates/sidebar'); ?>
    <div class="wrap-content col-sm-9">
      <?php if($listObject):?>
        <?php foreach($listObject as $object):?>
        <div class="ui-list-item">
          <div class="thumb">
            <a href="<?php echo makeLink($object['id'], $object['title'], 'series', $object['country'])?>">
              <img src="<?php echo getThumbnail($object['thumbnail'], 'series') ?>" alt="<?php echo $object['title']?>" class="img-responsive">
            </a>
          </div><!-- /.thumb -->
          <div class="meta">
            <h3 class="title"><a href="<?php echo makeLink($object['id'], $object['title'], 'series', $object['country'])?>"><?php echo $object['title']?></a></h3>
            <p><?php echo subString($object['description'], 300)?></p>
          </div><!-- /.meta -->
        </div><!-- /.ui-list-item -->
        <?php endforeach;?>
      <?php endif;?>

      <?php $this->load->view("templates/paging_frontend", array('total' => $total, 'max' => $max, 'offset' => $offset)); ?>

    </div><!-- /.col-sm-9 -->
  </div><!-- /.row -->
</div><!-- /.container -->