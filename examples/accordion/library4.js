///// General Functions ///////
jQuery(document).ready(function($) {
   // Code here will be executed on document ready. Use $ as normal.
   $('html').addClass( 'js' ).removeClass( 'no-js' );

   // Code for accordions
   $hdr = 'h2';
   $acc_id = 1;

   //$('.acc-group').find($hdr).css( "color", "green" ).nextUntil($hdr).wrap("<div class='new'></div>").css( "background-color", "red" );  

   $('.acc-group').find($hdr).each(function(){
      // Store current ID value
      $this_id = $acc_id;
      
      // Perform all the necessary functionality for this heading
      $(this).attr('id','acc-q-' + $this_id)
         .attr('data-id',$this_id)
         .addClass('acc-q');

      var $set = $(this).nextUntil($hdr);
      $set.wrapAll('<div class="acc-a" tabindex="-1" /></div>');

      // Now increment the $acc_id value ready for the next one
      $acc_id++;
   });




   // Hide any accordion 'answers' and close links
   $('.acc-group .acc-a').hide();
   
   // Begin setup of aria etc for accordion
   
   // For each accordion question
   $('.acc-group').find('.acc-q').each(function(){
      // Retrieve the data id allocated within php
      $thisId = $( this ).attr('data-id');
      // Retrieve the question for use later
      $thisQ = $( this ).html();
      
      // Set up attributes for this question
      $( this ).attr('tabindex','0')
         .attr('aria-expanded','false')
         .attr('role','button')
         .attr('id','acc-q-'+$thisId)
         .attr('aria-controls','acc-a-'+$thisId);
      // set up attributes for corresponding answer
      $( this ).next('.acc-a')
         .attr('aria-hidden','true')
         .attr('id','acc-a-'+$thisId)
         .attr('aria-labelledby','acc-q-'+$thisId)
         .attr('role','region');
   });
   
   
   // Handle clicks on the headers
   $('.acc-group').find('.acc-q').on('click', function(e) {
      var $thisId = $( this ).attr('data-id');
      if ($(this).attr( 'aria-expanded' ) == 'true') {
         hideAccordion($( this ));
         $( this ).focus();
      } else {
         showAccordion($( this ));
         $( '#acc-a-'+$thisId ).focus();
         
      }
   });
   // Handle key presses on the headers
   $('.acc-group').find('.acc-q').on('keydown', function(e) {
   
      var $target;
      var $openClose = false; // Flags whether to open or close the tab
   
      switch (e.keyCode) {

         case 13:   // Enter
         case 32:   // space
            $target = false;
            $openClose = true; // Trigger click
            break;
         default:
            $target = false
         break;
      }
      
      if ($target.length) {
         // If required to move focus elsewhere
         $target.focus();
      } else {
         if ($openClose) {
            // Issue click command
            $(this).click();
         }
      }

   });
   /*
   */
   function hideAccordion(obj) {
      var $thisId = $( obj ).attr('data-id');
      $('#acc-a-'+$thisId)
         .hide()
         .attr('aria-hidden','true');
      $('#acc-q-'+$thisId)
         .attr('aria-expanded','false');
   }
   function showAccordion(obj) {
      var $thisId = $( obj ).attr('data-id');
      $('#acc-a-'+$thisId)
         .show()
         .attr('aria-hidden','false');
      $('#acc-q-'+$thisId)
         .attr('aria-expanded','true');
   }
   
   
});


