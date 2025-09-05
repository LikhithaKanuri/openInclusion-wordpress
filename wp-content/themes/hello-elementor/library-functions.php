<?php
// Library functions
add_filter('widget_text', 'do_shortcode');


// Remove Various information from the header
// Do Not do this if you use it
remove_action('wp_head','wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link'); 

// Add support for thumbnails
if ( function_exists( 'add_theme_support' ) ) { 
  add_theme_support( 'post-thumbnails' ); 
}

/* Date functions */
function yxmxdToFormat($strDate, $pattern = 'j F Y'){// Converts yyyymm  to month year
   return date($pattern,mktime(0,0,0,substr($strDate,5,2),substr($strDate,8,2),substr($strDate,0,4))); 

}



////////////////////////// File Functions //////////////////////////
function appendToFile($outFile,$newLine) {
   $ret = false;
   $handle = fopen($outFile, "a") or exit;
   fwrite($handle, $newLine); 
   fclose($handle); 
   $ret = true;
   return $ret;
}


///////////////////////// Common shortcodes /////////////////////////
function clear_sc($atts, $content = null) {
  return '<div class="clearfix"></div>';
}
add_shortcode("clear", "clear_sc");

function break_sc($atts, $content = null) {
  return '<br>';
}
add_shortcode("break", "break_sc");





///////////////////////////  Form related functions ///////////////////////////////
//Cope with magic quotes
if(function_exists("get_magic_quotes_gpc")) {
   if (get_magic_quotes_gpc()) {
      function co_stripslashes_deep($value)	{
         $value = is_array($value) ?
               array_map('co_stripslashes_deep', $value) :
               stripslashes($value);
   
         return $value;
      }
   
      $_POST = array_map('co_stripslashes_deep', $_POST);
      $_GET = array_map('co_stripslashes_deep', $_GET);
      $_COOKIE = array_map('co_stripslashes_deep', $_COOKIE);
      $_REQUEST = array_map('co_stripslashes_deep', $_REQUEST);
   }
   
}




///////////////////////// Email Functions ///////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
// General purpose email function - works with attachments
// This work is licensed under a Creative Commons Attribution-NonCommercial 3.0 Unported License
//
// $attachments is an array - composed of :
//    path to file:mime type:Send name
function Email($remail, $rname, $semail, $sname, $cc, $bcc, $subject, $message, $attachments, $priority, $type)  {

   // Checks if carbon copy & blind carbon copy exist
   if($cc != null){
      $cc="CC: ".$cc."\r\n";
   }else{
      $cc="";
   }
   if($bcc != null){
      $bcc="BCC: ".$bcc."\r\n";
   }else{
      $bcc="";
   }
   
   // Checks the importance of the email
   if($priority == "high"){
      $priority = "X-Priority: 1\r\nX-MSMail-Priority: High\r\nImportance: High\r\n";
   } elseif($priority == "low"){
      $priority = "X-Priority: 3\r\nX-MSMail-Priority: Low\r\nImportance: Low\r\n";
   } else{
      $priority = "";
   }
   
   // Checks if it is plain text or HTML
   if($type == "plain"){
      $type="text/plain";
   }else{
      $type="text/html";
   }
   
   // The boundary is set up to separate the segments of the MIME email
   $boundary = md5(@date("Y-m-d-g:ia"));
   
   // The header includes most of the message details, such as from, cc, bcc, priority etc. 
   $header = "From: ".$sname." <".$semail.">\r\nMIME-Version: 1.0\r\nX-Mailer: PHP\r\nReply-To: ".$sname." <".$semail.">\r\nReturn-Path: ".$sname." <".$semail.">\r\n".$cc.$bcc.$priority."Content-Type: multipart/mixed; boundary = ".$boundary."\r\n\r\n";    
     
   // The full message takes the message and turns it into base 64, this basically makes it readable at the recipients end
   $fullmessage .= "--".$boundary."\r\nContent-Type: ".$type."; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n".chunk_split(base64_encode($message));
   
   // A loop is set up for the attachments to be included.
   if($attachments != null) {
     foreach ($attachments as $attachment)  {
       $attachment = explode(":", $attachment);
       $fullmessage .= "--".$boundary."\r\nContent-Type: ".$attachment[1]."; name=\"".$attachment[2]."\"\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment\r\n\r\n".chunk_split(base64_encode(file_get_contents($attachment[0])));
     }
   }
   
   // And finally the end boundary to set the end of the message
   $fullmessage .= "--".$boundary."--";
   
   return mail($rname."<".$remail.">", $subject, $fullmessage, $header);
}




///////////////////////// Security Questions ////////////////////////
$secureQs = array(
   array('4 + 2 =','6'),
   array('1 + 3 =','4'),
   array('4 + 3 =','7'),
   array('5 + 2 =','7'),
   array('3 + 2 =','5'),
   array('1 + 2 =','3'),
   array('3 + 3 =','6'),
   array('1 + 1 =','2')
   );

function getSecArrayCount() {
   global $secureQs;
   return count($secureQs);
}

function getSecQ($i) {
   global $secureQs;
   return $secureQs[$i][0];
}
function getSecA($i) {
   global $secureQs;
   return $secureQs[$i][1];
}


//////////////////// String processing functions ///////////////////////////

/** Method to add hyperlink html tags to any urls, twitter ids or hashtags in the tweet */ 
function processLinks($text) {
/* Commented out - preg_replace calls deprecated in PHP7 */
/*
	$text = utf8_decode( $text );
	$text = preg_replace('@(https?://([-\w\.]+)+(d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a target="sfs" href="$1">$1</a>',  $text );
	$text = preg_replace("#(^|[\n ])@([^ \"\t\n\r<]*)#ise", "'\\1<a target=\"sfs\" href=\"http://www.twitter.com/\\2\" >@\\2</a>'", $text);  
	$text = preg_replace("#(^|[\n ])\#([^ \"\t\n\r<]*)#ise", "'\\1<a target=\"sfs\" href=\"http://twitter.com/search?q=%23\\2\" >#\\2</a>'", $text);
	//$text = preg_replace("#(^|[\n ])\#([^ \"\t\n\r<]*)#ise", "'\\1<a target=\"sfs\" href=\"http://hashtags.org/search?query=\\2\" >#\\2</a>'", $text);
	return $text;
*/
}



///////////////////////// Social Bookmarks Stuff ////////////////////////
function getSocBookmarks($pageUrl, $pageTitle, $content, $reqBookmarks = null) {
/* Called from a page or a post this function writes out a series of social bookmarks.
   Accepts URL and title of page. An optional 3rd paragrph specifies the bookmarks to include and the order. If not passed then the default selection is used. */
   if ($reqBookmarks == null) {
      $reqBookmarks = array(
         'Facebook', 
         'Twitter', 
         'LinkedIn'
      );
   }
   
   // Add site name to front of title
   $pageTitle = get_bloginfo('name').': '.$pageTitle;
   
   $temp = strip_shortcodes($content);
   $content = strip_tags($temp);
   
   $imgTransp = '<img src="'.get_bloginfo ('template_url').'/images/transp.gif" height="30" width="30" alt="Share this page on XXXXX (opens new window)" title="Share this page on XXXXX (opens new window)">';

   $strHtml = '<div class="soc-book"><p><strong>Share this page (Links open new windows/tabs)</strong></p><ul>';
   foreach($reqBookmarks as $bookmark) {
      switch ($bookmark) {
         case 'Facebook':
            $max = 70; // Logest allowed string for title
            $shortTitle = shortenText($pageTitle, 70,true);
            $strHtml .= '<li><a class="facebook" rel="nofollow" target="_blank"  href="http://www.facebook.com/sharer.php?u='.urlencode($pageUrl).'&amp;t='.urlencode($shortTitle).'"  >'.str_replace ( 'XXXXX', $bookmark, $imgTransp).'</a></li>';
            break; 

         case 'Twitter': 
            $shortTitle = shortenText($pageTitle, 60,true);
            $strHtml .= '<li><a class="twitter" rel="nofollow" target="_blank"  href="http://twitter.com/share?url='.urlencode($pageUrl).'&amp;text='.urlencode($shortTitle).'">'.str_replace ( 'XXXXX', $bookmark, $imgTransp).'</a></li>';
            break; 
         
         case 'LinkedIn': 
         
            $excerpt =  shortenText($content, 50,true);

            $strHtml .= '<li><a class="linkedin" rel="nofollow" target="_blank"  href="http://www.linkedin.com/shareArticle?mini=true&amp;url='.urlencode($pageUrl).'">'.str_replace ( 'XXXXX', $bookmark, $imgTransp).'</a></li>';

            break; 
         
         case 'Delicious': 
            $max = 70; // Logest allowed string for title
            $shortTitle = shortenText($pageTitle, 70,true);
            $strHtml .= '<li><a class="delicious" rel="nofollow" target="_blank"  href="http://delicious.com/post?url='.urlencode($pageUrl).'&amp;title='.urlencode($shortTitle).'"  >'.str_replace ( 'XXXXX', $bookmark, $imgTransp).'</a></li>';

            break; 
         
         case 'Digg': 
            $max = 70; // Logest allowed string for title
            $shortTitle = shortenText($pageTitle, 70,true);
            $strHtml .= '<li><a class="digg" rel="nofollow" target="_blank"  href="http://digg.com/submit?url='.urlencode($pageUrl).'&amp;title='.urlencode($shortTitle).'"  >'.str_replace ( 'XXXXX', $bookmark, $imgTransp).'</a></li>';

            break; 
         
         
         case 'Reddit':
            $max = 70; // Logest allowed string for title
            $shortTitle = shortenText($pageTitle, 70,true);
            $strHtml .= '<li><a class="reddit" rel="nofollow" target="_blank"  href="http://www.reddit.com/submit?url='.urlencode($pageUrl).'&amp;title='.urlencode($shortTitle).'"  >'.str_replace ( 'XXXXX', $bookmark, $imgTransp).'</a></li>';
            
            break; 
      }
   }
   $strHtml .= '</ul>';
   $strHtml .= '</div>';
   return $strHtml;
}


function shortenText($text, $chars, $add = false) { 
// Shortens $text to word boundary if longer than specified length - $chars.
// Optional parameter $add determines whether to show elipsis
   
   // First check to see if string is longer than allowed
   if (strlen($text) < $chars ) {
      // no need to shorten text so return orig
      return $text;
   }
   
   
   if ($add && ($chars > 3)) {  
   // If elipsis required and allowed length greater than 3
      $newChars = $chars - 3;  // take 3 off allowed length
      
      // Try and break the string at a suitable
      $newText = substr($text, 0, strrpos(substr($text, 0, $newChars), ' '));
      
      if (strlen($newText) == 0 ) {
         $newText = substr($text, 0, $newChars).'...';
      } else {
         $newText = $newText.'...';
      }
   } else {
      $newText = substr($text, 0, strrpos(substr($text, 0, $chars), ' '));
      if (strlen($newText) == 0 ) {
         $newText = substr($text, 0, $chars);
      }
   }
   return $newText; 

} 



///////////////////////// Wordpress Stuff ///////////////////////////


function increase_postmeta_form_limit() {
	return 120;
}
add_filter('postmeta_form_limit', 'increase_postmeta_form_limit'); 



//automatic_feed_links();
add_theme_support( 'automatic-feed-links' );

?>