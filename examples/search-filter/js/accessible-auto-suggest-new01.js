
jQuery(document).ready(function($) {
	/*List of suburbs that will act as the list of suggestions for my auto-suggest widget*/
	var suburbs = ['Afghanistan',
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
'Zimbabwe'];
	/*Counter used to set IDs for each of the suggestions.*/
	var counter = 1;
	/*Array of keys used for the keyboard interactions*/
	var keys = {
		ESC: 27,
		TAB: 9,
		RETURN: 13,
		LEFT: 37,
		UP: 38,
		RIGHT: 39,
		DOWN: 40
	};
	
	/*Event handlers on the search input. One to perform the search and the other to deal with the keyboard interaction*/
	
	$("#search").on("input", function(event) {
		doSearch(suburbs);
	});

	$("#search").on("keydown", function(event) {
		doKeypress(keys, event);
	});


	/*This function performs the search based on the users input, and builds the list of suggestions*/
	function doSearch(suburbs) {

		var query = $("#search").val();
		$("#search").removeAttr("aria-activedescendant");

		/*If statement to start the search only after 2 characters have been enter. This  number can be higher or lower depending on your preference*/
		if ($("#search").val().length >= 2) {

			//Case insensitive search and return matches to build the  array of suggestions
			var results = $.grep(suburbs, function(item) {
				return item.search(RegExp("^" + query, "i")) != -1;

			});
			/*Make sure we have at least 1 suggestion*/
			if (results.length >= 1) {
				/*Start things fresh by removing the suggestions div and emptying the live region before we start*/
				$("#res").remove();
				$('#announce').empty();
				$(".autocomplete-suggestions").show();
				/*Create the listbox to store the suggestions*/
				$(".autocomplete-suggestions").append('<div id="res" role="listbox" tabindex="-1"></div>');
				counter = 1;
			}



			//Add suggestions to the list, limiting the list of displayed suggestions to 5
			for (term in results) {

				if (counter <= 5) {
					$("#res").append("<div role='option' tabindex='-1' class='autocomplete-suggestion' id='suggestion-" + counter + "'>" + results[term] + "</div>");
					counter = counter + 1;
				}

			}
			/*Count the number of suggestions available and annouce to screen readers via live region */
			var number = $("#res").children('[role="option"]').length
			if (number >= 1) {
				$("#announce").text(+number + " suggestions found" + ", to navigate use up and down arrows");
			}

		} else {
		/*If no results make sure the list does not display*/
			$("#res").remove()
			$('#announce').empty();
			$(".autocomplete-suggestions").hide();
		}

			//Bind click event to suggestions in results
			$("#res").on("click", "div", function() {
				/*When an option is clicked, copy it's text into the input field, then close and remove the list of suggestions*/
				$("#search").val($(this).text());
				$("#res").remove();
				$('#announce').empty();
				$(".autocomplete-suggestions").hide();
				counter = 1;

			});
			/* Prob need the same for on("touch") so that mobile users can access the options */

	}

	/*Function to deal with the keyborad interactions for the auto-suggest and highlight the currently selected option*/
	function doKeypress(keys, event) {
		var highligted = false;
		highligted = $('#res').children('div').hasClass('highligt');
		switch (event.which) {

			case keys.ESC:
				$("#search").removeAttr("aria-activedescendant");
				//$('#hint').val('');
				$("#res").remove();
				$('#announce').empty();
				$(".autocomplete-suggestions").hide();
				break;

			case keys.RIGHT:

				return selectOption(highligted)
				break;

			case keys.TAB:
				$("#search").removeAttr("aria-activedescendant");
				$("#res").remove();
				$('#announce').empty();
				$(".autocomplete-suggestions").hide();
				break;

			case keys.RETURN:
				if (highligted) {
					event.preventDefault();
					event.stopPropagation();
					return selectOption(highligted)
				}

			case keys.UP:
				event.preventDefault();
				event.stopPropagation();
				return moveUp(highligted);
				break;

			case keys.DOWN:
				event.preventDefault();
				event.stopPropagation();

				return moveDown(highligted);
				break;

			default:
				return;
		}
	}
	/*Function to move the user up the list of suggestions*/
	function moveUp(highligted) {
		var current;
		$("#search").removeAttr("aria-activedescendant");
		
		if (highligted) {
			console.log("Highlighted - " + highligted + "");
			current = $('.highligt');
			current.attr('aria-selected', false);
			current.removeClass('highligt').prev('div').addClass('highligt');
			current.prev('div').attr('aria-selected', true);
			$("#search").attr("aria-activedescendant", current.prev('div').attr('id'));
			highligted = false;
		} else {

			//Go back to the bottom of the list
			current = $("#res").children().last('div');
			current.addClass('highligt');
			current.attr('aria-selected', true);
			$("#search").attr("aria-activedescendant", current.attr('id'));

		}
	}
	/*Function to move the user down the list of suggestions*/
	function moveDown(highligted) {

		var current;
		$("#search").removeAttr("aria-activedescendant");
		
		if (highligted) {
			console.log("Highlighted - " + highligted + "");
			current = $('.highligt');
			current.attr('aria-selected', false);
			current.removeClass('highligt').next('div').addClass('highligt');
			current.next('div').attr('aria-selected', true);
			$("#search").attr("aria-activedescendant", current.next('div').attr('id'));
			highligted = false;
		} else {

			//Go back to the top of the list
			current = $("#res").children().first('div');
			current.addClass('highligt');
			current.attr('aria-selected', true);
			$("#search").attr("aria-activedescendant", current.attr('id'));
		}
	}

	/*Function to select the users chosen option*/
	function selectOption(highligted) {
		if (highligted) {
			$("#search").removeAttr("aria-activedescendant");
			$('#search').val($('.highligt').text());
			$('#search').focus();
			$("#res").remove();
			$('#announce').empty();
			$(".autocomplete-suggestions").hide();
		} else {
			return;
		}
	}

});
