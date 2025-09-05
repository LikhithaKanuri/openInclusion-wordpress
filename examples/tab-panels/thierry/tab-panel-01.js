jQuery(document).ready(function($) {

    var $panel1 = new tabpanel("tabpanel", false);

    if($panel1.$firstTab.offset().top < $panel1.$lastTab.offset().top)
    {
        $panel1.$panel.removeClass('tabpanel').addClass('accordion');
        $panel1.accordian = true;
    }

    $(window).resize(function() {
        if($panel1.accordian == false){
            if($panel1.$firstTab.offset().top < $panel1.$lastTab.offset().top) {
                $panel1.$panel.removeClass('tabpanel').addClass('accordion');
                $panel1.accordian = true;
                            $panel1.$panel.attr('aria-multiselectable', 'true');
            }
        }
        else {
            // Already an accordion. Do nothing
        }
    });

});

//
// keyCodes() is an object to contain keycodes needed for the application
//
function keyCodes() {
    // Define values for keycodes
    this.tab = 9;
    this.enter = 13;
    this.esc = 27;

    this.space = 32;
    this.pageup = 33;
    this.pagedown = 34;
    this.end = 35;
    this.home = 36;

    this.left = 37;
    this.up = 38;
    this.right = 39;
    this.down = 40;

} // end keyCodes

//
// tabpanel() is a class constructor to create a ARIA-enabled tab panel widget.
//
// @param (id string) id is the id of the div containing the tab panel.
//
// @param (accordian boolean) accordian is true if the tab panel should operate
//         as an accordian; false if a tab panel
//
// @return N/A
//
// Usage: Requires a div container and children as follows:
//
//         1. tabs/accordian headers have class 'tab'
//
//         2. panels are divs with class 'panel'
//
function tabpanel(id, accordian) {

    // define the class properties

    this.panel_id = id; // store the id of the containing div
    this.accordian = accordian; // true if this is an accordian control
    this.$panel = $('#' + id); // store the jQuery object for the panel
    this.keys = new keyCodes(); // keycodes needed for event handlers
    this.$tabs = this.$panel.find('h3'); // Array of panel tabs.
    this.$panels = this.$panel.find('h3 + div'); // Array of panels.
    this.$firstTab = this.$tabs.first(); // First tab
    this.$lastTab = this.$tabs.last(); // Last tab

    // Bind event handlers
    this.bindHandlers();

    // Initialize the tab panel
    this.init();
    return this;
} // end tabpanel() constructor








//
// Function init() is a member function to initialize the tab/accordian panel. Hides all panels. If a tab
// has the class 'selected', makes that panel visible; otherwise, makes first panel visible.
//
// @return N/A
//
tabpanel.prototype.init = function() {

        var $tab; // the selected tab - if one is selected

        // TK: set a class according to kind of widget
        (this.accordian) ? this.$panel.addClass('accordion'): this.$panel.addClass('tabpanel');

        // TK: create a link to land past the tab panel (used when user hit "escape" key) Isn't this a jump link?
        this.$panel.append("<a onblur='this.blur();' id='skip-widget' href='#tab0'>Tab through to leave this widget or click to go back to the first tab</a>");

        // add aria attributes to the panels
        this.$panels.attr('aria-hidden', 'true');
    

        // TK: set various attributes (@class, @aria-*, @id, etc.)
        this.$tabs.attr('aria-selected', 'false');
        this.$tabs.attr('role', 'tab');
        this.$tabs.attr('tabindex', '0');
        this.$tabs.addClass('tab');
        this.$tabs.filter('.selected').attr('aria-selected', 'true');
        this.$panels.addClass('panel');
        this.$panel.attr('role', 'tablist');
    
        if(this.accordion == true) {
            this.$panel.attr('aria-multiselectable', 'true');
        }

        // these loops to make sure we map tabs and panels properly
        for (var index = 0; index < $('h3').length; index++) {
            $('h3').eq(index).attr('id', 'tab' + index);
            $('h3').eq(index).attr('aria-controls', 'panel' + index);
        };
        for (var index = 0; index < $('h3 + div').length; index++) {
            $('h3 + div').eq(index).attr('id', 'panel' + index);
            $('h3 + div').eq(index).attr('aria-labelledby', 'tab' + index);
        };


        // hide all the panels
        // TK: removed this as it is done via CSS
        // this.$panels.hide();

        // get the selected tab
        $tab = this.$tabs.filter('.selected');

        if ($tab == undefined) {
            $tab = this.$tabs.first();
            $tab.addClass('selected');
        }

        // show the panel that the selected tab controls and set aria-hidden to false
        // TK: replaced with following line
        // this.$panel.find('#' + $tab.attr('aria-controls')).show().attr('aria-hidden', 'false');
        this.$panel.find('#' + $tab.attr('aria-controls')).attr('aria-hidden', 'false');

    } // end init()








//
// Function switchTabs() is a member function to give focus to a new tab or accordian header.
// If it's a tab panel, the currently displayed panel is hidden and the panel associated with the new tab
// is displayed.
//
// @param ($curTab obj) $curTab is the jQuery object of the currently selected tab
//
// @param ($newTab obj) $newTab is the jQuery object of new tab to switch to
//
// @return N/A
//
tabpanel.prototype.switchTabs = function($curTab, $newTab) {

        // Remove the highlighting from the current tab
        //$curTab.removeClass('selected');
        this.$tabs.removeClass('selected focus');

        // remove tab from the tab order and update its aria-selected attribute
        // TK: leave tabindex alone - see following line
        // $curTab.attr('tabindex', '-1').attr('aria-selected', 'false');
        $curTab.attr('aria-selected', 'false');

        // update the aria attributes

        // Highlight the new tab and update its aria-selected attribute
        $newTab.addClass('selected').attr('aria-selected', 'true');

        // If this is a tab panel, swap displayed tabs
        if (this.accordian == false) {
            // hide the current tab panel and set aria-hidden to true
            // TK: no need to hide the panel via JS
            // this.$panel.find('#' + $curTab.attr('aria-controls')).hide().attr('aria-hidden', 'true');
            this.$panel.find('#' + $curTab.attr('aria-controls')).attr('aria-hidden', 'true');

            // show the new tab panel and set aria-hidden to false
            // TK: replaced with the following line
            // this.$panel.find('#' + $newTab.attr('aria-controls')).show().attr('aria-hidden', 'false');
            this.$panel.find('#' + $newTab.attr('aria-controls')).attr('aria-hidden', 'false');
        }

        // Make new tab navigable
        // TK: no need to mess with tabindex value
        // $newTab.attr('tabindex', '0');

        // give the new tab focus
        $newTab.focus();

    } // end switchTabs()








//
// Function togglePanel() is a member function to display or hide the panel associated with an accordian header
//
// @param ($tab obj) $tab is the jQuery object of the currently selected tab
//
// @return N/A
//
tabpanel.prototype.togglePanel = function($tab) {

        $tab.toggleClass('selected focus');
        $panel = $tab.next();

        if ($panel.attr('aria-hidden') == 'true') {
            $panel.attr('aria-hidden', 'false');
        } else {
            $panel.attr('aria-hidden', 'true');
        }
    } // end togglePanel()







//
// Function bindHandlers() is a member function to bind event handlers for the tabs
//
// @return N/A
//
tabpanel.prototype.bindHandlers = function() {

        var thisObj = this; // Store the this pointer for reference

        //////////////////////////////
        // Bind handlers for the tabs / accordian headers

        // bind a tab keydown handler
        this.$tabs.keydown(function(e) {
            return thisObj.handleTabKeyDown($(this), e);
        });

        // bind a tab keypress handler
        this.$tabs.keypress(function(e) {
            return thisObj.handleTabKeyPress($(this), e);
        });

        // bind a tab click handler
        this.$tabs.click(function(e) {
            return thisObj.handleTabClick($(this), e);
        });

        // bind a tab focus handler
        this.$tabs.focus(function(e) {
            return thisObj.handleTabFocus($(this), e);
        });

        // bind a tab blur handler
        this.$tabs.blur(function(e) {
            return thisObj.handleTabBlur($(this), e);
        });

        /////////////////////////////
        // Bind handlers for the panels

        // bind a keydown handlers for the panel focusable elements
        // TK: replaced the next line with the following to bind to the tabpanel itself (to deal with esc key)
        // this.$panels.keydown(function(e) {
        this.$panel.keydown(function(e) {
            return thisObj.handlePanelKeyDown($(this), e);
        });

        // bind a keypress handler for the panel
        // TK: replaced the next line with the following to bind to the tabpanel itself (to deal with esc key)
        // this.$panels.keypress(function(e) {
        this.$panel.keypress(function(e) {
            return thisObj.handlePanelKeyPress($(this), e);
        });

    } // end bindHandlers()








//
// Function handleTabKeyDown() is a member function to process keydown events for a tab
//
// @param ($tab obj) $tab is the jquery object of the tab being processed
//
// @param (e obj) e is the associated event object
//
// @return (boolean) Returns true if propagating; false if consuming event
//
tabpanel.prototype.handleTabKeyDown = function($tab, e) {

        if (e.altKey) {
            // do nothing
            return true;
        }

        switch (e.keyCode) {
            case this.keys.enter:
            case this.keys.space:
                {
                    // Only process if this is an accordian widget
                    if (this.accordian == true) {
                        // display or collapse the panel

                        this.togglePanel($tab);

                        e.stopPropagation();
                        return false;

                    } else {
                        // Get the current selected tab
                        $curtab = this.$tabs.filter('.selected');

                        // switch to the new tab
                        this.switchTabs($curtab, $tab);
                    }
                    return true;
                }
            case this.keys.left:
            case this.keys.up:
                {

                    var thisObj = this;
                    var $prevTab; // holds jQuery object of tab from previous pass
                    var $newTab; // the new tab to switch to

                    if (e.ctrlKey) {
                        // Ctrl+arrow moves focus from panel content to the open
                        // tab/accordian header.
                    } else {

                        var curNdx = this.$tabs.index($tab);

                        if (curNdx == 0) {
                            // tab is the first one:
                            // set newTab to last tab
                            $newTab = this.$tabs.last();
                        } else {
                            // set newTab to previous
                            $newTab = this.$tabs.eq(curNdx - 1);
                        }
                        
                        if(this.accordian == true) {
                                $newTab.focus();
                        }
                        else {
                            // switch to the new tab
                            this.switchTabs($tab, $newTab);
                        }
                    }

                    e.stopPropagation();
                    return false;
                }
            case this.keys.right:
            case this.keys.down:
                {
                    var thisObj = this;
                    var foundTab = false; // set to true when current tab found in array
                    var $newTab; // the new tab to switch to

                    var curNdx = this.$tabs.index($tab);

                    if (curNdx == this.$tabs.length - 1) {
                        // tab is the last one:
                        // set newTab to first tab
                        $newTab = this.$tabs.first();
                    } else {
                        // set newTab to next tab
                        $newTab = this.$tabs.eq(curNdx + 1);
                    }

                        if(this.accordian == true) {
                            $newTab.focus();
                        }
                        else {
                                // switch to the new tab
                                this.switchTabs($tab, $newTab);
                        }
                    
                    e.stopPropagation();
                    return false;
                }
            case this.keys.home:
                {

                    // switch to the first tab
                    this.switchTabs($tab, this.$tabs.first());

                    e.stopPropagation();
                    return false;
                }
            case this.keys.end:
                {

                    // switch to the last tab
                    this.switchTabs($tab, this.$tabs.last());

                    e.stopPropagation();
                    return false;
                }
        }
    } // end handleTabKeyDown()









//
// Function handleTabKeyPress() is a member function to process keypress events for a tab.
//
//
// @param ($tab obj) $tab is the jquery object of the tab being processed
//
// @param (e obj) e is the associated event object
//
// @return (boolean) Returns true if propagating; false if consuming event
//
tabpanel.prototype.handleTabKeyPress = function($tab, e) {

        if (e.altKey) {
            // do nothing
            return true;
        }

        switch (e.keyCode) {
            case this.keys.enter:
            case this.keys.space:
            case this.keys.left:
            case this.keys.up:
            case this.keys.right:
            case this.keys.down:
            case this.keys.home:
            case this.keys.end:
                {
                    e.stopPropagation();
                    return false;
                }
            case this.keys.pageup:
            case this.keys.pagedown:
                {

                    // The tab keypress handler must consume pageup and pagedown
                    // keypresses to prevent Firefox from switching tabs
                    // on ctrl+pageup and ctrl+pagedown

                    if (!e.ctrlKey) {
                        return true;
                    }

                    e.stopPropagation();
                    return false;
                }
        }

        return true;

    } // end handleTabKeyPress()









//
// Function handleTabClick() is a member function to process click events for tabs
//
// @param ($tab object) $tab is the jQuery object of the tab being processed
//
// @param (e object) e is the associated event object
//
// @return (boolean) returns true
//
tabpanel.prototype.handleTabClick = function($tab, e) {

        // TK: added this "if" statement to do a toggle if this is a accordion
        if (this.accordian == true) {
            // display or collapse the panel

            this.togglePanel($tab);

            e.stopPropagation();
            return false;
        } else {

            // Remove the highlighting from all tabs
            this.$tabs.removeClass('selected');

            // remove all tabs from the tab order and reset their aria-selected attribute
            // TK: no need to mess with tabindex values
            // this.$tabs.attr('tabindex', '-1').attr('aria-selected', 'false');
            this.$tabs.attr('aria-selected', 'false');

            // hide all tab panels
            // TK: replaced with the following line so it is done via CSS
            // this.$panels.hide();
            this.$panels.addClass('hidden');

            // Highlight the clicked tab and update its aria-selected attribute
            $tab.addClass('selected').attr('aria-selected', 'true');
            // TK: remove the .hidden class
            $tab.next().removeClass('hidden');


        }





        // show the clicked tab panel
        // TK: removed this line as this is done via the "selected" class
        // this.$panel.find('#' + $tab.attr('aria-controls')).show();

        // make clicked tab navigable
        // TK: no need to mess with tabindex value
        // $tab.attr('tabindex', '0');

        // give the tab focus
        $tab.focus();

        return true;

    } // end handleTabClick()








//
// Function handleTabFocus() is a member function to process focus events for tabs
//
// @param ($tab object) $tab is the jQuery object of the tab being processed
//
// @param (e object) e is the associated event object
//
// @return (boolean) returns true
//
tabpanel.prototype.handleTabFocus = function($tab, e) {

        // Add the focus class to the tab
        $tab.addClass('focus');

        // TK: hide all panels, unselect all tabs, tag the tab, toggle aria-selected, show the panel, toggle aria-hidden
        //this.$panels.addClass('hidden');
        //this.$tabs.removeClass('selected');
        //$tab.addClass('selected').attr('aria-selected', 'true');
        //$tab.next().removeClass('hidden').attr('aria-hidden', 'false');

        return true;

    } // end handleTabFocus()









//
// Function handleTabBlur() is a member function to process blur events for tabs
//
// @param ($tab object) $tab is the jQuery object of the tab being processed
//
// @param (e object) e is the associated event object
//
// @return (boolean) returns true
//
tabpanel.prototype.handleTabBlur = function($tab, e) {

        // Remove the focus class to the tab
        $tab.removeClass('focus');

        // TK: same as focus


        return true;

    } // end handleTabBlur()









/////////////////////////////////////////////////////////
// Panel Event handlers
//

//
// Function handlePanelKeyDown() is a member function to process keydown events for a panel
//
// @param ($elem obj) $elem is the jquery object of the element being processed
//
// @param (e obj) e is the associated event object
//
// @return (boolean) Returns true if propagating; false if consuming event
//
tabpanel.prototype.handlePanelKeyDown = function($elem, e) {

        if (e.altKey) {
            // do nothing
            return true;
        }

        switch (e.keyCode) {
            case this.keys.esc:
                {

                    // TK: take user to the skip link so they can "escape" the widget at any time
                    this.$panel.find('#skip-widget').focus();

                    e.stopPropagation();
                    return false;
                }
            case this.keys.left:
            case this.keys.up:
                {

                    if (!e.ctrlKey) {
                        // do not process
                        return true;
                    }

                    // get the jQuery object of the tab
                    var $tab = $('#' + $elem.attr('aria-labelledby'));

                    // Move focus to the tab
                    $tab.focus();

                    e.stopPropagation();
                    return false;
                }
            case this.keys.pageup:
                {

                    var $newTab;

                    if (!e.ctrlKey) {
                        // do not process
                        return true;
                    }

                    // get the jQuery object of the tab
                    var $tab = this.$tabs.filter('.selected');

                    // get the index of the tab in the tab list
                    var curNdx = this.$tabs.index($tab);

                    if (curNdx == 0) {
                        // this is the first tab, set focus on the last one
                        $newTab = this.$tabs.last();
                    } else {
                        // set focus on the previous tab
                        $newTab = this.$tabs.eq(curNdx - 1);
                    }

                    // switch to the new tab
                    this.switchTabs($tab, $newTab);

                    e.stopPropagation();
                    e.preventDefault();
                    return false;
                }
            case this.keys.pagedown:
                {

                    var $newTab;

                    if (!e.ctrlKey) {
                        // do not process
                        return true;
                    }

                    // get the jQuery object of the tab
                    var $tab = $('#' + $elem.attr('aria-labelledby'));

                    // get the index of the tab in the tab list
                    var curNdx = this.$tabs.index($tab);

                    if (curNdx == this.$tabs.length - 1) {
                        // this is the last tab, set focus on the first one
                        $newTab = this.$tabs.first();
                    } else {
                        // set focus on the next tab
                        $newTab = this.$tabs.eq(curNdx + 1);
                    }

                    // switch to the new tab
                    this.switchTabs($tab, $newTab);

                    e.stopPropagation();
                    e.preventDefault();
                    return false;
                }
        }

        return true;

    } // end handlePanelKeyDown()









//
// Function handlePanelKeyPress() is a member function to process keypress events for a panel
//
// @param ($elem obj) $elem is the jquery object of the element being processed
//
// @param (e obj) e is the associated event object
//
// @return (boolean) Returns true if propagating; false if consuming event
//
tabpanel.prototype.handlePanelKeyPress = function($elem, e) {

        if (e.altKey) {
            // do nothing
            return true;
        }

        if (e.ctrlKey && (e.keyCode == this.keys.pageup || e.keyCode == this.keys.pagedown)) {
            e.stopPropagation();
            e.preventDefault();
            return false;
        }

        switch (e.keyCode) {
            case this.keys.esc:
                {

                    e.stopPropagation();
                    e.preventDefault();
                    return false;
                }
        }
        return true;

    } // end handlePanelKeyPress()

// focusable is a small jQuery extension to add a :focusable selector. It is used to
// get a list of all focusable elements in a panel. Credit to ajpiano on the jQuery forums.
//
$.extend($.expr[':'], {
    focusable: function(element) {
        var nodeName = element.nodeName.toLowerCase();
        var tabIndex = $(element).attr('tabindex');

        // the element and all of its ancestors must be visible
        if (($(element)[nodeName == 'area' ? 'parents' : 'closest'](':hidden').length) == true) {
            return false;
        }

        // If tabindex is defined, its value must be greater than 0
        if (!isNaN(tabIndex) && tabIndex < 0) {
            return false;
        }

        // if the element is a standard form control, it must not be disabled
        if (/input|select|textarea|button|object/.test(nodeName) == true) {

            return !element.disabled;
        }

        // if the element is a link, href must be defined
        if ((nodeName == 'a' || nodeName == 'area') == true) {

            return (element.href.length > 0);
        }

        // this is some other page element that is not normally focusable.
        return false;
    }
});