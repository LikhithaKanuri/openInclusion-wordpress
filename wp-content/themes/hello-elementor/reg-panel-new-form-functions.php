<?php
add_action( 'wp_enqueue_scripts', 'openinclusion_script_enqueuer');

function openinclusion_script_enqueuer() {
	
	// FRONT END JS AND CSS ONLY
	if (!is_admin()) {
		wp_enqueue_script(
			'open-library', 
			get_bloginfo ('template_url').'/assets/js/library.js',
			array( 'jquery' ),
			filemtime(get_theme_file_path('/assets/js/library.js')),
			true
		);
		wp_localize_script( 'open-library', 'my_ajax_object', array('ajax_url' => admin_url( 'admin-ajax.php' )));      
      wp_enqueue_script('modernizr', get_bloginfo ('template_url').'/assets/js/modernizr-custom.min.js', false,  false, true);
      wp_enqueue_script('cookie', get_bloginfo ('template_url').'/assets/js/js.cookie.js', false,  false, true);
      wp_enqueue_script('flyingfocus', get_bloginfo ('template_url').'/assets/js/flying-focus.js', false,  false, true);
  
   	
		wp_register_style( 'styles', get_bloginfo ('template_url').'/assets/css/formstyles.css', array(), filemtime(get_theme_file_path('/assets/css/formstyles.css')));
		wp_enqueue_style( 'styles' );
		
	} 

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
   $clean = $_POST;
   return $clean;
}
function setClean($arr) {
   global $clean;
   
   $clean = $arr;
}

/**********************************************************************************************
      This function redirects the logged in user to open vanilla forum
**********************************************************************************************/

function openvanilla_redirect() {
	$requestURI = $_SERVER['REQUEST_URI'];
   if(str_contains($requestURI, '/login')) {
      return false;
   }
   
   $current_user = wp_get_current_user();
   if($current_user) {
      //if ( get_user_meta( $current_user->ID, 'ActivationKey', true ) != false ){
		
         if($_SERVER['HTTP_HOST'] == 'localhost') {
            $redirect = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/wp-login.php";
         }
         else {
            $redirect = "https://". $_SERVER['HTTP_HOST']. "/wp-login.php";
         } 
		   //wp_logout();
         //wp_redirect($redirect);
         //exit();              
      //}
   }
   
   if ( is_page( 'user' ) && is_user_logged_in() ) {
       wp_redirect( "https://openinclusion.vanillastaging.com/");
       exit();
   }
}
add_action( 'template_redirect', 'openvanilla_redirect' );


///////////////////////// Shortcodes to place forms on pages //////////////////////////////
/**********************************************************************************************
Contact Forms

This function places the panel form on the page
**********************************************************************************************/
function opinc_panel_form_sc_v2($atts, $content = null) {
   // Get parameters
   extract(shortcode_atts(array(
   ), $atts));

   // Pull in stored values
   $arrErrs = getFormErrors();
   $clean = getClean();
   
   global $panelForm;
   
   // Call the function to print out the form and return
   $strHtml = printFormNew($panelForm, $clean, $arrErrs );
   $strHtml.= "<script>jQuery(document).ready(function($) { jQuery('#content').find('header').remove(); });</script>";
   // Need the javascript after the form
   // $strHtml .= '<script type="text/javascript" src="https://ly190.infusionsoft.com/app/webTracking/getTrackingCode?trackingId=3e8aae4c347ffce85759672e1959435e"></script>';
   return $strHtml;
}

add_shortcode("opinc-panel-form-reg", "opinc_panel_form_sc_v2");

/**********************************************************************************************
Part 2 Step 1 Form Shortcode

This function places the Part 2 Step 1 form on the page
**********************************************************************************************/
function opinc_part2_step1_form_sc($atts, $content = null) {
   // Get parameters
   extract(shortcode_atts(array(
   ), $atts));

   // Check if user is logged in
   if (!is_user_logged_in()) {
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         $redirect = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/login";
      }
      else {
         $redirect = "https://". $_SERVER['HTTP_HOST']. "/login";
      }       
      wp_redirect( $redirect ); exit;
   }

   // Pull in stored values
   $arrErrs = getFormErrors();
   $clean = getClean();
   
   // If no form data, populate with existing user data
   if (empty($clean) || !isset($clean['submitted'])) {
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         $user_info = get_user_meta($userid);
         
         // Populate form with existing user data
         $clean = array();
         if(isset($user_info['Country'][0])) $clean['inf_field_country'] = $user_info['Country'][0];
         if(isset($user_info['Region'][0])) $clean['inf_field_region'] = $user_info['Region'][0];
         if(isset($user_info['Postcode'][0])) $clean['inf_field_postcode'] = $user_info['Postcode'][0];
         if(isset($user_info['Over 18'][0])) $clean['inf_field_over18'] = $user_info['Over 18'][0];
         if(isset($user_info['Year_Born'][0])) $clean['inf_custom_YearBorn'] = $user_info['Year_Born'][0];
         if(isset($user_info['Has Disability'][0])) $clean['inf_field_hasDisability'] = $user_info['Has Disability'][0];
         if(isset($user_info['Relationship to Disability'][0])) {
            $relationship_data = $user_info['Relationship to Disability'][0];
            if(strpos($relationship_data, '|') !== false) {
               $clean['RelationShip'] = explode('|', $relationship_data);
            } else {
               $clean['RelationShip'] = array($relationship_data);
            }
         }
      }
   }
   
   global $part2Step1Form;
   
   // Call the function to print out the form and return
   $strHtml = printFormNew($part2Step1Form, $clean, $arrErrs );
   $strHtml.= "<script>jQuery(document).ready(function($) { jQuery('#content').find('header').remove(); });</script>";
   
   return $strHtml;
}

add_shortcode("opinc-part2-step1", "opinc_part2_step1_form_sc");   

/**********************************************************************************************
This function redirects to thank you page after registration. Validate if consent is submitted
**********************************************************************************************/
function redirectAfterRegistration(){
   ob_clean();
   ob_start();
   // if(isset($_POST['consent'])) {
      $mailSent = false;
      if(isValidUserInput()) {
         // user table data
         $userData = array(
         'user_pass' =>  (trim($_POST['inf_field_Password']) != "") ? $_POST['inf_field_Password'] : 'Welcome@123',
         'user_login' => (trim($_POST['inf_field_UserName']) != "") ? $_POST['inf_field_UserName'] : $_POST['inf_field_Email'],
         'user_email' => $_POST['inf_field_Email'],
         'first_name' => $_POST['inf_field_FirstName'],
         'last_name' => $_POST['inf_field_LastName'],
         );
         // error_log(print_r($userData, 1));
         $returnVal = wp_insert_user($userData);
         if(!$returnVal || is_wp_error($returnVal)) {
            // echo $returnVal->get_error_message();
         }
         else {
            $userId = $returnVal;
            $userMetaData = prepareUserMetaData();
            //error_log(print_r($userMetaData, 1));
            foreach( $userMetaData as $key => $val ) {
               update_user_meta( $userId, $key, $val ); 
            }
            $code = sha1( $userId . time() );

            $baseLink = "https://openinclusion.com/activation/";
            if(isset($_SERVER['HTTP_HOST'])) {
               if($_SERVER['HTTP_HOST'] == 'localhost') {
                  $baseLink = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/activation/";
               }
               else {
                  $baseLink = "https://". $_SERVER['HTTP_HOST']. "/activation/";
               }          
            }
   
            $activation_link = add_query_arg( array( 'key' => $code, 'user' => $userId ), $baseLink);
            add_user_meta( $userId, 'ActivationKey', $code);
            $mailSent = sentUserActivationMail($userData['user_email'], $userData['first_name'] . " " . $userData['last_name'], $activation_link);
         }
         $contactId = "";
         include_once (__DIR__."/../../../infusion/processv2.php");
         // var_dump($mailSent);
         // var_dump($contactId );
         // var_dump(isset($_SERVER['HTTP_HOST']));
         // exit();
         if($mailSent && $contactId && isset($_SERVER['HTTP_HOST'])) {
            if($_SERVER['HTTP_HOST'] == 'localhost') {
               $redirectUrl = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/thank-you/";
            }
            else {
               $redirectUrl = "https://" . $_SERVER['HTTP_HOST']."/thank-you/";
            }         
         }
         else {
            $redirectUrl = "https://staging4.openinclusion.com/thank-you/";
         }
         
         wp_redirect($redirectUrl);
         exit;
      }
   // }
}

add_action( 'template_redirect', 'redirectAfterRegistration');


/**********************************************************************************************
   Below two function prepare the registration form and meta data mapping array.
**********************************************************************************************/
function getUserMetaDataMapping() {
   return array(
      'inf_field_FirstName' => 'First Name',
      'inf_field_LastName' => 'Last Name',
      'inf_field_Email' => 'Email',
      'inf_field_countryphonecode' => 'Contact Number',
      'inf_field_countryphonecode' => 'Country Code',
      'inf_field_Phone2' => 'Contact Number',      
      'PreferToContact' => 'Prefer To Contact',
      'PreferToContactOthers' => 'Prefer To Contact',
      'PreferToContactOthers' => 'Prefer To Contact Other',
      'inf_field_country' => 'Country',
      'inf_field_region' => 'Region',
      'inf_custom_YearBorn' => 'Year_Born',
      'inf_option_Gender' => 'Gender',
      'inf_option_Gender_opentext' => 'Gender',
      'inf_option_Gender_opentext' => 'Gender OpenText',
      'SensoryNeeds' => 'Sensory Needs',
      'PhysicalNeeds' => 'Physical Needs',
      'CognitiveAndMentalhealthNeeds' => 'Cognitive And Mental health Needs',
      'CommunicationNeeds' => 'Communication Needs',
      'ChronichealthNeeds' => 'Chronic health Needs',
      'OtherNeeds' => 'Other Needs',
      'OtherTechnologies' => 'Other Technologies',
      // 'OtherNeedsOtherPleaseSpecify' => 'Other Needs',
      'OtherNeedsOtherPleaseSpecify_OpenText' => 'Other Needs Open Text',
      'inf_field_PrimaryNeed' => 'Primary Need',
      'inf_field_Age_Bracket' => 'Age Bracket',
      'inf_field_TemporaryAccessNeed' => 'Temporary Access Need',
      'DigitalandScreenTechnologies' => 'Digital and Screen Technologies',
      'MovementCanesandServiceAnimals' => 'Movement Canes and Service Animals',
      'CommunicationPreferences' => 'Communication Preferences',
      'PersonalSupportandHome' => 'PersonalSupportandHome',
      // 'OtherTechnologiesOtherPleaseSpecify' => 'Other Technologies',
      'OtherTechnologiesOtherPleaseSpecify_OpenText' => 'Other Technologies Open Text',
      // 'OtherTechnologiesOtherPleaseSpecify_OpenText' => 'Other Technologies Open Text',
      'consent' => 'Consent',
   );
}

function prepareUserMetaData() {
   $mappingArray = getUserMetaDataMapping();
   $output = array();
   foreach($mappingArray as $inputKey => $mappingKey) {
      if(isset($_POST[$inputKey])) {
         $inputVal = $_POST[$inputKey];
         if(is_array($inputVal)) {
            $data = implode("|", $inputVal);
            if ($inputKey !== "PreferToContactOthers" && count($inputVal) === 1) {
               $data .= "|";
            }
         }
         else {
            $data = $inputVal;
         }
         if(isset($output[$mappingKey])) {
            $existingData = $output[$mappingKey];
            $output[$mappingKey] = trim($existingData . " " . $data);
         }
         else {
            $output[$mappingKey] = trim($data);
         }
      }
      else{
         $data = "";
         $output[$mappingKey] = trim($data);
      }
   }
   // Phone code mapping
   if(isset($_POST['inf_field_country'])) {
      $country = $_POST['inf_field_country'];
      $phoneCodes = getPhoneCodes();
      if(isset($phoneCodes[$country])) {
         $output['Phone Code'] = $phoneCodes[$country];
      }
   }
   return $output;
}

/**********************************************************************************************
      This function validate the user registration have valid inputs.
**********************************************************************************************/
function isValidUserInput() {
   $return = true;
   /*
   if(!isset($_POST['inf_field_UserName'])){
      $return = false;
   } 
   if(!isset($_POST['inf_field_Password'])){
      $return = false;
   }
   */
   if(!isset($_POST['inf_field_re_Email'])) {
      $return = false;
   }
   if(!isset($_POST['inf_field_FirstName'])){
      $return = false;
   }
   if(!isset($_POST['inf_field_LastName'])){
      $return = false;
   }   
   return $return;
}

/**********************************************************************************************
      This function sents the user activation mail
**********************************************************************************************/
function sentUserActivationMail($toEmailId, $name, $activation_link) {
   $headers = array(
      'Content-Type: text/html; charset=UTF-8',
      'From: Open Inclusion <contact@openinclusion.com>'
    );
   // $headers = 'From: Open Inclusion';
   // $headers .= 'Content-Type: text/html; charset=UTF-8';
   $subject = "Please confirm your email for Open Inclusion’s new community engagement platform";
   $content = "
   <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">
   <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
   <link href=\"https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap\" rel=\"stylesheet\">
   <div style=\"background: #f2f2f2;\">
   <div style=\"height: 50px;background:#E5E8E8\"></div>
   <div style=\"max-width: 560px; padding: 20px; background: #ffffff; border-radius: 5px; margin: 40px auto; font-family: Poppins; color: #666;\">
   <div style=\"color: #444444; font-weight: normal;\"></div>
   <div style=\"padding: 0 30px 30px 30px;\">
   <div style=\"padding: 30px 0; font-size: 24px; text-align: center; line-height: 40px;\">
   <img src=\"https://staging4.openinclusion.com/wp-content/uploads/cropped-1.-MAIN-OpenInclusion_Stack_Navy-scaled-1.jpg\" style=\"height:100px\" />
   <div style=\"padding: 30px 0px; font-size: 24px; line-height: 40px; text-align: left;\">
   Dear ".ucwords(strtolower($name)).",
   
   <p style=\"font-family: Poppins;\"> Thank you for registering with Open Inclusion’s online community.<br/><strong>Please confirm your email to complete the initial set-up</strong> of your profile by clicking the button below:</p> 
  
   
   </div>
   <div>
   <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\" style=\"height: 50px;background: #2a3258;border-radius: 23px;\">
   <tr>
   <td style=\"text-align: left; padding-top:10px;padding-right:15px;padding-bottom:10px;padding-left:15px;\">
   <a href=\"".$activation_link."\" target=\"_blank\" rel=\"noopener\" style=\"border-radius: 23px;background: #2a3258;text-decoration: none;font-family: Poppins-Medium, Poppins;font-style: normal;font-weight: 500;line-height: 38px;height: 50px;padding: 6px; content: left; font-size: 24px\">
   <span style=\"color:#ffffff;\">Verify your email and login</span>
   </a>
   </td>
   </tr>
   </table>
   </div>
   <br />
    <div style=\"padding: 30px 0px; font-family: Poppins; font-size: 24px; line-height: 40px; text-align: left; content: left\">
   <p style=\"font-family: Poppins;content: left; font-size: 24px\">When you log in for the first time, you’ll be directed to the second part of the registration form, where you will have the opportunity to update your access needs and assistive technology preferences. This helps us invite you to research opportunities that best suit you
   </p>
   <p style=\"font-family: Poppins; font-size: 24px\">
   Thank you, <br />The Open Inclusion Team
   </p>
   </div>
   </div>
   </div>
   </div>
   <div style=\"height: 50px;background:#E5E8E8\"></div>
   </div>";

   return wp_mail( $toEmailId, $subject, $content, $headers);     
}

/**********************************************************************************************
      This function activate the user
**********************************************************************************************/
function opinc_panel_useractivation($atts, $content = null) {
   $user_id = filter_input( INPUT_GET, 'user', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
   if ( $user_id ) {
      // get user meta activation hash field
      $code = get_user_meta( $user_id, 'ActivationKey', true );
      if ( $code == filter_input( INPUT_GET, 'key' ) ) {
          delete_user_meta( $user_id, 'ActivationKey' );
      }
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         $redirect = "https:/staging4.openinclusion.com/multi-step-registration-1/";
         // $redirect = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/login";
      }
      else {
        // $redirect = "https://". $_SERVER['HTTP_HOST']. "/login";
        $redirect = "https://openinclusion.vanillastaging.com/entry/signin?target=https:/staging4.openinclusion.com/multi-step-registration-1/";
      }       
      wp_redirect( $redirect ); exit;
  }
}

add_shortcode("opinc-panel-activation", "opinc_panel_useractivation");  



/**********************************************************************************************
      This function show the user profile
**********************************************************************************************/
function opinc_userprofile() {
   $current_user = wp_get_current_user();
   if($current_user) {
      $userid = $current_user->ID;
      $user_info = array();
      $user_info = get_user_meta($userid);
      $user_info['inf_field_UserName'] = array($current_user->user_login);
      // echo "<pre>";
      // print_r($user_info);

      $profileFields = getProfileFields();
      $memberText = getUserRole();
      
      $formToMetaMapping = getUserMetaDataMapping();
      $metaToFormMapping = array_flip($formToMetaMapping);
      $outputHtml.= "<div class='contact panel-contact edit_profile_section'>";
      $outputHtml.= "<div class='edit-button-section'>";
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         $outputHtml.= "<h3>".$memberText."</h3>";
         $outputHtml.= "<button class='edit_button'><a class='edit_button_navigation' style='color:#FFFFFF' href='https//". $_SERVER['HTTP_HOST']. "/openinclusion/edit-profile'>Edit Profile</a></button>";
         $redirect = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/login";
      }
      else {
         $outputHtml.= "<h3>".$memberText."</h3>";
         $outputHtml.= "<button class='edit_button'><a href='https://". $_SERVER['HTTP_HOST']. "/edit-profile'><span style='color:#FFFFFF' class='edit_button_navigation' >Edit Profile</span></a></button>";
      }      
      $outputHtml.= "</div>";
      $outputHtml.= "<div class='user-profile-details'>";
      $outputHtml.= "<ul>";
      if(isset($profileFields['fields']) && is_array($profileFields['fields'])) {
         foreach($profileFields['fields'] as $field) {
            $name = $field['name'];
            if(isset($formToMetaMapping[$name])) {
               $metaKey = $formToMetaMapping[$name];
            }
            else {
               $metaKey = $name;
            }
            
            $liCss = (empty($field['li-class']))?'':' class="'.$field['li-class'].'"';
            switch($field['type']) {
               case 'other-html':
                  // Print the sections of the profile
                  $outputHtml.= '<li'.$liCss.'>';
                  $outputHtml.= $field['value'];
                  $outputHtml.= '</li>';
                  break;

               case 'text':
                  $outputHtml.= "<li class='clear'><span class='text'>".$field['label'] . "</span> ";
                  $outputHtml.= getMetaValue($user_info[$metaKey])."</li>";
                  break;

               case 'select':
                  $options = $field['options'];
                  $metaValue = getMetaValue($user_info[$metaKey]);
                  $displayText = "Display Text";
                  if(is_array($options)) {
                     foreach($options as $option){
                        if($metaValue == $option[0]) {
                           $displayText = $option[1];
                        }
                     }
                  }
                  $outputHtml.= "<li ".$liCss."><span class='text'>".$field['label']."</span>";
                  $outputHtml.= "<div style='text-align:left'>".$displayText."</div></li>";
                  break;
               
               case 'chkboxgroup-inf':
               case 'radiogroup-inf':
                  $options = $field['options'];
                  $metaValues = explode("|", getMetaValue($user_info[$metaKey]));
                  $selectedOptions = array();
                  if(is_array($options)) {
                     foreach($options as $option){
                        if(in_array($option[0], $metaValues)) {
                           $selectedOptions[] = $option[1];
                        }
                     }
                  }
                  $displayText = "<span><ul><li>";
                  $displayText.= implode("</li><li>", $selectedOptions);
                  $displayText.= "</li></ul></span>";               
                  $outputHtml.= "<li ".$liCss."><span class='text'>".$field['label']."</span>";
                  $outputHtml.= $displayText."</li>";
                  break;   
                  case 'submit':
                     if($_SERVER['HTTP_HOST'] == 'localhost') {
                        $redirect = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/edit-profile";
                     }
                     else {
                        $redirect = "https://". $_SERVER['HTTP_HOST']. "/edit-profile";
                     }                       
                     $outputHtml.= "<li class='clear'><div style='text-align:center'><a href='".$redirect."'>".$field['value']."</a></div></li>";
                     break;                 
               default:
               
               break;
            }
         } 
      }
      $outputHtml.= "</ul>";
      $outputHtml.= "</div>";
      $outputHtml.= "</div>";
      return $outputHtml;
   }
}

add_shortcode("opinc-userprofile", "opinc_userprofile");   


function opinc_editprofile() {
   $current_user = wp_get_current_user();
   if($current_user) {
      $userid = $current_user->ID;
      $user_info = array();
      $user_info = get_user_meta($userid);
      $metaDataMapping = getUserMetaDataMapping();
      $formValues = array();
      $formValues['inf_field_UserName'] = $current_user->user_login;
      foreach($metaDataMapping as $fKey => $mKey) {
         if(isset($user_info[$mKey])) {
            $metaValue = $user_info[$mKey][0];
            if (is_string($metaValue) && str_contains($metaValue, '|')) {
               $metaValue = explode("|", $metaValue);
            }  
            $formValues[$fKey] = $metaValue;
         }
      }
      // var_dump($formValues);
      // exit();
      $profileFields = getProfileEditFields();
      // Call the function to print out the form and return
      $user_email = $user_info['Email'][0];
      $strHtml = printFormNew($profileFields, $formValues, array() );
      // Need the javascript after the form
      // $strHtml .= '<script type="text/javascript" src="https://ly190.infusionsoft.com/app/webTracking/getTrackingCode?trackingId=3e8aae4c347ffce85759672e1959435e"></script>';
      return $strHtml;      
   }
   else {
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         $redirect = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/login";
      }
      else {
         $redirect = "https://". $_SERVER['HTTP_HOST']. "/login";
      }       
      wp_redirect( $redirect ); exit;
   }    
}

add_shortcode("opinc-editprofile", "opinc_editprofile");   


/**********************************************************************************************
This function redirects to profile page after edit
**********************************************************************************************/
function redirectAfterEditProfile(){
   ob_clean();
   ob_start();
   if(isset($_POST['editProfileToken']) && $_POST['editProfileToken'] == 'VQxt1|uIg1@5vNe*76V1#~*Y+Q6VVQxt') {
      $current_user = wp_get_current_user();
      // user table data
      $userid = $current_user->ID;
      $userData = array(
         'ID' => $userid,  
         'user_pass' =>  $_POST['inf_field_Password'],
         'user_login' => $_POST['inf_field_UserName'],
         'first_name' => $_POST['inf_field_FirstName'],
         'last_name' => $_POST['inf_field_LastName'],
      );
      $returnVal = wp_update_user($userData);
      $session_user_role = update_user_role(); 
      include_once (__DIR__."/../../../infusion/updateUserStatus.php");      
      $userMetaData = prepareUserMetaData();
      // exit();
      foreach( $userMetaData as $key => $val ) {
         update_user_meta( $userid, $key, $val ); 
      }
      //$contactId = "";
      //include_once (__DIR__."/../../../infusion/processv2.php");
      if(isset($_SERVER['HTTP_HOST'])) {
         if($_SERVER['HTTP_HOST'] == 'localhost') {
            $redirectUrl = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/profile";
         }
         else {
            if($session_user_role == 'Partial Member'){
               $redirectUrl = "https://". $_SERVER['HTTP_HOST']. "/registration-complete";
            }
            else if($session_user_role == 'Member'){
               $redirectUrl = "https://". $_SERVER['HTTP_HOST']. "/member-submission";
            }
         } 
         wp_redirect($redirectUrl);
         exit;      
      } 
   }
}
add_action( 'template_redirect', 'redirectAfterEditProfile');

/**********************************************************************************************
This function redirects after Part 2 Step 1 form submission
**********************************************************************************************/
function redirectAfterPart2Step1(){
   ob_clean();
   ob_start();
   if(isset($_POST['submit_part2_step1'])) {
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         
         // Check if user selected "None of the above" - screen them out
         if(isset($_POST['RelationShip']) && in_array('None-of-the-above', $_POST['RelationShip'])) {
            // Mark user as screened out
            update_user_meta( $userid, 'ScreenedOut', 'Yes');
            update_user_meta( $userid, 'ScreenedOutReason', 'None of the above relationship options selected');
            
            // Redirect to a "thank you but not eligible" page
            if(isset($_SERVER['HTTP_HOST'])) {
               if($_SERVER['HTTP_HOST'] == 'localhost') {
                  $redirectUrl = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/not-eligible/";
               }
               else {
                  $redirectUrl = "https://". $_SERVER['HTTP_HOST']. "/not-eligible/";
               }         
               wp_redirect($redirectUrl);
               exit;      
            }
         }
         
         // Update user meta data with Part 2 Step 1 information
         $userMetaData = prepareUserMetaData();
         foreach( $userMetaData as $key => $val ) {
            update_user_meta( $userid, $key, $val ); 
         }
         
         // Mark that user has completed Part 2 Step 1
         update_user_meta( $userid, 'Part2Step1Completed', 'Yes');
         
         // Update user status in Keap/Infusionsoft
         include_once (__DIR__."/../../../infusion/updateUserStatus.php");
         
         if(isset($_SERVER['HTTP_HOST'])) {
            if($_SERVER['HTTP_HOST'] == 'localhost') {
               $redirectUrl = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/part2-step2/";
            }
            else {
               $redirectUrl = "https://". $_SERVER['HTTP_HOST']. "/part2-step2/";
            }         
            wp_redirect($redirectUrl);
            exit;      
         } 
      }
   }
}
add_action( 'template_redirect', 'redirectAfterPart2Step1');


function getMetaValue($input) {
   if(is_array($input)) {
      return implode(",", $input);
   }
}

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


function getSelectedOptions($name, $userData) {
   $returnArray = array();
   if(is_array($userData)) {
      foreach($userData  as $key => $value) {
         $percentage = "";
         similar_text($name, $key, $percentage);
         if($percentage > 90) {
            $returnArray = $value;
         }    
      }
   }
   return $returnArray;
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
   //$strHtml .= '<p>Required information is marked with a <span class="mand">'.$formDef['mand-ind'].'</span></p>';

   // Start list
   $firstTime = true;
   $strHtml .= '<ul>';
   
   $otherFieldValue = '';
   // Print out each of the fields
   foreach($formDef['fields'] as $field) {
      $errMsg = getErrorMsg($arrErrs, $field['name']);
      $errInd = '';
      $ariaInvalidFrag = '';
      $fieldName = $field['name'];
      
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
      $liId = (empty($field['li-id']))?'':' id="'.$field['li-id'].'"';
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
         if(isset($field['label-suffix'])) {
            $labelTxt.= '<span class="text" style="font-size:15px">'.$field['label-suffix'].'</span>';
         }
      } else {
         // Checkbox group labels will be handled further down
      }
      switch ($field['type']) {
         case 'text':
            $strHtml .= '<li'.$liCss.' '.$liId.'>';
            $strHtml .= $labelTxt;
            // Check if clean array populated - to see if prev value stored
            if (count($clean) > 0) {
               $val = outScrn($clean[$field['name']]);
               $otherFieldValue = $val;
            } else {
               $val = '';
            }
            $strHtml .= '<input maxlength="'.$field['maxlen'].'" type="text" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$val.'"';
            $strHtml .= $reqAttr.$ariaInvalidFrag.$validStr.'><span class="errors" tabindex="0">'.$errMsg.'</span>';
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
            $strHtml .= '<span class="errors" tabindex="0">'.$errMsg.'</span>';
            $strHtml .= '</label></li>';
         break;
         
         case 'select':
            $strHtml .= '<li'.$liCss.' '.$liId.'>';
            $strHtml .= $labelTxt;
            $strHtml .= '<div class="custom"><select name="'.$field['name'].'" id="'.$field['name'].'"'.$reqAttr.$ariaInvalidFrag.$validStr.'>';
            // $strHtml .= '<option value="">Please choose...</option>';
            
            foreach ((array)$field['options'] as $option) {
               $selected = (isset($clean[$field['name']]) && $clean[$field['name']] == $option[0])?' selected="selected"':'';
               if(isset($option[3])) {
                  $selected.= " class=\"".$option[3]."\"";
               }
               $strHtml .= '<option value="'.$option[0].'"'.$selected.'>'.$option[1].'</option>';
            
            }
            $strHtml .= '</select></div><span class="errors" tabindex="0">'.$errMsg.'</span>';
            $strHtml .= '</label></li>';
         break;
         
         case 'chkbox':  // Single checkbox
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= '<input type="checkbox" name="'.$field['name'].'" id="'.$field['name'].'" value="'.outScrn($clean[$field['name']]).'"';
            $strHtml .= $labelTxt;
            $strHtml .= $reqAttr.$ariaInvalidFrag.$validStr.'><br><span class="errors" tabindex="0">'.$errMsg.'</span>';
            $strHtml .= '</label></li>';
         break;

         case 'chkboxgroup':
            $strHtml .= '<li'.$liCss.' '.$liId.'>';
            $strHtml .= '<fieldset data-type="chkbox"'.$validStr.'>';
            $strHtml .= '<legend id="'.$field['name'].'-legend" class="expandableLegend">'.$field['label'].' '.$reqStr.'</legend>';
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

               $strHtml .= '<li class="check-radio" >';
               //$strHtml .= '<input type="checkbox" name="'.$field['name'].'[]" id="'.$field['name'].'-'.$option[0].'" value="'.$option[0].'" aria-labelledby="'.$field['name'].'-legend '.$field['name'].'-'.$option[0].'-label '.$field['name'].'-errors"'.$checked.'>';
               $strHtml .= '<input type="checkbox" name="'.$field['name'].'[]" id="'.$field['name'].'-'.$option[0].'" value="'.$option[0].'" '.$checked.'>';
               $strHtml .= '<label for="'.$field['name'].'-'.$option[0].'" id="'.$field['name'].'-'.$option[0].'-label">';                
               $strHtml .= $option[1].'</label>';                
               $strHtml .= '</li>';
            }
            $strHtml .= '</ul>';
            $strHtml .= '<div class="fieldseterrors" id="'.$field['name'].'-errors">'.$errMsg.'</div>';
            $strHtml .= '</fieldset></li>';
         break;
         
         case 'chkboxgroup-inf':
            $strHtml .= '<li'.$liCss.' '.$liId.'>';
            $strHtml .= '<fieldset data-type="chkbox"'.$validStr.'>';
            $strHtml .= '<legend id="'.$field['name'].'-legend" class="expandableLegend">'.$field['label'].' '.$reqStr.'</legend>';
            $strHtml .= '<ul class="checkbox-radio">';
            $selectedValues = getSelectedOptions($fieldName, $clean);
            $containsOther = false;
            foreach ((array)$field['options'] as $option) {
               $clickevent = '';
               $checked = '';
               
               // echo "<br>";
               foreach ($option as $value) {
                  if (strpos($value, 'Other') !== false) {
                      $containsOther = true;
                      break;
                  }
               }
               if (in_array("any_paid_research", $option)) {
                  if (empty($clickevent)) {
                     $clickevent = 'OnClick="selectResearchRelatedOptions();"';
                 } else {
                     $clickevent = rtrim($clickevent, '"') . '; selectResearchRelatedOptions();"';
                 }
               }
               if(is_array($selectedValues) && in_array($option[0], $selectedValues)) {
                  $checked = ' checked="true" ';  
               }
               if($fieldName == "_PreferToContact"){
                  if(gettype($selectedValues)=='string' && strlen($selectedValues)>0){
                     $checked = ' checked="true" ';
                  }
               }
               // if($option[0] == 'OtherPleaseSpecify' && strlen($otherFieldValue)>0) {
               if (strpos($clickevent, 'hideshowOpenText') === false) {
                  if (empty($clickevent)) {
                     if($containsOther){
                        $clickevent = ' OnClick="hideshowOpenText(this,`otherFieldValue`)"';
                     }
                  } else {
                     if($containsOther){
                        $clickevent = rtrim($clickevent, '"') . ' hideshowOpenText(this,`otherFieldValue`);"';
                     }
                  }
               }
               // }
               // else{
               //    $clickevent.= ' OnClick="hideshowOpenText(this)"';
               // }                 
               $strHtml .= '<li class="check-radio" >';
               // $strHtml .= '<input type="checkbox" name="'.$option[2].'" id="'.$option[3].'" value="'.$option[0].'" 'aria-labelledby="'.$field['name'].'-legend '.$field['name'].'-'.$option[0].'-label '.$field['name'].'-errors"''.$checked . $clickevent .' >';
               $strHtml .= '<input type="checkbox" name="'.$option[2].'" id="'.$option[3].'" value="'.$option[0].'" '.$checked . $clickevent .' >';
               $strHtml .= '<label for="'.$option[3].'" id="'.$field['name'].'-'.$option[0].'-label">';                
               $strHtml .= $option[1].'</label>';
               if($option[0] == 'OtherPleaseSpecify' && strlen($otherFieldValue)>0) {
                  $strHtml .= '<script>';
                  $strHtml .= 'setTimeout(function() {';
                  $strHtml .= '  document.getElementById("'.$option[3].'").click();';
                  $strHtml .= '}, 100);';
                  $strHtml .= '</script>';
                  $strHtml .= '<label for="'.$option[2].'_OpenText" style="display:none"><span>"'.$option[1].'"</span></label><input type="text" name="'.$option[2].'_OpenText" id="'.$option[2].'_OpenText" value="'.$otherFieldValue.'" style="width:100%;display:none">'; 
               }
               else{
                  $strHtml .= '<label for="'.$option[2].'_OpenText" style="display:none"><span>"'.$option[1].'"</span></label><input type="text" name="'.$option[2].'_OpenText" id="'.$option[2].'_OpenText" value="" style="width:100%;display:none">';
               }                
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
            if(isset($clean[$fieldName])) {
               $selectedValue = $clean[$fieldName];
            }
            else {
               $selectedValue = '';
            }            
            foreach ((array)$field['options'] as $option) {
               // See if any of them have been checked
               $checked = '';
               if($option[0] == $selectedValue) {
                  $checked = 'checked="true"';
               }
               // if($option[0] == 'OtherPleaseSpecify' || $option[0] == 'APartOfCommunity' || $option[0] == 'ACommunityOrganisation' || $option[0] == 'OurCommunityOther'){
               //    $checked = 'checked="true"';
               // }
               $clickevent = '';
               if($option[0] == '776' || $option[0] == 'APartOfCommunity' || $option[0] == 'ACommunityOrganisation' || $option[0] == 'OurCommunityOther') {
                  // $selectedValues = getSelectedOptions($fieldName, $clean);
                  $clickevent.= ' OnClick="hideshowOpenText(this)"';  
               }
               else{
                  $clickevent.= ' OnClick="hideOpenText(this)"';
               }
               $strHtml .= '<li class="check-radio" >';
               // $strHtml .= '<input type="radio" name="'.$field['name'].'" id="'.$option[2].'" value="'.$option[0].'" aria-labelledby="'.$field['name'].'-legend '.$field['name'].'-'.$option[0].'-label '.$field['name'].'-errors"'.$checked.'>';
               // $strHtml .= '<input type="radio" name="'.$field['name'].'" id="'.$option[2].'" value="'.$option[0].'" '.$checked.'>';
               $strHtml .= '<input type="radio" name="'.$field['name'].'" id="'.$option[2].'" value="'.$option[0].'" '.$checked . $clickevent .'>';
               $strHtml .= '<label for="'.$option[2].'">';                
               $strHtml .= $option[1].'</label>';
               if($option[0] == '776' || $option[0] == 'OurCommunityOther'){
                  $strHtml .= '<label for="'.$option[2].'_OpenText" style="display:none"><span>"'.$option[1].'"</span></label><input type="text" name="'.$option[2].'_OpenText" id="'.$option[2].'_OpenText" value="" placeHolder="Please Enter Your Answer" style="width:100%;display:none">'; 
               }
               if($option[0] == 'APartOfCommunity' || $option[0] == 'ACommunityOrganisation') {
                  $strHtml .= '<label for="'.$option[2].'_OpenText" style="display:none"><span>"'.$option[1].'"</span></label><input type="text" name="'.$option[2].'_OpenText" id="'.$option[2].'_OpenText" value="" placeHolder="Please add the name of the person who referred you from the Open community" style="width:100%;display:none">'; 
               } 
   			 if(isset($option[4])) {
                  $strHtml .= '<label for="'.$option[3].'" style="display:none"><span>"'.$option[4].'"</span></label><input type="text" name="'.$option[3].'" id="'.$option[3].'" value="" style="width:100%">'; 
               }  
				
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
            $strHtml .= $reqAttr.$ariaInvalidFrag.$validStr.' /><span class="errors" tabindex="0">'.$errMsg.'</span>';
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
            if($field['value'] == 'Click Here'){
                  $clickevent.= ' OnClick="myFunction()"';
                  $strHtml .= '<li'.$liCss.''.$clickevent.'. tabindex="0"><span class="click-here" aria-label="Take me to section 4" >'.$field['value'].'</span></li>';
            }
            else{
               $strHtml .= '<li'.$liCss.' tabindex="0">'.$field['value'].'</li>';
            }
            break;

         case 'submit':
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= '<input type="submit" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$field['value'].'" />';
            $strHtml .= '</li>';
            break;

         case 'hidden':
            $strHidden .= '<input type="hidden" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$field['value'].'" />';
            break;

         case 'password':
            $strHtml .= '<li'.$liCss.'>';
            $strHtml .= $labelTxt;
            // Check if clean array populated - to see if prev value stored
            if (count($clean) > 0) {
               $val = outScrn($clean[$field['name']]);
            } else {
               $val = '';
            }
            $strHtml .= '<input maxlength="'.$field['maxlen'].'" type="password" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$val.'"';
            $strHtml .= $reqAttr.$ariaInvalidFrag.$validStr.'><input type="button" class="showpassword" onclick="hideshowPassword(\''.$field['name'].'\')" value="Show password"/><span class="errors" tabindex="0">'.$errMsg.'</span>';
            // $strHtml .= $reqAttr.$ariaInvalidFrag.$validStr.'><span class="errors">'.$errMsg.'</span>';
            $strHtml .= '</label></li>';
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
   $output = print_r($_POST, 1);
   error_log("Inserting to table :: ".$output);
   include("../infusion/process.php");
   die();
   wp_die(); 
}

function update_user_role(){
   $current_user = wp_get_current_user();
   $userid = $current_user->ID;
   $user_info = array();
   $user_info = get_user_meta($userid);
   $last_name=trim($user_info['last_name'][0]);// removing space btw starting and ending
   $user_name = $user_info['first_name'][0]."%20".$last_name;
   $filterURL = 'https://openinclusion.vanillastaging.com/api/v2/users/by-names?name='.$user_name;
   $filtercurl = curl_init($filterURL);
   // 1. Set the CURLOPT_RETURNTRANSFER option to true
   curl_setopt($filtercurl, CURLOPT_RETURNTRANSFER, true);

   // 2. Set the CURLOPT_POST option to true for PATCH request
   curl_setopt($filtercurl, CURLOPT_CUSTOMREQUEST, 'GET');

   // 3. Set custom headers for RapidAPI Auth and Content-Type header
   curl_setopt($filtercurl, CURLOPT_HTTPHEADER, [
   'X-RapidAPI-Host: kvstore.p.rapidapi.com',
   'X-RapidAPI-Key: [Input your RapidAPI Key Here]',
   'Content-Type: application/json',
   "Authorization: Bearer va.n5YzoOdlgbJCgQmfcmXTUYf4qME-Zzyi.y4SJdQ.VZhzFry"
   ]);
   $response = curl_exec($filtercurl);
   $array_response = json_decode($response);
   $vanillaUserId = $array_response[0]->userID;
   curl_close($filtercurl);
   $url = 'https://openinclusion.vanillastaging.com/api/v2/users/'.$vanillaUserId;
   $curl = curl_init($url);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
   curl_setopt($curl, CURLOPT_HTTPHEADER, [
   'X-RapidAPI-Host: kvstore.p.rapidapi.com',
   'X-RapidAPI-Key: [Input your RapidAPI Key Here]',
   'Content-Type: application/json',
   "Authorization: Bearer va.n5YzoOdlgbJCgQmfcmXTUYf4qME-Zzyi.y4SJdQ.VZhzFry"
   ]);
   $response = curl_exec($curl);
   $role_response = json_decode($response);
   $user_role = $role_response->roles[0]->name;
   curl_close($curl);
   if($user_role == 'Partial Member'){
   // if($formValues["SensoryNeeds"]||$formValues["PhysicalNeeds"]||$formValues["CognitiveAndMentalhealthNeeds"]||$formValues["CommunicationNeeds"]||$formValues["ChronichealthNeeds"]||$formValues["DigitalandScreenTechnologies"]||$formValues["MovementCanesandServiceAnimals"]||$formValues["CommunicationPreferences"]||$formValues["PersonalSupportandHome"]){
      $url = 'https://openinclusion.vanillastaging.com/api/v2/users/'.$vanillaUserId;
      $data = [
         "roleID"=> [
            8
         ]
      ];
      $curl = curl_init($url);
      // 1. Set the CURLOPT_RETURNTRANSFER option to true
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      // 2. Set the CURLOPT_POST option to true for PATCH request
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');

      // 3. Set the request data as JSON using json_encode function
      curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));

      // 4. Set custom headers for RapidAPI Auth and Content-Type header
      curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'X-RapidAPI-Host: kvstore.p.rapidapi.com',
      'X-RapidAPI-Key: [Input your RapidAPI Key Here]',
      'Content-Type: application/json',
      "Authorization: Bearer va.n5YzoOdlgbJCgQmfcmXTUYf4qME-Zzyi.y4SJdQ.VZhzFry"
      ]);
      $response = curl_exec($curl);
      curl_close($curl);
      $contactData=[
         "User Status"                       => 'Member'
      ];
   }
   return $user_role;
 }
?>