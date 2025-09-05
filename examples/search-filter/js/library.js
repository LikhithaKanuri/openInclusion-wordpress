///// General Functions ///////
jQuery(document).ready(function($) {
  // global variables
  var $errInProgress = false;
  var $fieldId = '';
  var str_html = '';

  // Code here will be executed on document ready. Use $ as normal.
  $('html').addClass( 'js' ).removeClass( 'no-js' );

  //Initial page load
  print_countries_list('');
      


  $('#country-birth').keyup(function() {
    // check how long input is

    print_countries_list($('#name').val());
  });

  




  $('#name').keyup(function() {
    print_countries_list($('#name').val());
  });



  function print_countries_list(str_frag) {
    var countries_arr = filterItems(str_frag);

    if (countries_arr.length > 0) {

      str_html = '<ul>';

      countries_arr.forEach(wrap_in_list);

      str_html += '</ul>';
      $('#countries-list').html(str_html);

      update_container('countries-status', countries_arr.length + ' countries matched your search string.')
    } else {
      str_html = 'No countries match your search string.'
      update_container('countries-status', 'No countries matched your search string.')

    }
    
    // Now populate div with results
    update_container('countries-list',str_html);

  }

  function update_container(id, str_msg) {
    $('#' + id).html('');
    $('#' + id).html(str_msg);
  }

  function wrap_in_list(element, index, array) {
    str_html += '<li>' + element + '</li>';
  }


  /////////////////////////////////// Search filters ///////////////////
  $('#country-birth').input(function() {

    alert('input triggered');
    // check for key pressed

    // If down arrow, check to see if country-panel is visible

      // If it is visible, put focus onto first element of list

      // Else do nothing

    // prob a letter key so check length of country-birth and if > 2
    // then trigger filter of list and display panel

    // If less than two chars, empty panel and make sure it's not visible


  });

});

/**
 * Array filters items based on search criteria (query)
 */
function filterItems(query) {
  if (query.length > 1) {
    console.log('query='+query+' length='+query.length);
    return countries.filter(function(el) {
        return el.toLowerCase().indexOf(query.toLowerCase()) > -1;
    })
  } else {
    return countries;
  }
}



var countries = [
'Afghanistan',
'Albania',
'Algeria',
'Andorra',
'Angola',
'Antigua & Deps',
'Argentina',
'Armenia',
'Australia',
'Austria',
'Azerbaijan',
'Bahamas',
'Bahrain',
'Bangladesh',
'Barbados',
'Belarus',
'Belgium',
'Belize',
'Benin',
'Bhutan',
'Bolivia',
'Bosnia Herzegovina',
'Botswana',
'Brazil',
'Brunei',
'Bulgaria',
'Burkina',
'Burundi',
'Cambodia',
'Cameroon',
'Canada',
'Cape Verde',
'Central African Rep',
'Chad',
'Chile',
'China',
'Colombia',
'Comoros',
'Congo',
'Congo (Democratic Rep)',
'Costa Rica',
'Croatia',
'Cuba',
'Cyprus',
'Czech Republic',
'Denmark',
'Djibouti',
'Dominica',
'Dominican Republic',
'East Timor',
'Ecuador',
'Egypt',
'El Salvador',
'Equatorial Guinea',
'Eritrea',
'Estonia',
'Ethiopia',
'Fiji',
'Finland',
'France',
'Gabon',
'Gambia',
'Georgia',
'Germany',
'Ghana',
'Greece',
'Grenada',
'Guatemala',
'Guinea',
'Guinea-Bissau',
'Guyana',
'Haiti',
'Honduras',
'Hungary',
'Iceland',
'India',
'Indonesia',
'Iran',
'Iraq',
'Ireland (Republic)',
'Israel',
'Italy',
'Ivory Coast',
'Jamaica',
'Japan',
'Jordan',
'Kazakhstan',
'Kenya',
'Kiribati',
'Korea North',
'Korea South',
'Kosovo',
'Kuwait',
'Kyrgyzstan',
'Laos',
'Latvia',
'Lebanon',
'Lesotho',
'Liberia',
'Libya',
'Liechtenstein',
'Lithuania',
'Luxembourg',
'Macedonia',
'Madagascar',
'Malawi',
'Malaysia',
'Maldives',
'Mali',
'Malta',
'Marshall Islands',
'Mauritania',
'Mauritius',
'Mexico',
'Micronesia',
'Moldova',
'Monaco',
'Mongolia',
'Montenegro',
'Morocco',
'Mozambique',
'Myanmar, (Burma)',
'Namibia',
'Nauru',
'Nepal',
'Netherlands',
'New Zealand',
'Nicaragua',
'Niger',
'Nigeria',
'Norway',
'Oman',
'Pakistan',
'Palau',
'Panama',
'Papua New Guinea',
'Paraguay',
'Peru',
'Philippines',
'Poland',
'Portugal',
'Qatar',
'Romania',
'Russian Federation',
'Rwanda',
'St Kitts & Nevis',
'St Lucia',
'Saint Vincent & the Grenadines',
'Samoa',
'San Marino',
'Sao Tome & Principe',
'Saudi Arabia',
'Senegal',
'Serbia',
'Seychelles',
'Sierra Leone',
'Singapore',
'Slovakia',
'Slovenia',
'Solomon Islands',
'Somalia',
'South Africa',
'South Sudan',
'Spain',
'Sri Lanka',
'Sudan',
'Suriname',
'Swaziland',
'Sweden',
'Switzerland',
'Syria',
'Taiwan',
'Tajikistan',
'Tanzania',
'Thailand',
'Togo',
'Tonga',
'Trinidad & Tobago',
'Tunisia',
'Turkey',
'Turkmenistan',
'Tuvalu',
'Uganda',
'Ukraine',
'United Arab Emirates',
'United Kingdom',
'United States',
'Uruguay',
'Uzbekistan',
'Vanuatu',
'Vatican City',
'Venezuela',
'Vietnam',
'Yemen',
'Zambia',
'Zimbabwe'
];

//console.log(filterItems('ap')); // ['apple', 'grapes']
//console.log(filterItems('an')); // ['banana', 'mango', 'orange']