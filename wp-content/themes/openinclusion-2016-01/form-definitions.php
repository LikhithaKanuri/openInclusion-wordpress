<?php

///////////////////////////  Form Definitions ///////////////////////////////
$panelForm = array();

add_action( 'init', 'openinc_create_form_defs', 3 );

// create taxonomy for courses
function openinc_create_form_defs() {
   global $panelForm;

   // Contact form 
   $panelForm = array(
      'cont-id' => '',
      'cont-class' => 'contact panel-contact',
      'form-id' => 'contactform',
      'submit-to' => 'https://ly190.infusionsoft.com/app/form/process/2af0196854e027b0d90cd68a831fef2f',			   
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
            'value' => '<h2 tabindex="0">Section 1 of 3 - Contact Details</h2>',
            'validation' => array(
            )
         ),
         array(
            'label' => __( 'First name', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_FirstName',
            'li-class' => 'short clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your first name', 'openinclusion' )),
                  array('len', __( 'Your first name can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'Last name', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_LastName',
            'li-class' => 'short',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your last name', 'openinclusion' )),
                  array('len', __( 'Your last name can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'Email', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_Email',
            'li-class' => 'short clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please supply your email address', 'openinclusion' )),
                  array('email', __( 'Please check the email address format', 'openinclusion' )),
                  array('len', __( 'Your email address can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'Phone (Landline)', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_Phone1',
            'li-class' => 'short clear',
            'maxlen' => 250,
            'validation' => array(
                  array('len', __( 'Your landline phone number can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'Phone (Mobile)', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_Phone2',
            'li-class' => 'short',
            'maxlen' => 250,
            'validation' => array(
                  array('len', __( 'Your mobile phone number can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'Tell us how you would prefer us to contact you. (Tick as many boxes as you wish)', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_PreferToContact',
            'li-class' => 'clear',
            'options' => get_contact_methods(),
            'validation' => array(
               array('reqd', __( 'Please select at least one contact method', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'Post code', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_PostalCode',
            'li-class' => 'short clear',
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
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">Section 2 of 3 - Personal Details</h2>',
            'validation' => array(
            )
         ),

         array(
            'label' => __( 'Gender', 'openinclusion' ),
            'type' => 'radiogroup-inf',
            'name' => 'inf_option_Gender',
            'li-class' => 'short clear',
            'options' => get_genders(),
            'validation' => array(
               array('reqd', __( 'Please select one gender', 'openinclusion' )),
            )
         ),

         array(
            'label' => __( 'What year were you born? (You must be over 18)', 'openinclusion' ),
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
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header3',
            'li-class' => 'clear',
            'value' => '<h2 tabindex="0">Section 3 of 3 - Finally some legal stuff<span class="srdr"> Important information follows this heading text.</span></h2>',
            'validation' => array(
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'legal1',
            'li-class' => 'clear',
            'value' => '<p>From time to time Open Inclusion Ltd may invite you to take part in consumer research, focus groups, mystery shopping, user testing or other projects.</p><p>The information you have given will enable us to choose the right people to take part in the project - there is no obligation to take part - you just say \'yes\' or \'no\'.</p><p>All of this work is carried out in accordance with the <a href="https://www.mrs.org.uk/pdf/mrs%20code%20of%20conduct%202014.pdf">Market Research Society Code of Conduct (external PDF)</a>.</p>',
            'validation' => array(
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4',
            'li-class' => 'clear',
            'value' => '<h3 tabindex="0">Data Protection Act - Privacy statement<span class="srdr"> Important information follows this heading text.</span></h3>',
            'validation' => array(
            )
         ),

         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'legal2',
            'li-class' => 'clear',
            'value' => '<p>The information you give on this form will only be used by Open Inclusion Ltd to enable them to match research criteria specified by their clients. It will not be shared with any other individuals or organisations.</p><p>Open Inclusion Ltd will take all reasonable technical and organisational precautions to prevent the loss, missue or alteration of the information you have given, and it will be stored in an encrypted database.</p><p>To amend any of the information we hold or have it removed from the database, please contact <a href="mailto:contact@openinclusion.com">contact@openinclusion.com</a>.</p>',
            'validation' => array(
            )
         ),

         array(
            'label' => __( 'I have read the above statements and understand that:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'inf_option_legals',
            'li-class' => 'clear',
            'options' => get_legals(),
            'validation' => array(
                  array('reqd-all', __( 'You must confirm both statements to join our panel.', 'openinclusion' )),
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
            'value' => __('Submit', 'openinclusion' )
         ),
      ),


      
   );
   
   
}
?>
