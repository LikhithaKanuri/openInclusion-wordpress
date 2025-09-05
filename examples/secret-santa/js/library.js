///// General Functions ///////
jQuery(document).ready(function($) {
   // global variables
   var $errInProgress = false;
   var $fieldId = '';

   jQuery.fn.customInput = function(){
      //alert('In function');
   	return $(this).each(function(){	
   		if($(this).is('.toggle [type=checkbox],.toggle [type=radio]')){ 
   			var input = $(this);
   			
   			// get the associated label using the input's id
   			var label = $('label[for='+input.attr('id')+']');
   			
   			// wrap the input + label in a div 
   			input.add(label).wrapAll('<div class="custom-'+ input.attr('type') +'"></div>');
   			
   			// necessary for browsers that don't support the :hover pseudo class on labels
   			label.hover(
   				function(){ $(this).addClass('hover'); },
   				function(){ $(this).removeClass('hover'); }
   			);
   			
   			//bind custom event, trigger it, bind click,focus,blur events					
   			input.on('updateState', function(){	
   				input.is(':checked') ? label.addClass('checked') : label.removeClass('checked checkedHover checkedFocus'); 
   			})
   			.trigger('updateState')
   			.on('click',function(){ 
   				$('input[name='+ $(this).attr('name') +']').trigger('updateState'); 
   			})
   			.on('focus',function(){ 
   				label.addClass('focus'); 
   				if(input.is(':checked')){  $(this).addClass('checkedFocus'); } 
   			})
   			.on('blur', function(){ label.removeClass('focus checkedFocus'); });
   		}
   	});
   };


   
   $('input').customInput();


   // Code here will be executed on document ready. Use $ as normal.
   $('html').addClass( 'js' ).removeClass( 'no-js' );

   // Click on Get started button
   $('#get-started').on('click', function(e) {
      //alert('Hello');
      $('#panel1').slideUp();
      $('#panel2').slideDown();
      $('#enter-detail-hdr').wait(1000).focus();
   });

   // Click on Submit details button
   $('#my-submit').on('click', function(e) {
      $errInProgress = false;
      
      //Do validation of name
      $myVal = $('#myname').val();
      if ($myVal.length < 1) {
         $errInProgress = true;
         $( '#myname' ).attr('aria-invalid','true');
         $( '#myname-errors' ).html('Please specify your name');
         $( '#show-my-name' ).html('');
      } else {
         $( '#myname' ).attr('aria-invalid','false');
         $( '#myname-errors' ).html('');
         $( '#show-my-name' ).html($myVal);
      }
      
      //Do validation of team
      $myVal = $('#workteam').val();
      if ($myVal.length < 1) {
         $errInProgress = true;
         $( '#workteam' ).attr('aria-invalid','true');
         $( '#workteam-errors' ).html('Please specify your team');
         $( '#show-my-team' ).html('');
      } else {
         $( '#workteam' ).attr('aria-invalid','false');
         $( '#workteam-errors' ).html('');
         $( '#show-my-team' ).html($myVal);
      }
      
      //Do validation of type
      $radVal = $('#category').find('input:radio:checked' ).val();
      if (!$radVal) {
         $errInProgress = true;
         $( '#category' ).addClass('error');
         $( '#category-errors' ).html('Please specify your gift type');
         $( '#show-my-gift' ).html('');
      } else {
         $( '#category' ).removeClass('error');
         $( '#category-errors' ).html('');
         $( '#show-my-gift' ).html($radVal);
      }
      
      if ($errInProgress){ 
         e.preventDefault(); 
         alert('Please fix the validation errors before submitting the form.');
         $('#myname').focus();
      } else {
         e.preventDefault(); 
         $('#panel2').slideUp();
         $('#panel3').slideDown();
         $('#your-detail-hdr').wait(1000).focus();
      
      }
      
      
   });
      // Click on Get started button
   $('#get-name').on('click', function(e) {
      //alert('Hello');
      $('#panel3').slideUp();
      $('#panel4').slideDown();
      $( '#selected-name' ).html('Ebeneezer Scrooge');
      $( '#selected-team' ).html('Finance');
      $( '#selected-gift' ).html('Sport');
      $('#name-selected-hdr').wait(1000).focus();
   });

});

/**
 * jquery.wait - insert simple delays into your jquery method chains
 * @author Matthew Lee matt@madleedesign.com
 */

(function ($) {
    function jQueryDummy ($real, delay, _fncQueue) {
        // A Fake jQuery-like object that allows us to resolve the entire jQuery
        // method chain, pause, and resume execution later.

        var dummy = this;
        this._fncQueue = (typeof _fncQueue === 'undefined') ? [] : _fncQueue;
        this._delayCompleted = false;
        this._$real = $real;

        if (typeof delay === 'number' && delay >= 0 && delay < Infinity)
            this.timeoutKey = window.setTimeout(function () {
                dummy._performDummyQueueActions();
            }, delay);

        else if (delay !== null && typeof delay === 'object' && typeof delay.promise === 'function')
            delay.then(function () {
                dummy._performDummyQueueActions();
            });

        else if (typeof delay === 'string')
            $real.one(delay, function () {
                dummy._performDummyQueueActions();
            });

        else
            return $real;
    }

    jQueryDummy.prototype._addToQueue = function(fnc, arg){
        // When dummy functions are called, the name of the function and
        // arguments are put into a queue to execute later

        this._fncQueue.unshift({ fnc: fnc, arg: arg });

        if (this._delayCompleted)
            return this._performDummyQueueActions();
        else
            return this;
    };

    jQueryDummy.prototype._performDummyQueueActions = function(){
        // Start executing queued actions.  If another `wait` is encountered,
        // pass the remaining stack to a new jQueryDummy

        this._delayCompleted = true;

        var next;
        while (this._fncQueue.length > 0) {
            next = this._fncQueue.pop();

            if (next.fnc === 'wait') {
                next.arg.push(this._fncQueue);
                return this._$real = this._$real[next.fnc].apply(this._$real, next.arg);
            }

            this._$real = this._$real[next.fnc].apply(this._$real, next.arg);
        }

        return this;
    };

    $.fn.wait = function(delay, _queue) {
        // Creates dummy object that dequeues after a times delay OR promise

        return new jQueryDummy(this, delay, _queue);
    };

    for (var fnc in $.fn) {
        // Add shadow methods for all jQuery methods in existence.  Will not
        // shadow methods added to jQuery _after_ this!
        // skip non-function properties or properties of Object.prototype

        if (typeof $.fn[fnc] !== 'function' || !$.fn.hasOwnProperty(fnc))
            continue;

        jQueryDummy.prototype[fnc] = (function (fnc) {
            return function(){
                var arg = Array.prototype.slice.call(arguments);
                return this._addToQueue(fnc, arg);
            };
        })(fnc);
    }
})(jQuery);
