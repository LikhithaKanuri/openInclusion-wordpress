jQuery(document).ready(function($) {
   var $errInProgress = false;
   var $fieldId = '';

   // Code here will be executed on document ready. Use $ as normal.
   $('html').addClass( 'js' ).removeClass( 'non-js' );

   
    
   // Code for accordions
   // Hide any accordion 'answers' and close links
   $('.acc-drop-radio-group').hide();

   // For each accordion question
   $('.acc-q').each(function(){
      // Retrieve the data id allocated within php
      $thisId = $( this ).attr('data-id');
      // Retrieve the question for use later
      $thisQ = $( this ).html();
      
      // Set up attributes for this question
      $( this ).attr('tabindex','0')
          .attr('id','acc-q-'+$thisId)
          .attr('aria-controls','acc-a-'+$thisId)
          .attr('aria-expanded','false')
          .attr('role','button');
      // set up attributes for corresponding answer
      $( this ).next('.acc-a')
         .attr('id','acc-a-'+$thisId)
         .attr('aria-hidden','true')
         .attr('aria-labelledby','acc-q-'+$thisId);
   });
   
   
   // Handle clicks on the headers
   $('.acc-q').on('click', function(e) {
      var $thisId = $( this ).attr('data-id');
      if ($(this).hasClass( 'expanded' ) ) {
         hideAccordion($( this ));
         $( this ).focus();
      } else {
         showAccordion($( this ));
         $( '#acc-a-'+$thisId ).find('input').first().focus();
         
      }
   });
   // Handle key presses on the headers
   $('.acc-q').on('keydown', function(e) {
   
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

  $(':radio[name="employees"]').change(function() {
    var category = $(this).filter(':checked').val();
    document.getElementById('acc-drop-legend01-value').innerHTML = ": " + category;
  });
   
   function hideAccordion(obj) {
      var $thisId = $( obj ).attr('data-id');
      $( obj ).removeClass( 'expanded' )
        .attr('aria-expanded','false');
      console.log("In hideAccordion - "+$thisId);
      //alert (dataId);
      $('#acc-a-'+$thisId).hide()
        .attr('aria-hidden','true');
      
   }
   function showAccordion(obj) {
      var $thisId = $( obj ).attr('data-id');
      $( obj ).addClass( 'expanded' )
        .attr('aria-expanded','true');

      console.log("In showAccordion - "+$thisId);
      //alert (dataId);
      $('#acc-a-'+$thisId).show()
        .attr('aria-hidden','false');
   }

   
   
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
   //document.getElementById(divId).innerHTML = strHtml;
}

//////////////// Google Map API Scripts ///////////////////////////////////
/*
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
*/
