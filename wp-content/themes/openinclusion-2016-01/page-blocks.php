<?php

//////////////////////// Building page components /////////////////////////

// INTRO TEXT //
function intro_text_sc($atts, $content = null) {
   
   $strHtml = '<div class="inner intro-text">';
   $strHtml .= do_shortcode($content);
   $strHtml .= '</div>';
   return $strHtml;
}
add_shortcode("intro-text", "intro_text_sc");   

// SECTION HEADER //
function sectionHdr_sc($atts, $content = null) {
/* Function to output a heading with an id  */
   // Get parameters
   extract(shortcode_atts(array(
      'hlev' => '3', // heading levl
      'id' => '', // id to add
   ), $atts));
   
   $hid = (!empty($id))?' id="'.$id.'"':'';
   
   $strHtml = '<h'.$hlev.$hid.'>';
   $strHtml .= do_shortcode($content);
   $strHtml .= '</h'.$hlev.'>';
   return $strHtml;
}
add_shortcode("section-hdr", "sectionHdr_sc");   



// WIDE ONE COL //
function wide_one_col_sc($atts, $content = null) {
/* Function to output a wide one column section  */
   // Get parameters
   extract(shortcode_atts(array(
      'bg' => 'blue',
      'padtop' => '',
      'class' => '',
   ), $atts));
   
   if ($padtop == 'n') {
      $padstr = ' nopadtop';
   } else {
      $padstr = '';
   }
   
   $classStr = (empty($class))?'':' '.$class;
   
   $strHtml = '<div class="wide-one '.$padstr.' '.$bg.$classStr.'"><div class="inner">';
   $strHtml .= do_shortcode($content);
   $strHtml .= '</div></div>';
   return $strHtml;
}
add_shortcode("wide-one-col", "wide_one_col_sc");   

// WIDEST COL //
function widest_col_sc($atts, $content = null) {
/* Function to output a wide one column section  */
   // Get parameters
   extract(shortcode_atts(array(
      'bg' => 'blue',
      'padtop' => '',
   ), $atts));
   
   if ($padtop == 'n') {
      $padstr = ' nopadtop';
   } else {
      $padstr = '';
   }
   
   $strHtml = '<div class="wide-one widest '.$padstr.' '.$bg.'"><div class="inner">';
   $strHtml .= do_shortcode($content);
   $strHtml .= '</div></div>';
   return $strHtml;
}
add_shortcode("widest-col", "widest_col_sc");   


// WIDE TWO COL //
function wide_two_col_sc($atts, $content = null) {
/* Function to output a wide one column section  */
   // Get parameters
   extract(shortcode_atts(array(
      'bg' => 'blue',
      'padtop' => '',
   ), $atts));
   
   if ($padtop == 'n') {
      $padstr = ' nopadtop';
   } else {
      $padstr = '';
   }

   $strHtml = '<div class="wide-one two-col '.$padstr.' '.$bg.'"><div class="inner">';
   $strHtml .= do_shortcode($content);
   $strHtml .= '</div></div>';
   return $strHtml;
}
add_shortcode("wide-two-col", "wide_two_col_sc");   

// COL BLOCK //
function col_block_sc($atts, $content = null) {
/* Function to output column block  */
   
   // Get parameters
   extract(shortcode_atts(array(
      'align' => '',
   ), $atts));
   
   if (!empty($align)) {
      $align = ' text-'.$align;
   }
   
   $strHtml = '<div class="col-block'.$align.'">';
   $strHtml .= do_shortcode($content);
   $strHtml .= '</div>';
   return $strHtml;
}
add_shortcode("col-block", "col_block_sc");   

// HIGHLIGHT TEXT //
function highlight_text_sc($atts, $content = null) {
/* Function to output some highlighted text - used within other shortcodes  */
   
   $strHtml = '<div class="highlight">';
   $strHtml .= do_shortcode($content);
   $strHtml .= '</div>';
   return $strHtml;
}
add_shortcode("highlight-text", "highlight_text_sc");   


// QUOTE BLOCK //
function quote_block_sc($atts, $content = null) {
/* Function to output the quote block */
   // Get parameters
   extract(shortcode_atts(array(
      'cite' => '',
   ), $atts));
   
   
   $strHtml = '<blockquote>';
   $strHtml .= do_shortcode($content);
   if (!empty($cite)) {
      $strHtml .= '<cite>- Source: '.$cite.'</cite>';
   }    
   $strHtml .= '</blockquote>';
   return $strHtml;
}
add_shortcode("quote-block", "quote_block_sc");   

// ASIDE //
function aside_sc($atts, $content = null) {
/* Function to output the quote aside */
   
   $strHtml = '<div class="aside">';
   $strHtml .= do_shortcode($content);
   $strHtml .= '</div>';
   return $strHtml;
}
add_shortcode("aside", "aside_sc");   



 