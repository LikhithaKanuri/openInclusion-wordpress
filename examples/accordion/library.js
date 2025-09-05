///// General Functions ///////
jQuery(document).ready(function($) {
   // Code here will be executed on document ready. Use $ as normal.
   $('html').addClass( 'js' ).removeClass( 'no-js' );

   // Code for accordions
   // Hide any accordion 'answers' and close links
   $('.acc-group .acc-a').hide();
   
   // Begin setup of aria etc for accordion
   $('.acc-group').attr('multiselectable','true').attr('role','tablist');
   
   // Make the accordion headers a link
   $('.acc-group  .acc-q').wrapInner('<a href="bla.htm"></a>' );
   
   // XXXXXXXXXXXXXXXXXXXXXXXXXXXX
   
   // For each accordion question
   $('.accordion .tdc-q').each(function(){
      // Retrieve the data id allocated within php
      $thisId = $( this ).attr('data-id');
      // Retrieve the question for use later
      $thisQ = $( this ).html();
      
      // Set up attributes for this question
      $( this ).attr('tabindex','0')
         .attr('aria-selected','false')
         .attr('aria-expanded','false')
         .attr('role','tab')
         .attr('id','tab'+$thisId)
         .attr('aria-controls','pan'+$thisId);
      // set up attributes for corresponding answer
      $( this ).next('.tdc-a')
         .attr('aria-hidden','true')
         .attr('id','pan'+$thisId)
         .attr('aria-labelledby','tab'+$thisId)
         .attr('role','tabpanel');
      // set up attributes for open and closing links
      $( this ).next('.tdc-a').next('.tdc-open')
         .attr('data-id',$thisId)
         .attr('data-q',$thisQ);
      $( this ).next('.tdc-a').next('.tdc-open').next('.tdc-close')
         .attr('data-id',$thisId)
         .attr('data-q',$thisQ);
   });
   $('.accordion.iq .tdc-open').each(function(){
      $thisId = $( this ).attr('data-id');
      $thisQ = $( this ).attr('data-q');
      
      $( this ).attr('id','open'+$thisId);
      $( this ).html('<a data-id="'+$thisId+'" href="#">+ Further information<span class="srdr"> about '+$thisQ+'</span></a>');
   });
   $('.accordion.iq .tdc-close').each(function(){
      $thisId = $( this ).attr('data-id');
      $thisQ = $( this ).attr('data-q');
      
      $( this ).attr('id','close'+$thisId);
      $( this ).html('<a data-id="'+$thisId+'" href="#">- Close<span class="srdr"> '+$thisQ+'</span></a>');
   });


      // See http://www.slideshare.net/sprungmarker/accessible-javascript-beispiel-akkordeon slide 25
   
   // Handle clicks on the headers
   $('.accordion').find('.tdc-q').on('click', function(e) {
      if ($(this).hasClass( 'open' )) {
         hideAccordion($( this ));
         $( this ).focus();
      } else {
         showAccordion($( this ));
      }
   });
   // Handle key presses on the headers
   $('.accordion').find('.tdc-q').on('keydown', function(e) {
   
      // define current, previous and next (possible) tabs
      var $original = $(this);
      var $prevHdr = $(this).prevAll('.tdc-q:first');
      var $nextHdr = $(this).nextAll('.tdc-q:first');
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
      /*
      this.enter      = 13;
      this.esc        = 27;
      this.space      = 32;
      */
      
      if ($target.length) {
         // If required to move focus elsewhere
         $target.focus();
      } else {
         if ($openClose) {
            // Issue click command
            $(this).click();
         }
      }

      /*
      if ($(this).hasClass( 'open' )) {
         hideAccordion($( this ));
         $( this ).focus();
      } else {
         showAccordion($( this ));
      }
      */
   });
   // Handle clicks and key presses on the 
   $('.tdc-open').find('a').on('click', function(e) {
      var $thisId = $( this ).attr('data-id');
      showAccordion($( this ));
      $('#close'+$thisId).find('a').focus();
      return false;
   });
   $('.tdc-close').find('a').on('click', function(e) {
      var $thisId = $( this ).attr('data-id');
      hideAccordion($( this ));
      $('#open'+$thisId).find('a').focus();
      return false;
   });
   
   function hideAccordion(obj) {
      var $thisId = $( obj ).attr('data-id');
      //alert (dataId);
      $('#pan'+$thisId)
         .slideUp()
         .attr('aria-hidden','true')
         .removeAttr('tabindex');
      $('#tab'+$thisId)
         .attr('aria-selected','false')
         .attr('aria-expanded','false')
         .removeClass( 'open' );
      $('#close'+$thisId).hide();
      $('#open'+$thisId).show();
   }
   function showAccordion(obj) {
      var $thisId = $( obj ).attr('data-id');
      //alert (dataId);
      $('#pan'+$thisId)
         .slideDown()
         .attr('aria-hidden','false')
         .attr('tabindex','0');
      $('#tab'+$thisId)
         .attr('aria-selected','true')
         .attr('aria-expanded','true')
         .addClass( 'open' );
      $('#open'+$thisId).hide();
      $('#close'+$thisId).show();
   }
   
   
});


