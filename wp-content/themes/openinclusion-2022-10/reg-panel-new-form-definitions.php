<?php
///////////////////////////  Form Definitions ///////////////////////////////
$panelForm = array();

add_action( 'init', 'openinc_create_form_defs_v3', 3 );

// create taxonomy for courses
function openinc_create_form_defs_v3() {
   global $panelForm;

   // Contact form 
   $panelForm = array(
      'cont-id' => '',
      'cont-class' => 'contact panel-contact',
      'form-id' => 'contactform',
      //'submit-to' => 'https://ly190.infusionsoft.com/app/form/process/2af0196854e027b0d90cd68a831fef2f',			   
      //'submit-to' => 'http://localhost/openinclusion/open-registration-panel-v3/',
      'client-val' => true,
      'mand-ind' => '*',
      'success-img' => '',
      'error-img' => '',
      'error-sect-id' => 'sub-errs',
      'error-sect-class' => '',
      'error-sect-hdr' => __( 'Submission Problems', 'openinclusion' ),
      'error-sect-hdr-level' => 2,
      'error-sect-intro' => '<p>'.__( 'We were not able to process your enquiry. Please review the following items and check what you entered', 'openinclusion' ).'</p>',
      'nonce-name' => 'panelnonce',
      'sq-reqd' => false,
      'sq-id' => 'sq',
      'sq-label' => __( 'Please answer this question:', 'openinclusion' ).' %1$s <span class="clarify">('.__( 'Helps stop spam', 'openinclusion' ).')</span>',
      'fields' => array(
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">1. Name and Contact details</h2><h4 tabindex="1">The questions with an asterisk * are mandatory</h4>',
            'validation' => array(
            )
         ),
         array(
            'label' => __( 'What is your first name?', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_FirstName',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your first name', 'openinclusion' )),
                  array('len', __( 'Your first name can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'What is your last name?<div><span style="font-size:12px">If you do not have a last name please write "not applicable"</span></div>', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_LastName',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('len', __( 'Your last name can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'What is your email address?<br/><span style="font-size:12px">If you do not have an email address or want to sign up without an email please contact us and we can do this for you.</span>', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_Email',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your email address', 'openinclusion' )),
                  array('email', __( 'Please check the email address format', 'openinclusion' )),
                  array('len', __( 'Your email address can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         array(
            'label' => __( 'Please re-enter your email address:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_re_Email',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your email address', 'openinclusion' )),
                  array('email', __( 'Please check the email address format', 'openinclusion' )),
                  array('len', __( 'Your email address can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'What is your phone number?', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_Phone2',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('len', __( 'Your mobile phone number can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         array(
            'label' => __( '', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_countryphonecode',
            'li-class' => 'clear',
            'options' => get_phoneCodes(),
            'validation' => array(),
         ),         

         array(
            'label' => __( 'What is your preferred contact method', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_PreferToContact',
            'li-class' => 'clear',
            'options' => get_contact_methods(),
            'validation' => array(
               array('reqd', __( 'Please select at least one contact method', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'Please let us know what your preferred contact methods are:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_PreferToContactOthers',
            'li-id' => 'PreferToContactOthers',
            'li-class' => 'clear',
            'inlineStyle' => 'display:none',
            'options' => get_contact_methods_others(),
            'validation' => array(/* 
               array('reqd', __( 'Please select at least one contact method', 'openinclusion' )),
             */)
         ),
         /*array(
            'label' => __( 'Post code', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_PostalCode',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your post code', 'openinclusion' )),
                  array('len', __( 'Your name can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         array(
            'label' => __( 'What region do you live in?', 'openinclusion' ),
            'type' => 'radiogroup-inf',
            'name' => 'inf_option_Whatregiondoyoulivein',
            'li-class' => 'columns clear',
            'options' => get_regions(),
            'validation' => array(
               array('reqd', __( 'Please select one region', 'openinclusion' )),
            )
         ), 
		 array(
            'label' => __( 'If you have been referred to the Open Inclusion Panel, please let us know who by.', 'openinclusion' ),
            'type' => 'textarea',
            'name' => 'info_custom_ReferredtotheOpenInclusionPanel',
            'li-class' => 'clear',
            'maxlen' => 65000,
            'validation' => array(
               array('len', __( 'Your comments can only be %1$d characters long', 'openinclusion' )),
            )
         ),*/

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">2. Create your online community login details</h2>',
            'validation' => array(
            )
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header3',
            'li-class' => 'clear',
            'value' => '<h3 tabindex="0">If you dont want to use the online community website please leave this section blank</h3>',
            'validation' => array(
            )
         ),
         array(
            'label' => __( 'Please create your user name for the online Open Inclusion community: <br/><span style="font-size:12px">The username you create will be what shows on your profile. We recommend using your first and last name.</span>', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_UserName',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your user name', 'openinclusion' )),
                  array('len', __( 'Your first name can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'Please create a password for the online Open Inclusion community:<br/><span style="font-size:12px">Passwords need to have 8 characters minimum and have a mix of letters and numbers.</span>', 'openinclusion' ),
            'type' => 'password',
            'name' => 'inf_field_Password',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your Password', 'openinclusion' )),
                  array('len', __( 'Your first name can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'Please re-enter your password:', 'openinclusion' ),
            'type' => 'password',
            'name' => 'inf_field_Password_reenter',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please re-supply your Password', 'openinclusion' )),
                  array('len', __( 'Your first name can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">3. About you </h2>',
            'validation' => array(
            )
         ),
         array(
            'label' => __( 'What country do you live in?', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_country',
            'li-class' => 'short clear',
            'options' => get_countries(),
            'validation' => array(
                  array('reqd', __( 'Please supply your country', 'openinclusion' ))
            )
         ),

         array(
            'label' => __( 'What region or state are you in?', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_region',
            'li-class' => 'short clear',
            'options' => get_regions(),
            'validation' => array(
                  array('reqd', __( 'Please supply your country', 'openinclusion' ))
            )
         ),

         array(
            'label' => __( 'What year were you born?<br/><span style="font-size:12px">You need to be 18 or over to join the Open Inclusion community and take part in research</span>', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_custom_YearBorn',
            'li-class' => 'short clear',
            'maxlen' => 4,
            'validation' => array(
                  array('reqd', __( 'Please tell us which year you were born in', 'openinclusion' )),
                  array('len', __( 'Your birth year can only be %1$d characters long', 'openinclusion' )),
                  array('int', __( 'Your birth year can only be numeric', 'openinclusion' )),
            )
         ),
        

         array(
            'label' => __( 'Which gender do you most identify with?', 'openinclusion' ),
            'type' => 'radiogroup-inf',
            'name' => 'inf_option_Gender',
            'li-class' => 'short clear',
            'options' => get_genders(),
            'validation' => array(
               array('reqd', __( 'Please select one gender', 'openinclusion' )),
            )
         ),
         /*
         

         array(
            'label' => __( 'Ethnicity', 'openinclusion' ),
            'type' => 'radiogroup-inf',
            'name' => 'inf_option_Ethnicity',
            'li-class' => 'clear',
            'options' => get_ethnicities(),
            'validation' => array(
            )
         ),

         array(
            'label' => __( 'Check any that apply to you', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'inf_option_conditions',
            'li-class' => 'columns clear',
            'options' => get_conditions(),
            'validation' => array(
               array('reqd', __( 'Please select at least one condition or impairment', 'openinclusion' )),
            )
         ),

         array(
            'label' => __( 'Use this box if you wish to tell more about any of the above', 'openinclusion' ),
            'type' => 'textarea',
            'name' => 'inf_custom_ImpairmentInfo',
            'li-class' => 'clear',
            'maxlen' => 65000,
            'validation' => array(
               array('len', __( 'Your comments can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         array(
            'label' => __( 'Tell us what enabling support you use and what access needs you have', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'inf_option_support',
            'li-class' => 'columns clear',
            'options' => get_supports(),
            'validation' => array(
            )
         ),

         array(
            'label' => __( 'Use this box to tell us more about any of the above and/or to tell us about what reasonable adjustments you need', 'openinclusion' ),
            'type' => 'textarea',
            'name' => 'inf_custom_AccessNeedinfo',
            'li-class' => 'clear',
            'maxlen' => 65000,
            'validation' => array(
               array('len', __( 'Your comments can only be %1$d characters long', 'openinclusion' )),
            )
         ),*/

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">4. Accessibility needs and assistive technology</span></h2>',
            'validation' => array(
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4_1',
            'li-class' => 'clear',
            'value' => '<h3 tabindex="0">Do any of the following have a significant impact on your daily activities?</span></h3>',
            'validation' => array(
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4_1_1',
            'li-class' => 'clear',
            'value' => '<h4 tabindex="0">Please let us know if you have any long-term accessibility needs or if you identify as having any specific disability.<br/>Please choose all that apply and add further details where possible.</span></h4>',
            'validation' => array(
            )
         ),

         array(
            'label' => __( 'Sensory needs', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_SensoryNeeds[]',
            'li-class' => 'clear',
            'options' => get_sensory_needs(),
            'validation' => array(               
            )
         ),

         array(
            'label' => __( 'Physical needs', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_PhysicalNeeds',
            'li-class' => 'clear',
            'options' => get_physical_needs(),
            'validation' => array(               
            )
         ),

         array(
            'label' => __( 'Cognitive and mental health needs', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_CognitiveAndMentalhealthNeeds',
            'li-class' => 'clear',
            'options' => get_cognitive_and_mentalhealth_needs(),
            'validation' => array(               
            )
         ),

         array(
            'label' => __( 'Communication needs', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'CommunicationNeeds',
            'li-class' => 'clear',
            'options' => get_communication_needs(),
            'validation' => array(               
            )
         ),

         array(
            'label' => __( 'Chronic health needs', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'ChronichealthNeeds',
            'li-class' => 'clear',
            'options' => get_chronichealth_needs(),
            'validation' => array(               
            )
         ),    

         array(
            'label' => __( 'Other', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'OtherNeeds',
            'li-class' => 'clear',
            'options' => get_other_needs(),
            'validation' => array(               
            )
         ),  
         
         array(
            'label' => __( 'Which one of these needs if any would you define as your primary need?</span><span class="footnote">A primary need is any need that has the most significant impact on your day to day experience', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_PrimaryNeed',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your answer for primary need', 'openinclusion' )),
                  array('len', __( 'Your primary need can only be %1$d characters long', 'openinclusion' )),
            )
         ),


         array(
            'label' => __( 'At what age did you incur this primary need?', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_Age_Bracket',
            'li-class' => 'clear',
            'options' => get_pnagegroups(),
            'validation' => array(
                  array('reqd', __( 'Please supply your answer', 'openinclusion' ))
            )
         ),

         array(
            'label' => __( 'Have you got any temporary access needs?', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_TemporaryAccessNeed',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your answer for temporary access need', 'openinclusion' )),
                  array('len', __( 'Your temporary access need can only be %1$d characters long', 'openinclusion' )),
            )
         ),


         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4_1',
            'li-class' => 'clear',
            'value' => '<h3 tabindex="0"><span>What assistive technologies or adaptive approaches (if any) do you use?</span></h3>',
            'validation' => array(
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4_1_1',
            'li-class' => 'clear',
            'value' => '<h4 tabindex="0"><span>Please choose all that apply and add further details where possible.</span></h4>',
            'validation' => array(
            )
         ),

         array(
            'label' => __( 'Digital and screen technologies, including hardware', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'DigitalandScreenTechnologies',
            'li-class' => 'clear',
            'options' => get_digitalandscreentechnologies(),
            'validation' => array(               
            )
         ), 

         array(
            'label' => __( 'Movement, canes and service animals', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'MovementCanesandServiceAnimals',
            'li-class' => 'clear',
            'options' => get_movementcanesandserviceanimals(),
            'validation' => array(               
            )
         ),          
         
         array(
            'label' => __( 'Communication, verbal and written preferences', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'CommunicationPreferences',
            'li-class' => 'clear',
            'options' => get_communicationpreferences(),
            'validation' => array(               
            )
         ),           
         
         array(
            'label' => __( 'Personal support and home', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'PersonalSupportandHome',
            'li-class' => 'clear',
            'options' => get_personalsupportandhome(),
            'validation' => array(               
            )
         ),            
         
         array(
            'label' => __( 'Other', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'OtherTechnologies',
            'li-class' => 'clear',
            'options' => get_othertechnologies(),
            'validation' => array(               
            )
         ),  

         //  *******************
         // Final Questions
         //  *********************
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header5',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">5. Terms, conditions and community agreement</span></h2>',
            'validation' => array(
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4_1',
            'li-class' => 'clear',
            'value' => '<h3 tabindex="0"><span>Open Inclusion community and research terms and conditions</span></h3>',
            'validation' => array(
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'legal1',
            'li-class' => 'clear',
            'value' => '<p>&#x2022;  From time to time Open Inclusion Ltd may invite you to take part in consumer research, focus groups, mystery shopping, user testing, or other projects that align with your stated preferences.</p><p>&#x2022;  The information you share will enable us to choose various opportunities to send you. We want the right people to take part in our projects – there is no obligation to take part – you just opt "in" to any opportunity you want to join.</p><p>&#x2022;  All work Open does is carried out in accordance with the Market Research Society Code of Conduct.</p><p>&#x2022;  People get the best out of forums when everyone is treated with respect and courtesy. We ask everyone to interact in a civilized and tasteful manner while using the Open Inclusion community website, surveys and forums. For full terms and conditions see our website terms and conditions page.</p>',
            'validation' => array(
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4',
            'li-class' => 'clear',
            'value' => '<h3 tabindex="0">General Data Protection Regulations - Privacy statement.</span></h3>',
            'validation' => array(
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'legal2',
            'li-class' => 'clear',
            'value' => '<p>&#x2022;  The information you give on this form will only be used by Open Inclusion and the people who work for us to enable us to match participants to research studies or work opportunities. Your details will not be shared with any other individuals, organisations, or third parties.</p><p>&#x2022;  Your details will be kept safe, and Open Inclusion will take all reasonable technical and organisational precautions to prevent the loss, misuse, or alteration of the information you have given. Data will be stored in an encrypted database.</p><p>&#x2022;  To amend any of the information, we hold or have it removed from our database, please email <a href="mailto:contact@openinclusion.com">contact@openinclusion.com</a>.</p><p>&#x2022;  If you would like to know more or understand your data protection rights, please look at our privacy policy.</p>',
            'validation' => array(
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4',
            'li-class' => 'clear',
            'value' => '<h3 tabindex="0">Consent</span></h3>',
            'validation' => array(
            )
         ),

         array(
            'label' => __( 'Please provide consent to all areas below to be an Open Inclusion Community member', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'inf_option_legals',
            'li-class' => 'clear',
            'options' => get_legals(),
            'validation' => array(
                  array('reqd-all', __( 'You must confirm your consent to join our panel.', 'openinclusion' )),
            )
         ),
      array(
         'name' => 'inf_form_xid',
         'type' => 'hidden',
         'value' => '2af0196854e027b0d90cd68a831fef2f',
      ),

      array(
         'name' => 'inf_form_name',
         'type' => 'hidden',
         'value' => 'Open Inclusion Panel Reg v2',
      ),

      array(
         'name' => 'infusionsoft_version',
         'type' => 'hidden',
         'value' => '1.49.0.36',
      ),


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         array(
            'name' => 'submit',
            'type' => 'submit',
            'li-class' => 'submit',
            'value' => __('Submit form', 'openinclusion' )
         ),
      ),


      
   );
}



function getProfileFields() {
   // Contact form 
   $profileFields = array(
      'fields' => array(
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">1. Name and Contact details</h2>',
         ),
         array(
            'label' => __( 'First name:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_FirstName',
            'li-class' => 'short clear',
         ),
         array(
            'label' => __( 'Last name:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_LastName',
            'li-class' => 'short clear',
         ),
         array(
            'label' => __( 'Email:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_Email',
            'li-class' => 'short clear',
         ),

         array(
            'label' => __( 'Phone number:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_Phone2',
            'li-class' => 'short clear',
         ),

         array(
            'label' => __( 'Preferred contact method:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_PreferToContact',
            'li-class' => 'short clear',
            'options' => get_contact_methods(),
         ),
         array(
            'label' => __( 'Other preferred contact methods:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_PreferToContactOthers',
            'li-id' => 'PreferToContactOthers',
            'li-class' => 'short clear',
            'inlineStyle' => 'display:none',
            'options' => get_contact_methods_others(),
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">2. Your online community login details</h2>',
         ),
         array(
            'label' => __( 'Your user name for the online Open Inclusion community:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_UserName',
            'li-class' => 'short clear',
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">3. About you </h2>',
            'validation' => array(
            )
         ),
         array(
            'label' => __( 'Country you live in:', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_country',
            'li-class' => 'short clear',
            'options' => get_countries(),
         ),

         array(
            'label' => __( 'Region or state:', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_region',
            'li-class' => 'short clear',
            'options' => get_regions(),
         ),

         array(
            'label' => __( 'Year were you born:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_custom_YearBorn',
            'li-class' => 'short clear',
         ),
        

         array(
            'label' => __( 'Gender:', 'openinclusion' ),
            'type' => 'radiogroup-inf',
            'name' => 'inf_option_Gender',
            'li-class' => 'short clear',
            'options' => get_genders(),
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">4. Accessibility needs and assistive technology</span></h2>',
         ),

         array(
            'label' => __( 'Sensory needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_SensoryNeeds',
            'li-class' => 'short clear',
            'options' => get_sensory_needs(),
         ),

         array(
            'label' => __( 'Physical needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_PhysicalNeeds',
            'li-class' => 'short clear',
            'options' => get_physical_needs(),
         ),

         array(
            'label' => __( 'Cognitive and mental health needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_CognitiveAndMentalhealthNeeds',
            'li-class' => 'short clear',
            'options' => get_cognitive_and_mentalhealth_needs(),
         ),

         array(
            'label' => __( 'Communication needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_CommunicationNeeds',
            'li-class' => 'short clear',
            'options' => get_communication_needs(),
         ),

         array(
            'label' => __( 'Chronic health needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_ChronichealthNeeds',
            'li-class' => 'short clear',
            'options' => get_chronichealth_needs(),
         ),    

         array(
            'label' => __( 'Other:', 'openinclusion' ),
            'type' => 'text',
            'name' => '_OtherNeedsOtherPleaseSpecify_OpenText',
            'li-class' => 'short clear',
            'options' => get_other_needs(),
         ),  
         
         array(
            'label' => __( 'Your primary need:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_PrimaryNeed',
            'li-class' => 'short clear',
         ),


         array(
            'label' => __( 'Age bracket you incur your primary need:', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_Age_Bracket',
            'li-class' => 'short clear',
            'options' => get_pnagegroups(),
         ),

         array(
            'label' => __( 'Temporary access needs:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_TemporaryAccessNeed',
            'li-class' => 'short clear',
         ),


         array(
            'label' => __( 'Digital and screen technologies, including hardware:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_DigitalandScreenTechnologies',
            'li-class' => 'short clear',
            'options' => get_digitalandscreentechnologies(),
         ), 

         array(
            'label' => __( 'Movement, canes and service animals:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_MovementCanesandServiceAnimals',
            'li-class' => 'short clear',
            'options' => get_movementcanesandserviceanimals(),
         ),          
         
         array(
            'label' => __( 'Communication, verbal and written preferences:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_CommunicationPreferences',
            'li-class' => 'short clear',
            'options' => get_communicationpreferences(),
         ),           
         
         array(
            'label' => __( 'Personal support and home:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_PersonalSupportandHome',
            'li-class' => 'short clear',
            'options' => get_personalsupportandhome(),
         ),            
         
         array(
            'label' => __( 'Other', 'openinclusion' ),
            'type' => 'text',
            'name' => '_OtherTechnologiesOtherPleaseSpecify_OpenText',
            'li-class' => 'short clear',
            'options' => get_othertechnologies(),
         ),  

         array(
            'name' => 'submit',
            'type' => 'submit',
            'li-class' => 'submit',
            'value' => __('Edit Profile', 'openinclusion' )
         ),
      ),
     
   );
   return $profileFields;   
}


function getProfileEditFields() {
   // Contact form 
   $profileFields = array(
      'cont-id' => '',
      'cont-class' => 'contact panel-contact',
      'form-id' => 'contactform',
      //'submit-to' => 'https://ly190.infusionsoft.com/app/form/process/2af0196854e027b0d90cd68a831fef2f',			   
      //'submit-to' => 'http://localhost/openinclusion/open-registration-panel-v3/',
      'client-val' => true,
      'mand-ind' => '*',
      'success-img' => '',
      'error-img' => '',
      'error-sect-id' => 'sub-errs',
      'error-sect-class' => '',
      'error-sect-hdr' => __( 'Submission Problems', 'openinclusion' ),
      'error-sect-hdr-level' => 2,
      'error-sect-intro' => '<p>'.__( 'We were not able to process your enquiry. Please review the following items and check what you entered', 'openinclusion' ).'</p>',
      'nonce-name' => 'panelnonce',
      'sq-reqd' => false,
      'sq-id' => 'sq',
      'sq-label' => __( 'Please answer this question:', 'openinclusion' ).' %1$s <span class="clarify">('.__( 'Helps stop spam', 'openinclusion' ).')</span>',
      'fields' => array(
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">1. Name and Contact details</h2><h4 tabindex="1">The questions with an asterisk * are mandatory</h4>',
            'validation' => array(
            )
         ),
         array(
            'label' => __( 'First name:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_FirstName',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your first name', 'openinclusion' )),
                  array('len', __( 'Your first name can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'Last name:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_LastName',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('len', __( 'Your last name can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         array(
            'label' => __( 'Phone number:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_Phone2',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('len', __( 'Your mobile phone number can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         array(
            'label' => __( '', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_countryphonecode',
            'li-class' => 'clear',
            'options' => get_phoneCodes(),
            'validation' => array(),
         ),         

         array(
            'label' => __( 'Preferred contact method:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_PreferToContact',
            'li-class' => 'clear',
            'options' => get_contact_methods(),
            'validation' => array(
               array('reqd', __( 'Please select at least one contact method', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'Other preferred contact methods:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_PreferToContactOthers',
            'li-id' => 'PreferToContactOthers',
            'li-class' => 'clear',
            'inlineStyle' => 'display:none',
            'options' => get_contact_methods_others(),
            'validation' => array()
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">2. Your online community login details</h2>',
         ),
         array(
            'label' => __( 'Your user name for the online Open Inclusion community:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_UserName',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your user name', 'openinclusion' )),
                  array('len', __( 'Your first name can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         array(
            'label' => __( 'Password:<br/><span style="font-size:12px">Passwords need to have 8 characters minimum and have a mix of letters and numbers.</span>', 'openinclusion' ),
            'type' => 'password',
            'name' => 'inf_field_Password',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your Password', 'openinclusion' )),
                  array('len', __( 'Your first name can only be %1$d characters long', 'openinclusion' )),
            )
         ),         

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">3. About you </h2>',
            'validation' => array(
            )
         ),
         array(
            'label' => __( 'Country you live in:', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_country',
            'li-class' => 'short clear',
            'options' => get_countries(),
            'validation' => array(
                  array('reqd', __( 'Please supply your country', 'openinclusion' ))
            )
         ),

         array(
            'label' => __( 'Region or state:', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_region',
            'li-class' => 'short clear',
            'options' => get_regions(),
            'validation' => array(
                  array('reqd', __( 'Please supply your country', 'openinclusion' ))
            )
         ),

         array(
            'label' => __( 'Year were you born:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_custom_YearBorn',
            'li-class' => 'short clear',
            'maxlen' => 4,
            'validation' => array(
                  array('reqd', __( 'Please tell us which year you were born in', 'openinclusion' )),
                  array('len', __( 'Your birth year can only be %1$d characters long', 'openinclusion' )),
                  array('int', __( 'Your birth year can only be numeric', 'openinclusion' )),
            )
         ),        

         array(
            'label' => __( 'Gender:', 'openinclusion' ),
            'type' => 'radiogroup-inf',
            'name' => 'inf_option_Gender',
            'li-class' => 'short clear',
            'options' => get_genders(),
            'validation' => array(
               array('reqd', __( 'Please select one gender', 'openinclusion' )),
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">4. Accessibility needs and assistive technology</span></h2>',
         ),

         array(
            'label' => __( 'Sensory needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_SensoryNeeds[]',
            'li-class' => 'clear',
            'options' => get_sensory_needs(),
            'validation' => array(               
            )
         ),

         array(
            'label' => __( 'Physical needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_PhysicalNeeds',
            'li-class' => 'clear',
            'options' => get_physical_needs(),
            'validation' => array(               
            )
         ),

         array(
            'label' => __( 'Cognitive and mental health needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_CognitiveAndMentalhealthNeeds',
            'li-class' => 'clear',
            'options' => get_cognitive_and_mentalhealth_needs(),
            'validation' => array(               
            )
         ),

         array(
            'label' => __( 'Communication needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'CommunicationNeeds',
            'li-class' => 'clear',
            'options' => get_communication_needs(),
            'validation' => array(               
            )
         ),

         array(
            'label' => __( 'Chronic health needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'ChronichealthNeeds',
            'li-class' => 'clear',
            'options' => get_chronichealth_needs(),
            'validation' => array(               
            )
         ),   

         array(
            'label' => __( 'Other', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'OtherNeeds',
            'li-class' => 'clear',
            'options' => get_other_needs(),
            'validation' => array(               
            )
         ), 
         
         array(
            'label' => __( 'Your primary need:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_PrimaryNeed',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your answer for primary need', 'openinclusion' )),
                  array('len', __( 'Your primary need can only be %1$d characters long', 'openinclusion' )),
            )
         ),


         array(
            'label' => __( 'Age bracket you incur your primary need:', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_Age_Bracket',
            'li-class' => 'clear',
            'options' => get_pnagegroups(),
            'validation' => array(
                  array('reqd', __( 'Please supply your answer', 'openinclusion' ))
            )
         ),

         array(
            'label' => __( 'Temporary access needs:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_TemporaryAccessNeed',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your answer for temporary access need', 'openinclusion' )),
                  array('len', __( 'Your temporary access need can only be %1$d characters long', 'openinclusion' )),
            )
         ),


         array(
            'label' => __( 'Digital and screen technologies, including hardware:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'DigitalandScreenTechnologies',
            'li-class' => 'clear',
            'options' => get_digitalandscreentechnologies(),
            'validation' => array(               
            )
         ), 

         array(
            'label' => __( 'Movement, canes and service animals:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'MovementCanesandServiceAnimals',
            'li-class' => 'clear',
            'options' => get_movementcanesandserviceanimals(),
            'validation' => array(               
            )
         ),         
         
         array(
            'label' => __( 'Communication, verbal and written preferences:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'CommunicationPreferences',
            'li-class' => 'clear',
            'options' => get_communicationpreferences(),
            'validation' => array(               
            )
         ),  
         array(
            'label' => __( 'Personal support and home:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'PersonalSupportandHome',
            'li-class' => 'clear',
            'options' => get_personalsupportandhome(),
            'validation' => array(               
            )
         ),           
         
         array(
            'label' => __( 'Other', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'OtherTechnologies',
            'li-class' => 'clear',
            'options' => get_othertechnologies(),
            'validation' => array(               
            )
         ),   

         array(
            'name' => 'submit',
            'type' => 'submit',
            'li-class' => 'submit',
            'value' => __('Submit form', 'openinclusion' )
         ),
      ),
     
   );
   return $profileFields;   
}
?>