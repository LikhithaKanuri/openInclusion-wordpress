///// General Functions ///////
jQuery(document).ready(function($) {
   // Form validation etc
   // Set up appearances for return from server side validation
   //$('input[aria-invalid="false"]').parent('label').addClass('valid');
   //$('select[aria-invalid="false"]').parent('label').addClass('valid');
   //$('textarea[aria-invalid="false"]').parent('label').addClass('valid');
   
   /*
   // Check some fields when we move away
   $('.contact input[type="text"], .contact textarea, .contact select').on('blur', function(e) {
      // Perform required validation
      validateTextSelectField($( this ));
      //validateDateOfBirth($( this ));
   });
   // Check select boxes when they change
   $('.contact select').on('change', function(e) {
      // Perform required validation
      validateTextSelectField($( this ));
      //validateDateOfBirth($( this ));
   });
   // Check radio buttons when value changes
   $('.contact fieldset input[type="radio"]').on('change', function(e) {
      // Perform required validation - we're checking for required
      // Check if radio button group is required to have one selected
      
      // traverse up to fieldset before passing to function.
      $parFieldset = $( this ).closest('fieldset');
      $ret = validateRadioGrp($( $parFieldset ));
   
   });
   // Check date of birth fields when value changes
   $('.contact fieldset[data-type="dob"] select').on('change', function(e) {
      // If the value is blank then fail the select and vice versa
      $val =  $( this ).val();
      if( $val != '' ) { 
         // Call function to validate date from fieldset
         
         // check that value of this one and siblings produce a valid date
         $( this ).parents('fieldset[data-type="dob"]').each(function() {
            $ret = validateDateOfBirth($(this));
         });
         if ($ret) { doTextSelectSuccess($( this )); }
      } else {
         doTextSelectFail($( this ), '');
      }
   });
   */
   $('#app2').hide();
   $(':radio[name="people"]').change(function() {
      var category = $(this).filter(':checked').val();
      if  (category == 's' ) {
         $('#app2').hide();
      } else {
         $('#app2').show();

      }
   });
   
   $('#meal-radio-sub').on('click', function(e) {
         console.log('submit-meal');
         $('#meal-fieldset').addClass('error');
         $('#meal-err').html('You must make a selection.');

         e.preventDefault();
   });

   $('#tv-check-sub').on('click', function(e) {
         console.log('submit-tv');
         $('#tva-fieldset').addClass('error');
         $('#tva-err').html('You can only choose a maximum of two programmes.');

         e.preventDefault();
   });

   $('input[type="submit"]').on('click', function(e) {
      $fieldId = '';
      $errInProgress = false;
      $errCount = 0;

      // Perform required validation - we're checking for length, email format, 
      // and required
      $( this ).parents('form')
         .find('input[type="text"], textarea, select').each(function() {
            // Call the validation routine on this field
            if(!validateTextSelectField($( this ))) {
               // A validation aerror found
               
               $errCount++;
               
               if (!$errInProgress) {
                  // Set global variable
                  $errInProgress = true;
                  
                  // strore ID of first field with error
                  $fieldId = $( this ).attr('id');
               }
            }
         
      });
      
      // Check fieldsets that have a data type
      $( this ).parents('form')
         .find('fieldset[data-type]').each(function() {
            // Find data type and branch accordingly
            $dataType = $( this ).attr('data-type');
            
            switch ($dataType) {
               case 'radio' :
                  // Check if radio button group is required to have one selected
                  if (!validateRadioGrp($( this ))) {
                     if (!$errInProgress) {
                        $errInProgress = true;  // Set global variable
                     }
                  }
                  break;
               case 'dob' :
                  if (!validateDateOfBirth($( this ))) {
                     if (!$errInProgress) {
                        $errInProgress = true; // Set global variable
                     }
                  }
                  break;
            }
            
      });
      if ($errInProgress){ 
         $('#error-notification').html($errCount+' errors have been found');
         e.preventDefault(); 
         
         //$('#error-notification').html($errCount+' errors have been found').attr('role','alert');
         //alert('Please fix the validation errors before submitting the form.');
         $currCount = 0;
         $( this ).parents('form')
            .find('input[aria-invalid="true"]').each(function() {
               $currCount++;
               $( this ).next('.errors').prepend('<span class="srdr">Error '+$currCount+' of '+ $errCount + ' </span>');
            });
         
         $('#'+$fieldId).focus();
         /*
         */
      }
      
   });


   function validateTextSelectField(obj) {
      // Store value of this control
      $myVal = $( obj ).val();
      
      // Check if required 
      if ($( obj ).attr('data-v-reqd')) {
         if ($myVal.length < 1) {
            $errMsg = $( obj ).attr('data-v-reqd');
            // Length less than 1 so fail this input
            doTextSelectFail($( obj ), $errMsg);
            return false; // End out of function
         } 
      } 

      // Still here the show success
      doTextSelectSuccess($( obj ));
      return true;
   }
   
   function doTextSelectSuccess(obj) {
      $( obj ).attr('aria-invalid','false')
         .next('.errors').html('')
         .parent('label').addClass('valid');
  
   }
   
   function doTextSelectFail(obj, message) {
      $( obj ).attr('aria-invalid','true')
         .next('.errors').html(message)
         .parent('label').removeClass('valid');
   }
   
   
});


