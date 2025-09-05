<?php 
/*********************************************************************************************************
   Check for the acf structures for the page
*********************************************************************************************************/
$tab_selected = '';

if(isset($_GET['tab'])) {
   $tab_selected =$_GET['tab'];
}  

//echo '</p>tab_selected='.$tab_selected.'</p>';


function a7_sanitise_anchor($str) {
	$str = trim(preg_replace("/[^A-Za-z ]/", "", $str));
	$str = strtolower(str_replace(' ', '-', $str));
	return $str;
}

if( have_rows('content') ):

   // loop through the rows of data
   while ( have_rows('content') ) : the_row();
      // Do a switch on the type of row layout found
      switch ( get_row_layout() ) {

         // Older styles first
         case 'full_width_text' :
?>
         
            <div class="wrapper <?php the_sub_field('background_colour');?> full-width-text-wrapper">
	            <a name="<?php echo a7_sanitise_anchor(get_sub_field('_a7_sub_heading'));?>"></a>
	            <section class="container clearfix <?php the_sub_field('_a7_section_classes');?>">
		            <?php if(get_sub_field('_a7_sub_heading')) { ?>
		            	<h2><?php the_sub_field('_a7_sub_heading');?></h2>
		            <?php } ?>
					<?php the_sub_field('full_width_text_content');?>
            	</section>
            </div>
         
<?php 
            break;
            
         case 'full_bleed_image_block' :
?>
         	<?php
	        	$_a7_image_type = get_sub_field('_a7_image_type');
				$_a7_image_location = get_sub_field('_a7_image_location');
			?>
			
            <div class="wrapper <?php the_sub_field('background_colour');?> full-bleed-image-block <?php echo $_a7_image_type ?>-image-wrapper">
	            <a name="<?php echo a7_sanitise_anchor(get_sub_field('_a7_sub_heading'));?>"></a>
				<section class="container clearfix <?php the_sub_field('_a7_image_location');?> <?php the_sub_field('_a7_image_location_vertical');?> <?php the_sub_field('_a7_section_classes');?>">
					<?php if ($_a7_image_location == "image-right" && $_a7_image_type == "fullbleed") { ?>
						<h2><?php the_sub_field('_a7_sub_heading');?></h2>
					<?php } ?>
					<div class="section-columns">
						<div class="section-column section-column-text">
							<?php if ($_a7_image_type == "circular" || $_a7_image_location == "image-left") { ?>
								<h2><?php the_sub_field('_a7_sub_heading');?></h2>
							<?php } ?>
							<?php the_sub_field('_a7_image_block_content');?>
							<?php if (get_sub_field('_a7_button_text') && get_sub_field('_a7_button_link') ) { ?>
								<div class="button-container button-center">
									<a href="<?php the_sub_field('_a7_button_link') ?>" class="button"><?php the_sub_field('_a7_button_text') ?></a>
								</div>
							<?php } ?>
						</div>
						<?php if ($_a7_image_type == "fullbleed") {
							$img_size = 'full';	
						} else {
							$img_size = 'person-grid';
						}
						?>
						<div class="section-column section-column-image section-column-image-<?php the_sub_field('_a7_image_type') ?>"> 
							<?php
								$image = wp_get_attachment_image( get_sub_field('_a7_full_bleed_image'), $img_size);
								echo $image;
							?>
						</div>
					</div>
            	</section>
            	<?php if (get_sub_field('_a7_full_width_content') ) { ?>
	            	<section class="container clearfix continuation">
		            	<?php the_sub_field('_a7_full_width_content');?>
		            </section>
		        <?php } ?>
            </div>
         
<?php 
            break;
            
         case 'case_study_block' :
?>
         
            <div class="wrapper purple case-study-wrapper">
	            <a name="<?php echo a7_sanitise_anchor(get_sub_field('_a7_sub_heading'));?>"></a>
				<section class="container clearfix case-study">
					<div class="column column-logo">
						<div class="logo-container">
							<?php
								$image = wp_get_attachment_image( get_sub_field('_a7_client_logo'), 'full');
								echo $image;
							?>							
						</div>
						<h3>Client: <?php the_sub_field('_a7_client_name') ?></h3>
						<h4 class="screen-reader-text">Learn more about our <?php the_sub_field('_a7_client_name') ?> case study</h4>
						<?php if (get_sub_field('_a7_case_study_link') ) { ?>
							<a href="<?php the_sub_field('_a7_case_study_link') ?>" class="button">Learn more</a>
						<?php } ?>
					</div>
					<div class="column column-text">
						<h3>Client: <?php the_sub_field('_a7_client_name') ?></h3>
						<?php the_sub_field('_a7_case_study_block_content');?>
					</div>

            	</section>
            </div>
         
<?php 
            break;
            
         case 'story_excerpt_block' :
		 	if(function_exists('a7_open_story')) a7_open_story(get_sub_field('a7_open_story_id'));
		 	
            break;
            
         case 'vanilla_block' :
		 	echo get_sub_field('_a7_vanilla_content');
		 	
            break;            
            
         case 'intro_text' : // The bit immediately beneath the h1
?>  
     
            <div class="wrapper intro-text-wrapper <?php the_sub_field('background_colour');?>">
	            <a name="intro-text"></a>
	            <section class="container clearfix intro-text">
	               <?php the_sub_field('intro_text_content');?>
	            </section>
            </div>
                     
<?php 
            break;
            
         case 'a7_team_gallery' :
?>
         
            <div class="wrapper team-gallery-wrapper <?php the_sub_field('background_colour');?>">
	            <a name="<?php echo strtolower(str_replace(' ', '-', get_sub_field('_a7_sub_heading')));?>"></a>
	            <section class="container clearfix <?php the_sub_field('_a7_section_classes');?>">
					<h2><?php the_sub_field('_a7_sub_heading');?></h2>
					<?php echo get_peopleSC(); ?>
            	</section>
            </div>
         
<?php 
            break;
            

         case 'tab_panel_block_start' :

            // Initialise output
            $str_html = '';

            // Retrieve class name for tab panel if set
            if (empty(get_sub_field('tab_panel_block_start_class_name'))) {
               $tab_panel_class = '';
            } else {
               $tab_panel_class = get_sub_field('tab_panel_block_start_class_name');
            }

            // Retrieve id for tab panel if set
            if (empty(get_sub_field('tab_panel_block_start_id'))) {
               $tab_panel_id = '';
            } else {
               $tab_panel_id = get_sub_field('tab_panel_block_start_id');
            }
            // Retrieve label for tab panel if set
            if (empty(get_sub_field('tab_panel_block_label'))) {
               $tab_panel_label = '';
            } else {
               $tab_panel_label = 'aria-label="'.get_sub_field('tab_panel_block_label').'"';
            }

            // Retrieve list of headers and panel ids
            if (empty(get_sub_field('tab_panel_block_headers_txt'))) {
               // Shouldn't be empty, but if it is, just put out a div and break out
               echo '<div><p>Headers text not set</p>';
               break;
            } else {
               $tab_panel_headers = get_sub_field('tab_panel_block_headers_txt');
            }
            // IDs
            if (empty(get_sub_field('tab_panel_block_headers_ids'))) {
               // Shouldn't be empty, but if it is, just put out a div and break out
               echo '<div><p>IDs not set</p>';
               break;
            } else {
               $tab_panel_ids = get_sub_field('tab_panel_block_headers_ids');
            }

            // Construct list of tab headers
            // Create arrays
            $arr_tab_hdrs_txt = explode('#', $tab_panel_headers);
            $arr_tab_hdrs_ids = explode('#', $tab_panel_ids);

            // Check counts agree
            $count_tab_hdrs_txt = count($arr_tab_hdrs_txt);
            $count_tab_hdrs_ids = count($arr_tab_hdrs_ids);
            if (!($count_tab_hdrs_txt == $count_tab_hdrs_ids)) {
               echo '<div><p>IDs count and header text count do not match.</p>';
               break;
            }

            $str_html .= '<section role="region" class="tab-panel-wrapper '.$tab_panel_class.'" id="'.$tab_panel_id.'"" '.$tab_panel_label.'>';
            //$str_html .= '<h2>Tab panel start</h2>';
            //$str_html .= '<p>Headers = '.$tab_panel_headers.'</p>';
            //$str_html .= '<p>IDs = '.$tab_panel_ids.'</p>';
            
            // Construct list of tab headers
            $str_html .= '<div class="wrapper"><div class="container">';
            $str_html .= '<ul class="tab-headers tab-'.$count_tab_hdrs_txt.'" id="tab-hdrs-'.$tab_panel_id.'">';
            for($i = 0, $l = $count_tab_hdrs_txt; $i < $l; ++$i) {
               $str_html .= '<li><a class="tab-header-link" id="hdr-'.$arr_tab_hdrs_ids[$i].'" data-id="'.$arr_tab_hdrs_ids[$i].'" href="#'.$arr_tab_hdrs_ids[$i].'">';
               $str_html .= $arr_tab_hdrs_txt[$i].'</a></li>';
            }
            $str_html .= '</ul></div></div>';

            // Now start 1st tab panel
            // Check if first tab is the selected one
            // Check tab selected is actually set to something - if not then choose the first one.
            if (empty($tab_selected) or ($tab_selected == $arr_tab_hdrs_ids[0])) {
               $str_selected = 'data-selected="true"';
            } else {
               $str_selected = '';
            }

            $str_html .= '<div id="'.$arr_tab_hdrs_ids[0].'" '.$str_selected.' tabindex="-1" class="tab-panel">';

            echo $str_html;
            

            break; // End of tab_panel_block_start

         case 'tab_panel_block_divider' :
            // Here we're going to end the previous individual tab panel and start a new one.
            // Need an ID to put into the div - tab_panel_divider_new_block_id
            if (empty(get_sub_field('tab_panel_divider_new_block_id'))) {
               // Shouldn't be empty, but if it is, just put out a div and break out
               echo '<div><p>IDs not set</p>';
               break;
            } else {
               $tab_panel_id = get_sub_field('tab_panel_divider_new_block_id');
            }
            // Check if first tab is the selected one
            if ($tab_selected == $tab_panel_id) {
               $str_selected = 'data-selected="true"';
            } else {
               $str_selected = '';
            }

?>
         
            </div> <!-- End of individual tab panel -->
            <div tabindex="-1" class="tab-panel" id="<?php echo $tab_panel_id;?>" <?php echo $str_selected;?>> <!-- Start of individual tab panel -->
         
<?php 
            break; // End of tab_panel_block_divider

         case 'tab_panel_block_end' :
            // All that's left to do is close the current individual tab panel and the tab panel section
?>
         
            </div> <!-- End of individual tab panel -->
            </section> <!-- End of tab panel section -->
         
<?php 
            break; // End of tab_panel_block_end

         // Build in newer styles here
         
      }
   
   endwhile;

else :

    // no layouts found

endif;


?>
