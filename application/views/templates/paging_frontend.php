  <?php if( !empty($max) && !empty($total) && $total > $max ){ ?>
    <?php
    $uri = isset($_SERVER['REDIRECT_URL']) ? rtrim(base_url(), '/'). $_SERVER['REDIRECT_URL'] : '';
        //$uri = isset( $uri ) && !empty($uri) ? base_url().$uri:'';//implode(',', $uri).',' : '';
        $offset = isset( $offset ) ? $offset : 0;
    ?>                                                                         
    <div class="ui-pagination row">
      <div class="col-sm-12">
        <ul class="pagination pull-right">
        <?php if( $offset >= $max ):?>
          <li><a href="<?php echo $uri.buildQueryString(array('p' => ($offset/$max)))?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
        <?php endif;?>
        <?php
            $num_page = floor($total / $max) + ( $total % $max != 0 ? 1 : 0 );     
            $i = 0;
            $rankPage = 15;
            $selectedPage = $offset / $max;
            $mid = (int)($rankPage/2);
            
            $pagesInRange = array();
            $start = $selectedPage-$mid;
            $end = $selectedPage+$mid;
            if($selectedPage-$mid<0){
            	$start = 0;
            	$end = $rankPage-1;
            }
            if($selectedPage==$num_page-1){
                if($num_page-$rankPage>0){
                    $start = $num_page-$rankPage;    
                }else{
                    $start = 0;
                }
            	$end = $num_page-1;
            }
            for($p = $start;$p<=$end;$p++){
            	if($p<$num_page){
            		$pagesInRange[]=$p;
            	}
            }        
            if(!empty($pagesInRange)){
            	foreach($pagesInRange as $i){
	                echo '<li><a '.($selectedPage == $i ? 'class="active"' : '' );
	                if($offset / $max != $i){
	                    echo ' href="'.$uri.buildQueryString(array('p' => ($i+1))) .'"';
	                }
	                echo '>'.($i + 1).'</a></li>';
	            }	
            }       
        ?>
         <?php if( $offset < $total - $max ):?>
           <li><a href="<?php echo $uri.buildQueryString(array('p' => ($offset/$max + 2))) ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
        <?php endif;?>
        </ul>
      </div>
    </div>
    <?php } ?>
