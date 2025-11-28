<?php
// Add after the opening <?php tag
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Add these session helper functions
function storeFormStepData($step, $data) {
    // Store user ID with session data to ensure it's for the current user
    if (!isset($_SESSION['form_steps'])) {
        $_SESSION['form_steps'] = array();
    }
    if (!isset($_SESSION['form_user_id'])) {
        $_SESSION['form_user_id'] = get_current_user_id();
    }
    $_SESSION['form_steps'][$step] = $data;
}

function getFormStepData($step) {
    // Check if session data belongs to current user
    $currentUserId = get_current_user_id();
    if (isset($_SESSION['form_user_id']) && $_SESSION['form_user_id'] != $currentUserId) {
        // Different user - clear old session data
        clearFormStepData();
        return array();
    }
    return isset($_SESSION['form_steps'][$step]) ? $_SESSION['form_steps'][$step] : array();
}

function clearFormStepData() {
    if (isset($_SESSION['form_steps'])) {
        unset($_SESSION['form_steps']);
    }
    if (isset($_SESSION['form_user_id'])) {
        unset($_SESSION['form_user_id']);
    }
}

// Clear session data on logout
add_action('wp_logout', 'clearFormStepData');

// Clear session data when a new user logs in (if different from session user)
add_action('wp_login', function($user_login, $user) {
    if (isset($_SESSION['form_user_id']) && $_SESSION['form_user_id'] != $user->ID) {
        clearFormStepData();
    }
}, 10, 2);

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
   $phoneCodes = get_phoneCodes();
   $phoneCodesJson = json_encode($phoneCodes);
   $selectedCode = isset($clean['inf_field_countryphonecode']) ? htmlspecialchars($clean['inf_field_countryphonecode'], ENT_QUOTES, 'UTF-8') : '';
   $selectedPhone = isset($clean['inf_field_Phone2']) ? htmlspecialchars($clean['inf_field_Phone2'], ENT_QUOTES, 'UTF-8') : '';
   $strHtml .= <<<HTML
   <script>
   jQuery(document).ready(function($) {
      // Populate country phone code dropdown
      var phoneCodes = {$phoneCodesJson};
      var select = $('#inf_field_countryphonecode');
      
      if (select.length && phoneCodes && phoneCodes.length > 0) {
         $.each(phoneCodes, function(index, option) {
            if (Array.isArray(option) && option.length >= 2) {
               var value = option[0];
               var label = option[1];
               var selected = (value === '{$selectedCode}') ? ' selected="selected"' : '';
               select.append('<option value="' + value + '"' + selected + '>' + label + '</option>');
            }
         });
      }
      
      // Apply selected value if exists in form data
      var storedCode = '{$selectedCode}';
      if (storedCode) {
         select.val(storedCode);
      }
      
      // Pre-fill phone number if exists
      var storedPhone = '{$selectedPhone}';
      if (storedPhone) {
         $('#inf_field_Phone2').val(storedPhone);
      }
   });
   </script>
HTML;
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

   // Clear session data if user hasn't started Part 2 Step 1 yet (fresh start)
   $current_user = wp_get_current_user();
   if($current_user) {
      $userid = $current_user->ID;
      $hasStartedStep1 = get_user_meta($userid, 'Part2Step1Completed', true);
      // If they haven't completed step 1 yet and there's no POST data, clear old session
      if(empty($hasStartedStep1) && !isset($_POST['submit_part2_step1']) && !isset($_POST['save_continue_later']) && !isset($_POST['previous_step1'])) {
         clearFormStepData();
      }
   }
   
   // Pull in stored values
   $arrErrs = getFormErrors();
   $clean = getClean();

   $sessionData = getFormStepData('step1');
    if (!empty($sessionData)) {
        $clean = $sessionData;
    } 
   // If no form data, populate with existing user data
   else if (empty($clean) || !isset($clean['submitted'])) {
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
         // Load relationship "Other" text field and ensure checkbox is checked
         if(isset($user_info['Relationship Other Text'][0]) && !empty($user_info['Relationship Other Text'][0])) {
            $clean['RelationShipOtherPleaseSpecify_OpenText'] = $user_info['Relationship Other Text'][0];
            if(!isset($clean['RelationShip'])) $clean['RelationShip'] = array();
            if(!in_array('OtherPleaseSpecify', $clean['RelationShip'])) {
               $clean['RelationShip'][] = 'OtherPleaseSpecify';
            }
         }
      }
   }
   
   global $part2Step1Form;
   
   // Call the function to print out the form and return
   $strHtml = printFormNew($part2Step1Form, $clean, $arrErrs );
   
   // Get saved region value for JavaScript
   $savedRegion = isset($clean['inf_field_region']) ? htmlspecialchars($clean['inf_field_region'], ENT_QUOTES, 'UTF-8') : '';
   
   $strHtml.= "<script>
   jQuery(document).ready(function($) { 
      jQuery('#content').find('header').remove();
      
      // Auto-show text fields that have values when page loads
      $('fieldset[data-type=\"chkbox\"] input[type=\"text\"]').each(function() {
         var textField = $(this);
         var textValue = textField.val();
         
         // If the text field has a value, show it
         if (textValue && textValue.length > 0) {
            textField.show();
         }
      });
      
      // Restore previously selected region after dropdown is populated
      var savedRegion = '{$savedRegion}';
      if (savedRegion) {
         setTimeout(function() {
            $('#inf_field_region').val(savedRegion);
         }, 200);
      }
   });
   </script>";
   
   return $strHtml;
}

add_shortcode("opinc-part2-step1", "opinc_part2_step1_form_sc");   

// Shortcode: Part 2 Step 2 (About My Access Needs)
function opinc_part2_step2_form_sc($atts, $content = null) {
   extract(shortcode_atts(array(
   ), $atts));

   if (!is_user_logged_in()) {
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         $redirect = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/login';
      } else {
         $redirect = "https://" . $_SERVER['HTTP_HOST']. "/login";
      }
      wp_redirect($redirect); exit;
   }

   // Don't clear session data - let it persist across navigation
   
   $arrErrs = getFormErrors();
   $clean = getClean();

   $sessionData = getFormStepData('step2');
    if (!empty($sessionData)) {
        $clean = $sessionData;
    } else if (empty($clean) || !isset($clean['submitted'])) {
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         $user_info = get_user_meta($userid);
         $clean = array();
         foreach (array('Sensory Needs' => 'SensoryNeeds','Physical Needs'=>'PhysicalNeeds','Cognitive And Mental health Needs'=>'CognitiveAndMentalhealthNeeds','Communication Needs'=>'CommunicationNeeds','Chronic health Needs'=>'ChronichealthNeeds','Other Needs'=>'OtherNeeds') as $metaKey => $fieldKey) {
            if(isset($user_info[$metaKey][0])) {
               $val = $user_info[$metaKey][0];
               $clean[$fieldKey] = strpos($val,'|') !== false ? explode('|', $val) : array($val);
            }
         }
         // Load "Other" text fields and ensure corresponding checkboxes are checked
         if(isset($user_info['Sensory Needs Open Text'][0]) && !empty($user_info['Sensory Needs Open Text'][0])) {
            $clean['SensoryNeedsOtherPleaseSpecify_OpenText'] = $user_info['Sensory Needs Open Text'][0];
            if(!isset($clean['SensoryNeeds'])) $clean['SensoryNeeds'] = array();
            if(!in_array('OtherPleaseSpecify', $clean['SensoryNeeds'])) {
               $clean['SensoryNeeds'][] = 'OtherPleaseSpecify';
            }
         }
         if(isset($user_info['Physical Needs Open Text'][0]) && !empty($user_info['Physical Needs Open Text'][0])) {
            $clean['PhysicalNeedsOtherPleaseSpecify_OpenText'] = $user_info['Physical Needs Open Text'][0];
            if(!isset($clean['PhysicalNeeds'])) $clean['PhysicalNeeds'] = array();
            if(!in_array('OtherPleaseSpecify', $clean['PhysicalNeeds'])) {
               $clean['PhysicalNeeds'][] = 'OtherPleaseSpecify';
            }
         }
         if(isset($user_info['Mental Health Open Text'][0]) && !empty($user_info['Mental Health Open Text'][0])) {
            $clean['CognitiveAndMentalhealthNeedsOtherMentalHealth_OpenText'] = $user_info['Mental Health Open Text'][0];
            if(!isset($clean['CognitiveAndMentalhealthNeeds'])) $clean['CognitiveAndMentalhealthNeeds'] = array();
            if(!in_array('OtherMentalHealth', $clean['CognitiveAndMentalhealthNeeds'])) {
               $clean['CognitiveAndMentalhealthNeeds'][] = 'OtherMentalHealth';
            }
         }
         if(isset($user_info['Communication Needs Open Text'][0]) && !empty($user_info['Communication Needs Open Text'][0])) {
            $clean['CommunicationNeedsOtherPleaseSpecify_OpenText'] = $user_info['Communication Needs Open Text'][0];
            if(!isset($clean['CommunicationNeeds'])) $clean['CommunicationNeeds'] = array();
            if(!in_array('OtherPleaseSpecify', $clean['CommunicationNeeds'])) {
               $clean['CommunicationNeeds'][] = 'OtherPleaseSpecify';
            }
         }
         if(isset($user_info['Chronic Health Open Text'][0]) && !empty($user_info['Chronic Health Open Text'][0])) {
            $clean['ChronichealthNeedsOtherLongTermCondition_OpenText'] = $user_info['Chronic Health Open Text'][0];
            if(!isset($clean['ChronichealthNeeds'])) $clean['ChronichealthNeeds'] = array();
            if(!in_array('OtherLongTermCondition', $clean['ChronichealthNeeds'])) {
               $clean['ChronichealthNeeds'][] = 'OtherLongTermCondition';
            }
         }
         if(isset($user_info['Other Needs Open Text'][0]) && !empty($user_info['Other Needs Open Text'][0])) {
            $clean['OtherNeedsOtherPleaseSpecify_OpenText'] = $user_info['Other Needs Open Text'][0];
            if(!isset($clean['OtherNeeds'])) $clean['OtherNeeds'] = array();
            if(!in_array('OtherPleaseSpecify', $clean['OtherNeeds'])) {
               $clean['OtherNeeds'][] = 'OtherPleaseSpecify';
            }
         }
      }
   }

   global $part2Step2Form;
   $strHtml = printFormNew($part2Step2Form, $clean, $arrErrs );
   $strHtml.= "<script>
   jQuery(document).ready(function($) { 
      jQuery('#content').find('header').remove();
      
      // Auto-show text fields that have values when page loads
      $('fieldset[data-type=\"chkbox\"] input[type=\"text\"]').each(function() {
         var textField = $(this);
         var textValue = textField.val();
         
         // If the text field has a value, show it
         if (textValue && textValue.length > 0) {
            textField.show();
         }
      });
   });
   </script>";
   return $strHtml;
}
add_shortcode("opinc-part2-step2", "opinc_part2_step2_form_sc");

// Shortcode: Part 2 Step 3 (Assistive Technologies I Use)
function opinc_part2_step3_form_sc($atts, $content = null) {
   extract(shortcode_atts(array(
   ), $atts));

   if (!is_user_logged_in()) {
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         $redirect = "http://" . $_SERVER['HTTP_HOST'].'openinclusion/login';
      } else {
         $redirect = "https://" . $_SERVER['HTTP_HOST']. "/login";
      }
      wp_redirect($redirect); exit;
   }

   // Don't clear session data - let it persist across navigation
   // Only clear when explicitly starting over or validation passes
   
   $arrErrs = getFormErrors();
   $clean = getClean();
    $sessionData = getFormStepData('step3');
    if (!empty($sessionData)) {
        $clean = $sessionData;
    } else if (empty($clean) || !isset($clean['submitted'])) {
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         $user_info = get_user_meta($userid);
         $clean = array();
         foreach (array(
            'Digital and Screen Technologies' => 'DigitalandScreenTechnologies',
            'PrintMedia' => 'PrintMedia',
            'Movement Canes and Service Animals' => 'MovementCanesandServiceAnimals',
            'Communication Preferences' => 'CommunicationPreferences',
            'PersonalSupportandHome' => 'PersonalSupportandHome',
            'Other Technologies' => 'OtherTechnologies',
            'ResearchFormats' => 'ResearchFormats'
         ) as $metaKey => $fieldKey) {
            if(isset($user_info[$metaKey][0])) {
               $val = $user_info[$metaKey][0];
               $clean[$fieldKey] = strpos($val,'|') !== false ? explode('|', $val) : array($val);
            }
         }
         if(isset($user_info['Digital and Screen Technologies Specific Software'][0])) {
            $clean['DigitalandScreenTechnologiesSpecificSoftware'] = $user_info['Digital and Screen Technologies Specific Software'][0];
         }
         // Load "Other" text fields for Step 3 and ensure corresponding checkboxes are checked
         if(isset($user_info['Digital Technologies Open Text'][0]) && !empty($user_info['Digital Technologies Open Text'][0])) {
            $clean['DigitalandScreenTechnologiesOtherPleaseSpecify_OpenText'] = $user_info['Digital Technologies Open Text'][0];
            if(!isset($clean['DigitalandScreenTechnologies'])) $clean['DigitalandScreenTechnologies'] = array();
            if(!in_array('OtherPleaseSpecify', $clean['DigitalandScreenTechnologies'])) {
               $clean['DigitalandScreenTechnologies'][] = 'OtherPleaseSpecify';
            }
         }
         if(isset($user_info['Print Media Open Text'][0]) && !empty($user_info['Print Media Open Text'][0])) {
            $clean['PrintMediaOtherPleaseSpecify_OpenText'] = $user_info['Print Media Open Text'][0];
            if(!isset($clean['PrintMedia'])) $clean['PrintMedia'] = array();
            if(!in_array('OtherPleaseSpecify', $clean['PrintMedia'])) {
               $clean['PrintMedia'][] = 'OtherPleaseSpecify';
            }
         }
         if(isset($user_info['Movement Aids Open Text'][0]) && !empty($user_info['Movement Aids Open Text'][0])) {
            $clean['MovementCanesandServiceAnimalsOtherNavigationalMobilityAid_OpenText'] = $user_info['Movement Aids Open Text'][0];
            if(!isset($clean['MovementCanesandServiceAnimals'])) $clean['MovementCanesandServiceAnimals'] = array();
            if(!in_array('OtherNavigationalMobilityAid', $clean['MovementCanesandServiceAnimals'])) {
               $clean['MovementCanesandServiceAnimals'][] = 'OtherNavigationalMobilityAid';
            }
         }
         if(isset($user_info['Communication Preferences Open Text'][0]) && !empty($user_info['Communication Preferences Open Text'][0])) {
            $clean['CommunicationPreferencesOtherPleaseSpecify_OpenText'] = $user_info['Communication Preferences Open Text'][0];
            if(!isset($clean['CommunicationPreferences'])) $clean['CommunicationPreferences'] = array();
            if(!in_array('OtherPleaseSpecify', $clean['CommunicationPreferences'])) {
               $clean['CommunicationPreferences'][] = 'OtherPleaseSpecify';
            }
         }
         if(isset($user_info['Other Technologies Open Text'][0]) && !empty($user_info['Other Technologies Open Text'][0])) {
            $clean['OtherTechnologiesOtherPleaseSpecify_OpenText'] = $user_info['Other Technologies Open Text'][0];
            if(!isset($clean['OtherTechnologies'])) $clean['OtherTechnologies'] = array();
            if(!in_array('OtherPleaseSpecify', $clean['OtherTechnologies'])) {
               $clean['OtherTechnologies'][] = 'OtherPleaseSpecify';
            }
         }
         // Load referred fields
         if(isset($user_info['Referred'][0])) {
            $clean['inf_field_referred'] = $user_info['Referred'][0];
         }
         if(isset($user_info['Referred By'][0])) {
            $clean['inf_field_referred_name'] = $user_info['Referred By'][0];
         }
      }
   }

   global $part2Step3Form;
   $strHtml = printFormNew($part2Step3Form, $clean, $arrErrs );
   $strHtml.= "<script>
   jQuery(document).ready(function($) { 
      jQuery('#content').find('header').remove();
      
      // Auto-show text fields that have values when page loads
      $('fieldset[data-type=\"chkbox\"] input[type=\"text\"]').each(function() {
         var textField = $(this);
         var textValue = textField.val();
         
         // If the text field has a value, show it
         if (textValue && textValue.length > 0) {
            textField.show();
         }
      });
      
      // Initialize referral name field visibility based on current selection
      var referredValue = $('input[name=\"inf_field_referred\"]:checked').val();
      if (referredValue === 'Yes') {
         $('#inf_field_referred_name_wrapper').show();
         $('#inf_field_referred_name').prop('disabled', false);
      } else {
         $('#inf_field_referred_name_wrapper').hide();
         $('#inf_field_referred_name').prop('disabled', true);
      }
   });
   </script>";
   return $strHtml;
}
add_shortcode("opinc-part2-step3", "opinc_part2_step3_form_sc");

// Shortcode: Part 2 Step 4 (Other Personal Characteristics)
function opinc_part2_step4_form_sc($atts, $content = null) {
   extract(shortcode_atts(array(
   ), $atts));

   if (!is_user_logged_in()) {
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         $redirect = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/login';
      } else {
         $redirect = "https://" . $_SERVER['HTTP_HOST']. "/login";
      }
      wp_redirect($redirect); exit;
   }

   // Don't clear session data - let it persist across navigation
   
   $arrErrs = getFormErrors();
   $clean = getClean();

   $sessionData = getFormStepData('step4');
   if (!empty($sessionData)) {
        $clean = $sessionData;
    } else if (empty($clean) || !isset($clean['submitted'])) {
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         $user_info = get_user_meta($userid);
         $clean = array();
         if(isset($user_info['Gender'][0])) $clean['inf_option_Gender'] = $user_info['Gender'][0];
         if(isset($user_info['inf_field_gender_at_birth_diff'][0])) $clean['inf_field_gender_at_birth_diff'] = $user_info['inf_field_gender_at_birth_diff'][0];
         if(isset($user_info['SexualOrientations'][0])) {
            $val = $user_info['SexualOrientations'][0];
            $clean['SexualOrientations'] = strpos($val,'|') !== false ? explode(', ', $val) : array($val);
         }
         if(isset($user_info['inf_option_pronouns'][0])) $clean['inf_option_pronouns'] = $user_info['inf_option_pronouns'][0];
                  if(isset($user_info['pronouns_other_text'][0])) $clean['inf_option_pronouns_other_please_specify_OpenText'] = $user_info['pronouns_other_text'][0];
                           // Load gender open text field if exists
         if(isset($user_info['Gender OpenText'][0])) {
            $clean['inf_option_Gender_776_OpenText'] = $user_info['Gender OpenText'][0];
         } elseif(isset($user_info['inf_option_Gender_opentext'][0])) {
            $clean['inf_option_Gender_776_OpenText'] = $user_info['inf_option_Gender_opentext'][0];
         }
         // Load sexual orientation open text field if exists
         if(isset($user_info['Sexual Orientations Open Text'][0])) {
            $clean['SexualOrientationsOtherPleaseSpecify_OpenText'] = $user_info['Sexual Orientations Open Text'][0];
         }
                  if(isset($user_info['identify_terms'][0])) $clean['inf_field_identify_terms'] = $user_info['identify_terms'][0];
         if(isset($user_info['identify_terms_text'][0])) $clean['inf_field_identify_terms_text'] = $user_info['identify_terms_text'][0];
         // if(isset($user_info['inf_field_identify_terms'][0])) $clean['inf_field_identify_terms'] = $user_info['inf_field_identify_terms'][0];
      }
   }

   global $part2Step4Form;
   $strHtml = printFormNew($part2Step4Form, $clean, $arrErrs );
   $strHtml.= "<script>jQuery(document).ready(function($) { jQuery('#content').find('header').remove(); });</script>";
   return $strHtml;
}
add_shortcode("opinc-part2-step4", "opinc_part2_step4_form_sc");

// Shortcode: Part 2 Step 5 (Joining the Community)
function opinc_part2_step5_form_sc($atts, $content = null) {
   extract(shortcode_atts(array(
   ), $atts));

   if (!is_user_logged_in()) {
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         $redirect = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/login";
      } else {
         $redirect = "https://" . $_SERVER['HTTP_HOST']. "/login";
      }
      wp_redirect($redirect); exit;
   }

   // Don't clear session data - let it persist across navigation
   
   $arrErrs = getFormErrors();
   $clean = getClean();

   $sessionData = getFormStepData('step5');
   if (!empty($sessionData)) {
        $clean = $sessionData;
    } else if (empty($clean) || !isset($clean['submitted'])) {
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         $user_info = get_user_meta($userid);
         $clean = array();
         if(isset($user_info['Community Agreement'][0])) {
            $val = $user_info['Community Agreement'][0];
            $clean['CommunityAgreement'] = strpos($val,'|') !== false ? explode('|', $val) : array($val);
         }
      }
   }

   global $part2Step5Form;
   $strHtml = printFormNew($part2Step5Form, $clean, $arrErrs );
   $strHtml.= "<script>jQuery(document).ready(function($) { jQuery('#content').find('header').remove(); });</script>";
   return $strHtml;
}
add_shortcode("opinc-part2-step5", "opinc_part2_step5_form_sc");

// Shortcode: Part 2 Step 6 (Privacy Protection)
function opinc_part2_step6_form_sc($atts, $content = null) {
   extract(shortcode_atts(array(
   ), $atts));

   if (!is_user_logged_in()) {
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         $redirect = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/login";
      } else {
         $redirect = "https://" . $_SERVER['HTTP_HOST']. "/login";
      }
      wp_redirect($redirect); exit;
   }

   // Don't clear session data - let it persist across navigation
   
   $arrErrs = getFormErrors();
   $clean = getClean();

   $sessionData = getFormStepData('step6');
   if(!empty($sessionData)) {
        $clean = $sessionData;
    } else if (empty($clean) || !isset($clean['submitted'])) {
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         $user_info = get_user_meta($userid);
         $clean = array();
         if(isset($user_info['Consent'][0])) {
            $val = $user_info['Consent'][0];
            $clean['PleaseConfirm'] = strpos($val,'|') !== false ? explode('|', $val) : array($val);
         }
      }
   }

   global $part2Step6Form;
   $strHtml = printFormNew($part2Step6Form, $clean, $arrErrs );
   $strHtml.= "<script>
   jQuery(document).ready(function($) { 
      jQuery('#content').find('header').remove();
      
      // Add visual feedback for checkbox validation
      var totalCheckboxes = $('#PleaseConfirm-legend').parent().find('input[type=\"checkbox\"]').length;
      
      $('#PleaseConfirm-legend').parent().find('input[type=\"checkbox\"]').on('change', function() {
         var checkedCount = $('#PleaseConfirm-legend').parent().find('input[type=\"checkbox\"]:checked').length;
         var errorDiv = $('#PleaseConfirm-errors');
         
         if (checkedCount < totalCheckboxes) {
            errorDiv.html('Please confirm all ' + totalCheckboxes + ' statements (' + checkedCount + '/' + totalCheckboxes + ' selected)').show();
            errorDiv.attr('role', 'alert');
         } else {
            errorDiv.html('').hide();
            errorDiv.removeAttr('role');
         }
      });
   });
   </script>";
   return $strHtml;
}
add_shortcode("opinc-part2-step6", "opinc_part2_step6_form_sc");

// Redirects after Step 6 submission
function redirectAfterPart2Step6(){
   ob_clean();
   ob_start();
   if ( isset($_POST['previous_step6']) ) {
       storeFormStepData('step6', $_POST);
       if($_SERVER['HTTP_HOST'] == 'localhost') {
           $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step5/';
       } else {
           $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step5/";
       }
       wp_redirect($redirectUrl);
       exit;
   }
   if(isset($_POST['submit_part2_step6']) || isset($_POST['save_continue_later_step6'])) {
      $current_user = wp_get_current_user();
      storeFormStepData('step6', $_POST);

      if($current_user) {
         $userid = $current_user->ID;
         // Validate all confirmations are selected - all 4 checkboxes must be checked
        // This validation applies to both "Save & Next Step" and "Save & Continue Later"
         $errs = array();
         if(!isset($_POST['PleaseConfirm']) || !is_array($_POST['PleaseConfirm']) || count($_POST['PleaseConfirm']) < 4) {
            $errs[] = array('PleaseConfirm', __('Please confirm all 4 statements to proceed', 'openinclusion'));
         }
         if(count($errs) > 0) { 
            setFormErrors($errs); 
            return; 
         }

         // Save meta
         $userMetaData = prepareUserMetaData();
         foreach( $userMetaData as $key => $val ) { update_user_meta( $userid, $key, $val ); }

                  // Update Keap with Step 6 data
         if (file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
            include_once (__DIR__."/../../../infusion/updateMultiStepData.php");
            $user_info = get_user_meta($userid);
            $user_email = isset($user_info['Email'][0]) ? $user_info['Email'][0] : '';
            
            // If no email in meta, try to get from user object
            if (empty($user_email)) {
                $user_email = $current_user->user_email;
            }
            
            // Only proceed if we have a valid email
            if (!empty($user_email)) {
                $result = updateKeapMultiStepData('step6', $_POST, $user_email);
                error_log("Keap update result for step6: " . ($result ? 'SUCCESS' : 'FAILED'));
            } else {
                error_log("No valid email found for step6");
            }
         }



         // Update status
         update_user_meta( $userid, 'Part2Step6Completed', 'Yes' );

         if(isset($_POST['save_continue_later_step6'])) {
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/mywordpress/thank-you-2/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/thank-you-2/"; }
            wp_redirect($redirectUrl); exit;
         }

         // Next: Step 7
         if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step7/'; }
         else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step7/"; }
         wp_redirect($redirectUrl); exit;
      }
   }
}
add_action( 'template_redirect', 'redirectAfterPart2Step6');

// Shortcode: Part 2 Step 7 (Identity Verification)
function opinc_part2_step7_form_sc($atts, $content = null) {
   extract(shortcode_atts(array(
   ), $atts));

   if (!is_user_logged_in()) {
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         $redirect = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/login";
      } else {
         $redirect = "https://" . $_SERVER['HTTP_HOST']. "/login";
      }
      wp_redirect($redirect); exit;
   }

   // Don't clear session data - let it persist across navigation
   
   $arrErrs = getFormErrors();
   $clean = getClean();

   $sessionData = getFormStepData('step7');
   if(!empty($sessionData)) {
        $clean = $sessionData;
    } else if (empty($clean) || !isset($clean['submitted'])) {
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         $user_info = get_user_meta($userid);
         $clean = array();
         if(isset($user_info['Open Verified Opt In'][0])) {
            $clean['OpenVerifiedOptIn'] = $user_info['Open Verified Opt In'][0];
         }
      }
   }

   global $part2Step7Form;
   $strHtml = printFormNew($part2Step7Form, $clean, $arrErrs );
   $strHtml.= "<script>jQuery(document).ready(function($) { jQuery('#content').find('header').remove(); });</script>";
   return $strHtml;
}
add_shortcode("opinc-part2-step7", "opinc_part2_step7_form_sc");

// Redirects after Step 7 submission
function redirectAfterPart2Step7(){
   ob_clean();
   ob_start();
   if ( isset($_POST['previous_step7']) ) {
       storeFormStepData('step7', $_POST);
       if($_SERVER['HTTP_HOST'] == 'localhost') {
           $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step6/';
       } else {
           $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step6/";
       }
       wp_redirect($redirectUrl);
       exit;
   }
   if(isset($_POST['submit_part2_step7']) || isset($_POST['save_continue_later_step7'])) {
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         // Save meta
         $userMetaData = prepareUserMetaData();
         foreach( $userMetaData as $key => $val ) { update_user_meta( $userid, $key, $val ); }

                  // Update Keap with Step 7 data
         if (file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
            include_once (__DIR__."/../../../infusion/updateMultiStepData.php");
            $user_info = get_user_meta($userid);
            $user_email = isset($user_info['Email'][0]) ? $user_info['Email'][0] : '';
            
            // If no email in meta, try to get from user object
            if (empty($user_email)) {
                $user_email = $current_user->user_email;
            }
            
            // Only proceed if we have a valid email
            if (!empty($user_email)) {
                $result = updateKeapMultiStepData('step7', $_POST, $user_email);
                error_log("Keap update result for step7: " . ($result ? 'SUCCESS' : 'FAILED'));
            } else {
                error_log("No valid email found for step7");
            }
         }

         // Update status
         update_user_meta( $userid, 'Part2Step7Completed', 'Yes' );

         // Send Open Verified email if opted in
         if(isset($_POST['OpenVerifiedOptIn'])) {
            $headers = array(
               'Content-Type: text/html; charset=UTF-8',
               'From: Open Inclusion <contact@openinclusion.com>'
            );
            $subject = "Open Verified: Verify your identity";
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
            Dear ".ucwords(strtolower($current_user->first_name)).",
            
            <p style=\"font-family: Poppins;\">Thank you for being a valued member of our community. We're inviting you to complete this form and become an Open Verified community member. By taking this step and verifying your identity, you'll help us combat fraud and provide high-quality, reliable insights to our clients. As an Open Verified member, you have a higher priority for paid research opportunities with some of our biggest and most trusted brands.</p>
            
            <h3 style=\"font-family: Poppins; color: #2a3258;\">Your information and how we use it</h3>
            
            <p style=\"font-family: Poppins; color: #000;\">We will ask you to confirm your name, email address and to upload your photographic ID. By continuing, you agree to these simple terms:</p>
            
            <p style=\"font-family: Poppins; color: #000;\"><strong>Why we need this:</strong> We're only using your information to confirm your identity (that you are who you say you are) as a Verified Member.</p>
            
            <p style=\"font-family: Poppins; color: #000;\"><strong>Who handles your data:</strong> Open Inclusion is in charge of your data.</p>
            
            <p style=\"font-family: Poppins; color: #000;\"><strong>How we keep your data safe:</strong> We'll collect your information through a secure tool called SurveyMonkey. Only Open Inclusion team members can see the information. This system has strong security in place to protect it.</p>
            
            <p style=\"font-family: Poppins; color: #000;\"><strong>Sharing:</strong> We will not share your personal information with anyone outside of the small, authorized Verification team at Open Inclusion who need it.</p>
            
            <p style=\"font-family: Poppins; color: #000;\"><strong>Keeping it:</strong> We will securely and permanently delete all your personal data, including your photo ID, within 30 days of your verification.</p>
            
            <p style=\"font-family: Poppins; color: #000;\"><strong>Your rights:</strong> You can ask to see, correct, or delete your data at any time. Just email research@openinclusion.com if you wish to do any of these things.</p>
            
            
            
            </div>
            <div>
            <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\" style=\"height: 50px;background: #2a3258;border-radius: 23px;\">
            <tr>
            <td style=\"text-align: left; padding-top:10px;padding-right:15px;padding-bottom:10px;padding-left:15px;\">
             <a href=\"https://www.surveymonkey.com/r/N7PQ9TF\" target=\"_blank\" rel=\"noopener\" style=\"border-radius: 23px;background: #2a3258;text-decoration: none;font-family: Poppins-Medium, Poppins;font-style: normal;font-weight: 500;line-height: 38px;height: 50px;padding: 6px; content: left; font-size: 24px\">
            <span style=\"color:#ffffff;\">Complete Verification Form</span>
            </a>
            </td>
            </tr>
            </table>
            </div>
            <br />
             <div style=\"padding: 30px 0px; font-family: Poppins; font-size: 24px; line-height: 40px; text-align: left; content: left\">
            <p style=\"font-family: Poppins; font-size: 24px\">
            Thank you, <br />The Open Inclusion Team
            </p>
            </div>
            </div>
            </div>
            </div>
            <div style=\"height: 50px;background:#E5E8E8\"></div>
            </div>";

            wp_mail( $current_user->user_email, $subject, $content, $headers);
         }

         if(isset($_POST['save_continue_later_step7'])) {
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/mywordpress/thank-you-2/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/thank-you-2/"; }
            wp_redirect($redirectUrl); exit;
         }

         // Redirect to Step 8 (Create Community Login)
         if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step8/'; }
         else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step8/"; }
         wp_redirect($redirectUrl); exit;
      }
   }
}
add_action( 'template_redirect', 'redirectAfterPart2Step7');

// Shortcode: Part 2 Step 8 (Create Community Login)
function opinc_part2_step8_form_sc($atts, $content = null) {
   extract(shortcode_atts(array(
   ), $atts));

   if (!is_user_logged_in()) {
      if($_SERVER['HTTP_HOST'] == 'localhost') { $redirect = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/login"; }
      else { $redirect = "https://" . $_SERVER['HTTP_HOST']. "/login"; }
      wp_redirect($redirect); exit;
   }

   // Don't clear session data - let it persist across navigation
   
   $arrErrs = getFormErrors();
   $clean = getClean();

   $sessionData = getFormStepData('step8');
   if(!empty($sessionData)) {
        $clean = $sessionData;
    } else if (empty($clean) || !isset($clean['submitted'])) {
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         $user_info = get_user_meta($userid);
         $clean = array();
         // Auto-generate username Firstname + last initial if missing
         $first = isset($user_info['First Name'][0]) ? $user_info['First Name'][0] : $current_user->first_name;
         $last = isset($user_info['Last Name'][0]) ? $user_info['Last Name'][0] : $current_user->last_name;
         $username = trim($first);
         if(!empty($last)) { $username .= substr(trim($last),0,1); }
         if(isset($user_info['inf_field_UserName'][0])) { $username = $user_info['inf_field_UserName'][0]; }
         $clean['inf_field_UserName'] = $username;
      }
   }

   global $part2Step8Form;
   $strHtml = printFormNew($part2Step8Form, $clean, $arrErrs );
   $strHtml.= "<script>jQuery(document).ready(function($) { jQuery('#content').find('header').remove(); });</script>";
     // Add Step 8 specific JavaScript
   $strHtml .= <<<HTML
   <script>
   jQuery(document).ready(function($) {
      // Auto-generate username
      function generateUsername() {
         var firstName = '';
         var lastName = '';
         
         // Get user data from PHP
         var userData = {
            firstName: '{$current_user->first_name}',
            lastName: '{$current_user->last_name}'
         };
         
         if (userData.firstName && userData.lastName) {
            var baseUsername = userData.firstName + ' ' + userData.lastName.charAt(0);
            $('#inf_field_UserName').val(baseUsername);
         }
      }
      
      // Password generation
      function generatePassword() {
         var length = 12;
         var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
         var password = "";
         for (var i = 0, n = charset.length; i < length; ++i) {
            password += charset.charAt(Math.floor(Math.random() * n));
         }
         return password;
      }
      
      // Toggle password visibility
      $(document).on('click', '.toggle-password', function() {
         var target = $(this).data('target');
         var input = $('#' + target);
         var button = $(this);
         var showText = button.data('show-text') || 'Show -- show password';
         var hideText = button.data('hide-text') || 'Hide';

         if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            button.text(hideText);
            button.attr('aria-label', 'Hide password');
         } else {
            input.attr('type', 'password');
            button.text(showText);
            button.attr('aria-label', 'Show password');
         }
      });

      // Generate new password (regenerate)
      $(document).on('click', '.generate-password', function() {
         var target = $(this).data('target');
         var input = $('#' + target);
         var newPassword = generatePassword();
         var toggleButton = $('.toggle-password[data-target="' + target + '"]');
         var hideText = toggleButton.data('hide-text') || 'Hide';

         input.val(newPassword).attr('type', 'text');

         if (toggleButton.length) {
            toggleButton.text(hideText).attr('aria-label', 'Hide password');
         }

         // If the second field has a value, clear it so user re-enters the new password
         if (target === 'inf_field_Password') {
            $('#inf_field_Password_reenter').val('');
            $('#password-mismatch-error').remove();
            $('#inf_field_Password_reenter').css('border-color', '');
         }
      });
            // Validate password match on blur of second field
      $('#inf_field_Password_reenter').on('blur', function() {
         var firstPassword = $('#inf_field_Password').val();
         var secondPassword = $(this).val();
         
         if (secondPassword && secondPassword !== firstPassword) {
            $(this).css('border-color', '#dc3545');
            if (!$('#password-mismatch-error').length) {
               // $(this).after('<span id="password-mismatch-error" class="errors" style="color: #dc3545; display: block; margin-top: 5px;">Passwords do not match. Please enter the same password shown above.</span>');
                              wrapper.after('<span id="password-mismatch-error" class="errors" style="color: #dc3545; display: block; margin-top: 5px;">Passwords do not match. Please enter the same password shown above.</span>');
            }
         } else if (secondPassword && secondPassword === firstPassword) {
            $(this).css('border-color', '#28a745');
            $('#password-mismatch-error').remove();
         }
      });
      
      // Generate password
      // $(document).on('click', '.generate-password', function() {
      //    var target = $(this).data('target');
      //    var input = $('#' + target);
      //    var password = generatePassword();
      //    input.val(password);
            $('#part2-step8-form').on('submit', function(e) {
         var firstPassword = $('#inf_field_Password').val();
         var secondPassword = $('#inf_field_Password_reenter').val();
         var wrapper = $('#inf_field_Password_reenter').closest('.password-field-wrapper');
         
         // Also update the re-enter field if it's empty
         // if (target === 'inf_field_Password' && $('#inf_field_Password_reenter').val() === '') {
         //    $('#inf_field_Password_reenter').val(password);
                  if (!secondPassword || secondPassword !== firstPassword) {
            e.preventDefault();
            $('#inf_field_Password_reenter').css('border-color', '#dc3545');
            if (!$('#password-mismatch-error').length) {
               // $('#inf_field_Password_reenter').after('<span id="password-mismatch-error" class="errors" style="color: #dc3545; display: block; margin-top: 5px;">Passwords do not match. Please enter the same password shown above.</span>');
                              wrapper.after('<span id="password-mismatch-error" class="errors" style="color: #dc3545; display: block; margin-top: 5px;">Passwords do not match. Please enter the same password shown above.</span>');
            }
            $('#inf_field_Password_reenter').focus();
            return false;
         }
      });
      
      // Initialize username generation
      generateUsername();
   });
   </script>
HTML;
   
   return $strHtml;
}
add_shortcode("opinc-part2-step8", "opinc_part2_step8_form_sc");

// Redirects after Step 8 submission
function redirectAfterPart2Step8(){
   ob_clean();
   ob_start();
   if(isset($_POST['submit_part2_step8']) || isset($_POST['save_continue_later_step8']) || isset($_POST['previous_step8'])) {
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         // Validate passwords match and username (server-side)
         $errs = array();
         if(!isset($_POST['inf_field_UserName']) || empty(trim($_POST['inf_field_UserName']))) {
            $errs[] = array('inf_field_UserName', __('Username is required', 'openinclusion'));
         }
         if(!isset($_POST['inf_field_Password']) || strlen($_POST['inf_field_Password']) < 8) {
            $errs[] = array('inf_field_Password', __('Password must be at least 8 characters', 'openinclusion'));
         }
         if(!isset($_POST['inf_field_Password_reenter']) || $_POST['inf_field_Password_reenter'] !== $_POST['inf_field_Password']) {
            $errs[] = array('inf_field_Password_reenter', __('Passwords do not match', 'openinclusion'));
         }
         if(count($errs) > 0 && isset($_POST['submit_part2_step8'])) { 
            setFormErrors($errs); 
            return; 
         }

         // Update WP user account (only if not "save continue later")
         if(!isset($_POST['save_continue_later_step8'])) {
            // Note: user_login (username) cannot be changed in WordPress after user creation
            // We update the password and store the desired username as display name and meta
            $userData = array(
               'ID' => $userid,
               'user_pass' => $_POST['inf_field_Password'],
               'display_name' => $_POST['inf_field_UserName'],  // Set as display name
               'nickname' => $_POST['inf_field_UserName']       // Set as nickname
            );
            wp_update_user($userData);
            
            // Store custom username in user meta for reference
            update_user_meta($userid, 'custom_username', $_POST['inf_field_UserName']);
            
            // IMPORTANT: Update the actual WordPress username in the database
            // This is the only way to change username after user creation
            global $wpdb;
            $username = sanitize_user($_POST['inf_field_UserName'], true);
            
            // Check if the new username is available
            $username_exists = username_exists($username);
            if(!$username_exists || $username_exists == $userid) {
               // Username is available or belongs to this user
               $wpdb->update(
                  $wpdb->users,
                  array('user_login' => $username),
                  array('ID' => $userid),
                  array('%s'),
                  array('%d')
               );
               
               // Clear user cache to reflect changes
               clean_user_cache($userid);
               
               error_log("Username successfully updated to: " . $username . " for user ID: " . $userid);
            } else {
               // Username already taken
               error_log("Username '" . $username . "' already exists. Keeping email as username.");
               $errs[] = array('inf_field_UserName', __('This username is already taken. Please choose a different one.', 'openinclusion'));
               if(count($errs) > 0) {
                  setFormErrors($errs);
                  return;
               }
            }
         }

         // Save meta
         $userMetaData = prepareUserMetaData();
         foreach( $userMetaData as $key => $val ) { update_user_meta( $userid, $key, $val ); }
                  // Update Keap with Step 8 data
         if (file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
            include_once (__DIR__."/../../../infusion/updateMultiStepData.php");
            $user_info = get_user_meta($userid);
            $user_email = isset($user_info['Email'][0]) ? $user_info['Email'][0] : '';
            
            // If no email in meta, try to get from user object
            if (empty($user_email)) {
                $user_email = $current_user->user_email;
            }
            
            // Only proceed if we have a valid email
            if (!empty($user_email)) {
                $result = updateKeapMultiStepData('step8', $_POST, $user_email);
                error_log("Keap update result for step8: " . ($result ? 'SUCCESS' : 'FAILED'));
            } else {
                error_log("No valid email found for step8");
            }
         }

         if(isset($_POST['save_continue_later_step8'])) {
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/mywordpress/thank-you-2/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/thank-you-2/"; }
            wp_redirect($redirectUrl); exit;
         }

         // Next: Step 9
         if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step9/'; }
         else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step9/"; }
         wp_redirect($redirectUrl); exit;
      }
   }
}
add_action( 'template_redirect', 'redirectAfterPart2Step8');

// Shortcode: Part 2 Step 9 (Thank You)
function opinc_part2_step9_form_sc($atts, $content = null) {
   extract(shortcode_atts(array(
   ), $atts));

   // Simple render
   global $part2Step9Form;
   $strHtml = printFormNew($part2Step9Form, array(), array() );
   $strHtml.= "<script>jQuery(document).ready(function($) { jQuery('#content').find('header').remove(); });</script>";
   return $strHtml;
}
add_shortcode("opinc-part2-step9", "opinc_part2_step9_form_sc");

// Redirects after Step 9 submission
function redirectAfterPart2Step9(){
   ob_clean();
   ob_start();
   if(isset($_POST['submit_part2_step9'])) {
      clearFormStepData();
         $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         
         // Update Keap with Step 9 data (final step)
         if (file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
            include_once (__DIR__."/../../../infusion/updateMultiStepData.php");
            $user_info = get_user_meta($userid);
            $user_email = isset($user_info['Email'][0]) ? $user_info['Email'][0] : '';
            
            // If no email in meta, try to get from user object
            if (empty($user_email)) {
                $user_email = $current_user->user_email;
            }
            
            // Only proceed if we have a valid email
            if (!empty($user_email)) {
                $result = updateKeapMultiStepData('step9', $_POST, $user_email);
                error_log("Keap update result for step9: " . ($result ? 'SUCCESS' : 'FAILED'));
            } else {
                error_log("No valid email found for step9");
            }
         }
         
         // Mark final completion
         update_user_meta($userid, 'Part2Step9Completed', 'Yes');
         update_user_meta($userid, 'RegistrationComplete', 'Yes');
      }
      
      // Final redirect to Vanilla community via jsConnect with client binding
    //  
     $redirectUrl = "https://staging4.openinclusion.com/login/?redirect_to=https://openinclusion.vanillastaging.com/entry/signin/";
      
      error_log("Step 9 complete - Redirecting to: " . $redirectUrl);
      wp_redirect($redirectUrl); exit;
   }
}
add_action( 'template_redirect', 'redirectAfterPart2Step9');

// function openinclusion_get_vanilla_jsconnect_url( $target = '/' ) {
//    $baseUrl = 'https://openinclusion.vanillastaging.com/entry/connect/jsconnect';
//    $queryArgs = array(
//       'target' => $target,
//    );

//    if ( defined( 'VF_OPTIONS_NAME' ) ) {
//       $vanillaOptions = get_option( VF_OPTIONS_NAME );
//       if ( is_array( $vanillaOptions ) && ! empty( $vanillaOptions['sso-clientid'] ) ) {
//          $queryArgs['client_id'] = $vanillaOptions['sso-clientid'];
//       } else {
//          error_log( 'Vanilla login redirect: sso-clientid missing. Falling back without client_id.' );
//       }
//    }

//    return add_query_arg( $queryArgs, $baseUrl );
// }

/**
 * After a successful WordPress login, send members straight to Vanilla.
 */
// function openinclusion_login_redirect_to_vanilla( $redirect_to, $requested_redirect_to, $user ) {
//    if ( is_wp_error( $user ) || ! $user || ! isset( $user->ID ) ) {
//       return $redirect_to;
//    }

//    // Always send newly logged-in users to Vanilla staging.
//    // return 'https://openinclusion.vanillastaging.com';
//       // Preserve requested redirect target if provided.
//    $target = '/';
//    if ( ! empty( $requested_redirect_to ) ) {
//       $target = $requested_redirect_to;
//    } elseif ( ! empty( $redirect_to ) && $redirect_to !== admin_url() ) {
//       $target = $redirect_to;
//    }

//    return openinclusion_get_vanilla_jsconnect_url( $target );
// }
// add_filter( 'login_redirect', 'openinclusion_login_redirect_to_vanilla', 999, 3 );


/**********************************************************************************************
      This function redirects to thank you page after registration. Validate if consent is submitted
**********************************************************************************************/
function redirectAfterRegistration(){
   ob_clean();
   ob_start();
   // if(isset($_POST['consent'])) {
      $mailSent = false;
          // Check if user is under 18 - screen them out
      // if(isset($_POST['inf_field_over18']) && $_POST['inf_field_over18'] == 'Not-yet') {
      //    // Set error message and return to form
      //    $arrErrs = array();
      //    $arrErrs[] = array('inf_field_over18', 'Thanks for your interest! Currently, we\'re only able to accept members aged 18 and over for our insight community.');
      //    setFormErrors($arrErrs);
      //    return;
      // if(isset($_POST['inf_field_over18']) && $_POST['inf_field_over18'] == 'No') {
      //    // Redirect to thank-you page with error message
      //    if(isset($_SERVER['HTTP_HOST'])) {
      //       if($_SERVER['HTTP_HOST'] == 'localhost') {
      //          $redirectUrl = "http://" . $_SERVER['HTTP_HOST']."/mywordpress/thank-you-2/";
      //       }
      //       else {
      //          $redirectUrl = "https://". $_SERVER['HTTP_HOST']. "/thank-you-2/";
      //       }
            
      //       // Add error message as URL parameter
      //       $errorMessage = urlencode('Thanks for your interest! Currently, we\'re only able to accept members aged 18 and over for our insight community.');
      //       $redirectUrl .= "?error=" . $errorMessage;
            
      //       wp_redirect($redirectUrl);
      //       exit;
      //    }
      // }
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

            // $baseLink = "https://openinclusion.com/activation/";
            $baseLink = "https://staging4.openinclusion.com/activation/";
            if(isset($_SERVER['HTTP_HOST'])) {
               if($_SERVER['HTTP_HOST'] == 'localhost') {
                  $baseLink = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/activation/";
               }
               else {
                  // $baseLink = "https://". $_SERVER['HTTP_HOST']. "/activation/";
                  $baseLink = "https://staging4.openinclusion.com/activation/";
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
               $redirectUrl = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/thank-you-2/";
            }
            else {
               $redirectUrl = "https://" . $_SERVER['HTTP_HOST']."/thank-you-2/";
            }         
         }
         else {
            $redirectUrl = "https://staging4.openinclusion.com/thank-you-2/";
         }
         
         wp_redirect($redirectUrl);
         exit;
      }
   // }
}

add_action( 'template_redirect', 'redirectAfterRegistration');

function cleanupRegistrationSession() {
    if (isset($_SESSION['registration_data'])) {
        unset($_SESSION['registration_data']);
    }
}

function handlePreviousStep() {
    if (isset($_POST['previous_step2'])) {
        wp_redirect(get_permalink(get_page_by_path('part2-step1'))); 
        exit;
    }
    if (isset($_POST['previous_step3'])) {
        wp_redirect(get_permalink(get_page_by_path('part2-step2'))); 
        exit;
    }
    if (isset($_POST['previous_step4'])) {
        wp_redirect(get_permalink(get_page_by_path('part2-step3'))); 
        exit;
    }
    if (isset($_POST['previous_step5'])) {
        wp_redirect(get_permalink(get_page_by_path('part2-step4'))); 
        exit;
    }
    if (isset($_POST['previous_step6'])) {
        wp_redirect(get_permalink(get_page_by_path('part2-step5'))); 
        exit;
    }
    if (isset($_POST['previous_step7'])) {
        wp_redirect(get_permalink(get_page_by_path('part2-step6'))); 
        exit;
    }
    if (isset($_POST['previous_step8'])) {
        wp_redirect(get_permalink(get_page_by_path('part2-step7'))); 
        exit;
    }
}
add_action('init', 'handlePreviousStep');


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
      'PreferToContactOtherPleaseSpecify_OpenText' => 'Prefer To Contact Other Text',
      'inf_field_country' => 'Country',
      'inf_field_region' => 'Region',
      'inf_custom_YearBorn' => 'Year_Born',
      'inf_option_Gender' => 'Gender',
      'inf_option_Gender_opentext' => 'Gender OpenText',
      'inf_option_Gender_776_OpenText' => 'Gender OpenText',
      'SensoryNeeds' => 'Sensory Needs',
      'PhysicalNeeds' => 'Physical Needs',
      'CognitiveAndMentalhealthNeeds' => 'Cognitive And Mental health Needs',
      'CommunicationNeeds' => 'Communication Needs',
      'ChronichealthNeeds' => 'Chronic health Needs',
      'OtherNeeds' => 'Other Needs',
      'OtherTechnologies' => 'Other Technologies',
      // 'OtherNeedsOtherPleaseSpecify' => 'Other Needs',
      'SensoryNeedsOtherPleaseSpecify_OpenText' => 'Sensory Needs Open Text',
      'PhysicalNeedsOtherPleaseSpecify_OpenText' => 'Physical Needs Open Text',
      'CognitiveAndMentalhealthNeedsOtherMentalHealth_OpenText' => 'Mental Health Open Text',
      'CommunicationNeedsOtherPleaseSpecify_OpenText' => 'Communication Needs Open Text',
      'ChronichealthNeedsOtherLongTermCondition_OpenText' => 'Chronic Health Open Text',
      'OtherNeedsOtherPleaseSpecify_OpenText' => 'Other Needs Open Text',
      'SexualOrientationsOtherPleaseSpecify_OpenText' => 'Sexual Orientations Open Text',
      'inf_field_PrimaryNeed' => 'Primary Need',
      'inf_field_Age_Bracket' => 'Age Bracket',
      'inf_field_TemporaryAccessNeed' => 'Temporary Access Need',
      'DigitalandScreenTechnologies' => 'Digital and Screen Technologies',
            'DigitalandScreenTechnologiesSpecificSoftware' => 'Digital and Screen Technologies Specific Software',
      'DigitalandScreenTechnologiesOtherPleaseSpecify_OpenText' => 'Digital Technologies Open Text',
      'PrintMedia' => 'Print Media',
      'PrintMediaOtherPleaseSpecify_OpenText' => 'Print Media Open Text',
      'MovementCanesandServiceAnimals' => 'Movement Canes and Service Animals',
      'MovementCanesandServiceAnimalsOtherNavigationalMobilityAid_OpenText' => 'Movement Aids Open Text',
      'CommunicationPreferences' => 'Communication Preferences',
      'CommunicationPreferencesOtherPleaseSpecify_OpenText' => 'Communication Preferences Open Text',
      'PersonalSupportandHome' => 'PersonalSupportandHome',
      // 'OtherTechnologiesOtherPleaseSpecify' => 'Other Technologies',
      'OtherTechnologiesOtherPleaseSpecify_OpenText' => 'Other Technologies Open Text',
      // 'OtherTechnologiesOtherPleaseSpecify_OpenText' => 'Other Technologies Open Text',
      'ResearchFormats' => 'ResearchFormats',
      'RelationShipOtherPleaseSpecify_OpenText' => 'Relationship Other Text',
      'inf_field_referred' => 'Referred',
      'inf_field_referred_name' => 'Referred By',
      'inf_field_identify_terms' => 'identify_terms',
      'inf_field_identify_terms_text' => 'identify_terms_text',
      'inf_option_pronouns_other_please_specify_OpenText' => 'pronouns_other_text',
      'PleaseConfirm' => 'Consent',
      // New: Community agreement consent mapping
      'CommunityAgreement' => 'Community Agreement',
      'OpenVerifiedOptIn' => 'Open Verified Opt In'
   );
}

function prepareUserMetaData() {
   $mappingArray = getUserMetaDataMapping();
   $output = array();
   
   // Define which fields should be treated as arrays (checkbox groups)
   $arrayFields = array(
      'SensoryNeeds', 'PhysicalNeeds', 'CognitiveAndMentalhealthNeeds', 'CommunicationNeeds', 
      'ChronichealthNeeds', 'OtherNeeds', 'DigitalandScreenTechnologies', 'PrintMedia',
      'MovementCanesandServiceAnimals', 'CommunicationPreferences', 'PersonalSupportandHome', 
      'OtherTechnologies', 'ResearchFormats', 'SexualOrientations', 'RelationShip',
      'PreferToContact', 'PreferToContactOthers', 'PleaseConfirm', 'CommunityAgreement'
   );
   
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
         // For array fields that aren't set, save as empty string with pipe to indicate empty array
         if(in_array($inputKey, $arrayFields)) {
            $data = "|"; // Empty array representation
         } else {
            $data = ""; // Regular empty field
         }
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
   $arrErrs = array();

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
      // Check if email addresses match
   if(isset($_POST['inf_field_Email']) && isset($_POST['inf_field_re_Email'])) {
      if($_POST['inf_field_Email'] !== $_POST['inf_field_re_Email']) {
         $arrErrs[] = array('inf_field_re_Email', 'Email addresses do not match');
         $return = false;
      }
   }
   
   // Set form errors if any validation failed
   if(!$return && !empty($arrErrs)) {
      setFormErrors($arrErrs);
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
   $subject = "Please confirm your email for Open Inclusion's new community engagement platform";
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
   
   <p style=\"font-family: Poppins;\"> Thank you for registering with Open Inclusion's online community.<br/><strong>Please confirm your email to complete the initial set-up</strong> of your profile by clicking the button below:</p> 
  
   
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
   <p style=\"font-family: Poppins;content: left; font-size: 24px\">When you log in for the first time, you'll be directed to the second part of the registration form, where you will have the opportunity to update your access needs and assistive technology preferences. <br>   </p>
   <p style=\"font-family: Poppins;content: left; font-size: 24px; margin-top: 10px; color: #500050;\">This helps us invite you to research opportunities that best suit you.</p>
   <p style=\"font-family: Poppins; font-size: 24px;\">
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

function sendContinueRegistrationEmail($toEmailId, $name, $nextStepUrl) {
   $headers = array(
      'Content-Type: text/html; charset=UTF-8',
      'From: Open Inclusion <contact@openinclusion.com>'
   );
   $subject = "Continue your Open Inclusion registration";
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
   Hello,
   
   <p style=\"font-family: Poppins;\">Thank you for completing part of Open Inclusion's Online Community registration form.</p>
   
   <p style=\"font-family: Poppins;\">To continue your registration and pick up where you left off, simply click the link below:</p>
   
   </div>
   <div>
   <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\" style=\"height: 50px;background: #2a3258;border-radius: 23px;\">
   <tr>
   <td style=\"text-align: left; padding-top:10px;padding-right:15px;padding-bottom:10px;padding-left:15px;\">
   <a href=\"".$nextStepUrl."\" target=\"_blank\" rel=\"noopener\" style=\"border-radius: 23px;background: #2a3258;text-decoration: none;font-family: Poppins-Medium, Poppins;font-style: normal;font-weight: 500;line-height: 38px;height: 50px;padding: 6px; content: left; font-size: 24px\">
   <span style=\"color:#ffffff;\">Continue Registration</span>
   </a>
   </td>
   </tr>
   </table>
   </div>
   <br />
    <div style=\"padding: 30px 0px; font-family: Poppins; font-size: 24px; line-height: 40px; text-align: left; content: left\">
   <p style=\"font-family: Poppins;content: left; font-size: 24px\">If you need any assistance, please don't hesitate to reach out to us at contact@openinclusion.com</p>
   <p style=\"font-family: Poppins; font-size: 24px\">
   Warmly, <br />The Open Inclusion Team
   </p>
   </div>
   </div>
   </div>
   </div>
   <div style=\"height: 50px;background:#E5E8E8\"></div>
   </div>";

   wp_mail( $toEmailId, $subject, $content, $headers);
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
         // Log the user in after successful activation
          wp_set_current_user( $user_id );
          wp_set_auth_cookie( $user_id );
      }
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         // $redirect = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/login";
                  $redirect = "https://staging4.openinclusion.com/multi-step-reg/";

      }
      else {
        // $redirect = "https://". $_SERVER['HTTP_HOST']. "/login";
        $redirect = "https://staging4.openinclusion.com/multi-step-reg/";
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
      
      // Add error handling around updateUserStatus.php
      if(file_exists(__DIR__."/../../../infusion/updateUserStatus.php")) {
         try {
            include_once (__DIR__."/../../../infusion/updateUserStatus.php");
         } catch (Error $e) {
            // Log error but don't stop the process
            error_log('updateUserStatus.php error in redirectAfterEditProfile: ' . $e->getMessage());
         } catch (Exception $e) {
            // Log error but don't stop the process  
            error_log('updateUserStatus.php exception in redirectAfterEditProfile: ' . $e->getMessage());
         }
      }
      
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
//    if(isset($_POST['submit_part2_step1'])) {
   if(isset($_POST['submit_part2_step1']) || isset($_POST['save_continue_later']) || isset($_POST['previous_step1'])) {
      storeFormStepData('step1', $_POST);
      $current_user = wp_get_current_user();
      if($current_user) {
         $userid = $current_user->ID;
         
         // Server-side validation for Step 1
         $errs = array();
         
         // Validate required fields
         if(!isset($_POST['inf_field_country']) || empty($_POST['inf_field_country'])) {
            $errs[] = array('inf_field_country', __('Please select your country', 'openinclusion'));
         }
         
         if(!isset($_POST['inf_field_region']) || empty($_POST['inf_field_region'])) {
            $errs[] = array('inf_field_region', __('Please select your region, province or state', 'openinclusion'));
         }
         
         if(!isset($_POST['inf_field_postcode']) || empty(trim($_POST['inf_field_postcode']))) {
            $errs[] = array('inf_field_postcode', __('Please enter your postcode', 'openinclusion'));
         }
         
         if(!isset($_POST['inf_field_over18']) || empty($_POST['inf_field_over18'])) {
            $errs[] = array('inf_field_over18', __('Please confirm if you are over 18', 'openinclusion'));
         }
         
         if(!isset($_POST['inf_custom_YearBorn']) || empty($_POST['inf_custom_YearBorn'])) {
            $errs[] = array('inf_custom_YearBorn', __('Please enter your birth year', 'openinclusion'));
         } else {
            // Validate birth year is numeric and reasonable
            $birthYear = intval($_POST['inf_custom_YearBorn']);
            $currentYear = date('Y');
            if($birthYear < 1900 || $birthYear > $currentYear) {
               $errs[] = array('inf_custom_YearBorn', __('Please enter a valid birth year', 'openinclusion'));
            }
         }
         
         if(!isset($_POST['inf_field_hasDisability']) || empty($_POST['inf_field_hasDisability'])) {
            $errs[] = array('inf_field_hasDisability', __('Please answer this question', 'openinclusion'));
         }
         
         if(!isset($_POST['RelationShip']) || !is_array($_POST['RelationShip']) || count($_POST['RelationShip']) === 0) {
            $errs[] = array('RelationShip', __('Please select at least one option', 'openinclusion'));
         }
         
        // If validation fails and user clicked "Save & Next Step", show errors
         if(count($errs) > 0 && isset($_POST['submit_part2_step1'])) {
            setFormErrors($errs);
            return; // Stay on current step with errors
         }
         
         // Business logic validations (screening)
         if(isset($_POST['inf_field_over18']) && $_POST['inf_field_over18'] == 'Not Yet') {
            // Mark user as screened out
            update_user_meta( $userid, 'ScreenedOut', 'Yes');
            update_user_meta( $userid, 'ScreenedOutReason', 'Under 18 years old');
            
            // Redirect to thank-you page with error message
            if(isset($_SERVER['HTTP_HOST'])) {
               if($_SERVER['HTTP_HOST'] == 'localhost') {
                  $redirectUrl = "http://" . $_SERVER['HTTP_HOST']."/mywordpress/thank-you-2/";
               }
               else {
                  $redirectUrl = "https://". $_SERVER['HTTP_HOST']. "/thank-you-2/";
               }
               
               // Add error message as URL parameter
               $errorMessage = urlencode('Thanks for your interest! Currently, we\'re only able to accept members aged 18 and over for our online community.');
               $redirectUrl .= "?error=" . $errorMessage;
               
               wp_redirect($redirectUrl);
               exit;
            }
         }
         
         // Update user meta data with Part 2 Step 1 information
         $userMetaData = prepareUserMetaData();
         foreach( $userMetaData as $key => $val ) {
            update_user_meta( $userid, $key, $val ); 
         }

                  // Include processv2.php for Keap API call
        //  if (file_exists(__DIR__."/../../../infusion/processv2.php")) {
        //     include_once (__DIR__."/../../../infusion/processv2.php");
        //  }

                  // Update Keap with Step 1 data
        //  if (function_exists('class_exists') && !class_exists('iSDK') && file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
        // if (file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
        if (file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
            include_once (__DIR__."/../../../infusion/updateMultiStepData.php");
            $user_info = get_user_meta($userid);

            $user_email = isset($user_info['Email'][0]) ? $user_info['Email'][0] : '';
            
            // If no email in meta, try to get from user object
            if (empty($user_email)) {
                $user_email = $current_user->user_email;
            }
            
            // Only proceed if we have a valid email
            if (!empty($user_email)) {
                $result = updateKeapMultiStepData('step1', $_POST, $user_email);
                error_log("Keap update result: " . ($result ? 'SUCCESS' : 'FAILED'));
            } else {
                error_log("=== KEAP DEBUG: No valid email found for step1 ===");
            }
         }
         
         
        //  // Mark that user has completed Part 2 Step 1
        //  update_user_meta( $userid, 'Part2Step1Completed', 'Yes');

        //  // Update Part 2 Step 1 data in Keap/Infusionsoft
        //  include_once (__DIR__."/../../../infusion/updatePart2Step1.php");
         
        //  // Update user status in Keap/Infusionsoft
        //  include_once (__DIR__."/../../../infusion/updateUserStatus.php");
         
        //  if(isset($_SERVER['HTTP_HOST'])) {
        //     if($_SERVER['HTTP_HOST'] == 'localhost') {
        //        $redirectUrl = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/part2-step2/";
        //     }
        //     else {
        //        $redirectUrl = "https://". $_SERVER['HTTP_HOST']. "/part2-step2/";
        //     }         
        //     wp_redirect($redirectUrl);
        //     exit;      
        //  } 
        // Check if "Save & Continue Later" button was clicked
         if(isset($_POST['save_continue_later'])) {
            // Send continue registration email
            $nextStepUrl = "https://" . $_SERVER['HTTP_HOST'] . "/part2-step2/";
            if($_SERVER['HTTP_HOST'] == 'localhost') {
               $nextStepUrl = "http://" . $_SERVER['HTTP_HOST'] . "/openinclusion/part2-step2/";
            }
            sendContinueRegistrationEmail($current_user->user_email, $current_user->first_name, $nextStepUrl);
            // Don't mark as completed, just save the data and redirect to thank you page
            if(isset($_SERVER['HTTP_HOST'])) {
               if($_SERVER['HTTP_HOST'] == 'localhost') {
                  $redirectUrl = "http://" . $_SERVER['HTTP_HOST']."/mywordpress/thank-you-2/";
               }
               else {
                  $redirectUrl = "https://". $_SERVER['HTTP_HOST']. "/thank-you-2/";
               }         
               wp_redirect($redirectUrl);
               exit;      
            }
         } else {
           // Mark that user has completed Part 2 Step 1 (only for "Save & Next Step")
            update_user_meta( $userid, 'Part2Step1Completed', 'Yes');
            
            // Update Part 2 Step 1 data in Keap/Infusionsoft (temporarily disabled to avoid class iSDK redeclare)
            // if (function_exists('class_exists') && !class_exists('iSDK') && file_exists(__DIR__."/../../../infusion/updatePart2Step1.php")) {
            //    include_once (__DIR__."/../../../infusion/updatePart2Step1.php");
            // }
            
            // Update user status in Keap/Infusionsoft (temporarily disabled to avoid class iSDK redeclare)
            // if (function_exists('class_exists') && !class_exists('iSDK') && file_exists(__DIR__."/../../../infusion/updateUserStatus.php")) {
            //    include_once (__DIR__."/../../../infusion/updateUserStatus.php");
            // }
            
            // Determine routing based on caregiver-only selection
            // $shouldSkip = false;
            // $shouldSkipStep2 = false;
            // if(isset($_POST['RelationShip'])) {
            //    // $shouldSkip = user_should_skip_step2_based_on_relationship($_POST['RelationShip']);
            //    $shouldSkipStep2 = user_should_skip_step2_based_on_relationship($_POST['RelationShip']);
            // }
            // if(isset($_SERVER['HTTP_HOST'])) {
            //    // if($_SERVER['HTTP_HOST'] == 'localhost') {
            //    //    $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step3/';
            //    // }
            //    // else {
            //    //    $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step3/";
            //    // }
            //    if($shouldSkipStep2) {
            //       // Caregiver-only: Skip Page 2, go to Page 3
            //       if($_SERVER['HTTP_HOST'] == 'localhost') {
            //          $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step3/';
            //       }
            //       else {
            //          $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step3/";
            //       }
            //    } else {
            //       // Everyone else: Continue to Page 2
            //       if($_SERVER['HTTP_HOST'] == 'localhost') {
            //          $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step2/';
            //       }
            //       else {
            //          $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step2/";
            //       }
            //    }         
            //    wp_redirect($redirectUrl);
            //    exit;      
            // }
            // Determine routing based on Q1 (hasDisability) and Q2 (relationship to disability)
            $shouldSkipStep2 = false;
            if(isset($_POST['RelationShip'])) {
               $hasDisabilityValue = isset($_POST['inf_field_hasDisability']) ? $_POST['inf_field_hasDisability'] : null;
               $shouldSkipStep2 = user_should_skip_step2_based_on_relationship($_POST['RelationShip'], $hasDisabilityValue);
            }
            
            // Route to appropriate step
            if(isset($_SERVER['HTTP_HOST'])) {
               if($shouldSkipStep2) {
                  // Caregiver-only: Skip Page 2, go to Page 3
                  if($_SERVER['HTTP_HOST'] == 'localhost') {
                     $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step3/';
                  }
                  else {
                     $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step3/";
                  }
               } else {
                  // Everyone else: Continue to Page 2
                  if($_SERVER['HTTP_HOST'] == 'localhost') {
                     $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step2/';
                  }
                  else {
                     $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step2/";
                  }  
               }
               wp_redirect($redirectUrl);
               exit;  
            }
         } 
      }
   }
}
add_action( 'template_redirect', 'redirectAfterPart2Step1');

// Caregiver-only routing from Step 1: If RelationShip is only caregiver categories and no others, skip Step 2
// function user_should_skip_step2_based_on_relationship($relationshipValues, $hasDisabilityValue = null) {
//    if(!is_array($relationshipValues)) return false;
//    $caregiverKeys = array('A-professional-caregiver-to-a-disabled-person','A-personal-caregiver-to-a-disabled-person');
//    $hasCaregiver = false;
//    $hasNonCaregiver = false;
//    foreach($relationshipValues as $val){
//       if(in_array($val, $caregiverKeys)) { $hasCaregiver = true; }
//       else { $hasNonCaregiver = true; }
//    }
//    return ($hasCaregiver && !$hasNonCaregiver);
// }

function user_should_skip_step2_based_on_relationship($relationshipValues, $hasDisabilityValue = null) {
   if(!is_array($relationshipValues)) return false;
   
   // Get Q1 value from POST if not provided
   if($hasDisabilityValue === null && isset($_POST['inf_field_hasDisability'])) {
      $hasDisabilityValue = $_POST['inf_field_hasDisability'];
   }
   
   // Define caregiver-only options
   $caregiverOnlyKeys = array(
      'A-professional-caregiver-to-a-disabled-person',
      'A-personal-caregiver-to-a-disabled-person'
   );
   
   // Define non-caregiver options (disabled, condition, over 65, other)
   $nonCaregiverKeys = array(
      'A-Disabled-Person',
      'A-person-with-specific-condition', 
      'Over-65-Years-Old',
      'A-parent-of-someone-with-a-disability',
      'A-spouse-child-or-sibling-of-a-disabled-person',
      'I-have-another-relationship-to-disability-or-age-related-needs'
   );
   
   $hasCaregiverOnly = false;
   $hasNonCaregiver = false;
   
   foreach($relationshipValues as $val) {
      if(in_array($val, $caregiverOnlyKeys)) {
         $hasCaregiverOnly = true;
      }
      if(in_array($val, $nonCaregiverKeys)) {
         $hasNonCaregiver = true;
      }
   }
   
   // Implement the new routing logic:
   // Q1 = No + Q2 caregiver only → skip Page 2
   // Q1 = No + Q2 caregiver + something else → go to Page 2
   // Q1 = Yes (any identity selected except None) → go to Page 2
   // Q1 = I'd rather not answer + Q2 caregiver only → skip Page 2
   // Q1 = I'd rather not answer + Q2 caregiver + something else → go to Page 2
   
   if($hasDisabilityValue === 'No' || $hasDisabilityValue === 'PreferNotToAnswer') {
      // For Q1 = No or "I'd rather not answer": skip Page 2 only if Q2 is caregiver only
      return ($hasCaregiverOnly && !$hasNonCaregiver);
   } else if($hasDisabilityValue === 'Yes') {
      // For Q1 = Yes: always go to Page 2 (unless "None of the above" is selected)
      return false;
   }
   
   // Default: go to Page 2
   return false;
}

// Check if user should be screened out
function user_should_be_screened_out($relationshipValues) {
   if(!is_array($relationshipValues)) return false;
   
   // Currently no screening out based on relationship selection
   return false;
}

// Redirects after Step 2 submission
function redirectAfterPart2Step2(){
   ob_clean();
   ob_start();

   if ( isset($_POST['previous_step2']) ) {
       storeFormStepData('step2', $_POST);
       if($_SERVER['HTTP_HOST'] == 'localhost') {
           $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step1/';
       } else {
           $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step1/";
       }
       wp_redirect($redirectUrl);
       exit;
   }
   if(isset($_POST['submit_part2_step2']) || isset($_POST['save_continue_later_step2'])) {
      storeFormStepData('step2', $_POST);

      $current_user = wp_get_current_user();

      if($current_user) {
         $userid = $current_user->ID;
         
         // Server-side validation for Step 2
         $errs = array();
         
         // Check if user indicated they have a disability and validate access needs
         $hasDisability = get_user_meta($userid, 'Has Disability', true);
         if($hasDisability === 'Yes') {
            $accessNeedsSelected = false;
            $accessNeedsFields = array('SensoryNeeds', 'PhysicalNeeds', 'CognitiveAndMentalhealthNeeds', 
                                       'CommunicationNeeds', 'ChronichealthNeeds', 'OtherNeeds');
            
            foreach($accessNeedsFields as $fieldName) {
               if(isset($_POST[$fieldName]) && is_array($_POST[$fieldName]) && count($_POST[$fieldName]) > 0) {
                  $accessNeedsSelected = true;
                  break;
               }
            }
            
            if(!$accessNeedsSelected) {
               $errs[] = array('SensoryNeeds', __('Since you indicated you have a disability, please select at least one access need category that applies to you', 'openinclusion'));
            }
         }
         
         // Additional validation: Ensure checkbox arrays are properly formatted
         $checkboxFields = array('SensoryNeeds', 'PhysicalNeeds', 'CognitiveAndMentalhealthNeeds', 
                                'CommunicationNeeds', 'ChronichealthNeeds', 'OtherNeeds');
         
         foreach($checkboxFields as $fieldName) {
            if(isset($_POST[$fieldName]) && !is_array($_POST[$fieldName])) {
               $errs[] = array($fieldName, __('Invalid data format for ' . $fieldName . '. Please refresh the page and try again.', 'openinclusion'));
            }
         }
         
        // If validation fails and user clicked "Save & Next Step", show errors
         if(count($errs) > 0 && isset($_POST['submit_part2_step2'])) {
            setFormErrors($errs);
            return; // Stay on current step with errors
         }
         
         // Save user meta data with proper error handling
         try {
            $userMetaData = prepareUserMetaData();
            foreach( $userMetaData as $key => $val ) {
               update_user_meta( $userid, $key, $val );
            }
         } catch (Exception $e) {
            // If there's an error in data preparation, show validation error
            $errs[] = array('general', __('There was an error processing your data. Please check your selections and try again.', 'openinclusion'));
            setFormErrors($errs);
            return;
         }

                  // Update Keap with Step 2 data (always update, including Save & Continue Later)
                 if (file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
            include_once (__DIR__."/../../../infusion/updateMultiStepData.php");
            $user_info = get_user_meta($userid);
                     $user_email = isset($user_info['Email'][0]) ? $user_info['Email'][0] : '';
            
            // If no email in meta, try to get from user object
            if (empty($user_email)) {
                $user_email = $current_user->user_email;
            }
            
            // Only proceed if we have a valid email
            if (!empty($user_email)) {
                // Use update mode if this is a re-save (going back) or if step was previously completed
                $isUpdate = isset($_POST['previous_step2']) || get_user_meta($userid, 'Part2Step2Completed', true) === 'Yes';
                $result = updateKeapMultiStepData('step2', $_POST, $user_email, $isUpdate);
                error_log("Keap update result for step2: " . ($result ? 'SUCCESS' : 'FAILED') . " (updateMode: " . ($isUpdate ? 'true' : 'false') . ")");
            } else {
                error_log("No valid email found for step2");
            }
         }
         
         // Handle Previous button - go back to Step 1
         if(isset($_POST['previous_step2'])) {
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step1/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step1/"; }
            wp_redirect($redirectUrl); exit;
         }

         if(isset($_POST['save_continue_later_step2'])) {
             // Send continue registration email
            $nextStepUrl = "https://" . $_SERVER['HTTP_HOST'] . "/part2-step3/";
            if($_SERVER['HTTP_HOST'] == 'localhost') {
               $nextStepUrl = "http://" . $_SERVER['HTTP_HOST'] . "/openinclusion/part2-step3/";
            }
            sendContinueRegistrationEmail($current_user->user_email, $current_user->first_name, $nextStepUrl);
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/mywordpress/thank-you-2/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/thank-you-2/"; }
            wp_redirect($redirectUrl); exit;
         }
         // Continue to Step 3 (only if validation passed)
         if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step3/'; }
         else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step3/"; }
         wp_redirect($redirectUrl); exit;
      }
   }
}
add_action( 'template_redirect', 'redirectAfterPart2Step2');

// Redirects after Step 3 submission
function redirectAfterPart2Step3(){
   ob_clean();
   ob_start();

   if(isset($_POST['submit_part2_step3']) || isset($_POST['save_continue_later_step3']) || isset($_POST['previous_step3']) ) {
      $current_user = wp_get_current_user();
      storeFormStepData('step3', $_POST);

      if(isset($_POST['previous_step3'])) {
         if($_SERVER['HTTP_HOST'] == 'localhost') {
            $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step2/';
         } else {
            $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step2/";
         }
         wp_redirect($redirectUrl);
         exit;
      }

   if(!isset($_POST['previous_step3'])) {
      if($current_user) {
         $userid = $current_user->ID;
         
         // Server-side validation for Step 3
         $errs = array();
         
         // Validate required research formats
         if(!isset($_POST['ResearchFormats']) || !is_array($_POST['ResearchFormats']) || count($_POST['ResearchFormats']) === 0) {
            $errs[] = array('ResearchFormats', __('Please select at least one research format you are interested in', 'openinclusion'));
         }
         
         // Validate required referral question
         if(!isset($_POST['inf_field_referred']) || empty($_POST['inf_field_referred'])) {
            $errs[] = array('inf_field_referred', __('Please let us know if you were referred by someone', 'openinclusion'));
         }
         
         // Validate specific software text field length if provided
         if(isset($_POST['DigitalandScreenTechnologiesSpecificSoftware']) && 
            strlen($_POST['DigitalandScreenTechnologiesSpecificSoftware']) > 500) {
            $errs[] = array('DigitalandScreenTechnologiesSpecificSoftware', __('Software description can only be 500 characters long', 'openinclusion'));
         }
         
         // Validate referrer name length if provided
         if(isset($_POST['inf_field_referred_name']) && 
            strlen($_POST['inf_field_referred_name']) > 250) {
            $errs[] = array('inf_field_referred_name', __('Referrer name can only be 250 characters long', 'openinclusion'));
         }
         
         // Additional validation: Ensure checkbox arrays are properly formatted to prevent null errors
         $checkboxFields = array(
            'DigitalandScreenTechnologies', 'PrintMedia', 'MovementCanesandServiceAnimals', 
            'CommunicationPreferences', 'PersonalSupportandHome', 'OtherTechnologies', 'ResearchFormats'
         );
         
         foreach($checkboxFields as $fieldName) {
            if(isset($_POST[$fieldName]) && !is_array($_POST[$fieldName])) {
               $errs[] = array($fieldName, __('Invalid data format for ' . $fieldName . '. Please refresh the page and try again.', 'openinclusion'));
            }
         }
         
        // If validation fails and user clicked "Save & Next Step", show errors
         if(count($errs) > 0 && isset($_POST['submit_part2_step3'])) {
            setFormErrors($errs);
            return; // Stay on current step with errors
         }
      }
         
         // Save user meta data with proper error handling
         try {
            $userMetaData = prepareUserMetaData();
            foreach( $userMetaData as $key => $val ) {
               update_user_meta( $userid, $key, $val );
            }

                     // Update Keap with Step 3 data
        //  if (function_exists('class_exists') && !class_exists('iSDK') && file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
        if (file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
            include_once (__DIR__."/../../../infusion/updateMultiStepData.php");
            $user_info = get_user_meta($userid);
         //    $user_email = $user_info['Email'][0];
         //    updateKeapMultiStepData('step3', $_POST, $user_email);
         // }
                     $user_email = isset($user_info['Email'][0]) ? $user_info['Email'][0] : '';
            
            // If no email in meta, try to get from user object
            if (empty($user_email)) {
                $user_email = $current_user->user_email;
            }
            
            // Only proceed if we have a valid email
            if (!empty($user_email)) {
                // Use update mode if this is a re-save (going back) or if step was previously completed
                $isUpdate = isset($_POST['previous_step3']) || get_user_meta($userid, 'Part2Step3Completed', true) === 'Yes';
                $result = updateKeapMultiStepData('step3', $_POST, $user_email, $isUpdate);
                error_log("Keap update result for step3: " . ($result ? 'SUCCESS' : 'FAILED') . " (updateMode: " . ($isUpdate ? 'true' : 'false') . ")");
            }
         }
            
            // Mark completion (only if not going back and not saving for later)
            if(!isset($_POST['previous_step3']) && !isset($_POST['save_continue_later_step3'])) {
            update_user_meta( $userid, 'Part2Step3Completed', 'Yes');
            }
            
            // Only include updateUserStatus.php if we're not in "save continue later" or "previous" mode and if file exists
            if(!isset($_POST['save_continue_later_step3']) && !isset($_POST['previous_step3']) && file_exists(__DIR__."/../../../infusion/updateUserStatus.php")) {
               try {
                  include_once (__DIR__."/../../../infusion/updateUserStatus.php");
               } catch (Error $e) {
                  // Log error but don't stop the process
                  error_log('updateUserStatus.php error: ' . $e->getMessage());
               } catch (Exception $e) {
                  // Log error but don't stop the process  
                  error_log('updateUserStatus.php exception: ' . $e->getMessage());
               }
            }
            
         } catch (Exception $e) {
            // If there's an error in data preparation, show validation error
            $errs[] = array('general', __('There was an error processing your data. Please check your selections and try again.', 'openinclusion'));
            setFormErrors($errs);
            return;
         }
         
         // Handle Previous button - go back to Step 2
         if(isset($_POST['previous_step3'])) {
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step2/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step2/"; }
            wp_redirect($redirectUrl); exit;
         }
         
         if(isset($_POST['save_continue_later_step3'])) {
              // Send continue registration email
            $nextStepUrl = "https://" . $_SERVER['HTTP_HOST'] . "/part2-step4/";
            if($_SERVER['HTTP_HOST'] == 'localhost') {
               $nextStepUrl = "http://" . $_SERVER['HTTP_HOST'] . "/openinclusion/part2-step4/";
            }
            sendContinueRegistrationEmail($current_user->user_email, $current_user->first_name, $nextStepUrl);
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/mywordpress/thank-you-2/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/thank-you-2/"; }
         } else {
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step4/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step4/"; }
         }
         wp_redirect($redirectUrl); exit;
      }
   }
}
add_action( 'template_redirect', 'redirectAfterPart2Step3');

// Redirects after Step 4 submission
function redirectAfterPart2Step4(){
   ob_clean();
   ob_start();
   if ( isset($_POST['previous_step4']) ) {
       storeFormStepData('step4', $_POST);
       if($_SERVER['HTTP_HOST'] == 'localhost') {
           $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step3/';
       } else {
           $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step3/";
       }
       wp_redirect($redirectUrl);
       exit;
   }
   if(isset($_POST['submit_part2_step4']) || isset($_POST['save_continue_later_step4']) || isset($_POST['previous_step4'])) {
      $current_user = wp_get_current_user();
      storeFormStepData('step4', $_POST);

      if($current_user) {
         $userid = $current_user->ID;
         
         // Server-side validation for Step 4
         $errs = array();
         
         // Validate required gender field
         if(!isset($_POST['inf_option_Gender']) || empty($_POST['inf_option_Gender'])) {
            $errs[] = array('inf_option_Gender', __('Please select one gender', 'openinclusion'));
         }
         
         // Validate required gender at birth question
         if(!isset($_POST['inf_field_gender_at_birth_diff']) || empty($_POST['inf_field_gender_at_birth_diff'])) {
            $errs[] = array('inf_field_gender_at_birth_diff', __('Please select one option', 'openinclusion'));
         }
         
         // Validate required sexual orientations
         if(!isset($_POST['SexualOrientations']) || !is_array($_POST['SexualOrientations']) || count($_POST['SexualOrientations']) === 0) {
            $errs[] = array('SexualOrientations', __('Please select at least one option', 'openinclusion'));
         }
         
         // Validate required pronouns
         if(!isset($_POST['inf_option_pronouns']) || empty($_POST['inf_option_pronouns'])) {
            $errs[] = array('inf_option_pronouns', __('Please select one preferred pronoun', 'openinclusion'));
         }
         
         // Validate required ethnic identity
         if(!isset($_POST['inf_field_identify_terms']) || empty(trim($_POST['inf_field_identify_terms']))) {
            $errs[] = array('inf_field_identify_terms', __('Please provide your description or choose prefer not to respond', 'openinclusion'));
         } else {
            // Validate length
            if(strlen($_POST['inf_field_identify_terms']) > 250) {
               $errs[] = array('inf_field_identify_terms', __('Ethnic identity description can only be 250 characters long', 'openinclusion'));
            }
         }
         
        // If validation fails and user clicked "Save & Next Step", show errors
         if(count($errs) > 0 && isset($_POST['submit_part2_step4'])) {
            setFormErrors($errs);
            return; // Stay on current step with errors
         }
         
         // Save user meta data
         $userMetaData = prepareUserMetaData();
         foreach( $userMetaData as $key => $val ) { update_user_meta( $userid, $key, $val ); }
                 // Update Keap with Step 4 data
        if (file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
        //  if (function_exists('class_exists') && !class_exists('iSDK') && file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
            include_once (__DIR__."/../../../infusion/updateMultiStepData.php");
            $user_info = get_user_meta($userid);
         //    $user_email = $user_info['Email'][0];
         //    updateKeapMultiStepData('step4', $_POST, $user_email);
         // }
                     $user_email = isset($user_info['Email'][0]) ? $user_info['Email'][0] : '';
            
            // If no email in meta, try to get from user object
            if (empty($user_email)) {
                $user_email = $current_user->user_email;
            }
            
            // Only proceed if we have a valid email
            if (!empty($user_email)) {
                // Use update mode if this is a re-save (going back) or if step was previously completed
                $isUpdate = isset($_POST['previous_step4']) || get_user_meta($userid, 'Part2Step4Completed', true) === 'Yes';
                $result = updateKeapMultiStepData('step4', $_POST, $user_email, $isUpdate);
                error_log("Keap update result for step4: " . ($result ? 'SUCCESS' : 'FAILED') . " (updateMode: " . ($isUpdate ? 'true' : 'false') . ")");
            }
         }
         
         // Handle Previous button - go back to Step 3
         if(isset($_POST['previous_step4'])) {
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step3/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step3/"; }
            wp_redirect($redirectUrl); exit;
         }
         
         if(isset($_POST['save_continue_later_step4'])) {
             // Send continue registration email
            $nextStepUrl = "https://" . $_SERVER['HTTP_HOST'] . "/part2-step5/";
            if($_SERVER['HTTP_HOST'] == 'localhost') {
               $nextStepUrl = "http://" . $_SERVER['HTTP_HOST'] . "/openinclusion/part2-step5/";
            }
            sendContinueRegistrationEmail($current_user->user_email, $current_user->first_name, $nextStepUrl);
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/mywordpress/thank-you-2/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/thank-you-2/"; }
         } else {
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step5/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step5/"; }
         }
         wp_redirect($redirectUrl); exit;
      }
   }
}
add_action( 'template_redirect', 'redirectAfterPart2Step4');

// Redirects after Step 5 submission
function redirectAfterPart2Step5(){
   ob_clean();
   ob_start();
   if ( isset($_POST['previous_step5']) ) {
       storeFormStepData('step5', $_POST);
       if($_SERVER['HTTP_HOST'] == 'localhost') {
           $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step4/';
       } else {
           $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step4/";
       }
       wp_redirect($redirectUrl);
       exit;
   }
   if(isset($_POST['submit_part2_step5']) || isset($_POST['save_continue_later_step5'])) {
      $current_user = wp_get_current_user();
      storeFormStepData('step5', $_POST);

      if($current_user) {
         $userid = $current_user->ID;
         // Validate consent
         if(!isset($_POST['CommunityAgreement']) || !is_array($_POST['CommunityAgreement']) || count($_POST['CommunityAgreement']) === 0) {
            $arrErrs = array();
            $arrErrs[] = array('CommunityAgreement', __('You must agree to proceed', 'openinclusion'));
            setFormErrors($arrErrs);
            return; // fall back to form render with error
         }
         // Save
         $userMetaData = prepareUserMetaData();
         foreach( $userMetaData as $key => $val ) { update_user_meta( $userid, $key, $val ); }
         // Mark completion
         update_user_meta( $userid, 'Part2Step5Completed', 'Yes');

                  // Update Keap with Step 5 data
         if (file_exists(__DIR__."/../../../infusion/updateMultiStepData.php")) {
            include_once (__DIR__."/../../../infusion/updateMultiStepData.php");
            $user_info = get_user_meta($userid);
            $user_email = isset($user_info['Email'][0]) ? $user_info['Email'][0] : '';
            
            // If no email in meta, try to get from user object
            if (empty($user_email)) {
                $user_email = $current_user->user_email;
            }
            
            // Only proceed if we have a valid email
            if (!empty($user_email)) {
                $result = updateKeapMultiStepData('step5', $_POST, $user_email);
                error_log("Keap update result for step5: " . ($result ? 'SUCCESS' : 'FAILED'));
            } else {
                error_log("No valid email found for step5");
            }
         }
         
         // Add error handling around updateUserStatus.php
         if(file_exists(__DIR__."/../../../infusion/updateUserStatus.php")) {
            try {
               include_once (__DIR__."/../../../infusion/updateUserStatus.php");
            } catch (Error $e) {
               // Log error but don't stop the process
               error_log('updateUserStatus.php error in redirectAfterPart2Step5: ' . $e->getMessage());
            } catch (Exception $e) {
               // Log error but don't stop the process  
               error_log('updateUserStatus.php exception in redirectAfterPart2Step5: ' . $e->getMessage());
            }
         }

         if(isset($_POST['save_continue_later_step5'])) {
            if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/mywordpress/thank-you-2/'; }
            else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/thank-you-2/"; }
            wp_redirect($redirectUrl); exit;
         }

         // Redirect to Step 6 (only if validation passed)
         if($_SERVER['HTTP_HOST'] == 'localhost') { $redirectUrl = "http://" . $_SERVER['HTTP_HOST'].'/openinclusion/part2-step6/'; }
         else { $redirectUrl = "https://" . $_SERVER['HTTP_HOST']. "/part2-step6/"; }
         wp_redirect($redirectUrl); exit;
      }
   }
}
add_action( 'template_redirect', 'redirectAfterPart2Step5');


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
               case 'email-match' :
                  $validStr .= ' data-v-email-match="'.$validate[1].'"';
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
            // Check if this is the referral name field - hide by default unless "Yes" is selected
            $displayStyle = '';
            if($field['name'] == 'inf_field_referred_name') {
               $displayStyle = ' style="display:none;"';
               // Check if referral is "Yes" in clean data
               if(isset($clean['inf_field_referred']) && $clean['inf_field_referred'] == 'Yes') {
                  $displayStyle = '';
               }
            }
            $strHtml .= '<li'.$liCss.' '.$liId.$displayStyle.'>';
            $strHtml .= $labelTxt;
            // Check if clean array populated - to see if prev value stored
            if (count($clean) > 0) {
               $val = outScrn($clean[$field['name']]);
               $otherFieldValue = $val;
            } else {
               $val = '';
            }
            $disabledAttr = ($field['name'] == 'inf_field_referred_name' && (!isset($clean['inf_field_referred']) || $clean['inf_field_referred'] != 'Yes')) ? ' disabled="disabled"' : '';
            $strHtml .= '<input maxlength="'.$field['maxlen'].'" type="text" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$val.'"'.$disabledAttr;
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

               // $shouldHaveOpenText = ($option[0] == 'OtherPleaseSpecify');
                $shouldHaveOpenText = ($option[0] == 'OtherPleaseSpecify' || 
                                      $option[0] == 'OtherMentalHealth' || 
                                      $option[0] == 'OtherLongTermCondition' ||
                                      $option[0] == 'OtherNavigationalMobilityAid');
               
               // echo "<br>";
               foreach ($option as $value) {
                  // if (strpos($value, 'Other') !== false) {
                  if (strpos($value, 'Other') !== false && $value != 'OtherClinicallyObese') {
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
                     // if($containsOther){
                     if($shouldHaveOpenText){
                        $clickevent = ' OnClick="hideshowOpenText(this,`otherFieldValue`)"';
                     }
                  } else {
                     // if($containsOther){
                     if($shouldHaveOpenText){
                        $clickevent = rtrim($clickevent, '"') . ' hideshowOpenText(this,`otherFieldValue`);"';
                     }
                  }
               }
               if($fieldName == 'DigitalandScreenTechnologies') {
                  $triggerOptions = array('ScreenReader', 'ScreenMagnifier', 'Dragonandother', 'ReadAloudSoftware');
                  if(in_array($option[0], $triggerOptions)) {
                     if(empty($clickevent)) {
                        $clickevent = ' OnClick="toggleSpecificSoftwareField()"';
                     } else {
                        $clickevent = rtrim($clickevent, '"') . '; toggleSpecificSoftwareField();"';
                     }
                  }
               }

               $strHtml .= '<li class="check-radio" >';
               // $strHtml .= '<input type="checkbox" name="'.$option[2].'" id="'.$option[3].'" value="'.$option[0].'" 'aria-labelledby="'.$field['name'].'-legend '.$field['name'].'-'.$option[0].'-label '.$field['name'].'-errors"''.$checked . $clickevent .' >';
               $strHtml .= '<input type="checkbox" name="'.$option[2].'" id="'.$option[3].'" value="'.$option[0].'" '.$checked . $clickevent .' >';
               $strHtml .= '<label for="'.$option[3].'" id="'.$field['name'].'-'.$option[0].'-label">';                
               $strHtml .= $option[1].'</label>';
            if($shouldHaveOpenText) {
               // if($option[0] == 'OtherPleaseSpecify' && strlen($otherFieldValue)>0) {
    if(strpos($option[2], '[]') !== false) {
                     // Replace [] with the option key and _OpenText
                     $openTextFieldName = str_replace('[]', $option[0], $option[2]) . '_OpenText';
                  } else {
                     $openTextFieldName = $option[2] . '_OpenText';
                  }
                  $existingValue = '';
                  if(isset($clean[$openTextFieldName])) {
                     $existingValue = $clean[$openTextFieldName];
                      } elseif($option[0] == 'OtherPleaseSpecify' && isset($clean['SexualOrientationsOtherPleaseSpecify_OpenText'])) {
                     // For sexual orientations, check the correct field name
                     $existingValue = $clean['SexualOrientationsOtherPleaseSpecify_OpenText'];
                  } elseif($option[0] == 'OtherPleaseSpecify' && strlen($otherFieldValue)>0) {
                     // For backward compatibility, also check otherFieldValue for OtherPleaseSpecify
                     $existingValue = $otherFieldValue;
                  }

                       // Use the correctly formatted field name for the input
                  $inputFieldName = $openTextFieldName;
                  $inputFieldId = $openTextFieldName;
                  
                  if(strlen($existingValue)>0) {
                  $strHtml .= '<script>';
                  $strHtml .= 'setTimeout(function() {';
                  $strHtml .= '  document.getElementById("'.$option[3].'").click();';
                  $strHtml .= '}, 100);';
                  $strHtml .= '</script>';
                  // $strHtml .= '<label for="'.$option[2].'_OpenText" style="display:none"><span>"'.$option[1].'"</span></label><input type="text" name="'.$option[2].'_OpenText" id="'.$option[2].'_OpenText" value="'.$otherFieldValue.'" style="width:100%;display:none">'; 
                  // $strHtml .= '<label for="'.$option[2].'_OpenText" style="display:none"><span>"'.$option[1].'"</span></label><input type="text" name="'.$option[2].'_OpenText" id="'.$option[2].'_OpenText" value="'.htmlspecialchars($existingValue, ENT_QUOTES, 'UTF-8').'" style="width:100%;display:none">'; 
                                      $strHtml .= '<input type="text" name="'.$inputFieldName.'" id="'.$inputFieldId.'" value="'.htmlspecialchars($existingValue, ENT_QUOTES, 'UTF-8').'" placeholder="Please describe" style="width:100%;display:none">'; 
               }
               else{
                  // $strHtml .= '<label for="'.$option[2].'_OpenText" style="display:none"><span>"'.$option[1].'"</span></label><input type="text" name="'.$option[2].'_OpenText" id="'.$option[2].'_OpenText" value="" style="width:100%;display:none">';
                                      $strHtml .= '<input type="text" name="'.$inputFieldName.'" id="'.$inputFieldId.'" value="" placeholder="Please describe" style="width:100%;display:none">';
               }
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
               // Handle referral field conditional display
               if($fieldName == 'inf_field_referred') {
                  if (empty($clickevent)) {
                     $clickevent = ' OnClick="toggleReferredNameField(this)"';
                  } else {
                     $clickevent = rtrim($clickevent, '"') . '; toggleReferredNameField(this);"';
                  }
               }
               // if($option[0] == '776' || $option[0] == 'APartOfCommunity' || $option[0] == 'ACommunityOrganisation' || $option[0] == 'OurCommunityOther') {
                              if($option[0] == '776' || $option[0] == 'APartOfCommunity' || $option[0] == 'ACommunityOrganisation' || $option[0] == 'OurCommunityOther' || $option[0] == 'OtherPleaseSpecify' || $option[0] == 'SelfDescribe') {
                  // $selectedValues = getSelectedOptions($fieldName, $clean);
                  $clickevent.= ' OnClick="hideshowOpenText(this)"';  
               }
               else if($fieldName != 'inf_field_referred'){
                  $clickevent.= ' OnClick="hideOpenText(this)"';
               }

                              // Add onClick handler for specific software field triggers in Part 2 Step 3
               // Trigger options: ScreenReader, ScreenMagnifier, Dragonandother, ReadAloudSoftware
               if($fieldName == 'DigitalandScreenTechnologies') {
                  $triggerOptions = array('ScreenReader', 'ScreenMagnifier', 'Dragonandother', 'ReadAloudSoftware');
                  if(in_array($option[0], $triggerOptions)) {
                     if(empty($clickevent)) {
                        $clickevent = ' OnClick="toggleSpecificSoftwareField()"';
                     } else {
                        $clickevent = rtrim($clickevent, '"') . '; toggleSpecificSoftwareField();"';
                     }
                  }
               }
               
               $strHtml .= '<li class="check-radio" >';
               // $strHtml .= '<input type="radio" name="'.$field['name'].'" id="'.$option[2].'" value="'.$option[0].'" aria-labelledby="'.$field['name'].'-legend '.$field['name'].'-'.$option[0].'-label '.$field['name'].'-errors"'.$checked.'>';
               // $strHtml .= '<input type="radio" name="'.$field['name'].'" id="'.$option[2].'" value="'.$option[0].'" '.$checked.'>';
               $strHtml .= '<input type="radio" name="'.$field['name'].'" id="'.$option[2].'" value="'.$option[0].'" '.$checked . $clickevent .'>';
               $strHtml .= '<label for="'.$option[2].'">';                
               $strHtml .= $option[1].'</label>';
               // if($option[0] == '776' || $option[0] == 'OurCommunityOther'){
               if($option[0] == '776' || $option[0] == 'OurCommunityOther' || $option[0] == 'OtherPleaseSpecify' || $option[0] == 'SelfDescribe'){
                  $openTextFieldName = $option[2] . '_OpenText';
                  if($fieldName == 'inf_option_Gender' && $option[0] == '776') {
                     $openTextFieldName = 'inf_option_Gender_opentext';
                     $openTextFieldId = 'inf_option_Gender_opentext';
                  } else {
                     $openTextFieldName = $option[2] . '_OpenText';
                     $openTextFieldId = $option[2] . '_OpenText';
                  }
                  $existingValue = '';
                  if(isset($clean[$openTextFieldName])) {
                     $existingValue = $clean[$openTextFieldName];
                  } elseif(isset($clean['inf_option_Gender_776_OpenText'])) {
                     // Fallback to check alternative field name
                     $existingValue = $clean['inf_option_Gender_776_OpenText'];
                  }
                  
                  if(strlen($existingValue) > 0) {
                     // Auto-click the radio button if there's an existing value
                     $strHtml .= '<script>';
                     $strHtml .= 'setTimeout(function() {';
                     $strHtml .= '  document.getElementById("'.$option[2].'").click();';
                     $strHtml .= '}, 100);';
                     $strHtml .= '</script>';
                     // $strHtml .= '<label for="'.$option[2].'_OpenText" style="display:none"><span>"'.$option[1].'"</span></label><input type="text" name="'.$option[2].'_OpenText" id="'.$option[2].'_OpenText" value="'.htmlspecialchars($existingValue, ENT_QUOTES, 'UTF-8').'" placeHolder="Please Enter Your Answer" style="width:100%;display:none">'; 
                     $strHtml .= '<input type="text" name="'.$openTextFieldName.'" id="'.$openTextFieldId.'" value="'.htmlspecialchars($existingValue, ENT_QUOTES, 'UTF-8').'" placeholder="Please Enter Your Answer" style="width:100%;display:none">'; 
                  } else {
                  // $strHtml .= '<label for="'.$option[2].'_OpenText" style="display:none"><span>"'.$option[1].'"</span></label><input type="text" name="'.$option[2].'_OpenText" id="'.$option[2].'_OpenText" value="" placeHolder="Please Enter Your Answer" style="width:100%;display:none">'; 
                     $strHtml .= '<input type="text" name="'.$openTextFieldName.'" id="'.$openTextFieldId.'" value="" placeholder="Please Enter Your Answer" style="width:100%;display:none">'; 
                   }
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
            // Add formnovalidate attribute to Previous buttons to bypass HTML5 validation
            $formnovalidate = (strpos($field['name'], 'previous_') === 0 || $field['name'] === 'previous') ? ' formnovalidate' : '';
            $strHtml .= '<input type="submit" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$field['value'].'"'.$formnovalidate.' />';
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
   'Content-Type: application/json',
   "Authorization: Bearer va.BPVD-hqyaWR2kX5U2yHFKiaaWHRZ46Na.Kcfhew.8SYfa8D"
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
   'Content-Type: application/json',
   "Authorization: Bearer va.BPVD-hqyaWR2kX5U2yHFKiaaWHRZ46Na.Kcfhew.8SYfa8D"
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
      'Content-Type: application/json',
      "Authorization: Bearer va.BPVD-hqyaWR2kX5U2yHFKiaaWHRZ46Na.Kcfhew.8SYfa8D"
      ]);
      $response = curl_exec($curl);
      curl_close($curl);
      $contactData=[
         "User Status" => 'Member'
      ];
   }
   return $user_role;
 }

 /**
 * Apply Keap tags for registration step completion
 */
function applyKeapStepTag($step, $userEmail) {
   try {
        // Check if files exist before including
        $isdk_path = get_template_directory() . '/../infusion/isdk.php';
        $properties_path = get_template_directory() . '/../infusion/myproperties.ini';
        
        if (!file_exists($isdk_path)) {
            error_log("iSDK file not found at: $isdk_path");
            return false;
        }
        
        if (!file_exists($properties_path)) {
            error_log("Properties file not found at: $properties_path");
            return false;
        }
        
        // Include the Keap integration files only if class doesn't exist
        if (!class_exists('isdk')) {
            include_once $isdk_path;
        }
        
        // Parse properties file
        $properties_ini = parse_ini_file($properties_path);
        if (!$properties_ini) {
            error_log("Failed to parse properties file");
            return false;
        }
        
        // Initialize Keap app
        $app = new isdk();
        
        if ($app->cfgCon("connectionName")) {
            // Find contact by email
            $returnFields = ['Id', 'FirstName', 'LastName', 'Email'];
            $conDat = $app->findByEmail($userEmail, $returnFields);
            
            if (!empty($conDat) && is_array($conDat) && isset($conDat[0]['Id'])) {
                $contactId = $conDat[0]['Id'];
                
                // Apply step completion tag
                $stepTag = isset($properties_ini['step_' . $step . '_completed']) ? 
                           $properties_ini['step_' . $step . '_completed'] : null;
                
                if ($stepTag) {
                    $tagResult = $app->grpAssign($contactId, $stepTag);
                    error_log("Step completion tag applied for step $step: " . print_r($tagResult, true));
                }
                
                // Apply phase tag
               //  $phaseTag = getPhaseTagForStep($step, $properties_ini);
               //  if ($phaseTag) {
               //      $phaseTagResult = $app->grpAssign($contactId, $phaseTag);
               //      error_log("Phase tag applied for step $step: " . print_r($phaseTagResult, true));
               //  }
                
                // Update registration status
                $statusUpdate = ['_RegistrationStatus' => 'In Progress - Step ' . substr($step, -1)];
                $app->updateCon($contactId, $statusUpdate);
                
                return true;
            } else {
                error_log("Contact not found for email: $userEmail when applying step $step tag");
            }
        } else {
            error_log("Keap connection failed when applying step $step tag");
        }
    } catch (Exception $e) {
        error_log("Error in applyKeapStepTag: " . $e->getMessage());
    }
    
    return false;
}

/**
 * Get the appropriate phase tag for a given step
 */
function getPhaseTagForStep($step, $properties_ini) {
    $phaseMapping = array(
        'step1' => 'phase_basic_info',
        'step2' => 'phase_access_needs', 
        'step3' => 'phase_technologies',
        'step4' => 'phase_demographics',
        'step5' => 'phase_community_agreement',
        'step6' => 'phase_privacy_consent',
        'step7' => 'phase_identity_verification',
        'step8' => 'phase_login_creation',
        'step9' => 'phase_complete'
    );
    
    if (isset($phaseMapping[$step])) {
        $phaseKey = $phaseMapping[$step];
        return isset($properties_ini[$phaseKey]) ? $properties_ini[$phaseKey] : null;
    }
    
    return null;
}
?>
?>