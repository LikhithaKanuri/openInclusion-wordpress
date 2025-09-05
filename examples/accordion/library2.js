///// General Functions ///////
jQuery(document).ready(function($) {
   // Code here will be executed on document ready. Use $ as normal.
   $('html').addClass( 'js' ).removeClass( 'no-js' );

   // Code for accordions
   // Hide any accordion 'answers' and close links
   $('.acc-group .acc-a').hide();
   
   // Begin setup of aria etc for accordion
   $('.acc-group').attr('aria-multiselectable','true').attr('role','tablist');
   
   // For each accordion question
   $('.acc-group').find('.acc-q').each(function(){
      // Retrieve the data id allocated within php
      $thisId = $( this ).attr('data-id');
      // Retrieve the question for use later
      $thisQ = $( this ).html();
      
      // Set up attributes for this question
      $( this ).attr('tabindex','0')
         .attr('aria-selected','false')
         .attr('aria-expanded','false')
         .attr('role','tab')
         .attr('id','acc-q-'+$thisId)
         .attr('aria-controls','acc-a-'+$thisId);
      // set up attributes for corresponding answer
      $( this ).next('.acc-a')
         .attr('aria-hidden','true')
         .attr('id','acc-a-'+$thisId)
         .attr('aria-labelledby','acc-q-'+$thisId)
         .attr('role','tabpanel');
   });
   
   
   // Handle clicks on the headers
   $('.acc-group').find('.acc-q').on('click', function(e) {
      var $thisId = $( this ).attr('data-id');
      if ($(this).attr( 'aria-selected' ) == 'true') {
         hideAccordion($( this ));
         $( this ).focus();
      } else {
         showAccordion($( this ));
         $( '#acc-a-'+$thisId ).focus();
         
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
         case 37:   // left arrow
         case 38:   // up arrow
            $target = $prevHdr;
            break;
         case 39:   // right arrow
         case 40:   // down arrow
            $target = $nextHdr;
            break;
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
      //console.log("In hideAccordion - "+$thisId);
      //alert (dataId);
      $('#acc-a-'+$thisId)
         .hide()
         .attr('aria-hidden','true')
      $('#acc-q-'+$thisId)
         .attr('aria-selected','false')
         .attr('aria-expanded','false')
   }
   function showAccordion(obj) {
      var $thisId = $( obj ).attr('data-id');
      //console.log("In showAccordion - "+$thisId);
      //alert (dataId);
      $('#acc-a-'+$thisId)
         .show()
         .attr('aria-hidden','false')
      $('#acc-q-'+$thisId)
         .attr('aria-selected','true')
         .attr('aria-expanded','true')
   }
   
   
});


