<?php
if ($suggestSeriesList):
  $dataArr = array_chunk($suggestSeriesList, 4);
  ?>
<div class="ui-section-title">
  <h3><a href="<?php echo makeLink($randomGenre['id'], $randomGenre['name'], 'genre') ?>">Related Series</a></h3>
</div><!-- /.ui-section-title -->
<?php
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
          </a>
        </div><!-- /.ui-thumb -->
        <div class="ui-meta">
          <h3 class="ui-title"><a href="<?php echo makeLink($data['id'], $data['title'], 'series', $data['country'])?>" class="ui-ellipsis-2"><?php echo $data['title']?></a></h3>
        </div><!-- /.ui-meta -->
      </div><!-- /.item-video -->
    </div><!-- /.col-sm-3 -->
  <?php endforeach ?>
  </div><!-- /.row -->
</div><!-- /.section-line -->
<?php endforeach; ?>
<?php endif; ?>