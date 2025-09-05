///// General Functions ///////
jQuery(document).ready(function($) {
   // Code here will be executed on document ready. Use $ as normal.
   $('html').addClass( 'js' ).removeClass( 'no-js' );

   // Code for accordions
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
          .attr('id','acc-q-'+$thisId);
      // set up attributes for corresponding answer
      $( this ).next('.acc-a')
         .attr('id','acc-a-'+$thisId);
   });
   
   
   // Handle clicks on the headers
   $('.acc-group').find('.acc-q').on('click', function(e) {
      var $thisId = $( this ).attr('data-id');
      if ($(this).hasClass( 'expanded' ) ) {
         hideAccordion($( this ));
         //$( this ).focus();
      } else {
         showAccordion($( this ));
         //$( '#acc-a-'+$thisId ).focus();
         
      }
   });
   // Handle key presses on the headers
   $('.acc-group').find('.acc-q').on('keydown', function(e) {
   
      // define current, previous and next (possible) tabs
      var $original = $(this);
      var $prevHdr = $(this).prevAll('.acc-q:first');
      var $nextHdr = $(this).nextAll('.acc-q:first');
      var $target;
      var $openClose = false; // Flags whether to open or close the tab
   
      switch (e.keyCode) {
         /*
         case 37:   // left arrow
         case 38:   // up arrow
            $target = $prevHdr;
            break;
         case 39:   // right arrow
         case 40:   // down arrow
            $target = $nextHdr;
            break;
         */
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
   
   function hideAccordion(obj) {
      var $thisId = $( obj ).attr('data-id');
      $( obj ).removeClass( 'expanded' );
      console.log("In hideAccordion - "+$thisId);
      //alert (dataId);
      $('#acc-a-'+$thisId).hide();
      
   }
   function showAccordion(obj) {
      var $thisId = $( obj ).attr('data-id');
      $( obj ).addClass( 'expanded' );

      console.log("In showAccordion - "+$thisId);
      //alert (dataId);
      $('#acc-a-'+$thisId).show();
   }
   
   
});


