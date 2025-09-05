function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}



jQuery(document).ready(function($) {
   var $errInProgress = false;
   var $fieldId = '';
   var $errArray = [];

   // Code here will be executed on document ready. Use $ as normal.
   $('html').addClass( 'js' ).removeClass( 'no-js' );

	
	/* Functions for access controls */
	function show_alt() {
		$('img').each(function() {
			$(this).wrap("<div class='hidden-image-wrapper'></div>");
			$(this).before('<div class="alt">' + $(this).attr('alt') + '</div>');
		});
		
	}
	
	function hide_alt() {
		$('.alt').remove();
		$('img').unwrap();
	}
   
   
	if (readCookie('access-text-zoom')) {
   	$("html").addClass('zoom');
   	move_access_controls();
	}
   	
   	
   if (readCookie('access-contrast')) {
   	$("body").addClass('contrast');
	}
   	
   if (readCookie('access-text-only')) {
   	$("body").addClass('text-only');
   	show_alt();
	}
   
	function toggle_text(current, text, alt_text) {
		return (current == text) ? alt_text : text
	}  

	$(".access-text-zoom").click(function() {
		$("html").toggleClass('zoom');
		//var span = $(this).find('span').text();
		//span = (span == 'Make Text Larger') ? 'Make Text Normal Size' : 'Make Text Larger'
		$(this).find('span').text(toggle_text(
			$(this).find('span').text(),
			'Make Text Larger',
			'Make Text Default Size'
		));
		if ($("html").hasClass('zoom')) {
			move_access_controls();
			createCookie('access-text-zoom', true, 365);
		} else {
			move_access_controls_back();
			eraseCookie('access-text-zoom');
		}
	});

	$(".access-contrast").click(function() {
		$("body").toggleClass('contrast');
		$(this).find('span').text(toggle_text(
			$(this).find('span').text(),
			'Make Colour Schmeme High Contrast',
			'Make Colour Schmeme Default Contrast'
		));
		if ($("body").hasClass('contrast')) {
			createCookie('access-contrast', true, 365);
		} else {
			eraseCookie('access-contrast');
		}
	});

	$(".access-text-only").click(function() {
		$("body").toggleClass('text-only');
		$(this).find('span').text(toggle_text(
			$(this).find('span').text(),
			'Show Text Only and Hide Images',
			'Show Images'
		));
		if ($("body").hasClass('text-only')) {
			show_alt();
			createCookie('access-text-only', true, 365);
		} else {
			hide_alt()
			eraseCookie('access-text-only');
		}
	});
	
	


	function move_access_controls() {
		if (!$('.accessibility-controls').hasClass('moved')) {
			$('#top').after($('.accessibility-controls'));
			$('.accessibility-controls').addClass('moved');			
		}
	}
	
	function move_access_controls_back() {
		if ($('.accessibility-controls').hasClass('moved')) {
			$('#main-nav').after($('.accessibility-controls'));
			$('.accessibility-controls').removeClass('moved');		
		}
	}
	
	var dynamic_mq = function() {
		if (Modernizr.mq('(max-width: 460px)')) { //smaller than 768 wide
			move_access_controls();
		} else {
			if (!$("html").hasClass('zoom')) {
				move_access_controls_back();
			}
		}
	}
	
	// Call on every window resize
   $(window).resize(dynamic_mq);
   // Call once on initial load
   dynamic_mq();
	
   ///////////////////// Blog List Functionality ////////
   // On Blog list pages - eg Blog Home, Category, Tags etc we want to make the whole
   // block clickable - assuming we know the URL (which is stored in data-url attribute).

   //// Initial set up
   $('.blog-post, .blog-post-first').each(function() {
            console.log('hello');
         $( this ).addClass('clickable');
      var url = $( this ).attr('data-url');
      if (url) {
         $( this ).on('click', function(e) {
            $(location).attr('href',url);
         });
      }
   });



   
   ///////////////////// Tab Panel Functionality ////////

   //// Initial set up
   
   // Look for every section.tab-panel-wrapper
   $('section.tab-panel-wrapper').each(function() {
      var $thisId = $( this ).attr('id');
      
      console.log('Hello '+$thisId);
      
      // Find tab header links and add attributes as appropriate
      $( '#tab-hdrs-'+$thisId).find('.tab-header-link').each(function() {
         var $controlsId = $( this ).attr('data-id');
         var $myId = $( this ).attr('id');
         $( this ).attr('aria-controls',$controlsId);

         // Find panels and add attributes as necessary
         var $my_panel = $( '#'+$controlsId);
         $my_panel.attr('role','region').attr('aria-labelledby',$myId);

         // Check which panel to make visible and make all the others hidden
         if ($my_panel.attr('data-selected') == 'true') {
            $my_panel.attr('aria-hidden', 'false').removeClass('hide');
            $( this ).attr('aria-expanded','true');
            $(this).parent('li').addClass('expanded');
         } else {
            $my_panel.attr('aria-hidden', 'true').addClass('hide');
            $( this ).attr('aria-expanded','false');
         }


      });

   });

   $('.tab-header-link').on('keydown', function(e) {
      // looking for Enter and space bar
      
      $interested = false;

      switch (e.keyCode) {
         case 13:   // Enter
         case 32:   // space
            $interested = true;
            break;
      }
      // If we're interested, issue the click command
      if ($interested) {
         e.preventDefault();
         $(this).click();
      }

   });

   //// Respond to actions on tab header - click and pressing enter
   $('.tab-header-link').on('click', function(e) {
      e.preventDefault();
      // Check if selected panel already open, if so, bail out
      $my_panel = $(this).attr('data-id'); // Get the id of my panel

      console.log('Hello in ' + $my_panel);
      
      // Identify other panels in the tab panel group
      // Find the ancestor that is an <ul class="tab-headers"> and then find all links in it.
      $(this).closest('.tab-headers').find('.tab-header-link').each(function() {
         $linked_panel = $(this).attr('data-id');
         console.log('Hello in ' + $linked_panel);

         // Close or open panels - adjusting aria as necessary
         if ($my_panel == $linked_panel) {
            // Corresponds to one that has been clicked
            $(this).attr('aria-expanded','true');
            $('#'+$linked_panel).removeClass('hide').attr('aria-hidden','false');
            // Now add an 'expanded' class to containing <li>
            $(this).parent('li').addClass('expanded');

         } else {
            // Not the one that's been clicked
            $(this).attr('aria-expanded','false');
            $('#'+$linked_panel).addClass('hide').attr('aria-hidden','true');
            // Now add an 'expanded' class to containing <li>
            $(this).parent('li').removeClass('expanded');
         }
      });


      // put focus on selected
      $('#'+$my_panel).focus();

      /*
      switch (e.keyCode) {
         case 37:   // left arrow
         case 38:   // up arrow
            $target = $prev;
            break;
         case 39:   // right arrow
         case 40:   // down arrow
            $target = $next;
            break;
         case 13:   // Enter
         case 32:   // space
            $target = false;
            $openClose = true; // Trigger click
            break;
         default:
            $target = false
         break;

      */

   });



   //////////////// End of Tab Panel Functionality ///////

   ///////////////////// TOC Generator //////////////////

   String.prototype.repeat = function(num) {
      return new Array(num + 1).join(this);
   }
   // Code to create a table of contents for long posts or pages
   // Code based on that found at: https://codepen.io/jtojnar/full/Juiop

   // Functionality looks for an element with an id="toc" in the page. It will not run
   // if it doesn't find such an element.
   // YOU MUST NEVER HAVE MORE THAN ONE TOC ELEMENT IN THE PAGE
   //console.log('In js');
   $("#toc").each(function() {
      var ToC =
         "<nav role='navigation' aria-label='Page table of contents' class='table-of-contents'>" +
            "<h2>On this page:</h2>" +
            "<ul>";

      var newLine, el, title, link, level, baseLevel, counter;

      // Initialise counter - this will be used to ensure that any id's allocated to headings are unique
      counter = 1;

      // OK search for all the headings within the <main> element
      $("main h2, main h3, main h4, main h5, main h6").each(function() {

         el = $(this);
         title = el.text();

         // Check if the header has an id set. If not, make a unique one
         if (!el.attr("id")) {
            el.attr("id", "toc-h"+counter)
         }
         
         // Get the id to use as a link
         link = "#" + el.attr("id");

         //console.log(title + ' ' + link);
       
         var prevLevel = level || 0;   // First time through will end up as zero
         level = this.nodeName.substr(1); // Get the current level from the heading tag
         if(!baseLevel) { // make sure you start with highest level of heading or it won't work
            baseLevel = level;
         }

         
         // Now decide what to put out based on whether we're moving to a different heading level or not
         if(prevLevel == 0) {
            newLine = '<li>';
         } else if(level == prevLevel) {
            newLine = '</li><li>';
         } else if(level > prevLevel) {
            newLine = '<ul><li>'.repeat(level - prevLevel);
         } else if(level < prevLevel) {
            newLine = '</li></ul>'.repeat(prevLevel - level) +
            '</li><li>';
         }
         // Having sorted out the list tags, now add the in-page link
         newLine += "<a href='" + link + "'>" + title + "</a>";

         // And add the new line into the master HTML string
         ToC += newLine;

         // increment counter
         counter++;

      }); // End of each header

      // We've now finished looping so end up all the lists as necessary
      ToC += '</li></ul>'.repeat(level - baseLevel) +
               "</li>" +
            "</ul>" +
         "</nav>";
      
      //console.log(ToC);
      
      // Write out table of contents into the #toc container
      $("#toc").prepend(ToC);
   }); // End of each toc



   ///////////////////// End of TOC Generator //////////////////
	
   $(document).scroll(function() {
      var y = $(this).scrollTop();
      if (y > 800) {
         $('#back-to-top').not('.zoom #back-to-top').fadeIn();
      } else {
         $('#back-to-top').fadeOut();
      }
   });

	$(".show-story-detail").click(function() {
		var id = $(this).data('id');
		$("#story-detail-"+id).slideToggle();
		//alert($(this).html());
		if ($(this).html() == "Learn More") {
			$(this).html('Close <svg viewBox="0 0 27 16" class="icon icon-accordian-close"><use xlink:href="#icon-accordian-close"></use></svg>');
		} else {
			$(this).html('Learn More');
		}
		return false;
	});


	
	$('input#show-mobile-nav').change(function(){
    	if (this.checked) {
	    	//$('.wrapper').hide();
	    	//$('#banner').hide();
	    	
         //$('body').addClass('no-scroll');
	    	//$('html').addClass('no-scroll');
	    	//$.lockBody();
    	} else {
	    	//$('body').removeClass('no-scroll');
	    	//$('html').removeClass('no-scroll');
	    	
         //$('.wrapper').show();
	    	//$('#banner').show();
    	}
	});
	
   //////// Function to set cookie cookie and hide cookie message
   $('#cookie-accept-js').on('click', function(e) {
      //console.log('Hello in jquery function');
      createCookie('opencookieaccept','1',360);
      $('#banner-cookie-bar').hide('slow');
      e.preventDefault();
      $('#logo').focus();
   });

/*
	var $docEl = $('body'),
	$wrap = $('.content'),
	scrollTop;
	
	$.lockBody = function() {
		if(window.pageYOffset) {
			scrollTop = window.pageYOffset;
	
			$wrap.css({
				top: - (scrollTop)
			});
		}
	
		$docEl.css({
			height: "100%",
			overflow: "hidden"
		});
	}
	
	$.unlockBody = function() {
		$docEl.css({
			height: "",
			overflow: ""
		});
	
		$wrap.css({
			top: ''
		});
	
		window.scrollTo(0, scrollTop);
		window.setTimeout(function () {
			scrollTop = null;
  		}, 0);
	}
*/
   

   $('#cookie-accept-js').on('click', function(e) {
      //console.log('Hello in jquery function');
      createCookie('opencookieaccept','1',360);
      $('#banner-cookie-bar').hide('slow');
      e.preventDefault();
      $('#logo').focus();
   });

   //////// Function to control transcripts visibility
   $('button.transhow').on('click', function(e) {
      // Retrieve the id relevant to this transcript
      var b_id = $(this).data('id');
      
      // Make the transcript long, and put focus on hide button
      $('#' + b_id).addClass('long');
      $('#bh' + b_id).focus();

   });


   $('button.tranhide').on('click', function(e) {
      // Retrieve the id relevant to this transcript
      var b_id = $(this).data('id');
      
      // Make the transcript short, and put focus on show button
      $('#' + b_id).removeClass('long');
      $('#bs' + b_id).focus();

   });





   
   
   // Check things that need sizes altered
   function checkSizes() {
      var h = 0;
      // check heights of blog page boxes and ensure they are all the same
      
      // Get all the blog boxes
      var $blog_boxes = $('#news-blocks').find('.news-block');
      // Check heights
      check_box_heights_simple($blog_boxes);

      // Get all the team boxes
      var $team_boxes = $('#team-boxes').find('.team-box');
      // Check heights
      check_box_heights_simple($team_boxes);
      
      // Check if any of the biog panels are open, and if so check heights and widths etc
      $('#team-boxes').find('.team-box[aria-selected="true"]').each(function() {
         var $thisId = $( this ).attr('data-id');
         
         console.log('Hello');
         
         // Get the revised bottom value
         var $myBelow = get_my_bottom(this);
         
         //Get info about #team-boxes
         var $tbOff = $('#team-boxes').offset();
         var $tbLeft = $tbOff.left;
         var $myWidth = $('#team-boxes').width();
         
         // change width and position of panel
         $('#team-biog-'+$thisId)
            .css('width',$myWidth)
            .offset({ top: $myBelow, left: $tbLeft })
         
         // Adjust height of lower margin of biog panel
         $panelH = $('#team-biog-'+$thisId).height();
         
         $( this ).css('margin-bottom', $panelH+'px');



      });
   }
   
   function check_box_heights_simple(obj) {
      var h = 0;
      if (obj.length){
         // Reset heights
         $(obj).css('height', 'auto');
         
         // Check over each one and store height if more than we've already got
         $(obj).each(function() {
            h = Math.max(h, Math.ceil($(this).height()));
         });
         
         // Adjust heights to the largest value
         $(obj).css('height', h+'px'); 
      }
      
   }
   
   function delayed_checkSizes() {
      window.setTimeout(checkSizes(), 1000);
   }
   
   // NAVIGATION RELATED FUNCTIONS - uncomment when/if they are needed
   /*
   $( '#nav').find('li:has(ul)')
      .attr('aria-haspopup','true')
      .doubleTapToGo();
   
   // Replicate dropdown functionality
   $hoverClass = 'hover';
   // Add appropriate class to list items that are being hovered over
   // and remove when move moves out
   $('#nav').find('li').hover(
      function() {
         $( this ).addClass($hoverClass);
      },
      function() {
         $( this ).removeClass($hoverClass);
      }
   )
   // Handle tabbing with dropdown menus
   $('#nav').find('li').find('a').focus(
      function() {
         $( this ).parentsUntil('#nav').addClass($hoverClass);
      }
   ).blur(
      function() {
         $( this ).parentsUntil('#nav').removeClass($hoverClass);
      }
   );
   
   // Handle the navigation for lower screen widths
   $('#shownav').addClass('active');
   $('#shownav').on('click', function(e) {
         $('#nav').addClass('active').focus();
         $('#hidenav').addClass('active');
         $( this ).removeClass('active');
         e.preventDefault();
      });
   $('#hidenav').on('click', function(e) {
         $('#nav').removeClass('active');
         $('#shownav').addClass('active').focus();
         $( this ).removeClass('active');
         e.preventDefault();
      });
      
   */
   // Initialise Search
   // Add placeholder
   $('#s').attr('placeholder','Search site');

   
   // Initialise team boxes - they work like an accordion
   
   $('#team-boxes').find('.team-link').each(function(){
      var $thisId = $( this ).attr('data-id');
      
      // Add aria attributes
      $( this ).attr('aria-controls', 'team-biog-' + $thisId)
         .attr('aria-selected', 'false')
         .attr('role','button');
   });
   
   // Initialise team biogs - they work like an accordion
   $('#team-biogs').find('.team-biog').each(function(){
      $thisId = $( this ).attr('data-id');
      
      // set aria attributes
      $( this ).attr('aria-hidden', 'true').attr('role','region');
   });
   
   
   // Routines for team box showing
   $('#team-boxes').find('.team-box').not('.no-link').on('click', function(e) {
      e.preventDefault();
      // Check boxes are all sorted
      checkSizes();
      
      var link = $(this).find('.team-link');
      
      // Store current ID
      var id = link.attr('data-id');
      
      var parentbox = $( '#team-box-' + id );
      var parentlink = $( '#team-box-' + id ).find('a.team-link');
      
      // if this box is already open then flag it - we just want to close everything and stop
      var open_new = true;
      
      //if ($(parentbox).attr('aria-selected') == 'true') {
      if (link.attr('aria-selected') == 'true') {
         open_new = false;
      }
      
      
      // Ensure all bottom margins are set to zero and all biogs are hidden
      $('#team-boxes').find('.team-box')
/*          .css('margin-bottom', 0) */
         .removeClass('selected')
         .find('a.team-link')
         .attr('aria-selected', 'false');
      $('#team-biogs').find('.team-biog')
         .hide()
         .attr('aria-hidden', 'true');
      
      if (!open_new) {
         // No need to open new window
         $(parentbox).removeClass('selected').focus();
         update_status('Biography panel closed');
      } else {
      
         $myBelow = get_my_bottom(parentbox);
         
         console.log('get_my_bottom(parentbox)='+$myBelow);

         //Get info about #team-boxes
         $tbOff = $('#team-boxes').offset();
         $tbLeft = $tbOff.left;
         $myWidth = $('#team-boxes').width();
         console.log('$tbLeft='+$tbLeft+' $myWidth='+$myWidth);
         
         // show this one
         $('#team-biog-'+id)
            .removeAttr('aria-hidden')
            .show()
            .css('width',$myWidth)
            .offset({ top: $myBelow, left: $tbLeft })
            .focus();
         
         $panelH = $('#team-biog-'+id).height();
         console.log('$panelH='+$panelH);
         
         $( parentbox )/* .css('margin-bottom', $panelH+'px') */.addClass('selected');
         link.attr('aria-selected', 'true');
      
      }
      
   });
   
   /*
   $('#team-boxes').find('.team-link').on('keydown', function(e) {
      // define current, previous and next (possible) tabs
      var $original = $(this);
      var $parents = $(this).parents('.team-box');
      
      // This code needs to cope with situations where a team-box might not actually contain a link
      var $prev = $($parents).prevAll('.has-link:first').find('.team-link');
      var $next = $($parents).nextAll('.has-link:first').find('.team-link');
      var $target;
      var $openClose = false; // Flags whether to open or close the tab
   
      switch (e.keyCode) {
         case 37:   // left arrow
         case 38:   // up arrow
            $target = $prev;
            break;
         case 39:   // right arrow
         case 40:   // down arrow
            $target = $next;
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
 
   */


   function get_my_bottom(obj) {
      // Retrieve new top value and height
      $myOff = $(obj).offset();
      $myTop = $myOff.top;
      $myLeft = $myOff.left;
      $myHeight = $(obj).height();
      $myBelow = $myTop + $myHeight;
      
      return $myBelow;
   }
   
   $('#team-biogs').find('.biog-close').find('.biog-close-link').on('click', function(e) {
      // Close the open panel and change the style etc of the parent team-box
      e.preventDefault();

      var id = $( this ).attr('data-id');

      close_this_panel(id);
   });
   
   function close_this_panel(id) {
   // function to close an open biog panel and tend to parent team-boc
   // parameter passed in is id value of this panel
      // Close the biog panel
      $('#team-biog-'+id)
         .attr('aria-hidden', 'true')
         .hide();
      
      // Work on parent link and give it focus
      $( '#team-link-' + id ).attr('aria-selected', 'false').focus();
      // Work on parent team-box
      $( '#team-box-' + id ).css('margin-bottom', 0).removeClass('selected');
   
   }
   
   // Form validation etc ///////////////////////////////////////////////////////////////////////////////////////
   // Set up appearances for return from server side validation
   $('input[aria-invalid="false"]').parent('label').addClass('valid');
   $('select[aria-invalid="false"]').parent('label').addClass('valid');
   $('textarea[aria-invalid="false"]').parent('label').addClass('valid');
   
   // Check some fields when we move away
   $('.contact input[type="text"], .contact textarea, .contact select').on('blur', function(e) {
      // Perform required validation
      validateTextSelectField($( this ));
   });
   // Check dob year
   $('#inf_custom_YearBorn').on('blur', function(e) {
     // validate_dob_year($(this));
   });
   // Check select boxes when they change
   $('.contact select').on('change', function(e) {
      // Perform required validation
      validateTextSelectField($( this ));
   });
   // Check radio buttons when value changes
   $('.contact').find('fieldset').find('input[type="radio"]').on('change', function(e) {
      // Perform required validation - we're checking for required
      // Check if radio button group is required to have one selected
      
      // traverse up to fieldset before passing to function.
      $parFieldset = $( this ).closest('fieldset');
      $ret = validateRadioGrp($( $parFieldset ));
   
   });
   
   /****************** Form is submitted **************************/
   $('.contact').find('input[type="button"]').on('click', function(e) {
      
      $fieldId = '';
      $errInProgress = false;
      // Initialise error array
      $errArray = [];

      // Perform required validation - we're checking for length, email format, 
      // and required
      $( this ).parents('form')
         .find('input[type="text"], textarea, select')
         .each(function() {
            // Call the validation routine on this field
            if(!validateTextSelectField($( this ))) {
               // A validation aerror found
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
               case 'chkbox' :
                  // Check if checkbox group is required to have one selected
                  if (!validateCheckboxGrp($( this ))) {
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
      
      // Perform any special validation routines here
      //if they tick 'preferred on text relay', they need to have filled in a landline or mobile number (to confirm when Stephen emails me)
      if ($('#inf_option_CallsviaTextRelayServiceONLY').prop('checked')) { 
         // Check that landline number has a value

         if (($('#inf_field_Phone1').val().length < 1) && ($('#inf_field_Phone2').val().length < 1))  { // If it's empty
            $errInProgress = true;
            doTextSelectFail($('#inf_field_Phone1'), 'You need to enter either a landline or mobile number if you wish to be contacted via text relay.');         
            doTextSelectFail($('#inf_field_Phone2'), 'You need to enter either a landline or mobile number if you wish to be contacted via text relay.');         
         } else {
            doTextSelectSuccess($('#inf_field_Phone1'));         
            doTextSelectSuccess($('#inf_field_Phone2'));         
         }
      }
      // If they tick 'preferred on mobile' or 'preferred on text', they need to have filled in a mobile number
      if ($('#inf_option_Mobile').prop('checked') || $('#inf_option_Text').prop('checked')) { 
         // Check that mobile number has a value

         if ($('#inf_field_Phone2').val().length < 1) { // If it's empty
            $errInProgress = true;
            doTextSelectFail($('#inf_field_Phone2'), 'You need to enter a mobile number if you wish to be contacted via text or mobile.');         
         } else {
            doTextSelectSuccess($('#inf_field_Phone2'));         
         }
      }
      //if they tick 'preferred on landline', they need to have filled in a landline number
      if ($('#inf_option_Landline').prop('checked')) { 
         // Check that landline number has a value

         if ($('#inf_field_Phone1').val().length < 1) { // If it's empty
            $errInProgress = true;
            doTextSelectFail($('#inf_field_Phone1'), 'You need to enter a landline number if you wish to be contacted via landline.');         
         } else {
            doTextSelectSuccess($('#inf_field_Phone1'));         
         }
      }
      //the 'what year were you born' needs to be a number between 1900 and 1997 (so they'll be max 115 years old, and minimum 18 years old)
      //validate_dob_year($('#inf_custom_YearBorn'));

      if ($errInProgress){ 
         e.preventDefault(); 

         alert('Sorry, parts of the form were not completed correctly. Please review and submit again. Closing this window will take you to a list of the problems we found.');
         // Process error array
         //console.log('Error length = ' + $errArray.length); form-error
         print_out_form_errors('form-error', $errArray );

         //$('#'+$fieldId).focus();
      } else{
       //my_ajax_object.ajax_url;
	          
       var formDataa = $('#contactform').serialize();
       jQuery('.loader').css("display", "block");
       jQuery('#submit').css("cursor", "no-drop");
       jQuery.ajax({
         type: "POST",
         dataType: "json",
         url: my_ajax_object.ajax_url,
           data: { 
                  action: "runFunction",
                  submitAction: 'submitOCformData',
                  formData: formDataa
               },
            success: function( data ) {
               console.log(data);
				// Moved this statement here by Kenpath team for fixing firefox browser issue
               window.location.href = "https://openinclusion.com/thank-you";
               jQuery('.loader').css("display", "none");
            }
         });
		  
          
      }
   });

   function print_out_form_errors(err_id, err_arr) {
      // Print out errors at top of screen
      // err_id = id of <section> containing errors
      // err_arr = raw array of errors. (0) = id of element, (1) = error message
      // Process error array
      console.log('Error length = ' + err_arr.length);

      // Check if anything to report - if not, return
      if (err_arr.length == 0) return;

      // We've got something so start the output
      var out_html = '<section tabindex="-1" aria-labelledby="error-heading" id="' + err_id + '-section">';
      out_html += '<h2 id="error-heading">Form submission problems</h2><ul>';

      // Loop through array to pull out data
      for (i = 0; i < err_arr.length; i++) {
         out_html +='<li><a href="#' + err_arr[i][0] + '">' + err_arr[i][1] + '</a></li>';
      }

      out_html += '</ul></section>';
      
      console.log(out_html);
      
      $( '#'+err_id + '-list' ).html(out_html) ;
      $( '#'+err_id + '-section' ).focus();
      
      return;
   }
   
   function validate_dob_year(obj) {
      //the 'what year were you born' needs to be a number between 1900 and 1997 (so they'll be max 115 years old, and minimum 18 years old)
      var $dob_y = $(obj).val();
      var currentTime = new Date();
      var year = currentTime.getFullYear()
      if (($dob_y.length < 0) || isNaN($dob_y)) { 
         $errInProgress = true;
         doTextSelectFail($(obj), 'You need to enter your year of birth as 4 digits.');         
      } else {
         if (parseInt($dob_y) > (year - 19)) {
            $errInProgress = true;
            doTextSelectFail($(obj), 'You must have been born during or before ' + (year - 19) + ' to join the panel.');         
         }
         if (parseInt($dob_y) < 1900) {
            $errInProgress = true;
            doTextSelectFail($(obj), 'Sorry, we can only accept people born after 1900.');         
         }
      }
   
   }

   function validateRadioGrp(obj) {
      // We're being passed the fieldset that contains a radio button group
      
      // First check that radio button group is required
      if ($( obj ).attr('data-v-reqd')) {
         // Retrieve error message and value of any checked radio button
         $errMsg = $( obj ).attr('data-v-reqd');
         // $radVal = $( obj ).find('input:radio:checked' ).val();
         $radVal = $( obj ).find('input[type=radio]:checked' ).val(); 
         //alert($errMsg);
         // if no value then error
         if (!$radVal) {
            doRadioCheckboxGroupFail($( obj), $errMsg );
            return false;
         } 
      } 
      
      // Still here then return true
      doRadioCheckboxGroupSuccess($( obj ));
      return true;
   }

   function validateCheckboxGrp(obj) {
      // We're being passed the fieldset that contains a checkbox group
      // Initialise flag
      var success = true;
      
      // First check that radio button group is required
      if ($( obj ).attr('data-v-reqd')) {
         // Retrieve error message and value of any checked radio button
         $errMsg = $( obj ).attr('data-v-reqd');
		  // Commented by Kenpath team for fixing firefox browser issue
         // $radVal = $( obj ).find('input:checkbox:checked' ).val(); 
		 $radVal = $( obj ).find('input[type=checkbox]:checked' ).val(); 
         //alert($errMsg);
         // if no value then error
         if (!$radVal) {
            doRadioCheckboxGroupFail($( obj), $errMsg );
            success = false;
            return false;
         } 
      } else if ($( obj ).attr('data-v-reqd-all')){
         //console.log('Fail=' + fail + ' In data-v-reqd-all');
         // Check for situations where all checkboxes are required to be checked
         // Retrieve error message and value of any checked radio button
         $errMsg = $( obj ).attr('data-v-reqd-all');
         $( obj ).find('input:checkbox').each(function() {
            //console.log('Fail=' + fail + ' In data-v-reqd-all each');
            if(!$(this).prop('checked')) {
               //console.log('In data-v-reqd-all each one not checked');
               doRadioCheckboxGroupFail($( obj), $errMsg );
               success = false;
               return false;
            }
         });
      }
      
      if (success) {
         doRadioCheckboxGroupSuccess($( obj ));
         // Still here then return true
         return true;
      } else {
         return false;
      }
   }

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

      // Check length
      if ($( obj ).attr('data-v-len')) {
         // Get the maxlength from 
         $arrLen = $( obj ).attr('data-v-len').split('~');
         if ($myVal.length > $arrLen[0]) {
            doTextSelectFail($( obj ), $arrLen[1]);
            return false; // End out of function
         }
      }
      // Check integer
      if ($( obj ).attr('data-v-int')) {
         if (!isInt($myVal)) {
            $errMsg = $( obj ).attr('data-v-int');
            // Length less than 1 so fail this input
            doTextSelectFail($( obj ), $errMsg);
            return false; // End out of function
         } 
      }
      if ($( obj ).attr('data-v-email')) {
         if (($myVal.length > 0) && (!isEmail($myVal))) {
            $errMsg = $( obj ).attr('data-v-email');
            doTextSelectFail($( obj ), $errMsg);
            return false; // End out of function
         
         }
      }
      if ($( obj ).attr('data-v-sq')) {
         $arrVal = $( obj ).attr('data-v-sq').split('~');
         if ($myVal != $arrVal[0]) {
            $errMsg = $arrVal[1];
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
      // Add error message to array for reporting on screen
      console.log($( obj ).attr('id') + ', ' + message);
      $errArray.push([$( obj ).attr('id'), message]);
   }
   
   function doRadioCheckboxGroupFail(obj, message) {
      // Object passed in is fieldset for radio button group
      // We need to highlight the ul within the fieldset
      // and set 
                     console.log('doRadioCheckboxGroupFail ' + $(obj).prop("tagName") + ' ' + $(obj).find('legend').attr('id'));

      $( obj ).children('ul').addClass('error');
      $( obj ).children('.fieldseterrors').html(message);
      $( obj ).children('legend').removeClass('valid');
      // Add error message to array for reporting on screen
      console.log($( obj ).find('legend').attr('id') + ', ' + message);
      $errArray.push([$( obj ).find('legend').attr('id'), message]);

   }

   function doRadioCheckboxGroupSuccess(obj) {
      // Object passed in is fieldset for radio button group
      // We need to highlight the ul within the fieldset
      // and set 
                     console.log('doRadioCheckboxGroupSuccess ' + $(obj).prop("tagName") + ' ' + $(obj).find('legend').attr('id'));
      
      $( obj ).children('ul').removeClass('error');
      $( obj ).children('.fieldseterrors').html('');
      $( obj ).children('legend').addClass('valid');
   }

   function doDobFail(obj, message) {
      // Object passed in is fieldset for date of birth group
      // We need to highlight the ul within the fieldset
      // and set 
      $( obj ).children('.fieldseterrors').html(message);
      $( obj ).find('li.dob-d select').attr('aria-invalid','true');
      $( obj ).children('legend').removeClass('valid');
      // Add error message to array for reporting on screen
      console.log($( obj ).find('legend').attr('id') + ', ' + message);
      $errArray.push([$( obj ).find('legend').attr('id'), message]);
   }

   function doDobSuccess(obj) {
      // Object passed in is fieldset for date of birth group
      // We need to highlight the ul within the fieldset
      // and set 
      $( obj ).children('.fieldseterrors').html('');
      $( obj ).find('li.dob-d select').attr('aria-invalid','false');
      $( obj ).children('legend').addClass('valid');
   }
   
   //////// Function to update status div - it's an aria-live region
   function update_status(str){
      $( '#status-txt' ).html('');
      $( '#status-txt' ).html(str);
   }
   
   // Run it when window resizes
   $(window).resize(checkSizes);
   //delayed_checkSizes();
   $(window).on('load', function() {
    // executes when complete page is fully loaded, including all frames, objects and images
    //alert("window is loaded");
    //console.log('window.load')
    //checkSizes();
    //delayed_checkSizes();
   });
   $(window).load(function() {
     checkSizes();
   });

});



// General helper functions
function isEmail(email) {
   var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
   return regex.test(email);
}
function isInt(value) {
   return !isNaN(value) && parseInt(Number(value)) == value;
}
function isValidDate(text) {
   // Expecting m/d/yyyy
    var date = Date.parse(text);

    if (isNaN(date)) {
        return false;
    }

    var comp = text.split('/');

    if (comp.length !== 3) {
        return false;
    }

    var m = parseInt(comp[0], 10);
    var d = parseInt(comp[1], 10);
    var y = parseInt(comp[2], 10);
    var date = new Date(y, m - 1, d);
    return (date.getFullYear() == y && date.getMonth() + 1 == m && date.getDate() == d);
}

