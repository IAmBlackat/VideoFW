<div class="container">
  <div class="row">
    <?php $this->load->view('templates/sidebar'); ?>

    <div class="wrap-content col-sm-9">
      <!--
      <div class="func-filter row">
        <div class="col-sm-3">
          <?php //echo $genreSelectbox?>
        </div>
        <div class="col-sm-3">
          <?php //echo $statusSelectbox?>
        </div>
        <div class="col-sm-3">
          <?php //echo $yearSelectbox?>
        </div>
        <div class="col-sm-3">
          <?php //echo $typeSelectbox?>
        </div>
      </div>
-->

      <div class="row">
        <?php foreach($listAllSeries as $char => $seriesListByChar):?>
        <div class="col-sm-4">
          <div class="box-list">
            <div class="box-list-title">
              <a href="<?php echo makeLink(0, $char, 'list_series_by_char')?>" class="list-title"><?php echo $char?></a>
              <a href="<?php echo makeLink(0, $char, 'list_series_by_char')?>" class="view-more">View More</a>
            </div>
            <div class="ui-list">
              <ul>
                <?php foreach($seriesListByChar as $series):?>
                <li>
                  <a href="<?php echo makeLink($series['id'], $series['title'], 'series', $series['country'])?>"><?php echo $series['title']?></a>
                  <span class="info"><?php echo $series['release_date']?></span>
                </li>
                <?php endforeach;?>
              </ul>
            </div><!-- /.ui-list -->
          </div><!-- /.box-list -->
        </div><!-- /.box-list -->
        <?php endforeach;?>

      </div><!-- /.row -->
    </div><!-- /.col-sm-9 -->
  </div><!-- /.row -->
</div><!-- /.container -->