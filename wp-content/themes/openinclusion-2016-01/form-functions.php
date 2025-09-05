<?php
// Arrays for courses and internet access
$contact_method_array = array(
   array('Email',__( 'Email', 'openinclusion' ),'_PreferToContact', '_PreferToContact_Email'),
   array('Landline',__( 'Landline', 'openinclusion' ),'_PreferToContact', '_PreferToContact_Landline'),
   array('Mobile',__( 'Mobile', 'openinclusion' ),'_PreferToContact', '_PreferToContact_Mobile'),
   array('Text',__( 'Text', 'openinclusion' ),'_PreferToContact', '_PreferToContact_Text'),
   array('Calls via Text Relay Service',__( 'Calls via Text Relay Service', 'openinclusion' ),'_PreferToContact', '_PreferToContact_cvtrs')
);
function get_contact_methods() {
   global $contact_method_array;
   return $contact_method_array; 
}

$region_array = array(
   array('481',__( 'London', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_481'),
   array('483',__( 'South East', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_483'),
   array('485',__( 'South West', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_485'),
   array('487',__( 'East of England', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_487'),
   array('489',__( 'East Midlands', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_489'),
   array('491',__( 'West Midlands', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_491'),
   array('493',__( 'Yorkshire &amp; Humber', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_493'),
   array('495',__( 'North East', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_495'),
   array('497',__( 'North West', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_497'),
   array('499',__( 'Scotland', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_499'),
   array('501',__( 'Wales', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_501'),
   array('503',__( 'Northern Ireland', 'openinclusion' ),'inf_option_Whatregiondoyoulivein_503')
);
function get_regions() {
   global $region_array;
   return $region_array; 
}

$gender_array = array(
   array('505',__( 'Male', 'openinclusion' ),'inf_option_Gender_505'),
   array('507',__( 'Female', 'openinclusion' ),'inf_option_Gender_507'),
array('782',__( 'Non-binary', 'openinclusion' ),'inf_option_Gender_782'),
array('776',__( 'I prefer to self-describe', 'openinclusion' ),'inf_option_Gender_776'),
	array('774',__( 'Prefer not to say', 'openinclusion' ),'inf_option_Gender_774'),
);
function get_genders() {
   global $gender_array;
   return $gender_array; 
}


   //array('511',__( 'Asian or British Asian', 'openinclusion' ),'inf_option_Ethnicity_511'),
   //array('515',__( 'Chinese or other ethnic group', 'openinclusion' ),'inf_option_Ethnicity_515'),

$ethnicity_array = array(
   array('509',__( 'White', 'openinclusion' ),'inf_option_Ethnicity_509'),
   array('1248',__( 'South Asian or British South Asian', 'openinclusion' ),'1248-Panel-Ethnicity-South-Asian'),
   array('1248',__( 'East Asian or British East Asian', 'openinclusion' ),'1249-Panel-Ethnicity-East-Asian'),
   array('1248',__( 'Middle Eastern or British Middle Eastern', 'openinclusion' ),'1250-Panel-Ethnicity-Middle-Eastern'),
   array('513',__( 'Black, African, Caribbean or Black British', 'openinclusion' ),'inf_option_Ethnicity_513'),
   array('517',__( 'Mixed', 'openinclusion' ),'inf_option_Ethnicity_517'),
   array('1240',__( 'Other ethnic group', 'openinclusion' ),'1240-Panel-Ethnicity-Other'),
);
function get_ethnicities() {
   global $ethnicity_array;
   return $ethnicity_array; 
}
$conditions_array = array(
   array('519',__( 'Just getting older', 'openinclusion' ),'inf_option_Justgettingolder'),
   array('521',__( 'Partially sighted', 'openinclusion' ),'inf_option_Partiallysighted'),
   array('523',__( 'Blind with some useful vision', 'openinclusion' ),'inf_option_Blindwithsomeusefulvision'),
   array('525',__( 'Blind without useful vision', 'openinclusion' ),'inf_option_Blindwithoutusefulvision'),
   array('527',__( 'Deaf', 'openinclusion' ),'inf_option_Deaf'),
   array('529',__( 'Hard of hearing', 'openinclusion' ),'inf_option_Hardofhearing'),
   array('531',__( 'Mobility impaired', 'openinclusion' ),'inf_option_Mobilityimpaired'),
   array('533',__( 'Manual dexterity difficulties', 'openinclusion' ),'inf_option_Manualdexteritydifficulties'),
   array('535',__( 'Speech impaired', 'openinclusion' ),'inf_option_Speechimpaired'),
   array('537',__( 'Learning difficulties / disability', 'openinclusion' ),'inf_option_Learningdifficultiesdisability'),
   array('539',__( 'Cognitive impaired or Learn differently', 'openinclusion' ),'inf_option_Cognitiveloss'),
   array('541',__( 'Dyslexia', 'openinclusion' ),'inf_option_Dyslexia'),
   array('543',__( 'Mental health issues or ADHD/ASD', 'openinclusion' ),'inf_option_Mentalhealthissues'),
   array('545',__( 'Colour blind', 'openinclusion' ),'inf_option_Colourblind'),
   array('547',__( 'Left handed', 'openinclusion' ),'inf_option_Lefthanded'),
   array('549',__( 'Under 4 feet 11 inches tall ', 'openinclusion' ),'inf_option_Under49'),
   array('551',__( 'Over 6 feet 2 inches tall', 'openinclusion' ),'inf_option_Over66'),
);
function get_conditions() {
   global $conditions_array;
   return $conditions_array; 
}
$supports_array = array(
   array('553',__( 'Manual wheelchair user', 'openinclusion' ),'inf_option_Manualwheelchairuser'),
   array('555',__( 'Powered wheelchair user', 'openinclusion' ),'inf_option_Poweredwheelchairuser'),
   array('557',__( 'Mobility scooter user', 'openinclusion' ),'inf_option_Mobilityscooteruser'),
   array('559',__( 'Assistance dog user', 'openinclusion' ),'inf_option_Assistancedoguser'),
   array('561',__( 'Hearing aid user', 'openinclusion' ),'inf_option_Hearingaiduser'),
   array('563',__( 'Induction loop', 'openinclusion' ),'inf_option_Inductionloop'),
   array('565',__( 'BSL', 'openinclusion' ),'inf_option_BSL'),
   array('567',__( 'Level / ramped access', 'openinclusion' ),'inf_option_Levelrampedaccess'),
   array('569',__( 'Accessible parking', 'openinclusion' ),'inf_option_Accessibleparking'),
   array('571',__( 'Braille', 'openinclusion' ),'inf_option_Braille'),
   array('573',__( 'Large print', 'openinclusion' ),'inf_option_Largeprint'),
   array('575',__( 'Assistive technology on your computer / tablet / phone', 'openinclusion' ),'inf_option_Assistivetechnologyonyourcomputertabletphone'),
   array('577',__( 'PA / carer', 'openinclusion' ),'inf_option_PAcarer'),
);
function get_supports() {
   global $supports_array;
   return $supports_array; 
}

$legals_array = array(
   array('579',__( 'There is no obligation to accept work offered and I can ask to be removed from the panel at any time', 'openinclusion' ),'inf_option_ThereisnoobligationtoacceptworkofferedandIcanasktoberemovedfromthepanelatanytime'),
   array('581',__( 'I am responsible for any income tax that may be due on payment I receive from Open Inclusion ltd.', 'openinclusion' ),'inf_option_IamresponsibleforanyincometaxthatmaybedueonpaymentIreceivefromOpenInclusionltd'),
);
function get_legals() {
   global $legals_array;
   return $legals_array; 
}

$days_array = array(
   array('1',__( 'Monday', 'openinclusion' )),
   array('2',__( 'Tuesday', 'openinclusion' )),
   array('3',__( 'Wednesday', 'openinclusion' )),
   array('4',__( 'Thursday', 'openinclusion' )),
   array('5',__( 'Friday', 'openinclusion' )),
   array('6',__( 'Saturday', 'openinclusion' )),
   array('7',__( 'Sunday', 'openinclusion' )),
);
function get_days_array() {
   global $days_array;
   
   return $days_array;
}


/////////////////// Arrays for date of birth //////////////////
$arrDobD = array();
$arrDobM = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$arrDobY = array();

function getDayArray() {
   global $arrDobD;
   
   //Populate if not already done so
   if (count($arrDobD) == 0) {
      for ($i = 1; $i <= 31; $i++) {
         $arrDobD[] = $i;
      }
   }
   
   return $arrDobD;
}
function getMonthArray() {
   global $arrDobM;
   
   return $arrDobM;
}

function getYearArray() {
   global $arrDobY;
   
   //Populate if not already done so
   if (count($arrDobY) == 0) {
      $thisYr = (int)date('Y');
      for ($i = $thisYr; $i >= ($thisYr - 100); $i--) {
         $arrDobY[] = $i;
      }
   }
   
   return $arrDobY;
}



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


// Sanitise email addresses
function safeEmail($str) {
   return  filter_var($str, FILTER_SANITIZE_EMAIL);
}

function outScrn($inTxt) {
// Takes a varible name and applies necessary protection
   $inTxt = htmlspecialchars($inTxt, ENT_QUOTES, 'UTF-8');

   return $inTxt;
}
function errInd($fieldName) {
   $path = get_bloginfo('template_url').'/images/';
   $html = '<img src="'.$path.'error-ind.gif" height="16" width="16" alt="Error Indicator" title="Error Indicator" id="'.$fieldName.'-err-ind" /> ';

   return $html;
}

function getErrorMsg($arr, $field) {
   foreach($arr as $item) {
      if ($item[0] == $field) return $item[1];
   }
   
   return '';

}



/////////////////// Validation functions /////////
function isValidURL($url) {

   return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

function checkUrlStart($url) {
   // check url starts with http:// - and if not, add on front
   $test1 = 'http://';
   $test2 = 'https://';
   
   $pos1 = strrpos ( $url , $test1, 0 );
   $pos2 = strrpos ( $url , $test2, 0 );
   
   //echo ($pos1 != 0);
   
   if (($pos1 === 0) or ($pos2 === 0 ) ) {
      return $url;
   } else {
      return $test1.$url;
   }
}

// Errors array
$arrErrs = array();
function getFormErrors() {
   global $arrErrs;
   
   return $arrErrs;
}
function setFormErrors($arr) {
   global $arrErrs;
   
   $arrErrs = $arr;
}

// Clean form values
$clean = array();
function getClean() {
   global $clean;
   
   return $clean;
}
function setClean($arr) {
   global $clean;
   
   $clean = $arr;
}

///////////////////////// Shortcodes to place forms on pages //////////////////////////////
/**********************************************************************************************
Contact Forms

This function places the panel form on the page
**********************************************************************************************/
function opinc_panel_form_sc($atts, $content = null) {
   // Get parameters
   extract(shortcode_atts(array(
   ), $atts));

   // Pull in stored values
   $arrErrs = getFormErrors();
   $clean = getClean();
   
   //var_dump($clean);
   
   global $panelForm;
   
   // Call the function to print out the form and return
   $strHtml = printFormNew($panelForm, $clean, $arrErrs );
   
   // Need the javascript after the form
   $strHtml .= '<script type="text/javascript" src="https://ly190.infusionsoft.com/app/webTracking/getTrackingCode?trackingId=3e8aae4c347ffce85759672e1959435e"></script>';
   return $strHtml;


}
add_shortcode("opinc-panel-form", "opinc_panel_form_sc");   


function getFormFieldLength($formDef, $fieldName) {
   foreach((array)$formDef['fields'] as $field) {
      if ($field['name'] == $fieldName) {
         return $field['maxlen'];
         break;
      }
   }
   return false;
}
function checkSelectInOptions($formDef, $fieldName, $val) {
// Checks that a submitted value from a select control 
// matches one of the available options
   foreach((array)$formDef['fields'] as $field) {
      if (($field['type'] == 'select') and ($field['name'] == $fieldName)) {
         foreach((array)$field['options'] as $option) {
            if ($option[0] == $val) {
               return true;
               break;
            }
         }
      }
   }
   return false;
}
function checkCheckboxInOptions($formDef, $fieldName, $val) {
// Checks that a submitted value from a checkbox group 
// matches one of the available options
   foreach((array)$formDef['fields'] as $field) {
      if (($field['type'] == 'chkboxgroup') and ($field['name'] == $fieldName)) {
         foreach((array)$field['options'] as $option) {
            if ($option[0] == $val) {
               return true;
               break;
            }
         }
      }
   }
   return false;
}

function printFormErrors($formDef,$arrErrs ) {
   $strHtml = '';
   
   if (count($arrErrs) > 0){
      $strHtml .= '<div id="'.$formDef['error-sect-id'].'" class="'.$formDef['error-sect-class'].'">';
      $strHtml .= '<h'.$formDef['error-sect-hdr-level'].'>'.$formDef['error-sect-hdr'].'</h'.$formDef['error-sect-hdr-level'].'>';
      $strHtml .= $formDef['error-sect-intro'];
      $strHtml .= '<ul>';
      foreach ($arrErrs as $err) {
         if (!empty($err[0])) {
            $strHtml .= '<li><a href="#'.$err[0].'">'.$err[1].'</a></li>';
         } else {
            $strHtml .= '<li>'.$err[1].'</li>';
         }
      }
      $strHtml .= '</ul></div>';
   }
   return $strHtml;
}


////////////// New Print form /////////////////////////////
function printFormNew($formDef,$clean, $arrErrs ) {
   // Initialise strings
   $strHidden = '';

   //Check action URL
   if (empty($formDef['submit-to'])) {
      $action_url = get_the_permalink();
   } else {
      $action_url = $formDef['submit-to'];
   }
   
   // Start error container
   $strHtml = '<div id="form-error-list"></div>';

   // Start container and form
   $strHtml .= '<div class="'.$formDef['cont-class'].'">';
   $strHtml .= '<form role="form" aria-label="Panel form" action="'.$action_url.'" method="post" name="'.$formDef['form-id'].'" id="'.$formDef['form-id'].'">';
   $strHtml .= '<p>Required information is marked with a <span class="mand">'.$formDef['mand-ind'].'</span></p>';

   // Start list
   $firstTime = true;
   $strHtml .= '<ul>';
   
   // Print out each of the fields
   foreach($formDef['fields'] as $field) {
      $errMsg = getErrorMsg($arrErrs, $field['name']);
      $errInd = '';
      $ariaInvalidFrag = '';
      
      // Check for any errors on this field
      if (!empty($errMsg)) {
         //$errInd = errInd($field['name']);
         $ariaInvalidFrag = ' aria-invalid="true"';
      } else {
         if (!empty($clean['submitted']) and $clean['submitted']) {
            $ariaInvalidFrag = ' aria-invalid="false"';
         }
      }  
      
      // Retrieve class name for <li>
      $liCss = (empty($field['li-class']))?'':' class="'.$field['li-class'].'"';
      // Is field required?
      $reqd = false;
      $reqStr = '';
      $reqAttr = '';
      // These straings are used in client side validation and will contain 
      // the validation that can be done on the client.
      $validStr = '';
      $validFrag = '';
      
      //echo '<p>Field '.$field['name'].'</p>';
      //if (!empty($field['validation'])) var_dump($field['validation']);

      // Check in validation array - if it exists
      if (!empty($field['validation'])) {
         foreach((array) $field['validation'] as $validate) {
            switch ($validate[0]) {
               case 'reqd' :
                  $reqd = true;
                  $reqStr = '&nbsp;<span class="mand">'.$formDef['mand-ind'].'</span>';
                  $reqAttr = ' aria-required="true"';
                  
                  $validStr .= ' data-v-reqd="'.$validate[1].'"';
                  break;
               case 'reqd-all' :
                  $reqd = true;
                  $reqStr = '&nbsp;<span class="mand">'.$formDef['mand-ind'].'</span>';
                  $reqAttr = ' aria-required="true"';
                  
                  $validStr .= ' data-v-reqd-all="'.$validate[1].'"';
                  break;
               case 'len' :
                  $validStr .= ' data-v-len="'.$field['maxlen'].'~'.sprintf($validate[1],$field['maxlen']).'"';
                  break;
               case 'email' :
                  $validStr .= ' data-v-email="'.$validate[1].'"';
                  break;
               case 'int' :
                  $validStr .= ' data-v-int="'.$validate[1].'"';
                  break;
               case 'sqldate' :
                  $validStr .= ' data-v-sqldate="'.$validate[1].'"';
                  break;
               case 'sq' :
                  $validStr .= ' data-v-sq="'.getSecA($clean['sq']).'~'.$validate[1].'"';
                  break;
           }
         }
      } // end of if (!empty($field['validation']))

      if ($field['type'] != 'chkboxgroup') {
         // Construct start of label
         if (!empty($field['label'])) {
            $labelTxt = '<label for="'.$field['name'].'"><span class="text">'.$errInd.$field['label'].$reqStr.'</span>';
         } else {
            $labelTxt = '';
         }
      } else {
         // Checkbox group labels will be handled further down
      }
      
      switch ($field['type']) {
         case 'text':
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= $labelTxt;
            // Check if clean array populated - to see if prev value stored
            if (count($clean) > 0) {
               $val = outScrn($clean[$field['name']]);
            } else {
               $val = '';
            }
            $strHtml .= '<input maxlength="'.$field['maxlen'].'" type="text" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$val.'"';
            $strHtml .= $reqAttr.$ariaInvalidFrag.$validStr.'><span class="errors">'.$errMsg.'</span>';
            $strHtml .= '</label></li>';
            break;
         
         case 'textarea':
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= $labelTxt;
            $strHtml .= '<textarea cols="" rows="3" name="'.$field['name'].'" id="'.$field['name'].'"'.$reqAttr.$ariaInvalidFrag.$validStr.'>';
            // Check if clean array populated - to see if prev value stored
            if (count($clean) > 0) {
               $strHtml .= outScrn($clean[$field['name']]);
            }
            $strHtml .= '</textarea>';
            $strHtml .= '<span class="errors">'.$errMsg.'</span>';
            $strHtml .= '</label></li>';
            break;
         
         case 'select':
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= $labelTxt;
            $strHtml .= '<select name="'.$field['name'].'" id="'.$field['name'].'"'.$reqAttr.$ariaInvalidFrag.$validStr.'>';
            $strHtml .= '<option value="">Please choose...</option>';
            
            foreach ((array)$field['options'] as $option) {
               $selected = ($clean[$field['name']] == $option[0])?' selected="selected"':'';
               $strHtml .= '<option value="'.$option[0].'"'.$selected.'>'.$option[1].'</option>';
            
            }
            $strHtml .= '</select><span class="errors">'.$errMsg.'</span>';
            $strHtml .= '</label></li>';
            break;
         
         case 'chkbox':  // Single checkbox
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= '<input type="checkbox" name="'.$field['name'].'" id="'.$field['name'].'" value="'.outScrn($clean[$field['name']]).'"';
            $strHtml .= $labelTxt;
            $strHtml .= $reqAttr.$ariaInvalidFrag.$validStr.'><br><span class="errors">'.$errMsg.'</span>';
            $strHtml .= '</label></li>';
            break;

          case 'chkboxgroup':
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= '<fieldset data-type="chkbox"'.$validStr.'>';
            $strHtml .= '<legend id="'.$field['name'].'-legend">'.$field['label'].' '.$reqStr.'</legend>';
            $strHtml .= '<ul class="checkbox-radio">';
            
            foreach ((array)$field['options'] as $option) {
               // See if any of them have been checked
               $checked = '';
               foreach ((array)$clean[$field['name']] as $sel) {
                  if ($sel == $option[0]) {
                     $checked = ' checked="checked"';
                     break;
                  }
               }

               $strHtml .= '<li class="check-radio">';
               $strHtml .= '<input type="checkbox" name="'.$field['name'].'[]" id="'.$field['name'].'-'.$option[0].'" value="'.$option[0].'" aria-labelledby="'.$field['name'].'-legend '.$field['name'].'-'.$option[0].'-label '.$field['name'].'-errors"'.$checked.'>';
               $strHtml .= '<label for="'.$field['name'].'-'.$option[0].'" id="'.$field['name'].'-'.$option[0].'-label">';                
               $strHtml .= $option[1].'</label>';                
               $strHtml .= '</li>';
            }
            $strHtml .= '</ul>';
            $strHtml .= '<div class="fieldseterrors" id="'.$field['name'].'-errors">'.$errMsg.'</div>';
            $strHtml .= '</fieldset></li>';
            break;
         
          case 'chkboxgroup-inf':
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= '<fieldset data-type="chkbox"'.$validStr.'>';
            $strHtml .= '<legend id="'.$field['name'].'-legend">'.$field['label'].' '.$reqStr.'</legend>';
            $strHtml .= '<ul class="checkbox-radio">';
            
            foreach ((array)$field['options'] as $option) {
               // See if any of them have been checked
               $checked = '';

               $strHtml .= '<li class="check-radio">';
               $strHtml .= '<input type="checkbox" name="'.$option[2].'" id="'.$option[3].'" value="'.$option[0].'" aria-labelledby="'.$field['name'].'-legend '.$field['name'].'-'.$option[0].'-label '.$field['name'].'-errors"'.$checked.'>';
               $strHtml .= '<label for="'.$option[3].'" id="'.$field['name'].'-'.$option[0].'-label">';                
               $strHtml .= $option[1].'</label>';                
               $strHtml .= '</li>';
            }
            $strHtml .= '</ul>';
            $strHtml .= '<div class="fieldseterrors" id="'.$field['name'].'-errors">'.$errMsg.'</div>';
            $strHtml .= '</fieldset></li>';
            break;
         
          case 'radiogroup-inf':
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= '<fieldset data-type="radio"'.$validStr.'>';
            $strHtml .= '<legend id="'.$field['name'].'-legend">'.$field['label'].' '.$reqStr.'</legend>';
            $strHtml .= '<ul class="checkbox-radio">';
            
            foreach ((array)$field['options'] as $option) {
               // See if any of them have been checked
               $checked = '';

               $strHtml .= '<li class="check-radio">';
               $strHtml .= '<input type="radio" name="'.$field['name'].'" id="'.$option[2].'" value="'.$option[0].'" aria-labelledby="'.$field['name'].'-legend '.$field['name'].'-'.$option[0].'-label '.$field['name'].'-errors"'.$checked.'>';
               $strHtml .= '<label for="'.$option[2].'" id="'.$field['name'].'-'.$option[0].'-label">';                
               $strHtml .= $option[1].'</label>';                
               $strHtml .= '</li>';
            }
            $strHtml .= '</ul>';
            $strHtml .= '<div class="fieldseterrors" id="'.$field['name'].'-errors">'.$errMsg.'</div>';
            $strHtml .= '</fieldset></li>';
            break;
         
         case 'sq':
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= sprintf($labelTxt, getSecQ($clean['sq'])) ;
            $strHtml .= '<input maxlength="'.$field['maxlen'].'" type="text" name="'.$field['name'].'" id="'.$field['name'].'" value=""';
            $strHtml .= $reqAttr.$ariaInvalidFrag.$validStr.' /><span class="errors">'.$errMsg.'</span>';
            $strHtml .= '</label></li>';
            break;
         
         case 'start-fieldset':
            $strHtml .= '<fieldset id="'.$field['name'].'">';
            $strHtml .= '<legend>'.$field['legend'].'</legend>';
            break;

         case 'end-fieldset':
            $strHtml .= '</fieldset>';
            break;

         case 'other-html':
            $strHtml .= '<li'.$liCss.'>'.$field['value'].'</li>';
            break;

         case 'submit':
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= '<input type="submit" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$field['value'].'" />';
            $strHtml .= '</li>';
            break;

         case 'hidden':
            $strHidden .= '<input type="hidden" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$field['value'].'" />';
            break;

      }
      
   
   }

   $strHtml .= '</ul>';
  
   // Check if a security question is being used and if so,
   // put out the hidden fields with the index value inside
   if ($formDef['sq-reqd']) {
      $strHidden .= '<input type="hidden" name="'.$formDef['sq-id'].'" id="'.$formDef['sq-id'].'" value="'.$clean['sq'].'" />';

   }
   if (!empty($formDef['nonce-name'])) {
      $strHidden .= wp_nonce_field($formDef['nonce-name'],$formDef['nonce-name'],true, false);
   }
   
   $strHtml .= $strHidden; // Add hidden fields
   $strHtml .= '</form></div>';
   return $strHtml;

}

////////////////////////////////////////////////////


function validateForm($formDef,$clean, $arrErrs ) {
// This is definitely not finished - work in progress only

   $raw = array();
   
   foreach((array)$formDef['fields'] as $field) {
      
      foreach((array)$field['validation'] as $validate) {
      
         // Clean up the values passed in and store
         switch ($field['type']) {
            case 'text':
            case 'textarea':
               $clean[$field['name']] = substr(sanitize_text_field($_POST[$field['name']]), 0, $field['maxlen']);
               break;
            case 'select':
               // Select value must be one of the supplied values
               if (ccCheckArr($field['options'], $_POST[$field['name']])) {
                  $clean[$field['name']] = $_POST[$field['name']];
               } else {
                  $clean[$field['name']] = '';
               }
               
               break;
         }
         switch ($validate[0]) {
            case 'reqd':
               break;
         }



      } // End foreach $formDef['fields']
   } // End foreach $formDef['fields']
   var_dump($clean);
   
   // Update the global version of the $clean array
   setClean($clean);
   return false;
}

function setAria($errMsg, $clean) {
   $str = '';
   // Check for any errors on this field
   if (!empty($errMsg)) {
      $str = ' aria-invalid="true"';
   } else {
      if ($clean['submitted']) {
         $str = ' aria-invalid="false"';
      }
   }  

   return $str;
}

function validateDate($date, $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}


function check_uk_postcode($string){
    // Start config
    $valid_return_value = true;
    $invalid_return_value = false;
    $exceptions = array('BS981TL', 'BX11LT', 'BX21LB', 'BX32BB', 'BX55AT', 'CF101BH', 'CF991NA', 'DE993GG', 'DH981BT', 'DH991NS', 'E161XL', 'E202AQ', 'E202BB', 'E202ST', 'E203BS', 'E203EL', 'E203ET', 'E203HB', 'E203HY', 'E981SN', 'E981ST', 'E981TT', 'EC2N2DB', 'EC4Y0HQ', 'EH991SP', 'G581SB', 'GIR0AA', 'IV212LR', 'L304GB', 'LS981FD', 'N19GU', 'N811ER', 'NG801EH', 'NG801LH', 'NG801RH', 'NG801TH', 'SE18UJ', 'SN381NW', 'SW1A0AA', 'SW1A0PW', 'SW1A1AA', 'SW1A2AA', 'SW1P3EU', 'SW1W0DT', 'TW89GS', 'W1A1AA', 'W1D4FA', 'W1N4DJ');
    // Add Overseas territories ?
    array_push($exceptions, 'AI-2640', 'ASCN1ZZ', 'STHL1ZZ', 'TDCU1ZZ', 'BBND1ZZ', 'BIQQ1ZZ', 'FIQQ1ZZ', 'GX111AA', 'PCRN1ZZ', 'SIQQ1ZZ', 'TKCA1ZZ');
    // End config


    $string = strtoupper(preg_replace('/\s/', '', $string)); // Remove the spaces and convert to uppercase.
    $exceptions = array_flip($exceptions);
    if(isset($exceptions[$string])){return $valid_return_value;} // Check for valid exception
    $length = strlen($string);
    if($length < 5 || $length > 7){return $invalid_return_value;} // Check for invalid length
    $letters = array_flip(range('A', 'Z')); // An array of letters as keys
    $numbers = array_flip(range(0, 9)); // An array of numbers as keys

    switch($length){
        case 7:
            if(!isset($letters[$string[0]], $letters[$string[1]], $numbers[$string[2]], $numbers[$string[4]], $letters[$string[5]], $letters[$string[6]])){break;}
            if(isset($letters[$string[3]]) || isset($numbers[$string[3]])){
                return $valid_return_value;
            }
        break;
        case 6:
            if(!isset($letters[$string[0]], $numbers[$string[3]], $letters[$string[4]], $letters[$string[5]])){break;}
            if(isset($letters[$string[1]], $numbers[$string[2]]) || isset($numbers[$string[1]], $letters[$string[2]]) || isset($numbers[$string[1]], $numbers[$string[2]])){
                return $valid_return_value;
            }
        break;
        case 5:
            if(isset($letters[$string[0]], $numbers[$string[1]], $numbers[$string[2]], $letters[$string[3]], $letters[$string[4]])){
                return $valid_return_value;
            }
        break;
    }

    return $invalid_return_value;
}


add_action( 'wp_ajax_runFunction', 'runFunction' );
add_action( 'wp_ajax_nopriv_runFunction', 'runFunction' );
function runFunction() {	
   include("../infusion/process.php");
    die();
  wp_die(); 
 
}



?>