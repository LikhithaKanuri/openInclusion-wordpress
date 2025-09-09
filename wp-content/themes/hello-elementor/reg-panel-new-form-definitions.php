<?php
///////////////////////////  Form Definitions ///////////////////////////////
$panelForm = array();

add_action( 'init', 'openinc_create_form_defs_v3', 3 );

// create taxonomy for courses
function openinc_create_form_defs_v3() {
   global $panelForm;
   $loginPath = "";
   if(isset($_SERVER['HTTP_HOST'])) {
      if($_SERVER['HTTP_HOST'] == 'localhost') {
         $loginPath = "http://" . $_SERVER['HTTP_HOST']."/openinclusion/login/";
      }
      else {
         $loginPath = "https://" . $_SERVER['HTTP_HOST']."/login/";
      }         
   }
   else {
      $loginPath = "https://staging4.openinclusion.com/login/";
   }
   $current_user = wp_get_current_user();
   $sub_object = $current_user->{'data'};
   $key_exists = property_exists($sub_object, 'user_login');
   } 
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
         // array(
         //    'label' => '',
         //    'type' => 'other-html',
         //    'name' => 'banner1',
         //    'li-class' => 'clear',
         //    'value' => '<h2>Open Inclusion Community Registration</h2><span class="text">Thank you for your interest in registering to become part of the Open Inclusion disability and age-inclusive community. Please provide a few details about yourself. This should take just a couple of minutes to complete.</span>',
         //    'validation' => array(
         //    )
         // ),

         array(
            'label' => '',
            'type'  => 'other-html',
            'name'  => 'banner1',
            'li-class' => 'clear',
            'value' => '
                <h2>Open Inclusion Community Registration</h2>
                <p>
                    Thank you for your interest in joining the Open Inclusion community! 
                    We\'re excited to welcome you to our global network, where your voice helps shape 
                    more inclusive and accessible solutions and you’ll be paid for your valuable insights.
                </p>
                <p>Joining is straightforward. There are three steps:</p>
                <p><strong>1. Provide very basic info</strong> about yourself including your name, contact details and preferences.</p>
                <p><strong>2.</strong> You will then <strong>receive a welcome email with a link</strong> you will need to click on to verify your account.</p>
                <p><strong>3. From the welcome email link, you’ll go to our registration process</strong> where we will ask you about your 
                access needs, assistive technology usage, and some broader demographic questions. 
                This will take approximately 10-15 minutes to complete.</p>
            ',
            'validation' => array()
         ),



         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'banner1',
            'li-class' => 'clear',
            'value' => '<span class="text">Required questions are marked with an asterix *</span>',
            'validation' => array(
            )
         ),         
         // array(
         //    'label' => '',
         //    'type' => 'other-html',
         //    'name' => 'header2',
         //    'li-class' => 'clear',
         //    'value' => '<h2 class="sectionhead">1. Name and Contact details</h2>',
         //    'validation' => array(
         //    )
         // ),
         array(
            'label' => __( 'What is your first name?', 'openinclusion' ),
            'label-suffix' => __( 'Please enter your name below."', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_FirstName',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please enter your first name', 'openinclusion' )),
                  array('len', __( 'Your first name can only be %1$d characters long', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'What is your last name?', 'openinclusion' ),
            'label-suffix' => __( 'Please enter your last name below."', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_LastName',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
               array('reqd', __( 'Please enter your last name', 'openinclusion' )),
               array('len', __( 'Your last name can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         array(
             'label' => '',
             'type' => 'other-html',
             'name' => 'banner1',
             'li-class' => 'clear',
             'value' => '
                 <div class="form-section-header">
                     <h4 class="section-title">Please share your contact details *</h4>
                     <p class="section-description">We need your contact details to stay in touch with you.</p>
                 </div>
             ',
             'validation' => array(
                 array('reqd', __( 'We require these contact details to be completed to join the Open Community', 'openinclusion' )),
             )
         ),


         array(
            'label' => __( 'My email address:', 'openinclusion' ),
            // 'label-suffix' => __( 'Please enter your email address and phone number.', 'openinclusion', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_Email',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please provide your email address', 'openinclusion' )),
                  array('email', __( 'Please check the email address format', 'openinclusion' )),
                  array('len', __( 'Your email address can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         array(
            'label' => __( 'Please re-enter email address:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_re_Email',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please provide your email address', 'openinclusion' )),
                  array('email', __( 'Please check the email address format', 'openinclusion' )),
                  array('len', __( 'Your email address can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         array(
            'label' => __( 'International phone number code', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_countryphonecode',
            'li-id' => 'licountryphonecode',
            'li-class' => 'clear',
            'options' => get_phoneCodes(),
            'validation' => array(),
         ),           
         
         array(
            'label' => __( 'My phone number:', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_Phone2',
            'li-id' => 'liphonenumber',
            'li-class' => 'clear',
            'maxlen' => 250,
            'validation' => array(
                  array('reqd', __( 'Please provide your phone number', 'openinclusion' )),
                  array('len', __( 'Your mobile phone number can only be %1$d characters long', 'openinclusion' )),
            )
         ),

         // array(
         //    'label' => __( 'Your user name for the online Open Inclusion community:', 'openinclusion' ),
         //    'type' => 'text',
         //    'name' => 'inf_field_UserName',
         //    'li-class' => 'short clear',
         // ),
         // array(
         //    'label' => __( 'Please create a password for the online Open Inclusion community:', 'openinclusion' ),
         //    'label-suffix' => __( 'Passwords need to have 8 characters minimum and have a mix of letters and numbers.', 'openinclusion' ),
         //    'type' => 'password',
         //    'name' => 'inf_field_Password',
         //    'li-class' => 'clear',
         //    'maxlen' => 250,
         //    'validation' => array(
         //          /* array('reqd', __( 'Please supply your Password', 'openinclusion' )), */
         //          array('len', __( 'Your first name can only be %1$d characters long', 'openinclusion' )),
         //    )
         // ),
         // array(
         //    'label' => __( 'Please re-enter your password:', 'openinclusion' ),
         //    'type' => 'password',
         //    'name' => 'inf_field_Password_reenter',
         //    'li-class' => 'clear',
         //    'maxlen' => 250,
         //    'validation' => array(
         //          /* array('reqd', __( 'Please re-supply your Password', 'openinclusion' )), */
         //          array('len', __( 'Your first name can only be %1$d characters long', 'openinclusion' )),
         //    )
         // ),

         // array(
         //    'label' => __( 'How may we contact you?<br/> <span class=preferred_text> Please select all that apply.<br/> You can update this at any time either on the community platform or by contacting us and asking us to update your preferences for you at support@openinclusion.com.</span>', 'openinclusion' ),
         //    'type' => 'chkboxgroup-inf',
         //    'name' => 'PreferToContact',
         //    'li-class' => 'short clear',
         //    'options' => get_contact_methods(),
         //    'validation' => array(
         //       array('reqd', __( 'Please select at least one contact method', 'openinclusion' )),
         //    )
         // ),

         // array(
         //    'label' => __( 'What is your most preferred contact method?', 'openinclusion' ),
         //    'type' => 'radiogroup-inf',
         //    'name' => 'PreferToContactOthers',
         //    'li-id' => 'PreferToContactOthers',
         //    'li-class' => 'clear',
         //    'inlineStyle' => 'display:none',
         //    'options' => get_contact_methods(),
         //    'validation' => array(/* 
         //       array('reqd', __( 'Please select at least one contact method', 'openinclusion' )),
         //     */)
         // ),

         array(
            'label' => __( 'What are your preferred contact methods?<br/> <span class=preferred_text> Please select all that apply.</span>', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'PreferToContact',
            'li-class' => 'short clear',
            'options' => get_contact_methods(),
            'validation' => array(/* 
               array('reqd', __( 'Please select at least one contact method', 'openinclusion' )),
             */)
         ),

         // array(
         //    'label' => '',
         //    'type' => 'other-html',
         //    'name' => 'header2',
         //    'li-class' => 'clear',
         //    'value' => '<h2 class="sectionhead">2. About you </h2>',
         //    'validation' => array(
         //    )
         // ),

         // array(
         //    'label' => __( 'What is your identity or relationship regarding disability or ageing? I am,', 'openinclusion' ),
         //    'type' => 'chkboxgroup-inf',
         //    'name' => 'RelationShip',
         //    'li-class' => 'clear',
         //    'options' => get_relationship_needs(),
         //    'validation' => array(  
         //       array('reqd', __( 'Please select atleast one option for your identity or relationship regarding disability or ageing', 'openinclusion' )),             
         //    )
         // ),

         // array(
         //    'label' => __( 'How did you find out about Open Inclusion and our inclusive insight community?', 'openinclusion' ),
         //    'type' => 'radiogroup-inf',
         //    'name' => 'OurCommunity',
         //    'li-class' => 'clear',
         //    'options' => find_out_community_needs(),
         //    'validation' => array(               
         //    )
         // ),

         // //  *******************
         // // Final Questions
         // //  *********************
         // array(
         //    'label' => '',
         //    'type' => 'other-html',
         //    'name' => 'header5',
         //    'li-class' => 'clear',
         //    'value' => '<h2 class="sectionhead">4. Terms, conditions and community agreement</span></h2>',
         //    'validation' => array(
         //    )
         // ),

         // array(
         //    'label' => '',
         //    'type' => 'other-html',
         //    'name' => 'header4_1',
         //    'li-class' => 'clear',
         //    'value' => '<h3>Open Inclusion community and research terms and conditions</h3>',
         //    'validation' => array(
         //    )
         // ),

         // array(
         //    'label' => '',
         //    'type' => 'other-html',
         //    'name' => 'header4_1',
         //    'li-class' => 'clear',
         //    'value' => '<span class=terms-and-condition-community>Welcome to Open Inclusion’s Disability and Age-Inclusive Community</span>',
         //    'validation' => array(
         //    )
         // ),
         
         // array(
         //    'label' => '',
         //    'type' => 'other-html',
         //    'name' => 'legal1',
         //    'li-class' => 'clear',
         //    'value' => '<ul class="terms">
         //       <li><b>Open Inclusion has created an online community</b> for engagement with and between community members. By completing this information you will be welcomed to the online community hub where you can find and share information about disability or age related experiences, barriers and solutions.</li>
         //       <li><b>Our community is currently only open to those currently 18 years old or older.</b>  If you are not yet 18, thank you for your interest, but we do not currently extend to your age range.  Please come back in a few years when you are 18.</li>
         //       <li><b>If you wish to engage in paid research opportunities you will be asked to provide some more detailed information</b> on the community platform about your access needs, assistive technologies, adaptive approaches, and other demographics.</li>
         //       <li><b>The additional information you will be asked to provide allows us to make sure that we better understand you and your relevant experiences.</b>We can then invite you to research suited to you and balance participation in a way that best represents the breadth of experiences across the community for each project. </li>
         //       <li><b>Paid research opportunities can include:</b> mystery shopping, surveys, interviews, product or service user testing, transport journey testing, interviews or focus groups. Most are conducted online, although depending on where you live, we do have some in-person consumer research and client engagement opportunities also. </li>
         //       <li><b>There is never an obligation for you to take part in any research </b> that you are invited to. You can "opt in" to any opportunities that you wish to join, or simply wait for another one that better suits you.</li>
         //       <li><b>Equally, there is no obligation for Open to provide you with research</b> although we wish to engage everyone in our community. We select participants from people who are interested in taking part,   balancing the range of characteristics and different regions or other demographics as suited to the project. You may not always get selected for projects you would like to be part of. Please ask again next time. We love that you wish to be involved. </li>
         //       <li><b>We prefer selecting community members who are Open Verified</b> particularly for more involved, higher paid research. Some projects may be only available to Open Verified members. These members have shown us identification or met with us (online or in person) so that we are sure that they are who they say they are, and have genuine lived experiences as described to us. </li>
         //       <li><b>At Open we expect and ask that everyone is treated with respect and courtesy at all times</b> including other community members, research participants, researchers, or anyone else involved in the work. We ask everyone to interact in a considerate and kind manner at all times, including while using the Open Inclusion community hub, or in any other digital, phone based or in-person surveys, forums or engagement. We reserve the right to expel anyone from research or the community who behaves in a way that breaches this.</li>
         //       <li><b>We are conscious that we can err also.</b> If you ever have any constructive feedback or a complaint about your experiences with Open Inclusion please contact <a href="feedback@openinclusion.com"> feedback@openinclusion.com </a>. This goes directly to our leadership team who will take steps to understand and address the issue, both for you and for future engagements. We always appreciate learning how we can do better. </li>
         //       <li><b>All research we conduct is carried out in accordance with the current <a href="https://www.mrs.org.uk/standards/code-of-conduct">Market Research Society’s Code of Conduct. </a></b></li>
         //       <li><b>Privacy and General Data Protection Regular protections </b></li>
         //       <ul class="sub-list">
         //          <li>
         //             <b>Your details will not be shared with any other individuals, organisations, or third parties.</b> The information you provide through this registration process will only be used by Open Inclusion and the people who work for us. It will be used to help us match participants to research studies or other engagement opportunities aligned to your interests.
         //          </li>
         //          <li>
         //             <b>Your personal data will be kept safe.</b> Open Inclusion will take all reasonable technical and organisational precautions to prevent the loss, misuse, or alteration of the information you have given. Data is stored in an encrypted database. 
         //          </li>
         //          <li>
         //             <b>You can unsubscribe</b> from receiving research invitations or any other emails from us at any time. To do so just hit the ‘unsubscribe’ link which is at the bottom of all emails.
         //          </li>
         //          <li>
         //             <b>To amend or remove any of the personal information</b> we hold about you, you can contact us or update the information on your profile page. To remove all personal data from our database please email us at <a href="support@openinclusion.com"> support@openinclusion.com.</a>.
         //          </li>
         //          <li>
         //             <b>If you would like to know more</b> or understand your data protection rights, please read our <a href="https://openinclusion.com/privacy-policy/">privacy policy.</a>
         //          </li>
         //       </ul>
         //       <li>For full terms and conditions see the <a href="https://openinclusion.com/terms/">terms and conditions</a> page on our website</li>
         //    </ul>',
         //    'validation' => array(
         //    )
         // ),

         // array(
         //    'label' => __('<span>Please confirm</span>'),
         //    'type' => 'chkboxgroup-inf',
         //    'name' => 'PleaseConfirm',
         //    'li-class' => 'short clear',
         //    'options' => get_confirm_methods(),
         //    'validation' => array(
         //       array('reqd', __( 'Please select all conditions', 'openinclusion' )),
         //       array('check_all_selected', __( 'Please select all conditions', 'openinclusion' )),
         //    ),
         // ),
   
         
         // array(
         //    'label' => '',
         //    'type' => 'other-html',
         //    'name' => 'header4',
         //    'li-class' => 'clear',
         //    'value' => '<p>By clicking the submit button below, you agree that we may process your information in accordance with our terms and conditions. (See the points in Section 4. Terms, Conditions and Community Agreement)</p>',
         //    'validation' => array(
         //    )
         // ),


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



function getProfileFields() {
   // Contact form 
   $profileFields = array(
      'fields' => array(
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 class="sectionhead">1. Name and Contact details</h2>',
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
            'name' => 'PreferToContact',
            'li-class' => 'short clear',
            'options' => get_contact_methods(),
         ),
         array(
            'label' => __( 'Other preferred contact methods:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'PreferToContactOthers',
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
            'value' => '<h2 class="sectionhead">2. Your online community login details</h2>',
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 class="sectionhead">3. About you </h2>',
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
            'value' => '<h2 class="sectionhead">4. Accessibility needs and assistive technology</span></h2>',
         ),

         array(
            'label' => __( 'Sensory needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'SensoryNeeds',
            'li-class' => 'short clear',
            'options' => get_sensory_needs(),
         ),

         array(
            'label' => __( 'Physical needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'PhysicalNeeds',
            'li-class' => 'short clear',
            'options' => get_physical_needs(),
         ),

         array(
            'label' => __( 'Cognitive and mental health needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'CognitiveAndMentalhealthNeeds',
            'li-class' => 'short clear',
            'options' => get_cognitive_and_mentalhealth_needs(),
         ),

         array(
            'label' => __( 'Communication needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'CommunicationNeeds',
            'li-class' => 'short clear',
            'options' => get_communication_needs(),
         ),

         array(
            'label' => __( 'Chronic health needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'ChronichealthNeeds',
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
            'label' => __( 'At what age did you incur your primary need ?', 'openinclusion' ),
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
            'name' => 'DigitalandScreenTechnologies',
            'li-class' => 'short clear',
            'options' => get_digitalandscreentechnologies(),
         ), 

         array(
            'label' => __( 'Movement, canes and service animals:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'MovementCanesandServiceAnimals',
            'li-class' => 'short clear',
            'options' => get_movementcanesandserviceanimals(),
         ),          
         
         array(
            'label' => __( 'Communication, verbal and written preferences:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'CommunicationPreferences',
            'li-class' => 'short clear',
            'options' => get_communicationpreferences(),
         ),           
         
         array(
            'label' => __( 'Personal support and home:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'PersonalSupportandHome',
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
   // $member_text = getUserRole();
   
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
         // array(
         //    'label' => '',
         //    'type' => 'other-html',
         //    'name' => 'header2',
         //    'li-class' => 'clear',
         //    'value' => '<h3>'.$member_text.'</h3>',
         // ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'edit-profile-button',
            'li-class' => 'clear',
            'value' => 'Click Here',
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2>Registration for paid research opportunities at Open </h2>',
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<span>Thank you for your interest in being invited to paid research opportunities within the Open Inclusion disability and age-inclusive insight community.</span>',
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<span>Please provide a few more details about yourself, your disability or age-related access needs, assistive technologies or adaptive approaches and some additional demographics such as gender, age and ethnicity. This should take approximately 5-10 minutes to complete.</span>',
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<span>This information helps us match you to research that is happening in your region and includes people with your experiences.</span>',
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<span>Required questions are marked with an asterix *</span>',
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header4',
            'li-id'=> 'header4',
            'li-class' => 'clear',
            'value' => '<h2 class="sectionhead" id="edit-part">1. Accessibility needs and assistive technology</span></h2>',
         ),

         array(
            'label' => __( 'Sensory needs:', 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => '_SensoryNeeds',
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
            'label' => __( 'If you have more than one access need, what would you say is your primary need/needs?', 'openinclusion' ),
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
            'label' => __( 'At what age did you incur your primary need ?', 'openinclusion' ),
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
            'type' => 'hidden',
            'name' => 'editProfileToken',
            'li-class' => 'clear',
            'value' => 'VQxt1|uIg1@5vNe*76V1#~*Y+Q6VVQxt',
         ),           
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<h2 class="sectionhead">2. A bit about you more generally</h2>',
            'validation' => array(
            )
         ),
         array(
            'label' => __( 'What country  do you live in:', 'openinclusion' ),
            'type' => 'select',
            'name' => 'inf_field_country',
            'li-class' => 'short clear',
            'options' => get_countries(),
            'validation' => array(
                  array('reqd', __( 'Please supply your country', 'openinclusion' ))
            )
         ),

         array(
            'label' => __( 'What region, province or state do you live in?', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_region',
            'li-class' => 'short clear',
            'validation' => array(
                  array('reqd', __( 'Please supply your country', 'openinclusion' ))
            )
         ),

         array(
            'label' => __( 'What year were you born?', 'openinclusion' ),
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
            'label' => __( 'What are your preferred pronouns?', 'openinclusion' ),
            'type' => 'radiogroup-inf',
            'name' => 'inf_option_pronouns',
            'li-class' => 'short clear',
            'options' => get_preferred_pronouns(),
            'validation' => array(
               array('reqd', __( 'Please select one preferred pronouns', 'openinclusion' )),
            )
         ),
         array(
            'label' => __( 'How do you identify in terms of ethnicity and race? <br /> Please self-describe as is most relevant to you.  
            E.g. Black British, Japanese Asian or Asian Australian (please note that your identity does not necessarily align to where you live). If you do not wish to respond please write “prefer not to respond”.
            ', 'openinclusion' ),
            'type' => 'text',
            'name' => 'inf_field_identify_terms',
            'li-class' => 'short clear',
            'validation' => array(
                  array('reqd', __( 'Please type your ethnicity', 'openinclusion' ))
            )
         ),
         array(
            'label' => __( 'Do you identify as coming from a marginalised ethnicity or race within the community you live?' ),
            'type' => 'radiogroup-inf',
            'name' => 'inf_field_ethnicity',
            'options' => get_marginalised_ethnicity(),
            'li-class' => 'short clear',
            'validation' => array(
                  array('reqd', __( 'Please select any one ethnicity', 'openinclusion' ))
            )
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'header2',
            'li-class' => 'clear',
            'value' => '<span>What research formats would you most like to be invited to?</span>',
            'validation' => array(
            )
         ),
         array(
            'label' => __( "Please select all that you are interested in. This doesn;t commit you to then saying yes to any specific research. You can always opt in or out.", 'openinclusion' ),
            'type' => 'chkboxgroup-inf',
            'name' => 'ResearchFormats',
            'li-class' => 'short clear',
            'options' => get_research_formats(),
            'validation' => array(
               array('reqd', __( 'Please select one research formats', 'openinclusion' )),
            )
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'thank_you',
            'li-class' => 'clear',
            'value' => '<h4 class="sectionhead">Thank you! </h2>',
            'validation' => array(
            )
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'thank_you_header1',
            'li-class' => 'clear',
            'value' => '<span>We look forward to sharing paid research opportunities with you soon.</span>',
            'validation' => array(
            )
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'thank_you_header1',
            'li-class' => 'clear',
            'value' => '<span>We cannot know exactly when the next research opportunity will arise that includes respondents with your access needs and other characteristics. We hope that it will not be long.
            </span>',
            'validation' => array(
            )
         ), array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'thank_you_header1',
            'li-class' => 'clear',
            'value' => '<span> At minimum, we will keep you up to date with research, awards programs and other happenings across the community via our quarterly newsletter so that you can keep informed and connected. We really look forward to learning about your perspectives in future research.
            </span>',
            'validation' => array(
            )
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'thank_you_header1',
            'li-class' => 'clear',
            'value' => '<span>For now please engage with others across our community in discussions. 
            </span>',
            'validation' => array(
            )
         ),
         array(
            'label' => '',
            'type' => 'other-html',
            'name' => 'thank_you_header1',
            'li-class' => 'clear',
            'value' => '<span>On the <a href="">Open Inclusion community hub</a> you can share challenges, find inclusive and innovative solutions, tell us and others about things you love or frustrations you have experienced. Come and get involved! It is a space that was designed by and for our community, and is continually evolving with your and other members input.</span>',
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

// Part 2 Step 1 form definition
$part2Step1Form = array(
   'cont-id' => '',
   'cont-class' => 'contact panel-contact',
   'form-id' => 'part2-step1-form',
   'client-val' => true,
   'mand-ind' => '*',
   'success-img' => '',
   'error-img' => '',
   'error-sect-id' => 'sub-errs',
   'error-sect-class' => '',
   'error-sect-hdr' => __( 'Submission Problems', 'openinclusion' ),
   'error-sect-hdr-level' => 2,
   'error-sect-intro' => '<p>'.__( 'We were not able to process your enquiry. Please review the following items and check what you entered', 'openinclusion' ).'</p>',
   'nonce-name' => 'part2_step1_nonce',
   'sq-reqd' => false,
   'sq-id' => 'sq',
   'sq-label' => __( 'Please answer this question:', 'openinclusion' ).' %1$s <span class="clarify">('.__( 'Helps stop spam', 'openinclusion' ).')</span>',
   'fields' => array(
      array(
         'label' => '',
         'type' => 'other-html',
         'name' => 'banner1',
         'li-class' => 'clear',
         'value' => '
            <h2>PAGE 1: ABOUT ME</h2>
            <p>It is important for us to learn more about you so that we can invite you to research that best matches your experiences.</p>
            <p>This form should take about 10-15 minutes to complete. In it, we will ask you about:</p>
            <ul>
               <li>Your age, where you live and your connection to disability</li>
               <li>Your access needs</li>
               <li>The assistive technologies and adaptive solutions you use</li>
               <li>Some key demographics such as your gender and age</li>
            </ul>
            <p>Once completed, including reviewing and consenting to Open Inclusion\'s Terms and Conditions, you\'ll be part of the Open Inclusion Insight Community. We look forward to working with you.</p>
            <p>Required questions are marked with an asterisk *</p>
         ',
         'validation' => array()
      ),
      
      array(
         'label' => __( 'What country do you live in?', 'openinclusion' ),
         'type' => 'select',
         'name' => 'inf_field_country',
         'li-class' => 'clear',
         'options' => get_countries(),
         'validation' => array(
            array('reqd', __( 'Please select your country', 'openinclusion' ))
         )
      ),
      
      array(
         'label' => __( 'What region, province or state do you live in?', 'openinclusion' ),
         'type' => 'text',
         'name' => 'inf_field_region',
         'li-class' => 'clear',
         'maxlen' => 250,
         'validation' => array()
      ),
      
      array(
         'label' => __( 'Please enter your postcode', 'openinclusion' ),
         'type' => 'text',
         'name' => 'inf_field_postcode',
         'li-class' => 'clear',
         'maxlen' => 20,
         'validation' => array()
      ),
      
      array(
         'label' => __( 'Are you over 18?', 'openinclusion' ),
         'type' => 'radiogroup-inf',
         'name' => 'inf_field_over18',
         'li-class' => 'clear',
         'options' => array(
            array('Yes', 'Yes', 'inf_field_over18_yes'),
            array('No', 'No', 'inf_field_over18_no')
         ),
         'validation' => array(
            array('reqd', __( 'Please confirm if you are over 18', 'openinclusion' ))
         )
      ),
      
      array(
         'label' => __( 'What year were you born?', 'openinclusion' ),
         'type' => 'text',
         'name' => 'inf_custom_YearBorn',
         'li-class' => 'clear',
         'maxlen' => 4,
         'validation' => array(
            array('reqd', __( 'Please enter your birth year', 'openinclusion' )),
            array('int', __( 'Please enter a valid year', 'openinclusion' ))
         )
      ),
      
      array(
         'label' => __( 'Do you have one or more long-term physical, sensory or cognitive conditions or disabilities that significantly impact your ability to carry out day-to-day activities?', 'openinclusion' ),
         'type' => 'radiogroup-inf',
         'name' => 'inf_field_hasDisability',
         'li-class' => 'clear',
         'options' => array(
            array('Yes', 'Yes', 'inf_field_hasDisability_yes'),
            array('No', 'No', 'inf_field_hasDisability_no'),
            array('PreferNotToAnswer', 'I\'d rather not answer', 'inf_field_hasDisability_prefer_not')
         ),
         'validation' => array(
            array('reqd', __( 'Please answer this question', 'openinclusion' ))
         )
      ),
      
      array(
         'label' => __( 'What is your identity or relationship regarding disability or ageing? I identify as:', 'openinclusion' ),
         'type' => 'chkboxgroup-inf',
         'name' => 'RelationShip',
         'li-class' => 'clear',
         'options' => get_relationship_needs(),
         'validation' => array(
            array('reqd', __( 'Please select at least one option', 'openinclusion' ))
         )
      ),
      
      array(
         'name' => 'submit_part2_step1',
         'type' => 'submit',
         'li-class' => 'submit',
         'value' => __('Continue to Next Step', 'openinclusion' )
      ),
   )
);

function getUserRole(){
   $current_user = wp_get_current_user();
   $userid = $current_user->ID;
   $user_info = array();
   $user_info = get_user_meta($userid);
   $last_name=trim($user_info['last_name'][0]);// removing space btw starting and ending
   $user_name = $user_info['first_name'][0]."%20".$last_name;
   $sub_object = $current_user->{'data'};
   $key_exists = property_exists($sub_object, 'user_login');  
   $filterURL = 'https://community.openinclusion.com/api/v2/users/by-names?name='.$user_name;
   $filtercurl = curl_init($filterURL);
   // 1. Set the CURLOPT_RETURNTRANSFER option to true
   curl_setopt($filtercurl, CURLOPT_RETURNTRANSFER, true);
   // 2. Set the CURLOPT_POST option to true for PATCH request
   curl_setopt($filtercurl, CURLOPT_CUSTOMREQUEST, 'GET');
   // 3. Set the request data as JSON using json_encode function
   // curl_setopt($filtercurl, CURLOPT_POSTFIELDS,  json_encode($data));
   // 4. Set custom headers for RapidAPI Auth and Content-Type header
   curl_setopt($filtercurl, CURLOPT_HTTPHEADER, [
      'X-RapidAPI-Host: kvstore.p.rapidapi.com',
      'X-RapidAPI-Key: [Input your RapidAPI Key Here]',
      'Content-Type: application/json',
      "Authorization: Bearer va.EPyE5NVNdMSkGEAXvAOloHuQuaYyuBle.OTnodg.eibcLKP"
   ]);
   $response = curl_exec($filtercurl);
   $array_response = json_decode($response);
   $vanillaUserId = $array_response[0]->userID;
   curl_close($filtercurl);
   $url = 'https://community.openinclusion.com/api/v2/users/'.$vanillaUserId;
   $curl = curl_init($url);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
   curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'X-RapidAPI-Host: kvstore.p.rapidapi.com',
      'X-RapidAPI-Key: [Input your RapidAPI Key Here]',
      'Content-Type: application/json',
      "Authorization: Bearer va.EPyE5NVNdMSkGEAXvAOloHuQuaYyuBle.OTnodg.eibcLKP"
   ]);
   $response = curl_exec($curl);
   $role_response = json_decode($response);
   $user_role = $role_response->roles[0]->name;
   curl_close($curl);
   $member_text = '';
   if($user_role == 'Member (Partial)'){
      $member_text = 'If you would like to become a full community member and have opportunities to take part in research opportunities, please click the edit profile button and fill out section 4 of the form and click the submit form button.';
   }
   elseif($user_role == 'Member (Full)'){
      $member_text = 'If you wish to update your profile to reflect your current situation, please update and re-submit the form';
   }
   return $member_text;
}
?>