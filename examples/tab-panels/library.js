///// General Functions ///////
jQuery(document).ready(function($) {
   // Code here will be executed on document ready. Use $ as normal.
   $('html').addClass( 'js' ).removeClass( 'no-js' );

   // Code for tab-panels
   // Hide all tab panels within a group - except for the first
   $('.tab-panel-group').find('.tab-panel').slice(1).addClass('hidden');
   
   // Begin setup of aria etc for tab-panels

   // Make tab header links 
   //  - role of button, 
   //  - set aria-controls
   //  - set aria-expanded = false except first one = true



   // For each tab header
   $('.tab-headers').find('a').each(function(i, el){
      // Retrieve the data id allocated within the element
      $thisId = $( this ).attr('data-id');

      // Store controls id
      $thisControls = 'tab-panel-'+$thisId;
      
      // Retrieve the question for use later
      $thisQ = $( this ).html();
      
      // Set up attributes for this question
      $( this ).attr('role','button')
         .attr('aria-controls',$thisControls);
      
      if (i === 0) {  // first one??
         // first element.. 
         $( this ).attr('aria-expanded','true');
      } else {
         $( this ).attr('aria-expanded','false');
      }

      // Now process the corresponding tab panel
      // Make tab panels
      //  - set aria-labelledby to correct id
      //  - role=region
      //  - aria-hidden=true except for first one which should be false

      $('#' + $thisControls ).attr('role','region')
         .attr('aria-labelledby','tab-hdr-'+$thisId);
      
      if (i === 0) {  // first one??
         // first element.. 
         $( '#' + $thisControls ).attr('aria-hidden','false');
      } else {
         $( '#' + $thisControls ).attr('aria-hidden','true');
      }

   });


  
   
   // Handle clicks on the tab headers
   $('.tab-header').on('click', function(e) {
      // Retrieve stored id
      var $thisId = $( this ).attr('data-id');
      // Retrieve panel I control
      var $thisControls = 'tab-panel-'+$thisId;

      //console.log('Clicked' + $thisId);

      // Hide all the tab panels in this tab-panel-group
      $( this ).closest('.tab-panel-group')
         .find('.tab-panel')
         .addClass('hidden')
         .attr('aria-hidden','true');

      //Show the panel that I control,
      $( '#' + $thisControls ).removeClass('hidden')
         .attr('aria-hidden','false');

      // Update aria on all tab-headers
      $( this ).closest('.tab-headers')
          .find('.tab-header')
          .attr('aria-expanded','false');

      // Update the one that was clicked
      $( this ).attr('aria-expanded','true');

      // Put focus on visible panel
      $( '#' + $thisControls ).focus();

      /*

      if ($(this).attr( 'aria-expanded' ) == 'true') {
         hideAccordion($( this ));
         $( this ).focus();
      } else {
         showAccordion($( this ));
         $( '#acc-a-'+$thisId ).focus();
         
      }
      */
   });
   // Handle key presses on the headers
   $('.tab-header').on('keydown', function(e) {
   
   
      switch (e.keyCode) {
         case 13:   // Enter
         case 32:   // space
            //console.log($(this).attr('data-id'));
            $(this).click(); // Trigger click
            break;
         default:
         break;
      }
      

   });
   
   
   
});


