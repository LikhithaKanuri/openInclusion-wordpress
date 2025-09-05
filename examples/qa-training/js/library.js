jQuery(document).ready(function($) {
   var $errInProgress = false;
   var $fieldId = '';

   // Code here will be executed on document ready. Use $ as normal.
   $('html').addClass( 'js' ).removeClass( 'non-js' );

   // Navigation related functions
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


   // Check things that need sizes altered
   function checkSizes() {
      // check heights of home page middle widgets
      // Ensure they are all the same
     
      // check heights of home page lower widgets
      // Ensure they are all the same
      h = 0;
      hlwCol = 0;
      adjust = false;
      var $homeLowerWidgets = $('#home-lower-widget-block').find('.col');
      
      if ($homeLowerWidgets.length){
         // Reset heights
         $homeLowerWidgets.css('height', 'auto');

         // Check over each one
         $homeLowerWidgets.each(function() {
            // Retrieve new top value
            $hlwColOff = $(this).offset();
            $hlwColNew = $hlwColOff.top;
            
            // Store height
            h = Math.max(h, Math.ceil($(this).height()));
            
            // If it's not first time through and the values are same
            // the we need to adjust height.
            if ((hlwCol == $hlwColNew) && (hlwCol != 0)) {
               adjust = true;
            }
            // Store new top value.
            hlwCol = $hlwColNew;
         });
         
         // Adjust heights if we need to.
         if (adjust) { $homeLowerWidgets.css('height', h+'px'); }
      }
      // check heights of home page lower widgets
      // Ensure they are all the same
      h = 0;
      hlwCol = 0;
      adjust = false;
      var $homeButtonWidgets = $('#home-middle').find('.hp-button');

      if ($homeButtonWidgets.length){
         // Reset heights
         $homeButtonWidgets.css('height', 'auto');

         adjust = true;
         // Check over each one
         $homeButtonWidgets.each(function() {
            
            // Store height
            h = Math.max(h, Math.ceil($(this).height()));
            
         });
         
         // Adjust heights if we need to.
         if (adjust) { $homeButtonWidgets.css('height', h+'px'); }
      }
     
      
   }  // End of checksizes()

   // Initial run of checksizes
   checkSizes();
   
   // Run it when window resizes
   $(window).resize(checkSizes);

   // Form validation etc
   // Set up appearances for return from server side validation
   $('input[aria-invalid="false"]').parent('label').addClass('valid');
   $('select[aria-invalid="false"]').parent('label').addClass('valid');
   $('textarea[aria-invalid="false"]').parent('label').addClass('valid');
   
   // Check some fields when we move away
   $('.contact input[type="text"], .contact textarea, .contact select').on('blur', function(e) {
      // Perform required validation
      validateTextSelectField($( this ));
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
   // Check date of birth fields when value changes
   $('.contact').find('fieldset[data-type="dob"]').find('select').on('change', function(e) {
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
   $('.contact').find('input[type="submit"]').on('click', function(e) {
      $fieldId = '';
      $errInProgress = false;

      // Perform required validation - we're checking for length, email format, 
      // and required
      $( this ).parents('form')
         .find('input[type="text"], textarea, select').each(function() {
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
         e.preventDefault(); 
         alert('Please fix the validation errors before submitting the form.');
         $('#'+$fieldId).focus();
         /*
         */
      }
      
   });

   function validateDateOfBirth(obj) {
      // We're being passed the fieldset that contains a dob select group
      // The mandatory check for each of the fields will aready have been done
      // So we just need to pick up any failures of mandatory check
      // Then check the validity of any date present
      
      // There will be three li items - each with a select within it
      $dVal = $( obj ).find('li.dob-d select').val();
      $mVal = $( obj ).find('li.dob-m select').val();
      $yVal = $( obj ).find('li.dob-y select').val();
      
      if (($dVal.length > 0) && ($mVal.length > 0) && ($yVal.length > 0)) {
         // Date present so check validity
         $testDate = $mVal + '/' + $dVal + '/' + $yVal;
         if (!isValidDate($testDate)) {
            doDobFail($( obj ), 'Date of birth not valid date');
            return false;
         } else {
            // Date is valid
            doDobSuccess($( obj ));
         }
      } else {
         // At least one of the fields not set
         // Set visible error message
         doDobFail($( obj ), 'Please check date of birth');
         return false;
     }
      
      return true;
  }

   function validateRadioGrp(obj) {
      // We're being passed the fieldset that contains a radio button group
      
      // First check that radio button group is required
      if ($( obj ).attr('data-v-reqd')) {
         // Retrieve error message and value of any checked radio button
         $errMsg = $( obj ).attr('data-v-reqd');
         $radVal = $( obj ).find('input:radio:checked' ).val();
         //alert($errMsg);
         // if no value then error
         if (!$radVal) {
            doRadioFail($( obj), $errMsg );
            return false;
         } 
      } 
      
      // Still here then return true
      doRadioSuccess($( obj ));
      return true;
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
   }
   
   function doRadioFail(obj, message) {
      // Object passed in is fieldset for radio button group
      // We need to highlight the ul within the fieldset
      // and set 
      $( obj ).children('ul').addClass('error');
      $( obj ).children('.fieldseterrors').html(message);
      $( obj ).children('legend').removeClass('valid');
   }
   function doRadioSuccess(obj) {
      // Object passed in is fieldset for radio button group
      // We need to highlight the ul within the fieldset
      // and set 
      
      $( obj ).children('ul').removeClass('error');
      $( obj ).children('.fieldseterrors').html('');
      $( obj ).children('legend').addClass('valid');
   }
   function doDobFail(obj, message) {
      // Object passed in is fieldset for radio button group
      // We need to highlight the ul within the fieldset
      // and set 
      $( obj ).children('.fieldseterrors').html(message);
      $( obj ).find('li.dob-d select').attr('aria-invalid','true');
      $( obj ).children('legend').removeClass('valid');
   }
   function doDobSuccess(obj) {
      // Object passed in is fieldset for radio button group
      // We need to highlight the ul within the fieldset
      // and set 
      $( obj ).children('.fieldseterrors').html('');
      $( obj ).find('li.dob-d select').attr('aria-invalid','false');
      $( obj ).children('legend').addClass('valid');
   }
   
   // Check 
   if (typeof mapDiv === 'undefined') {
   } else {
      showMap(mapDiv, mapData);
   }

   ccfsCheckFontSizeCookie();
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

function setCookie( name, value, expires, path, domain, secure )
{
// set time, it's in milliseconds
var today = new Date();
today.setTime( today.getTime() );

/*
if the expires variable is set, make the correct
expires time, the current script below will set
it for x number of days, to make it for hours,
delete * 24, for minutes, delete * 60 * 24
*/
if ( expires )
{
expires = expires * 1000 * 60 * 60 * 24;
}
var expires_date = new Date( today.getTime() + (expires) );

document.cookie = name + "=" +escape( value ) +
( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
( ( path ) ? ";path=" + path : "" ) +
( ( domain ) ? ";domain=" + domain : "" ) +
( ( secure ) ? ";secure" : "" );
}

function getCookie( check_name ) {
	// first we'll split this cookie up into name/value pairs
	// note: document.cookie only returns name=value, not the other components
	var a_all_cookies = document.cookie.split( ';' );
	var a_temp_cookie = '';
	var cookie_name = '';
	var cookie_value = '';
	var b_cookie_found = false; // set boolean t/f default f

	for ( i = 0; i < a_all_cookies.length; i++ )
	{
		// now we'll split apart each name=value pair
		a_temp_cookie = a_all_cookies[i].split( '=' );


		// and trim left/right whitespace while we're at it
		cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');

		// if the extracted name matches passed check_name
		if ( cookie_name == check_name )
		{
			b_cookie_found = true;
			// we need to handle case where cookie has no value but exists (no = sign, that is):
			if ( a_temp_cookie.length > 1 )
			{
				cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
			}
			// note that in cases where cookie is initialized but no value, null is returned
			return cookie_value;
			break;
		}
		a_temp_cookie = null;
		cookie_name = '';
	}
	if ( !b_cookie_found )
	{
		return null;
	}
}

// this deletes the cookie when called
function deleteCookie( name, path, domain ) {
if ( Get_Cookie( name ) ) document.cookie = name + "=" +
( ( path ) ? ";path=" + path : "") +
( ( domain ) ? ";domain=" + domain : "" ) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}



/////////////////// Change Font Size Functions /////////////////////////////////
var ccfsUnits = '%';
var ccfsDefault = 110;
var ccfsCurrent = 110;
var ccfsInterval = 10;
var ccfsMin = 40;
var ccfsMax = 300;
var ccfsDivId = 'body';
var ccfsCookieName = 'ccfs_font_size';

function ccOutputFontSizeSwitcher() {
   // This function will output the HTML to set up the font size switcher. 
   // Delivering the HTML in a function will ensure that switcher is not output 
   // if javascript is not available.
   var strHtml = '';
   
   return strHtml;
}
function ccfsCheckFontSizeCookie() {
   var cookieVal = getCookie( ccfsCookieName );
   if (null != cookieVal) {
      //alert('cookie set - cookieVal=' + cookieVal);
      ccfsCurrent = parseInt(cookieVal);
      //alert ('ccfsCurrent = ' + ccfsCurrent);
      if (parseInt(ccfsCurrent) > ccfsMax) {
         //alert('ccfsCurrent was higher than max = ' + ccfsCurrent);
         ccfsCurrent = ccfsMax;
      }
      if (parseInt(ccfsCurrent) < ccfsMin) {
         //alert('ccfsCurrent was lower than min = ' + ccfsCurrent);
         ccfsCurrent = ccfsMin;
      }
      ccfsUpdateFontSize();
   } else {
      //alert('no cookie set');
   }
}
function ccfsIncreaseFontSize() {
   ccfsCurrent = ccfsCurrent + ccfsInterval;
   if (ccfsCurrent > ccfsMax) ccfsCurrent = ccfsMax;
   ccfsUpdateFontSize();
   ccfsUpdateCookie();
}

function ccfsDecreaseFontSize() {
   ccfsCurrent = ccfsCurrent - ccfsInterval;
   if (ccfsCurrent < ccfsMin) ccfsCurrent = ccfsMin;
   ccfsUpdateFontSize();
   ccfsUpdateCookie();
}

function ccfsDefaultFontSize() {
   ccfsCurrent = ccfsDefault;
   ccfsUpdateFontSize();
   ccfsUpdateCookie();

}

function ccfsUpdateFontSize() {
   // Set the font size of the specified div to the new current value
   var newVal = ccfsCurrent + ccfsUnits;
   document.getElementById(ccfsDivId).style.fontSize = newVal;
}
function ccfsUpdateCookie() {
   setCookie( ccfsCookieName, ccfsCurrent, 120, '/' )
}
function ccfsDeleteCookie() {
   deleteCookie( ccfsCookieName, '/' )
}

/*
Add text resize links - function to write text resize links into the page
*/
function addTextResizer(divId) {
   var strHtml = '<h2>Text size</h2><ul>';
   strHtml += '<li><button class="smaller" onclick="ccfsDecreaseFontSize();">A <span class="visuallyhidden">Make text smaller</span></button></li>';
   strHtml += '<li><button class="middle" onclick="ccfsDefaultFontSize();">A <span class="visuallyhidden">Make text default size</span></button></li>'; 
   strHtml += '<li><button class="bigger" onclick="ccfsIncreaseFontSize();">A <span class="visuallyhidden">Make text larger</span></button></li>';
   strHtml += '</ul>';

   //alert(strHtml);
   document.getElementById(divId).innerHTML = strHtml;
}

//////////////// Google Map API Scripts ///////////////////////////////////
function initialize_map_simple(lat , lng, mapHolder, strTitle, strStr) {
    //var myLatlng = new google.maps.LatLng(45.124099,-123.113634);
    var myLatlng = new google.maps.LatLng(lat,lng);
    var myOptions = {
        zoom: 15,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
 
    var map = new google.maps.Map( document.getElementById(mapHolder), myOptions );

    var contentString = '<div id="mkrcontent">'+
        '<p><strong>' + strTitle + '</strong></p></div>';

    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: strTitle,
    });
    
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
    });
}

/////////////////////// Set MapDiv Height /////////////////////////
function setMapDivHeight(mapDiv) {
   // Get the width of the map div
   mapDivWidth = document.getElementById(mapDiv).clientWidth;
   
   // Work out ideal height
   mapDivIdealHeight = Math.floor(mapDivWidth/1.6);
   
   // Set ideal height to mimum value if too short
   if (mapDivIdealHeight < 200 ) mapDivIdealHeight = 200;
   
   // Now update height of div
   document.getElementById(mapDiv).style.height = mapDivIdealHeight + 'px';
   //alert('mapDivWidth = '+ mapDivWidth + ' mapDivIdealHeight='+ mapDivIdealHeight + 'px');
   
}

function showMap(mapDiv, data ) {

   setMapDivHeight(mapDiv);
   // test length of data - probably 1
   if ( data.length == 1) {
      var detail = data[0];
      
      // create map
      initialize_map_simple(detail["lat"],detail["long"], mapDiv, detail["title"], '');
   } else {
      // Future proof - multiple markers coming through
      for (var i=0; i < data.length; i++) {
      
      }
   }
}

