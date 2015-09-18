  <?php if( !empty($max) && !empty($total) && $total > $max ){ ?>
    <?php          
        $uri = isset( $uri ) && !empty($uri) ? base_url().$uri:'';//implode(',', $uri).',' : '';
        $offset = isset( $offset ) ? $offset : 0;
    ?>                                                                         
    <div class="float_right">
        <a href="<?php echo$uri.'0'?>">First</a>
        <?php if( $offset >= $max ):?>
            &nbsp;&nbsp;<a href="<?php echo$uri.($offset/$max - 1) * $max?>">Back</a>
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
	                echo '&nbsp;&nbsp;<a '.($selectedPage == $i ? 'class="current"' : '' );
	                if($offset / $max != $i){
	                    echo ' href="'.$uri.$i * $max.'"';
	                }
	                echo '>'.($i + 1).'</a>';
	            }	
            }       
        ?>
         <?php if( $offset < $total - $max ):?>
            &nbsp;&nbsp;<a href="<?php echo$uri.($offset/$max + 1) * $max?>">Next</a>
        <?php endif;?>
        &nbsp;&nbsp;<a href="<?php echo$uri.($num_page-1)*$max;/*($i - 1) * $max*/?>">Last</a>
    </div>
    <?php } ?>