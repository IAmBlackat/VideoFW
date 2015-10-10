<div class="ui-section-title">
  <h3><a href="<?php echo $link?>"><?php echo $title?></a></h3> <a href="<?php echo $link?>" class="view-more">View all</a>
</div><!-- /.ui-section-title -->
<?php
if ($datas):
  $dataArr = array_chunk($datas, 4);
  foreach ($dataArr as $dataChunk):
    ?>
    <div class="section-line">
      <div class="row pm-row">
        <?php foreach ($dataChunk as $data): ?>
          <div class="col-sm-3">
            <div class="item-video">
              <div class="ui-thumb">
                <a href="<?php echo makeLink($data['id'], $data['title'], 'video')?>">
                  <img src="<?php echo getThumbnail($data['series']['thumbnail'], 'series') ?>" alt="<?php echo $data['series']['title']?>" class="img-responsive" />
                </a>
                <span class="episode">Episode <?php echo $data['episode']?></span>
              </div><!-- /.ui-thumb -->
              <div class="ui-meta">
                <h3 class="ui-title"><a href="<?php echo makeLink($data['series']['id'], $data['series']['title'], 'series', $data['series']['country'])?>" class="ui-ellipsis-2"><?php echo $data['series']['title']?></a></h3>
              </div><!-- /.ui-meta -->
            </div><!-- /.item-video -->
          </div><!-- /.col-sm-3 -->
        <?php endforeach ?>
      </div><!-- /.row -->
    </div><!-- /.section-line -->
  <?php endforeach; ?>
<?php endif; ?>