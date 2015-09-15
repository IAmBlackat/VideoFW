<?php if($data && isset($data['items'])):
  $items = $data['items'];
  ?>
  <div class="row">
    <div class="col-sm-12">
      <div class="slider">
        <?php foreach($items as $itemId => $item):?>
        <div class="item"><a href="<?php echo $item['item_link']?>"><img src="<?php echo getThumbnail($item['item_thumbnail'], 'editor') ?>" alt="<?php echo $item['item_text']?>"></a></div><!-- /.item -->
        <?php endforeach;?>
      </div><!-- /.slider -->
    </div><!-- /.col-sm-12 -->
  </div><!-- /.row -->
<?php endif;?>