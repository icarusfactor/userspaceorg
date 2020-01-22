<?php
/**
 * @package  APFM RSS CTRL
 */
// SQL FOR THIS CLASS
//CREATE TABLE `ap_section_feeds` (
//           id INT AUTO_INCREMENT PRIMARY KEY,
//          `section` varchar(32) COLLATE utf8_bin NOT NULL,
//          `site` varchar(64) COLLATE utf8_bin NOT NULL,
//          `url` varchar(256) COLLATE utf8_bin NOT NULL,
//          `rss` varchar(256) COLLATE utf8_bin NOT NULL,
//          `catagory` varchar(20) COLLATE utf8_bin NOT NULL,
//          `enable` BOOLEAN,
//          `last_post_date` DATETIME            
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


class APFMrssctrl
{

public static function section_count() {
        global $wpdb;
	$rqcnt="SELECT DISTINCT COUNT(post_title) FROM wp_posts WHERE post_type='tablepress_table';";

	return $wpdb->get_var( $rqcnt );
}

public function section_names() {
	global $wpdb;
	$section_names=[];
	$cnt=0;
	$rqsec="SELECT DISTINCT post_title FROM wp_posts WHERE post_type='tablepress_table';";
	$row="";

	foreach( $wpdb->get_results( $rqsec ) as $key => $row) {
		$section_names[$cnt] = $row->post_title;
		$cnt++;
	}
	return $section_names;
} //end of function : section_names

public function section_content() {
	global $wpdb;
	$cnt=0;
	$row="";
	$section_content=[];
	$rqtxt="SELECT post_content FROM wp_posts WHERE post_type='tablepress_table';";

	foreach( $wpdb->get_results( $rqtxt ) as $key => $row) {
		$section_content[ $cnt ] = $row->post_content;
    		//Split all of the items into elements based on being inside the brackets
    		$section_content[$cnt] = preg_split('/\h*[][]/', $section_content[$cnt] , -1, PREG_SPLIT_NO_EMPTY);
    		//remove single character array , which is the comma only entries.  
    		$section_content[$cnt] = array_filter($section_content[$cnt],function($v){ return strlen($v) > 1; });
    		//reindex array so counts are correct.
    		$section_content[$cnt] = array_values( array_filter( $section_content[$cnt] ) );
    		$cnt++;
	}
	return $section_content;
} //end of function: section_content 


public function section_items( $content ) {
    $section_items=[];
    $cntX=0;
    $cntXB=0;
    while( $cntX < count( $content ) ) {
       //Check and cut out all the end array cruft. 
         if(isset( $content[$cntX ] ) ){
            $section_items[ $cntXB ] = explode(",", $content[ $cntX ]);
            //Check position 4 if enabled or not. If set to 0 tick down array. 
            if( strchr( $section_items[ $cntXB ][4] ,"0",false) != "0" ) {  unset($section_items[$cntXB]);$cntXB--; }
            //echo $section_items[ $cntXB ][4];
           }
         $cntX++;
         $cntXB++;
        }
        // Remove first item of array. Which is the header and not needed.  
        array_shift( $section_items );
	return $section_items;
}

public function push_data( $section_array , $section_id ) {
	global $wpdb;
	//$section_array = $this->section_items( $section_content[ $section_id ]  );
	$section_names = $this->section_names();
        $size_it = count( $section_array ); 
        $cnt=0;
        $max_loop_iterations = 200;
        while($cnt <= $size_it - 1 )
         {
	  $sec_name = $section_names[ $section_id ];
	  $sitename = $section_array[ $cnt ][0];
	  $site_url = $section_array[ $cnt ][1];
	  $site_rss = $section_array[ $cnt ][2];
	  $site_cat = $section_array[ $cnt ][3];
	  $enabled  = "1"; //hard coded for now ,wont use as this is filtered out in advance. But may use it later for client testing issues.

          // error_log( "push_data 1:".$selection_id." 2:".$sec_name." 3:".$sitename." 4:".$site_url );
          error_log("push_data - SIZE: ".$size_it." LOG: ". print_r( $section_array, true ) );

	  $rqpushsec="INSERT INTO `ap_section_feeds` ( `id`,`section`,`site`,`url`,`rss`,`catagory`,`enable`,`last_post_date`  ) VALUES ( NULL,'".$sec_name."',".$sitename.",".$site_url.",".$site_rss.",".$site_cat.",".$enabled.", NOW() - INTERVAL FLOOR(RAND()*730+1) + 8030 HOUR );";
          $wpdb->query($rqpushsec);
           if ($cnt++ == $max_loop_iterations) {
    		error_log("push_data:max_loop_iterations: Too many iterations...");
    		break;
  		}
         }
	return 1;
}

public function clear_push_data( $section_id  ) {
	global $wpdb;
	$section_names = $this->section_names();
        error_log( "clear_push_data 1:".$selection_id." 2:".$section_names[ $section_id ] );
	$rqpushsec="DELETE FROM ap_section_feeds WHERE section LIKE '%".$section_names[ $section_id ]."%';";
	$wpdb->query($rqpushsec);
	return 1;
	}

} //End of APFMrssctrl class
