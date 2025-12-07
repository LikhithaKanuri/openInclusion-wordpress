// Initialize the specific software field - hide by default, then check if it should be shown
jQuery(document).ready(function($) {
   // First hide the field
   var specificSoftwareField = $('#specific-software-field');
   if (specificSoftwareField.length > 0) {
      specificSoftwareField.hide();
      specificSoftwareField.find('input, textarea').prop('disabled', true);
      
      // Then check if any triggering checkboxes are already checked (e.g., when editing)
      var screenReaderSelected = $('#DigitalandScreenTechnologies_ScreenReader').prop('checked');
      var screenMagnifierSelected = $('#DigitalandScreenTechnologies_ScreenMagnifier').prop('checked');
      var dragonSelected = $('#DigitalandScreenTechnologies_Dragonandother').prop('checked');
      var readAloudSelected = $('#DigitalandScreenTechnologies_ReadAloudSoftware').prop('checked');
      
      if (screenReaderSelected || screenMagnifierSelected || dragonSelected || readAloudSelected) {
         specificSoftwareField.show();
         specificSoftwareField.find('input, textarea').prop('disabled', false);
      }
   }
});

function createCookie(name, value, days) {
   if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      var expires = "; expires=" + date.toGMTString();
   }
   else var expires = "";
   document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
   var nameEQ = name + "=";
   var ca = document.cookie.split(';');
   for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
   }
   return null;
}

function eraseCookie(name) {
   createCookie(name, "", -1);
}

jQuery(document).ready(function ($) {

   if (sessionStorage.getItem('previousButtonClicked') === 'true') {
      // Temporarily disable flying-focus for a short period
      setTimeout(function() {
         sessionStorage.removeItem('previousButtonClicked');
      }, 1000);
      // Add class to body to signal flying-focus to skip
      $('body').addClass('previous-navigation-active');
      setTimeout(function() {
         $('body').removeClass('previous-navigation-active');
      }, 1000);
   }
   var $errInProgress = false;
   var $fieldId = '';
   var $errArray = [];

   // Dynamic region/state/province functionality
   function updateRegionOptions(country) {
      var regionSelect = $('#inf_field_region');
      var regionLabel = $('label[for="inf_field_region"] .text');
      
      // Clear existing options
      regionSelect.empty();
      
      // Define country-specific options
      var regionOptions = {
         '01007': [ // UK
            {value: '', text: 'Please select from list'},
            {value: '01101-Community-Region-UK-London', text: 'London'},
            {value: '01102-Community-Region-UK-SouthEast', text: 'South East'},
            {value: '01103-Community-Region-UK-SouthWest', text: 'South West'},
            {value: '01104-Community-Region-UK-EastEng', text: 'East England'},
            {value: '01105-Community-Region-UK-EastMidlands', text: 'East Midlands'},
            {value: '01106-Community-Region-UK-WestMidlands', text: 'West Midlands'},
            {value: '01107-Community-Region-UK-YorksHumber', text: 'Yorkshire and The Humber'},
            {value: '01108-Community-Region-UK-NorthEast', text: 'North East'},
            {value: '01109-Community-Region-UK-NorthWest', text: 'North West'},
            {value: '01110-Community-Region-UK-Scotland', text: 'Scotland'},
            {value: '01111-Community-Region-UK-Wales', text: 'Wales'},
            {value: '01112-Community-Region-UK-Nireland', text: 'Northern Ireland'},
            {value: '01112-Community-Region-UK-BritishIsles', text: 'British Isles'}
         ],
         '01008': [ // USA
            {value: '', text: 'Please select from list'},
            {value: '01113-Community-Region-USA-AL', text: 'Alabama'},
            {value: '01113-Community-Region-USA-AK', text: 'Alaska'},
            {value: '01113-Community-Region-USA-AZ', text: 'Arizona'},
            {value: '01113-Community-Region-USA-AR', text: 'Arkansas'},
            {value: '01113-Community-Region-USA-CA', text: 'California'},
            {value: '01113-Community-Region-USA-CO', text: 'Colorado'},
            {value: '01113-Community-Region-USA-CT', text: 'Connecticut'},
            {value: '01113-Community-Region-USA-DE', text: 'Delaware'},
            {value: '01113-Community-Region-USA-FL', text: 'Florida'},
            {value: '01113-Community-Region-USA-GA', text: 'Georgia'},
            {value: '01113-Community-Region-USA-HI', text: 'Hawaii'},
            {value: '01113-Community-Region-USA-ID', text: 'Idaho'},
            {value: '01113-Community-Region-USA-IL', text: 'Illinois'},
            {value: '01113-Community-Region-USA-IN', text: 'Indiana'},
            {value: '01113-Community-Region-USA-IA', text: 'Iowa'},
            {value: '01113-Community-Region-USA-KS', text: 'Kansas'},
            {value: '01113-Community-Region-USA-KY', text: 'Kentucky'},
            {value: '01113-Community-Region-USA-LA', text: 'Louisiana'},
            {value: '01113-Community-Region-USA-ME', text: 'Maine'},
            {value: '01113-Community-Region-USA-MD', text: 'Maryland'},
            {value: '01113-Community-Region-USA-MA', text: 'Massachusetts'},
            {value: '01113-Community-Region-USA-MI', text: 'Michigan'},
            {value: '01113-Community-Region-USA-MN', text: 'Minnesota'},
            {value: '01113-Community-Region-USA-MS', text: 'Mississippi'},
            {value: '01113-Community-Region-USA-MO', text: 'Missouri'},
            {value: '01113-Community-Region-USA-MT', text: 'Montana'},
            {value: '01113-Community-Region-USA-NE', text: 'Nebraska'},
            {value: '01113-Community-Region-USA-NV', text: 'Nevada'},
            {value: '01113-Community-Region-USA-NH', text: 'New Hampshire'},
            {value: '01113-Community-Region-USA-NJ', text: 'New Jersey'},
            {value: '01113-Community-Region-USA-NM', text: 'New Mexico'},
            {value: '01113-Community-Region-USA-NY', text: 'New York'},
            {value: '01113-Community-Region-USA-NC', text: 'North Carolina'},
            {value: '01113-Community-Region-USA-ND', text: 'North Dakota'},
            {value: '01113-Community-Region-USA-OH', text: 'Ohio'},
            {value: '01113-Community-Region-USA-OK', text: 'Oklahoma'},
            {value: '01113-Community-Region-USA-OR', text: 'Oregon'},
            {value: '01113-Community-Region-USA-PA', text: 'Pennsylvania'},
            {value: '01113-Community-Region-USA-RI', text: 'Rhode Island'},
            {value: '01113-Community-Region-USA-SC', text: 'South Carolina'},
            {value: '01113-Community-Region-USA-SD', text: 'South Dakota'},
            {value: '01113-Community-Region-USA-TN', text: 'Tennessee'},
            {value: '01113-Community-Region-USA-TX', text: 'Texas'},
            {value: '01113-Community-Region-USA-UT', text: 'Utah'},
            {value: '01113-Community-Region-USA-VT', text: 'Vermont'},
            {value: '01113-Community-Region-USA-VA', text: 'Virginia'},
            {value: '01113-Community-Region-USA-WA', text: 'Washington'},
            {value: '01113-Community-Region-USA-WV', text: 'West Virginia'},
            {value: '01113-Community-Region-USA-WI', text: 'Wisconsin'},
            {value: '01113-Community-Region-USA-WY', text: 'Wyoming'},
            {value: '01113-Community-Region-USA-DC', text: 'Washington DC'}
         ],
         '01011': [ // Canada
            {value: '', text: 'Please select from list'},
            {value: '011xx-Community-Region-Canada-AB', text: 'Alberta'},
            {value: '011xx-Community-Region-Canada-BC', text: 'British Columbia'},
            {value: '011xx-Community-Region-Canada-MB', text: 'Manitoba'},
            {value: '011xx-Community-Region-Canada-NB', text: 'New Brunswick'},
            {value: '011xx-Community-Region-Canada-NL', text: 'Newfoundland and Labrador'},
            {value: '011xx-Community-Region-Canada-NT', text: 'Northwest Territories'},
            {value: '011xx-Community-Region-Canada-NS', text: 'Nova Scotia'},
            {value: '011xx-Community-Region-Canada-NU', text: 'Nunavut'},
            {value: '011xx-Community-Region-Canada-ON', text: 'Ontario'},
            {value: '011xx-Community-Region-Canada-PE', text: 'Prince Edward Island'},
            {value: '011xx-Community-Region-Canada-QC', text: 'Quebec'},
            {value: '011xx-Community-Region-Canada-SK', text: 'Saskatchewan'},
            {value: '011xx-Community-Region-Canada-YT', text: 'Yukon'}
         ],
         '01009': [ // Australia
            {value: '', text: 'Please select from list'},
            {value: '011xx-Community-Region-Australia-ACT', text: 'Australian Capital Territory'},
            {value: '011xx-Community-Region-Australia-NSW', text: 'New South Wales'},
            {value: '011xx-Community-Region-Australia-NT', text: 'Northern Territory'},
            {value: '011xx-Community-Region-Australia-QLD', text: 'Queensland'},
            {value: '011xx-Community-Region-Australia-SA', text: 'South Australia'},
            {value: '011xx-Community-Region-Australia-TAS', text: 'Tasmania'},
            {value: '011xx-Community-Region-Australia-VIC', text: 'Victoria'},
            {value: '011xx-Community-Region-Australia-WA', text: 'Western Australia'}
         ],
         '01010': [ // Ireland
            {value: '', text: 'Please select from list'},
            {value: '011xx-Community-Region-Ireland-Leinster', text: 'Leinster'},
            {value: '011xx-Community-Region-Ireland-Ulster', text: 'Ulster'},
            {value: '011xx-Community-Region-Ireland-Munster', text: 'Munster'},
            {value: '011xx-Community-Region-Ireland-Connacht', text: 'Connacht'}
         ],
         '01012': [ // New Zealand
            {value: '', text: 'Please select from list'},
            {value: '011xx-Community-Region-NewZealand-Auckland', text: 'Auckland'},
            {value: '011xx-Community-Region-NewZealand-NewPlymouth', text: 'New Plymouth'},
            {value: '011xx-Community-Region-NewZealand-Wellington', text: 'Wellington'},
            {value: '011xx-Community-Region-NewZealand-Nelson', text: 'Nelson'},
            {value: '011xx-Community-Region-NewZealand-Canterbury', text: 'Canterbury'},
            {value: '011xx-Community-Region-NewZealand-Otago', text: 'Otago'}
         ]
      };
      
      // Update label based on country
      var labelText = 'What region do you live in?';
      if (country === '01008') { // USA
         labelText = 'What state do you live in?';
      } else if (country === '01007') { // UK
         labelText = 'What region do you live in?';
      } else if (country === '01011') { // Canada
         labelText = 'What province do you live in?';
      } else if (country === '01009') { // Australia
         labelText = 'What state do you live in?';
      } else if (country === '01010') { // Ireland
         labelText = 'What province do you live in?';
      } else if (country === '01012') { // New Zealand
         labelText = 'What region do you live in?';
      }
      
      if (regionLabel.length) {
         regionLabel.text(labelText);
      }
      
      // Populate options
      if (regionOptions[country]) {
         // Ensure it's a select field with proper wrapper
         if (regionSelect.prop('tagName') !== 'SELECT') {
            regionSelect.replaceWith('<div class="custom"><select name="inf_field_region" id="inf_field_region"></select></div>');
            regionSelect = $('#inf_field_region');
         } else {
            // If it's already a select, wrap it in the custom div if not already wrapped
            if (!regionSelect.parent().hasClass('custom')) {
               regionSelect.wrap('<div class="custom"></div>');
            }
         }
         $.each(regionOptions[country], function(index, option) {
            regionSelect.append($('<option></option>').val(option.value).text(option.text));
         });
      } else {
         // For other countries, show a text input instead
         if (regionSelect.prop('tagName') !== 'INPUT') {
            regionSelect.replaceWith('<input type="text" name="inf_field_region" id="inf_field_region" maxlength="250" placeholder="Please enter your region/state/province">');
         }
         if (regionLabel.length) {
            regionLabel.text('What region do you live in?');
         }
      }
   }
   
   // Handle country change
   $('#inf_field_country').on('change', function() {
      var selectedCountry = $(this).val();
      updateRegionOptions(selectedCountry);
      // Re-bind region validation because control may have been replaced
      $(document).off('change.inputRegion input.inputRegion');
      $(document).on('change.inputRegion input.inputRegion', '#inf_field_region', function(){
         validateTextSelectField(this);
      });
   });
   
   // Initialize on page load if country is already selected
   var initialCountry = $('#inf_field_country').val();
   if (initialCountry) {
      updateRegionOptions(initialCountry);
   }
   
   // Ensure region field is visible
   $('#region-field-container').show();
   $('#inf_field_region').show();
   
   // Bind region validation for current control as well
   $(document).on('change.inputRegion input.inputRegion', '#inf_field_region', function(){
      validateTextSelectField(this);
   });

   $('#um-submit-btn').removeAttr("class");
   //var registerButtonHTML = $('div.um-right').html();
   //newButton = "<button>"+ registerButtonHTML + "</button>";
   //$('div.um-right').html(newButton);
   //$('div.um-right').attr("style", "text-align:left");
   //$('div.um-right').find("a").removeAttr("class");
   registerButton = "<a href='https://openinclusion.com/register'><input type='button' value='Register'></a>";
   guestButton = "<a href='https://community.openinclusion.com/'><input type='button' value='Continue as Guest'></a>";
   $('div.um-left').append(registerButton);
   $('div.um-right').html(guestButton);
   $('div.um-left').attr("style", "text-align:right");
   $('div.um-right').attr("style", "text-align:left");
   // $('div.um-form').find("form").attr("style", "margin-bottom:50px;");
   $('div.um-col-alt-b').find("a").removeAttr('class');

   // Code here will be executed on document ready. Use $ as normal.
   $('html').addClass('js').removeClass('no-js');
   $('#inf_field_TemporaryAccessNeed').parent().attr('id', 'tanbox');
   if ($('#TemporaryAccessNeedsYes').prop('checked')) {
      $('#tanbox').show();
   }
   else {
      $('#tanbox').hide();
   }

   $('#TemporaryAccessNeedsYes').click(function () {
      if ($(this).prop('checked')) {
         $('#TemporaryAccessNeedsNo').prop('checked', false);
         $('#TemporaryAccessNeedsNA').prop('checked', false);
         $('#tanbox').show();
      }
      else {
         $('#tanbox').hide();
      }
   });

   $('#TemporaryAccessNeedsNo').click(function () {
      if ($(this).prop('checked')) {
         $('#TemporaryAccessNeedsYes').prop('checked', false);
         $('#TemporaryAccessNeedsNA').prop('checked', false);
         $('#tanbox').hide();
      }
   });

   $('#TemporaryAccessNeedsNA').click(function () {
      if ($(this).prop('checked')) {
         $('#TemporaryAccessNeedsYes').prop('checked', false);
         $('#TemporaryAccessNeedsNo').prop('checked', false);
         $('#tanbox').hide();
      }
   });




   /* Functions for access controls */
   function show_alt() {
      $('img').each(function () {
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

   $(".access-text-zoom a").click(function () {

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

   $(".access-contrast").click(function () {
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

   $(".access-text-only").click(function () {
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

   var dynamic_mq = function () {
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
   $('.blog-post, .blog-post-first').each(function () {
      console.log('hello');
      $(this).addClass('clickable');
      var url = $(this).attr('data-url');
      if (url) {
         $(this).on('click', function (e) {
            $(location).attr('href', url);
         });
      }
   });




   ///////////////////// Tab Panel Functionality ////////

   //// Initial set up

   // Look for every section.tab-panel-wrapper
   $('section.tab-panel-wrapper').each(function () {
      var $thisId = $(this).attr('id');

      console.log('Hello ' + $thisId);

      // Find tab header links and add attributes as appropriate
      $('#tab-hdrs-' + $thisId).find('.tab-header-link').each(function () {
         var $controlsId = $(this).attr('data-id');
         var $myId = $(this).attr('id');
         $(this).attr('aria-controls', $controlsId);

         // Find panels and add attributes as necessary
         var $my_panel = $('#' + $controlsId);
         $my_panel.attr('role', 'region').attr('aria-labelledby', $myId);

         // Check which panel to make visible and make all the others hidden
         if ($my_panel.attr('data-selected') == 'true') {
            $my_panel.attr('aria-hidden', 'false').removeClass('hide');
            $(this).attr('aria-expanded', 'true');
            $(this).parent('li').addClass('expanded');
         } else {
            $my_panel.attr('aria-hidden', 'true').addClass('hide');
            $(this).attr('aria-expanded', 'false');
         }


      });

   });

   $('.tab-header-link').on('keydown', function (e) {
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
   $('.tab-header-link').on('click', function (e) {
      e.preventDefault();
      // Check if selected panel already open, if so, bail out
      $my_panel = $(this).attr('data-id'); // Get the id of my panel

      console.log('Hello in ' + $my_panel);

      // Identify other panels in the tab panel group
      // Find the ancestor that is an <ul class="tab-headers"> and then find all links in it.
      $(this).closest('.tab-headers').find('.tab-header-link').each(function () {
         $linked_panel = $(this).attr('data-id');
         console.log('Hello in ' + $linked_panel);

         // Close or open panels - adjusting aria as necessary
         if ($my_panel == $linked_panel) {
            // Corresponds to one that has been clicked
            $(this).attr('aria-expanded', 'true');
            $('#' + $linked_panel).removeClass('hide').attr('aria-hidden', 'false');
            // Now add an 'expanded' class to containing <li>
            $(this).parent('li').addClass('expanded');

         } else {
            // Not the one that's been clicked
            $(this).attr('aria-expanded', 'false');
            $('#' + $linked_panel).addClass('hide').attr('aria-hidden', 'true');
            // Now add an 'expanded' class to containing <li>
            $(this).parent('li').removeClass('expanded');
         }
      });


      // put focus on selected
      $('#' + $my_panel).focus();

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

   String.prototype.repeat = function (num) {
      return new Array(num + 1).join(this);
   }
   // Code to create a table of contents for long posts or pages
   // Code based on that found at: https://codepen.io/jtojnar/full/Juiop

   // Functionality looks for an element with an id="toc" in the page. It will not run
   // if it doesn't find such an element.
   // YOU MUST NEVER HAVE MORE THAN ONE TOC ELEMENT IN THE PAGE
   //console.log('In js');
   $("#toc").each(function () {
      var ToC =
         "<nav role='navigation' aria-label='Page table of contents' class='table-of-contents'>" +
         "<h2>On this page:</h2>" +
         "<ul>";

      var newLine, el, title, link, level, baseLevel, counter;

      // Initialise counter - this will be used to ensure that any id's allocated to headings are unique
      counter = 1;

      // OK search for all the headings within the <main> element
      $("main h2, main h3, main h4, main h5, main h6").each(function () {

         el = $(this);
         title = el.text();

         // Check if the header has an id set. If not, make a unique one
         if (!el.attr("id")) {
            el.attr("id", "toc-h" + counter)
         }

         // Get the id to use as a link
         link = "#" + el.attr("id");

         //console.log(title + ' ' + link);

         var prevLevel = level || 0;   // First time through will end up as zero
         level = this.nodeName.substr(1); // Get the current level from the heading tag
         if (!baseLevel) { // make sure you start with highest level of heading or it won't work
            baseLevel = level;
         }


         // Now decide what to put out based on whether we're moving to a different heading level or not
         if (prevLevel == 0) {
            newLine = '<li>';
         } else if (level == prevLevel) {
            newLine = '</li><li>';
         } else if (level > prevLevel) {
            newLine = '<ul><li>'.repeat(level - prevLevel);
         } else if (level < prevLevel) {
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

   $(document).scroll(function () {
      var y = $(this).scrollTop();
      if (y > 800) {
         $('#back-to-top').not('.zoom #back-to-top').fadeIn();
      } else {
         $('#back-to-top').fadeOut();
      }
   });

   $(".show-story-detail").click(function () {
      var id = $(this).data('id');
      $("#story-detail-" + id).slideToggle();
      //alert($(this).html());
      if ($(this).html() == "Learn More") {
         $(this).html('Close <svg viewBox="0 0 27 16" class="icon icon-accordian-close"><use xlink:href="#icon-accordian-close"></use></svg>');
      } else {
         $(this).html('Learn More');
      }
      return false;
   });



   $('input#show-mobile-nav').change(function () {
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
   $('#cookie-accept-js').on('click', function (e) {
      //console.log('Hello in jquery function');
      createCookie('opencookieaccept', '1', 360);
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


   $('#cookie-accept-js').on('click', function (e) {
      //console.log('Hello in jquery function');
      createCookie('opencookieaccept', '1', 360);
      $('#banner-cookie-bar').hide('slow');
      e.preventDefault();
      $('#logo').focus();
   });

   //////// Function to control transcripts visibility
   $('button.transhow').on('click', function (e) {
      // Retrieve the id relevant to this transcript
      var b_id = $(this).data('id');

      // Make the transcript long, and put focus on hide button
      $('#' + b_id).addClass('long');
      $('#bh' + b_id).focus();

   });


   $('button.tranhide').on('click', function (e) {
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
      $('#team-boxes').find('.team-box[aria-selected="true"]').each(function () {
         var $thisId = $(this).attr('data-id');

         console.log('Hello');

         // Get the revised bottom value
         var $myBelow = get_my_bottom(this);

         //Get info about #team-boxes
         var $tbOff = $('#team-boxes').offset();
         var $tbLeft = $tbOff.left;
         var $myWidth = $('#team-boxes').width();

         // change width and position of panel
         $('#team-biog-' + $thisId)
            .css('width', $myWidth)
            .offset({ top: $myBelow, left: $tbLeft })

         // Adjust height of lower margin of biog panel
         $panelH = $('#team-biog-' + $thisId).height();

         $(this).css('margin-bottom', $panelH + 'px');



      });
   }

   function check_box_heights_simple(obj) {
      var h = 0;
      if (obj.length) {
         // Reset heights
         $(obj).css('height', 'auto');

         // Check over each one and store height if more than we've already got
         $(obj).each(function () {
            h = Math.max(h, Math.ceil($(this).height()));
         });

         // Adjust heights to the largest value
         $(obj).css('height', h + 'px');
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
   $('#s').attr('placeholder', 'Search site');


   // Initialise team boxes - they work like an accordion

   $('#team-boxes').find('.team-link').each(function () {
      var $thisId = $(this).attr('data-id');

      // Add aria attributes
      $(this).attr('aria-controls', 'team-biog-' + $thisId)
         .attr('aria-selected', 'false')
         .attr('role', 'button');
   });

   // Initialise team biogs - they work like an accordion
   $('#team-biogs').find('.team-biog').each(function () {
      $thisId = $(this).attr('data-id');

      // set aria attributes
      $(this).attr('aria-hidden', 'true').attr('role', 'region');
   });


   // Routines for team box showing
   $('#team-boxes').find('.team-box').not('.no-link').on('click', function (e) {
      e.preventDefault();
      // Check boxes are all sorted
      checkSizes();

      var link = $(this).find('.team-link');

      // Store current ID
      var id = link.attr('data-id');

      var parentbox = $('#team-box-' + id);
      var parentlink = $('#team-box-' + id).find('a.team-link');

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

         console.log('get_my_bottom(parentbox)=' + $myBelow);

         //Get info about #team-boxes
         $tbOff = $('#team-boxes').offset();
         $tbLeft = $tbOff.left;
         $myWidth = $('#team-boxes').width();
         console.log('$tbLeft=' + $tbLeft + ' $myWidth=' + $myWidth);

         // show this one
         $('#team-biog-' + id)
            .removeAttr('aria-hidden')
            .show()
            .css('width', $myWidth)
            .offset({ top: $myBelow, left: $tbLeft })
            .focus();

         $panelH = $('#team-biog-' + id).height();
         console.log('$panelH=' + $panelH);

         $(parentbox)/* .css('margin-bottom', $panelH+'px') */.addClass('selected');
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

   $('#team-biogs').find('.biog-close').find('.biog-close-link').on('click', function (e) {
      // Close the open panel and change the style etc of the parent team-box
      e.preventDefault();

      var id = $(this).attr('data-id');

      close_this_panel(id);
   });

   function close_this_panel(id) {
      // function to close an open biog panel and tend to parent team-boc
      // parameter passed in is id value of this panel
      // Close the biog panel
      $('#team-biog-' + id)
         .attr('aria-hidden', 'true')
         .hide();

      // Work on parent link and give it focus
      $('#team-link-' + id).attr('aria-selected', 'false').focus();
      // Work on parent team-box
      $('#team-box-' + id).css('margin-bottom', 0).removeClass('selected');

   }

   // Form validation etc ///////////////////////////////////////////////////////////////////////////////////////
   // Set up appearances for return from server side validation
   $('input[aria-invalid="false"]').parent('label').addClass('valid');
   $('select[aria-invalid="false"]').parent('label').addClass('valid');
   $('textarea[aria-invalid="false"]').parent('label').addClass('valid');

   // Check some fields when we move away
   $('.contact input[type="text"], .contact textarea, .contact select').on('blur', function (e) {
      // Perform required validation
      validateTextSelectField($(this));
   });
   // Check dob year
   $('#inf_custom_YearBorn').on('blur', function (e) {
      // validate_dob_year($(this));
   });
   // Check select boxes when they change
   $('.contact select').on('change', function (e) {
      // Perform required validation
      validateTextSelectField($(this));
   });
   // Check radio buttons when value changes
   $('.contact').find('fieldset').find('input[type="radio"]').on('change', function (e) {
      // Perform required validation - we're checking for required
      // Check if radio button group is required to have one selected

      // traverse up to fieldset before passing to function.
      $parFieldset = $(this).closest('fieldset');
      $ret = validateRadioGrp($($parFieldset));

   });

   $("#inf_field_re_Email").blur(function () {
      if ($(this).val() == $("#inf_field_Email").val()) {
         $(this).removeAttr("style");
      }
      else {
         $(this).attr("style", "background: #D99F9F; border: 1px solid rgba(255, 0,0, 0.8)");
      }
   });

   /*
   $("#inf_field_countryphonecode").attr("style","width:25%;float:left");
   $("#inf_field_Phone2").attr("style","width:75%");
   $("#inf_field_countryphonecode").insertBefore( "#inf_field_Phone2" );
   $("#inf_field_countryphonecode").wrap("<div class='custom2'></div>");
   */

   // Very simple approach: just move phone number field next to country code
   $("#licountryphonecode").append($("#liphonenumber").html());
   $("#liphonenumber").html("");


   $("#PreferToContactOthers").hide();
   if ($("#PreferToContact_Others").prop("checked")) {
      $("#PreferToContactOthers").show();
   }

   $("#PreferToContact_Others").click(function () {
      if ($(this).prop("checked")) {
         $("#PreferToContactOthers").show();
      }
      else {
         $("#PreferToContactOthers").hide();
      }
   });
   $("#inf_field_Password_reenter").blur(function () {
      if ($(this).val() == $("#inf_field_Password").val()) {
         $(this).removeAttr("style");
      }
      else {
         $(this).attr("style", "background: #D99F9F; border: 1px solid rgba(255, 0,0, 0.8)");
         document.getElementById('passwordMismatch').innerHTML = 'Your two passwords do not match or Your password does not meet the requirements of more than eight characters and a mix of letters and numbers'
      }
   });

   // Old country change handler removed - now using new dynamic region functionality above

   $("#_PreferToContact_Others").click(function () {
      var msg = $(this).parents("fieldset").attr("data-v-reqd")
      if ($(this).prop("checked")) {
         $("#PreferToContactOthers").find("fieldset").attr("data-v-reqd", msg);
      }
      else {
         $("#PreferToContactOthers").find("fieldset").removeAttr("data-v-reqd");
      }
   });

   $("#inf_option_Gender-legend").parent().find("input[type=radio]").each(function () {
      if ($(this).val() != '776') {
         $(this).click(function () {
            clearOffText(this, 'inf_option_Gender_opentext');
         });
      }
      else {
         $(this).click(function () {
            clearOffText(this, 'addrequired');
         });
      }
   });



   $("#inf_option_Gender_opentext").focus(function () {
      $(this).parent().find("input").trigger("click");
   });

   // Part 2 Step 1: Over 18 toggle and screen-out handling
   (function() {
      var $form = $('#part2-step1-form');
      if (!$form.length) return;

      return;

      function getFieldLiByInput($input) {
         if (!$input.length) return $();
         var $fieldsetLi = $input.closest('fieldset').closest('li');
         if ($fieldsetLi.length) return $fieldsetLi;
         return $input.closest('li');
      }

      var $over18Yes = $('#inf_field_over18_yes');
      var $over18No = $('#inf_field_over18_not_yet');
      var $over18Li = getFieldLiByInput($over18Yes.length ? $over18Yes : $over18No);

      var $yearBornLi = getFieldLiByInput($('#inf_custom_YearBorn'));
      var $hasDisabilityLi = getFieldLiByInput($('#inf_field_hasDisability_yes'));
      if (!$hasDisabilityLi.length) { $hasDisabilityLi = getFieldLiByInput($('#inf_field_hasDisability_no')); }
      var $relationshipLi = getFieldLiByInput($('#RelationShip_disabled_person'));
      if (!$relationshipLi.length) { $relationshipLi = getFieldLiByInput($('input[id^="RelationShip_"]').first()); }
      var $submitLi = getFieldLiByInput($('#submit_part2_step1'));

      // Insert screen-out message container after the over18 field
      var messageText = "Thanks for your interest! Currently, we're only able to accept members aged 18 and over for our online community.";
      var $screenMsg = $('<li class="clear" id="over18-screenout-msg" style="display:none;"><div role="alert" aria-live="polite">'+ messageText +'</div></li>');
      if ($over18Li.length) {
         $over18Li.after($screenMsg);
      }

      function setDisabled($li, disabled) {
         if (!$li || !$li.length) return;
         $li.find('input, select, textarea, button').each(function() {
            var $el = $(this);
            if (disabled) {
               $el.data('orig-disabled', $el.prop('disabled'));
               $el.prop('disabled', true).attr('aria-disabled', 'true');
            } else {
               $el.prop('disabled', false).removeAttr('disabled').attr('aria-disabled', 'false');
            }
         });
      }

      function toggleUnder18State(isUnder18) {
         if (isUnder18) {
            // Hide remaining fields
            $yearBornLi.hide();
            $hasDisabilityLi.hide();
            $relationshipLi.hide();
            // Disable their controls to avoid client-side validation
            setDisabled($yearBornLi, true);
            setDisabled($hasDisabilityLi, true);
            setDisabled($relationshipLi, true);
            // Keep message hidden until submit
            $('#over18-screenout-msg').hide();
         } else {
            // Show fields
            $yearBornLi.show();
            $hasDisabilityLi.show();
            $relationshipLi.show();
            // Re-enable controls
            setDisabled($yearBornLi, false);
            setDisabled($hasDisabilityLi, false);
            setDisabled($relationshipLi, false);
            // Ensure message hidden
            $('#over18-screenout-msg').hide();
         }
      }

      // Bind change handler
      $form.on('change', 'input[name="inf_field_over18"]', function() {
         var val = $(this).val();
         toggleUnder18State(val !== 'Yes');
      });

      // Initialize on load based on current selection (default to hidden unless explicitly Yes)
      var initial = $form.find('input[name="inf_field_over18"]:checked').val();
      var isUnder18Init = (initial !== 'Yes');
      toggleUnder18State(isUnder18Init);

      // Prevent submit if Not Yet selected; redirect to over18 page
      $form.on('submit', function(e) {
         var selected = $form.find('input[name=\"inf_field_over18\"]:checked').val();
         if (selected === 'Not Yet') {
            e.preventDefault();
            window.location.href = 'https://staging4.openinclusion.com/over18/';
            return false;
         } else {
            $('#over18-screenout-msg').hide();
         }
      });

      // Also intercept submit button to redirect immediately on Not Yet
      $form.find('input[type=\"submit\"]').on('click', function(e) {
         var selected = $form.find('input[name=\"inf_field_over18\"]:checked').val();
         if (selected === 'Not Yet') {
            e.preventDefault();
            e.stopImmediatePropagation();
            e.stopPropagation();
            window.location.href = 'https://staging4.openinclusion.com/over18/';
            return false;
         } else {
            $('#over18-screenout-msg').hide();
         }
      });

         // Real-time email matching validation
   $(document).on('blur', '#inf_field_re_Email', function() {
      var email1 = $('#inf_field_Email').val();
      var email2 = $(this).val();
      
      if (email2.length > 0 && email1 !== email2) {
         doTextSelectFail($(this), 'Email addresses do not match');
      } else if (email2.length > 0 && email1 === email2) {
         doTextSelectSuccess($(this));
      }
   });
   
   // Also validate when the main email field changes
   $(document).on('blur', '#inf_field_Email', function() {
      var email1 = $(this).val();
      var email2 = $('#inf_field_re_Email').val();
      
      if (email2.length > 0 && email1 !== email2) {
         doTextSelectFail($('#inf_field_re_Email'), 'Email addresses do not match');
      } else if (email2.length > 0 && email1 === email2) {
         doTextSelectSuccess($('#inf_field_re_Email'));
      }
   });

   })();
   
   
   /****************** Form is submitted **************************/
   $('.contact').find('input[type="submit"]').on('click', function (e) {
      // Check if this is a "Previous" button - if so, skip ALL validation
      var buttonName = $(this).attr('name');
      var buttonClass = $(this).attr('class');
      
      // Check if it's a previous button by name or class
      if ((buttonName && (buttonName.indexOf('previous_') === 0 || buttonName === 'previous')) ||
          (buttonClass && buttonClass.indexOf('previous-button') >= 0)) {
         // This is a previous button - remove all validation attributes temporarily
         var $form = $(this).parents('form');
         
         // Remove data-v-reqd attributes from all fields to bypass validation
         $form.find('[data-v-reqd]').each(function() {
            $(this).attr('data-v-reqd-backup', $(this).attr('data-v-reqd'));
            $(this).removeAttr('data-v-reqd');
         });
         
         // Remove fieldset validation attributes (including reqd-all for checkboxes)
         $form.find('fieldset[data-v-reqd]').each(function() {
            $(this).attr('data-v-reqd-backup', $(this).attr('data-v-reqd'));
            $(this).removeAttr('data-v-reqd');
         });
         $form.find('fieldset[data-v-reqd-all]').each(function() {
            $(this).attr('data-v-reqd-all-backup', $(this).attr('data-v-reqd-all'));
            $(this).removeAttr('data-v-reqd-all');
         });
         
         // Clear any existing error displays
         $form.find('.errors').html('');
         $form.find('.fieldseterrors').html('');
         $form.find('[aria-invalid]').attr('aria-invalid', 'false');
         
         // Allow form submission without validation
         return true;
      }
      
      $fieldId = '';
      $errInProgress = false;
      // Initialise error array
      $errArray = [];

      // Perform required validation - we're checking for length, email format, 
      // and required all
      $(this).parents('form')
         .find('input[type="text"], input[type="password"], textarea, select')
         .each(function () {
            // Call the validation routine on this field
            if (!validateTextSelectField($(this))) {
               // A validation aerror found
               if (!$errInProgress) {
                  // Set global variable
                  $errInProgress = true;

                  // strore ID of first field with error
                  $fieldId = $(this).attr('id');
               }
            }
         });

      // Check fieldsets that have a data type
      $(this).parents('form')
         .find('fieldset[data-type]').each(function () {
            // Find data type and branch accordingly
            $dataType = $(this).attr('data-type');

            switch ($dataType) {
               case 'radio':
                  // Check if radio button group is required to have one selected
                  if (!validateRadioGrp($(this))) {
                     if (!$errInProgress) {
                        $errInProgress = true;  // Set global variable
                     }
                  }
                  break;
               case 'chkbox':
                  // Check if checkbox group is required to have one selected
                  if (!validateCheckboxGrp($(this))) {
                     if (!$errInProgress) {
                        $errInProgress = true;  // Set global variable
                     }
                  }
                  break;
               case 'dob':
                  if (!validateDateOfBirth($(this))) {
                     if (!$errInProgress) {
                        $errInProgress = true; // Set global variable
                     }
                  }
                  break;
            }

         });

      if ($('#inf_custom_YearBorn').val()) {
         const d = new Date();
         let year = d.getFullYear();
         var inBornYear = $('#inf_custom_YearBorn').val();
         
         // Check if year is exactly 4 digits
         if (!/^\d{4}$/.test(inBornYear)) {
            $errInProgress = true;
            doTextSelectFail($('#inf_custom_YearBorn'), 'Please ensure format is 4 numbers');
         }
         else {
            var diffInYear = parseInt(year) - parseInt(inBornYear);
            if (diffInYear < 18) {
               $errInProgress = true;
               doTextSelectFail($('#inf_custom_YearBorn'), 'You need to be 18 or over to join the Open Inclusion community and take part in research.');
            }
            else {
               doTextSelectSuccess($('#inf_custom_YearBorn'));
            }
         }
      }

      // Perform any special validation routines here
      //if they tick 'preferred on text relay', they need to have filled in a landline or mobile number (to confirm when Stephen emails me)
      if ($('#inf_option_CallsviaTextRelayServiceONLY').prop('checked')) {
         // Check that landline number has a value

         if (($('#inf_field_Phone1').val().length < 1) && ($('#inf_field_Phone2').val().length < 1)) { // If it's empty
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

      if ($errInProgress) {
         e.preventDefault();

         // alert('Sorry, parts of the form were not completed correctly. Please review and submit again. Closing this window will take you to a list of the problems we found.');
         // Process error array
         //console.log('Error length = ' + $errArray.length); form-error
         print_out_form_errors('form-error', $errArray);

         //$('#'+$fieldId).focus();
      } else {
         //alert(my_ajax_object.ajax_url);
         //my_ajax_object.ajax_url;
         jQuery('.loader').css("display", "block");
         //jQuery('#submit').css("cursor", "no-drop");
         $('#contactform').submit();
         /*
         var formDataa = $('#contactform').serialize();
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
              },
              error: function(xhr){
                 alert("An error occured: " + xhr.status + " " + xhr.statusText);
               }
           });
          
          return false;  
          */
         //return true;
      }
   });

   function print_out_form_errors(err_id, err_arr) {
      // Print out errors at top of screen
      // err_id = id of <section> containing errors
      // err_arr = raw array of errors. (0) = id of element, (1) = error message
      // Process error array
      console.log('error', err_arr)
      console.log('Error length = ' + err_arr.length);

      // Check if anything to report - if not, return
      if (err_arr.length == 0) return;

      // We've got something so start the output
      var out_html = '<section tabindex="-1" aria-labelledby="error-heading" id="' + err_id + '-section">';
      // out_html += '<h2 id="error-heading">Form submission problems</h2><ul>';

      // // Loop through array to pull out data
      // for (i = 0; i < err_arr.length; i++) {
      //    out_html +='<li><a href="#' + err_arr[i][0] + '">' + err_arr[i][1] + '</a></li>';
      // }

      out_html += '</ul></section>';
      const element = document.getElementById(err_arr[0][0]);
      var isStep7Page = window.location.pathname.indexOf('part2-step7') !== -1 || 
      window.location.pathname.indexOf('step7') !== -1 ||
      document.getElementById('part2-step7-form') !== null;    
      if (isStep7Page && element) {
         // For step7, just focus the element but don't scroll
         // The custom script will handle scrolling to the error message
         try {
            element.focus();
         } catch(e) {
            // If focus fails, try scrollIntoView with preventScroll option
            if (element.scrollIntoView) {
               element.scrollIntoView({ behavior: 'instant', block: 'nearest' });
            }
         }
      } else if (element) {
      element.focus()
      element.scrollIntoView();
      }

      $('#' + err_id + '-list').html(out_html);
      // $( '#'+err_id + '-section' ).focus();

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
      if ($(obj).attr('data-v-reqd')) {
         // Retrieve error message and value of any checked radio button
         $errMsg = $(obj).attr('data-v-reqd');
         // $radVal = $( obj ).find('input:radio:checked' ).val();
         $radVal = $(obj).find('input[type=radio]:checked').val();
         //alert($errMsg);
         // if no value then error
         if (!$radVal) {
            doRadioCheckboxGroupFail($(obj), $errMsg);
            return false;
         }
      }

      // Still here then return true
      doRadioCheckboxGroupSuccess($(obj));
      return true;
   }

   function validateCheckboxGrp(obj) {
      // We're being passed the fieldset that contains a checkbox group
      // Initialise flag
      var success = true;

      // First check that radio button group is required
      if ($(obj).attr('data-v-reqd')) {
         // Retrieve error message and value of any checked radio button
         $errMsg = $(obj).attr('data-v-reqd');
         // Commented by Kenpath team for fixing firefox browser issue
         // $radVal = $( obj ).find('input:checkbox:checked' ).val(); 
         $radVal = $(obj).find('input[type=checkbox]:checked').val();
         //alert($errMsg);
         // if no value then error
         if (!$radVal) {
            doRadioCheckboxGroupFail($(obj), $errMsg);
            success = false;
            return false;
         }
      } else if ($(obj).attr('data-v-reqd-all')) {
         //console.log('Fail=' + fail + ' In data-v-reqd-all');
         // Check for situations where all checkboxes are required to be checked
         // Retrieve error message and value of any checked radio button
         $errMsg = $(obj).attr('data-v-reqd-all');
         $(obj).find('input[type=checkbox]').each(function () {
            //console.log('Fail=' + fail + ' In data-v-reqd-all each');
            if (!$(this).prop('checked')) {
               //console.log('In data-v-reqd-all each one not checked');
               doRadioCheckboxGroupFail($(obj), $errMsg);
               success = false;
               return false;
            }
         });
      }

      if (success) {
         doRadioCheckboxGroupSuccess($(obj));
         // Still here then return true
         return true;
      } else {
         return false;
      }
   }

   function validateTextSelectField(obj) {
      // Store value of this control
      $myVal = $(obj).val();

      // Check if required 
      if ($(obj).attr('data-v-reqd')) {
         if ($myVal.length < 1) {
            $errMsg = $(obj).attr('data-v-reqd');
            // Length less than 1 so fail this input
            doTextSelectFail($(obj), $errMsg);
            return false; // End out of function
         }
      }

      // Check length
      if ($(obj).attr('data-v-len')) {
         // Get the maxlength from 
         $arrLen = $(obj).attr('data-v-len').split('~');
         if ($myVal.length > $arrLen[0]) {
            doTextSelectFail($(obj), $arrLen[1]);
            return false; // End out of function
         }
      }
      // Check integer
      if ($(obj).attr('data-v-int')) {
         if (!isInt($myVal)) {
            $errMsg = $(obj).attr('data-v-int');
            // Length less than 1 so fail this input
            doTextSelectFail($(obj), $errMsg);
            return false; // End out of function
         }
      }
      if ($(obj).attr('data-v-email')) {
         if (($myVal.length > 0) && (!isEmail($myVal))) {
            $errMsg = $(obj).attr('data-v-email');
            doTextSelectFail($(obj), $errMsg);
            return false; // End out of function

         }
      }
      if ($(obj).attr('data-v-sq')) {
         $arrVal = $(obj).attr('data-v-sq').split('~');
         if ($myVal != $arrVal[0]) {
            $errMsg = $arrVal[1];
            doTextSelectFail($(obj), $errMsg);
            return false; // End out of function

         }
      }
      // Still here the show success
      doTextSelectSuccess($(obj));
      return true;
   }

   function doTextSelectSuccess(obj) {
      // var $errorsEl = ($(obj).is('select')) ? $(obj).closest('.custom').next('.errors') : $(obj).next('.errors');
      // var $errorsEl = ($(obj).is('select')) ? $(obj).closest('.custom').next('.errors') : $(obj).closest('label').find('.errors');
      var $errorsEl = ($(obj).is('select')) ? $(obj).closest('.custom').next('.errors') : $('#' + obj.attr('id') + '_error');

      var $labelEl = $(obj).closest('label');
      $(obj).attr('aria-invalid', 'false');
      
      // Remove any existing error messages
      $(obj).nextAll('.errors').remove();
      
      if ($errorsEl.length) { $errorsEl.html(''); }
      if ($labelEl.length) { $labelEl.addClass('valid'); }
   }

   function doTextSelectFail(obj, message) {
      var $errorsEl = ($(obj).is('select')) ? $(obj).closest('.custom').next('.errors') : $('#' + obj.attr('id') + '_error');
      var $labelEl = $(obj).closest('label');
      $(obj).attr('aria-invalid', 'true');
      
      // Remove any existing error messages first to prevent duplicates
      $(obj).nextAll('.errors').remove();
      
      // Check if error element already exists to prevent duplicates
      if ($errorsEl.length) { 
         $errorsEl.html(message);
      } else {
         // Create error element if it doesn't exist
         var $errorSpan = $('<span class="errors" style="color: #CC063B; display: block; margin-top: 5px;">' + message + '</span>');
         $(obj).after($errorSpan);
      }
      if ($labelEl.length) { $labelEl.removeClass('valid'); }
      
      // Check if error already exists for this field before adding to array
      var fieldId = $(obj).attr('id');
      var errorExists = false;
      for (var i = 0; i < $errArray.length; i++) {
         if ($errArray[i][0] === fieldId) {
            errorExists = true;
            break;
         }
      }
      
      // Add error message to array for reporting on screen only if it doesn't already exist
      if (!errorExists) {
         console.log($(obj).attr('id') + ', ' + message);
         $errArray.push([$(obj).attr('id'), message]);
      }
   }

   function doRadioCheckboxGroupFail(obj, message) {
      // Object passed in is fieldset for radio button group
      // We need to highlight the ul within the fieldset
      // and set 
      console.log('doRadioCheckboxGroupFail ' + $(obj).prop("tagName") + ' ' + $(obj).find('legend').attr('id'));

      $(obj).children('ul').addClass('error');
      $(obj).children('.fieldseterrors').html(message);
      $(obj).children('legend').removeClass('valid');
      
      // Check if error already exists for this field before adding to array
      var fieldId = $(obj).find('legend').attr('id');
      var errorExists = false;
      for (var i = 0; i < $errArray.length; i++) {
         if ($errArray[i][0] === fieldId) {
            errorExists = true;
            break;
         }
      }
      
      // Add error message to array for reporting on screen only if it doesn't already exist
      if (!errorExists) {
         console.log($(obj).find('legend').attr('id') + ', ' + message);
         $errArray.push([$(obj).find('legend').attr('id'), message]);
      }

   }

   function doRadioCheckboxGroupSuccess(obj) {
      // Object passed in is fieldset for radio button group
      // We need to highlight the ul within the fieldset
      // and set 
      console.log('doRadioCheckboxGroupSuccess ' + $(obj).prop("tagName") + ' ' + $(obj).find('legend').attr('id'));

      $(obj).children('ul').removeClass('error');
      $(obj).children('.fieldseterrors').html('');
      $(obj).children('legend').addClass('valid');
   }

   function doDobFail(obj, message) {
      // Object passed in is fieldset for date of birth group
      // We need to highlight the ul within the fieldset
      // and set 
      $(obj).children('.fieldseterrors').html(message);
      $(obj).find('li.dob-d select').attr('aria-invalid', 'true');
      $(obj).children('legend').removeClass('valid');
      
      // Check if error already exists for this field before adding to array
      var fieldId = $(obj).find('legend').attr('id');
      var errorExists = false;
      for (var i = 0; i < $errArray.length; i++) {
         if ($errArray[i][0] === fieldId) {
            errorExists = true;
            break;
         }
      }
      
      // Add error message to array for reporting on screen only if it doesn't already exist
      if (!errorExists) {
         console.log($(obj).find('legend').attr('id') + ', ' + message);
         $errArray.push([$(obj).find('legend').attr('id'), message]);
      }
   }

   function doDobSuccess(obj) {
      // Object passed in is fieldset for date of birth group
      // We need to highlight the ul within the fieldset
      // and set 
      $(obj).children('.fieldseterrors').html('');
      $(obj).find('li.dob-d select').attr('aria-invalid', 'false');
      $(obj).children('legend').addClass('valid');
   }

   //////// Function to update status div - it's an aria-live region
   function update_status(str) {
      $('#status-txt').html('');
      $('#status-txt').html(str);
   }

   // Run it when window resizes
   $(window).resize(checkSizes);
   //delayed_checkSizes();
   $(window).on('load', function () {
      // executes when complete page is fully loaded, including all frames, objects and images
      //alert("window is loaded");
      //console.log('window.load')
      //checkSizes();
      //delayed_checkSizes();
   });
   $(window).load(function () {
      checkSizes();
   });

});


function showHideBanner(element) {
   // if(jQuery("div.bannercontent").find("li.bannerlicontent").is(":visible")) {
   //    jQuery("div.bannercontent").find("li.bannerlicontent").hide();
   // }
   // else {
   //    jQuery("div.bannercontent").find("li.bannerlicontent").show();
   // }
   // if(jQuery("div.support_bannercontent").find("span.listcontent").is(":visible")) {
   //    jQuery("div.support_bannercontent").find("span.listcontent").hide();
   // }
   // else {
   //    jQuery("div.support_bannercontent").find("li.bannerlicontent").show();
   // }
   if (event.keyCode == 13 || event.type == 'click') {
      const expanded = document.getElementById("supportBannerExpanded").getAttribute('aria-expanded') === 'true';
      element.setAttribute('aria-expanded', !expanded);
      if (jQuery("div.support_bannercontent").find("span.listcontent").is(":visible")) {
         jQuery("div.support_bannercontent").find("span.listcontent").hide();
      }
      else {
         jQuery("div.support_bannercontent").find("span.listcontent").show();
      }
   }
}

function showHidePrivacyBanner(element) {
   if (event.keyCode == 13 || event.type == 'click') {
      const expanded = document.getElementById("privacyBannerExpanded").getAttribute('aria-expanded') === 'true';
      element.setAttribute('aria-expanded', !expanded);
      if (jQuery("div.privacy_bannercontent").find("span.privacycontent").is(":visible")) {
         jQuery("div.privacy_bannercontent").find("span.privacycontent").hide();
      }
      else {
         jQuery("div.privacy_bannercontent").find("span.privacycontent").show();
      }
   }
}


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
function hideshowPassword(elementId) {
   if (jQuery("#" + elementId).attr('type') == 'password') {
      jQuery("#" + elementId).attr('type', 'text');
   }
   else {
      jQuery("#" + elementId).attr('type', 'password');
   }
}

function hideshowOpenText(element) {
   var $element = jQuery(element);
   var $textInputs = $element.parent().find('input[type=text]');

   if ($element.prop('checked')) {
      $textInputs.each(function() {
         var $input = jQuery(this);
         $input.show().prop('disabled', false);
         if (this.id === 'inf_option_Gender_opentext' || this.id === 'inf_option_Gender_776_OpenText') {
            $input.attr('data-v-reqd', 'Please supply an answer');
         }
      });
   } else {
      $textInputs.each(function() {
         var $input = jQuery(this);
         $input.hide().prop('disabled', true).val('');
         if (this.id === 'inf_option_Gender_opentext' || this.id === 'inf_option_Gender_776_OpenText') {
            $input.removeAttr('data-v-reqd');
         }
      });
   }

   toggleGenderSelfDescribeField();
   // Check if "Prefer not to respond" is selected and hide all open text fields in the same fieldset
   checkPreferNotToRespond(element);
}

function checkPreferNotToRespond(element) {
   // Get the fieldset that contains this checkbox
   var fieldset = jQuery(element).closest('fieldset');
   
   // Check if "Prefer not to respond" is checked in this fieldset
   var preferNotToRespond = fieldset.find('input[value="PreferNotToSay"]');
   
   console.log('checkPreferNotToRespond called', element, preferNotToRespond.length, preferNotToRespond.prop('checked'));
   
   if (preferNotToRespond.length > 0 && preferNotToRespond.prop('checked')) {
      // Hide all open text fields in this fieldset
      fieldset.find('input[type=text]').hide();
      fieldset.find('input[type=text]').prop('disabled', true).val('');
      
      // Also hide the specific "Other. Please describe" text fields
      fieldset.find('input[id*="OtherPleaseSpecify_OpenText"]').hide();
      fieldset.find('input[id*="OtherPleaseSpecify_OpenText"]').prop('disabled', true).val('');
      
      // Additional targeting for the specific text fields
      fieldset.find('input[name*="OtherPleaseSpecify_OpenText"]').hide();
      fieldset.find('input[name*="OtherPleaseSpecify_OpenText"]').prop('disabled', true).val('');
      
      // Target specific known IDs
      jQuery('#OtherNeedsOtherPleaseSpecify_OpenText').hide();
      jQuery('#OtherNeedsOtherPleaseSpecify_OpenText').prop('disabled', true).val('');
      jQuery('#OtherTechnologiesOtherPleaseSpecify_OpenText').hide();
      jQuery('#OtherTechnologiesOtherPleaseSpecify_OpenText').prop('disabled', true).val('');
      jQuery('#SexualOrientationsOtherPleaseSpecify_OpenText').hide();
      jQuery('#SexualOrientationsOtherPleaseSpecify_OpenText').prop('disabled', true).val('');
      
      
      console.log('Hiding text fields in fieldset');
   } else {
      // Show open text fields only for "OtherPleaseSpecify" checked options
      // fieldset.find('input[type=checkbox]:checked').each(function() {
      //    if (jQuery(this).val() !== 'PreferNotToSay' && jQuery(this).val() === 'OtherPleaseSpecify') {
      //       jQuery(this).parent().find('input[type=text]').show();
      //       jQuery(this).parent().find('input[type=text]').prop('disabled', false);
            fieldset.find('input[id*="OtherPleaseSpecify_OpenText"], input[name*="OtherPleaseSpecify_OpenText"]').hide();
      fieldset.find('input[id*="OtherPleaseSpecify_OpenText"], input[name*="OtherPleaseSpecify_OpenText"]').prop('disabled', true);
      
      // Then, show only the text field for each specific "OtherPleaseSpecify" option that is checked
      fieldset.find('input[type=checkbox]:checked, input[type=radio]:checked').each(function() {
         var $input = jQuery(this);
         var inputValue = $input.val();
         var inputId = $input.attr('id');
         
         // Skip "PreferNotToSay" options
         if (inputValue === 'PreferNotToSay') {
            return;
         }
      // });
      
      // var otherPleaseSpecify = fieldset.find('input[value="OtherPleaseSpecify"]');
      // if (otherPleaseSpecify.length > 0 && otherPleaseSpecify.prop('checked')) {
      //    fieldset.find('input[id*="OtherPleaseSpecify_OpenText"]').show();
      //    fieldset.find('input[id*="OtherPleaseSpecify_OpenText"]').prop('disabled', false);
         
      //    fieldset.find('input[name*="OtherPleaseSpecify_OpenText"]').show();
      //    fieldset.find('input[name*="OtherPleaseSpecify_OpenText"]').prop('disabled', false);
         
      //    // Show specific known IDs
      //    jQuery('#OtherNeedsOtherPleaseSpecify_OpenText').show();
      //    jQuery('#OtherNeedsOtherPleaseSpecify_OpenText').prop('disabled', false);
      //    jQuery('#OtherTechnologiesOtherPleaseSpecify_OpenText').show();
      //    jQuery('#OtherTechnologiesOtherPleaseSpecify_OpenText').prop('disabled', false);
      //    jQuery('#SexualOrientationsOtherPleaseSpecify_OpenText').show();
      //    jQuery('#SexualOrientationsOtherPleaseSpecify_OpenText').prop('disabled', false);
      // }
      
      var isOtherOption = (inputValue === 'OtherPleaseSpecify' || inputValue === 'SelfDescribe');
         
         // Also check if the ID contains "other" (case-insensitive) for other variations
         var hasOtherInId = inputId && (inputId.toLowerCase().indexOf('other') !== -1);
         
         if (isOtherOption || hasOtherInId) {
            // Find the associated text field using the checkbox/radio ID
            // Text field ID is typically: {checkbox_id}_OpenText
            var textFieldId = inputId + '_OpenText';
            var $textField = jQuery('#' + textFieldId);
            
            // Special handling for gender field
            if (inputId === 'inf_option_Gender_776') {
               $textField = jQuery('#inf_option_Gender_opentext');
            }
            
            // If not found by ID, try finding it in the parent li element
            if (!$textField.length) {
               $textField = $input.closest('li').find('input[type=text]');
            }
            
            // Show only this specific text field
            if ($textField.length) {
               $textField.show();
               $textField.prop('disabled', false);
            }
         }
      });
      console.log('Showing text fields in fieldset');
   }
}

function selectResearchRelatedOptions(){
   var isChecked = jQuery('#inf_option_any_paid_research').prop('checked');
    if (isChecked) {
        // Select the next 7 checkboxes
      //   var nextCheckboxes = jQuery('#inf_option_any_paid_research').closest('li.check-radio').nextAll('li.check-radio').slice(0, 7).find('input[type="checkbox"]');
              var nextCheckboxes = jQuery('#inf_option_any_paid_research').closest('li.check-radio').nextAll('li.check-radio').slice(0, 8).find('input[type="checkbox"]');
            nextCheckboxes.prop('checked', true);
    } else {
        // Uncheck the next 7 checkboxes if "Any paid research" is unchecked
      //   var nextCheckboxes = jQuery('#inf_option_any_paid_research').closest('li.check-radio').nextAll('li.check-radio').slice(0, 7).find('input[type="checkbox"]');
              var nextCheckboxes = jQuery('#inf_option_any_paid_research').closest('li.check-radio').nextAll('li.check-radio').slice(0, 8).find('input[type="checkbox"]');
            nextCheckboxes.prop('checked', false);
    }
}

function toggleReferredNameField(element) {
   // Show/hide the referral name text field based on Yes/No selection
   var isYes = jQuery(element).val() === 'Yes';
   var referredNameWrapper = jQuery('#inf_field_referred_name_wrapper');
   var referredNameField = jQuery('#inf_field_referred_name');
   
   if (isYes) {
      // referredNameWrapper.show();
      referredNameWrapper.addClass('show-referred-field').show();
      referredNameField.prop('disabled', false);
   } else {
      // referredNameWrapper.hide();
      referredNameWrapper.removeClass('show-referred-field').hide();
      referredNameField.prop('disabled', true).val('');
   }
}

function hideOpenText(element) {
   // Hide all open text fields in the same fieldset when other options are selected
   var fieldset = jQuery(element).closest('fieldset');
   fieldset.find('input[type=text]').each(function() {
      var $input = jQuery(this);
      $input.hide().prop('disabled', true);
      if ($input.is('#inf_option_Gender_opentext') || $input.is('#inf_option_Gender_776_OpenText')) {
         $input.removeAttr('data-v-reqd');
      }
   });
   
   // Also specifically hide the gender text field for backward compatibility
   var openTextElement = document.getElementById('inf_option_Gender_776_OpenText');
   if (openTextElement) {
      openTextElement.style.display = 'none';
   }
   var openTextElement2 = document.getElementById('inf_option_Gender_opentext');
   if (openTextElement2) {
      openTextElement2.style.display = 'none';
   }

   toggleGenderSelfDescribeField();
}

function clearOffText(element, targetId) {
   if (targetId == 'addrequired') {
      if (jQuery(element).prop("checked")) {
         jQuery("#inf_option_Gender_opentext").attr("data-v-reqd", "Please supply an answer");
      }
      else {
         jQuery("#inf_option_Gender_opentext").removeAttr("data-v-reqd");
      }
   }
   else {
      jQuery("#" + targetId).val("");
      jQuery("#" + targetId).removeAttr("data-v-reqd");
   }

   toggleGenderSelfDescribeField();
}

function myFunction() {
   const element = document.getElementById("edit-part");
   element.scrollIntoView();
}

// Specific software helper configuration
var SPECIFIC_SOFTWARE_TRIGGER_IDS = [
   '#DigitalandScreenTechnologies_ScreenReader',
   '#DigitalandScreenTechnologies_ScreenMagnifier',
   '#DigitalandScreenTechnologies_Dragonandother',
   '#DigitalandScreenTechnologies_ReadAloudSoftware'
];

var specificSoftwareHiddenInput = null;

function getSoftwareLabelFromCheckbox(checkbox) {
   if (!checkbox || !checkbox.length) {
      return '';
   }

   var label = checkbox.closest('label');
   if (label.length) {
      var cloned = label.clone();
      cloned.children().remove();
      var labelText = jQuery.trim(cloned.text());
      if (labelText) {
         return labelText;
      }
      return jQuery.trim(label.text());
   }

   return checkbox.attr('aria-label') || checkbox.data('software-label') || checkbox.val() || '';
}

function ensureSpecificSoftwareHiddenInput() {
   if (specificSoftwareHiddenInput && specificSoftwareHiddenInput.length) {
      return specificSoftwareHiddenInput;
   }

   var templateLi = jQuery('#specific-software-field');
   if (!templateLi.length) {
      return null;
   }

   var fieldInput = templateLi.find('input, textarea').first();
   if (!fieldInput.length) {
      return null;
   }

   specificSoftwareHiddenInput = fieldInput;
   if (fieldInput.attr('type') !== 'hidden') {
      fieldInput.attr('type', 'hidden');
   }
   fieldInput.val('');

   return specificSoftwareHiddenInput;
}

function initializeSpecificSoftwareField() {
   var templateLi = jQuery('#specific-software-field');
   if (!templateLi.length || templateLi.data('specific-software-ready')) {
      return;
   }

   var hiddenInput = ensureSpecificSoftwareHiddenInput();
   if (!hiddenInput) {
      return;
   }

   templateLi.hide();
   templateLi.data('specific-software-ready', true);
}

function createSpecificSoftwareField(checkbox) {
   var templateLi = jQuery('#specific-software-field');
   var hiddenInput = ensureSpecificSoftwareHiddenInput();

   if (!templateLi.length || !hiddenInput) {
      return null;
   }

   var baseId = hiddenInput.attr('id') || hiddenInput.attr('name') || 'DigitalandScreenTechnologiesSpecificSoftware';
   var softwareId = checkbox.attr('id');
   var softwareLabel = getSoftwareLabelFromCheckbox(checkbox) || 'Selected software';
   var maxLength = hiddenInput.attr('maxlength') || 500;
   var placeholder = hiddenInput.attr('placeholder');
   var inputClass = hiddenInput.attr('class') || '';
   var ariaBase = hiddenInput.attr('aria-label') || 'Specific software details';

   var fieldId = baseId + '_' + softwareId;
   var fieldPrompt = 'Please describe your use of ' + softwareLabel;

   var newField = jQuery('<li class="specific-software-field-instance clear" data-software-id="' + softwareId + '"></li>');
   var label = jQuery('<label></label>').attr('for', fieldId).text(fieldPrompt);
   var input = jQuery('<input type="text" />')
      .attr({
         id: fieldId,
         name: fieldId,
         maxlength: maxLength,
         'data-software-id': softwareId,
         'data-software-label': softwareLabel,
         'aria-label': ariaBase + ' for ' + softwareLabel
      })
      .addClass(inputClass);

   if (placeholder) {
      input.attr('placeholder', placeholder);
   }

   input.on('input change blur', updateSpecificSoftwareHiddenInput);

   newField.append(label);
   newField.append(input);

   return newField;
}

function updateSpecificSoftwareHiddenInput() {
   var hiddenInput = ensureSpecificSoftwareHiddenInput();
   if (!hiddenInput) {
      return;
   }

   var segments = [];

   jQuery('.specific-software-field-instance').each(function() {
      var fieldInput = jQuery(this).find('input, textarea').first();
      if (!fieldInput.length) {
         return;
      }

      var value = jQuery.trim(fieldInput.val());
      if (!value) {
         return;
      }

      var softwareLabel = fieldInput.data('software-label');
      if (softwareLabel) {
         segments.push(softwareLabel + ': ' + value);
      } else {
         segments.push(value);
      }
   });

   hiddenInput.val(segments.join('; '));
}

function getExistingSpecificSoftwareValues() {
   var hiddenInput = ensureSpecificSoftwareHiddenInput();
   if (!hiddenInput || !hiddenInput.length) {
      return {};
   }

   var value = hiddenInput.val();
   if (!value) {
      return {};
   }

   var map = {};
   value.split(';').forEach(function(segment) {
      var trimmed = jQuery.trim(segment);
      if (!trimmed) {
         return;
      }

      var colonIndex = trimmed.indexOf(':');
      if (colonIndex === -1) {
         return;
      }

      var label = jQuery.trim(trimmed.slice(0, colonIndex)).toLowerCase();
      var text = jQuery.trim(trimmed.slice(colonIndex + 1));

      if (label) {
         map[label] = text;
      }
   });

   return map;
}

function toggleSpecificSoftwareField(changedCheckbox) {
   initializeSpecificSoftwareField();

   var hasChecked = false;
   var newlyRenderedInputs = [];
   var existingValues = getExistingSpecificSoftwareValues();

   SPECIFIC_SOFTWARE_TRIGGER_IDS.forEach(function(selector) {
      var checkbox = jQuery(selector);
      if (!checkbox.length) {
         return;
      }

      var softwareId = checkbox.attr('id');
      var existingField = jQuery('.specific-software-field-instance[data-software-id="' + softwareId + '"]');

      if (checkbox.prop('checked')) {
         hasChecked = true;
         if (!existingField.length) {
            var newField = createSpecificSoftwareField(checkbox);
            if (!newField) {
               return;
            }

            var parentLi = checkbox.closest('li');
            if (parentLi.length) {
               newField.insertAfter(parentLi);
            } else {
               var templateLi = jQuery('#specific-software-field');
               if (templateLi.length) {
                  templateLi.after(newField);
               } else {
                  checkbox.after(newField);
               }
            }

            var inputField = newField.find('input').first();
            if (inputField.length) {
               var labelKey = (inputField.data('software-label') || '').toString().toLowerCase();
               if (labelKey && existingValues.hasOwnProperty(labelKey)) {
                  inputField.val(existingValues[labelKey]);
               }
               newlyRenderedInputs.push(inputField);
            }
         }
      } else if (existingField.length) {
         existingField.find('input, textarea').off('input change blur', updateSpecificSoftwareHiddenInput);
         existingField.remove();
      }
   });

   updateSpecificSoftwareHiddenInput();

   var focusTarget = changedCheckbox && changedCheckbox.length ? changedCheckbox.attr('id') : null;

   newlyRenderedInputs.forEach(function($input) {
      if ($input && $input.length) {
         $input.prop('disabled', false).show();
         if (focusTarget && $input.data('software-id') === focusTarget && !$input.is(':focus')) {
            $input.focus();
         }
      }
   });
}

function toggleGenderSelfDescribeField() {
   var $otherOption = jQuery('#inf_option_Gender_776');
   var $otherInput = jQuery('#inf_option_Gender_opentext');

   if (!$otherOption.length || !$otherInput.length) {
      return;
   }

   if ($otherOption.prop('checked')) {
      $otherInput.show().prop('disabled', false).attr('data-v-reqd', 'Please supply an answer');
   } else {
      $otherInput.hide().prop('disabled', true).removeAttr('data-v-reqd').val('');
   }
}

// Function to toggle ethnicity text field based on selection
function toggleEthnicityTextField() {
   var ethnicityTextField = jQuery('#ethnicity-text-field');
   var selfDescribeSelected = jQuery('#inf_option_ethnicity_self_describe').prop('checked');
   
   if (selfDescribeSelected) {
      ethnicityTextField.show();
      ethnicityTextField.find('input').prop('disabled', false);
   } else {
      ethnicityTextField.hide();
      ethnicityTextField.find('input').prop('disabled', true).val('');
   }
}

// Function to handle pronouns "Other" field
function togglePronounsTextField() {
   var pronounsOtherField = jQuery('#inf_option_pronouns_other_please_specify_OpenText');
   var otherSelected = jQuery('#inf_option_pronouns_other_please_specify').prop('checked');
   
   if (otherSelected) {
      pronounsOtherField.show();
      pronounsOtherField.prop('disabled', false);
   } else {
      pronounsOtherField.hide();
      pronounsOtherField.prop('disabled', true).val('');
   }
}

   // Bind change handlers to gender radio buttons
   $('input[name="inf_option_Gender"]').on('change', function() {
      toggleGenderSelfDescribeField();
   });

   // Bind change handlers to pronouns radio buttons
   $('input[name="inf_option_pronouns"]').on('change', function() {
      togglePronounsTextField();
   });

   // Initialize pronouns field visibility on page load
   togglePronounsTextField();

   // Initialize gender self describe field visibility on page load
   toggleGenderSelfDescribeField();

   // Bind change handlers to checkboxes with "Prefer not to respond" options
   $('input[value="PreferNotToSay"]').on('change', function() {
      checkPreferNotToRespond(this);
   });

   // Bind change handlers to "Other. Please describe" checkboxes
   $('input[value="OtherPleaseSpecify"]').on('change', function() {
      checkPreferNotToRespond(this);
   });

   // Initialize "Prefer not to respond" behavior on page load
   $('input[value="PreferNotToSay"]').each(function() {
      checkPreferNotToRespond(this);
   });
   
   // Initialize "Other. Please describe" behavior on page load
   $('input[value="OtherPleaseSpecify"]').each(function() {
      checkPreferNotToRespond(this);
   });

   // Bind change handlers to specific digital technology checkboxes that trigger the software field
   $('input[id="DigitalandScreenTechnologies_ScreenReader"]').on('change', function() {
      toggleSpecificSoftwareField(jQuery(this));
   });
   
   $('input[id="DigitalandScreenTechnologies_ScreenMagnifier"]').on('change', function() {
      toggleSpecificSoftwareField(jQuery(this));
   });
   
   $('input[id="DigitalandScreenTechnologies_Dragonandother"]').on('change', function() {
      toggleSpecificSoftwareField(jQuery(this));
   });
   
   $('input[id="DigitalandScreenTechnologies_ReadAloudSoftware"]').on('change', function() {
      toggleSpecificSoftwareField(jQuery(this));
   });

   // Initialize the specific software field immediately
   initializeSpecificSoftwareField();
   
// Initialize the conditional logic when document is ready
jQuery(document).ready(function($) {
   // Bind change handlers to the digital technology checkboxes using event delegation
   $(document).on('change', 'input[id^="DigitalandScreenTechnologies_"]', function() {
      console.log('Digital technology checkbox changed:', this.id, this.checked);
      toggleSpecificSoftwareField(jQuery(this));
   });

   // Bind change handlers to ethnicity radio buttons
   $('input[name="inf_field_identify_terms"]').on('change', function() {
      toggleEthnicityTextField();
   });
   
   
   // Initialize on page load
   initializeSpecificSoftwareField();
   toggleSpecificSoftwareField();
   toggleEthnicityTextField();
   togglePronounsTextField();
   toggleGenderSelfDescribeField();
});

function triggerSaveAndContinue($form) {
    if (!$form || !$form.length) return false;
    
    // Reset global validation state
    $errInProgress = false;
    $fieldId = '';
    $errArray = [];
    
    // 1. Validate ALL text/select fields
    $form.find('input[type="text"], input[type="password"], input[type="email"], textarea, select').each(function() {
        if (!validateTextSelectField(this)) {
            if (!$errInProgress) {
                $errInProgress = true;
                $fieldId = $(this).attr('id');
            }
        }
    });
    
    // 2. Validate radio/checkbox groups
    $form.find('fieldset[data-type="radio"], fieldset[data-type="chkbox"]').each(function() {
        if ($(this).attr('data-type') === 'radio' && !validateRadioGrp(this)) {
            if (!$errInProgress) $errInProgress = true;
        }
        if ($(this).attr('data-type') === 'chkbox' && !validateCheckboxGrp(this)) {
            if (!$errInProgress) $errInProgress = true;
        }
    });
    
    // 3. If errors exist, show them and stop
    if ($errInProgress) {
        print_out_form_errors('form-error', $errArray);
        if ($fieldId) $('#' + $fieldId).focus();
        return false;
    }
    
    // 4. SUCCESS: Click the CORRECT submit button (Save & Next Step)
    // IMPORTANT: Skip "Previous" and "Save & Continue Later" buttons
    var $saveBtn = $form.find('input[type="submit"][name*="submit_part2"], button[type="submit"][name*="submit_part2"]')
        .filter(':visible')
        .filter(function() {
            var name = $(this).attr('name');
            // Only select the main submit button, not "previous" or "save_continue_later"
            return name && name.indexOf('submit_part2') !== -1 && 
                   name.indexOf('previous') === -1 && 
                   name.indexOf('save_continue_later') === -1;
        })
        .first();
    
    // Fallback: If specific button not found, look for any submit button with correct criteria
    if (!$saveBtn.length) {
        $saveBtn = $form.find('input[type="submit"], button[type="submit"]')
            .filter(':visible')
            .filter(function() {
                var $this = $(this);
                var name = $this.attr('name');
                var value = $this.val();
                // Exclude Previous and Save Later buttons
                return !$this.hasClass('previous-button') && 
                       !$this.hasClass('save-later') &&
                       name && name.indexOf('previous') === -1 &&
                       name && name.indexOf('save_continue_later') === -1;
            })
            .first();
    }
    
    if ($saveBtn.length) {
        $saveBtn.click();
        return true;
    }
    
    return false;
}

(function($) {
    // Clean namespaced handler - only targets registration forms
    $(document).off('keydown.enterNav');
    
    var formSelector = 'form.registration-form, form[data-step], #part2-step1-form, #part2-step2-form, #part2-step3-form, #part2-step4-form, #part2-step5-form, #part2-step6-form, #part2-step7-form, #part2-step8-form, #contactform';
    
    // Handle text inputs, selects, password fields - validate on Enter
    $(document).on('keydown.enterNav', formSelector + ' input[type="text"], ' + formSelector + ' input[type="password"], ' + formSelector + ' input[type="email"], ' + formSelector + ' select', function(e) {
        if (e.keyCode !== 13 && e.which !== 13) return;
        
        e.preventDefault();
        e.stopImmediatePropagation();
        
        var $el = $(this);
        var $form = $el.closest('form');
        
        // Validate current field
        var isValid = validateTextSelectField($el[0]);
        
        if (!isValid) {
            $el.focus();
            return false;
        }
        
        // Field valid - trigger Save & Continue
        triggerSaveAndContinue($form);
        return false;
    });
    
    // Textareas: Allow Enter for line breaks; Ctrl/Cmd+Enter to submit
    $(document).on('keydown.enterNav', formSelector + ' textarea', function(e) {
        if (e.keyCode !== 13 && e.which !== 13) return;
        if (e.ctrlKey || e.metaKey) {
            e.preventDefault();
            triggerSaveAndContinue($(this).closest('form'));
            return false;
        }
        return true; // Allow line break
    });
    
    // **FIX**: Checkboxes and radios: Prevent Enter from submitting, allow Space
    $(document).on('keydown.enterNav', formSelector + ' input[type="checkbox"], ' + formSelector + ' input[type="radio"]', function(e) {
        if (e.keyCode === 13 || e.which === 13) {
            e.preventDefault();  // Prevent page reload
            e.stopImmediatePropagation();
            return false;  // Don't submit
        }
        return true;  // Allow Space to toggle
    });
    
    // Submit buttons: Allow Enter to trigger click
    $(document).on('keydown.enterNav', formSelector + ' input[type="submit"], ' + formSelector + ' button[type="submit"]', function(e) {
        if (e.keyCode === 13 || e.which === 13) {
            e.preventDefault();
            $(this).trigger('click');
            return false;
        }
    });
    
    // Allow normal form submission (don't prevent it)
    $(document).on('submit.enterNav', formSelector, function(e) {
        return true;
    });
    
})(jQuery);
