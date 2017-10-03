/**
 * jQuery Popup Overlay
 *
 * @version 1.4.3
 * @requires jQuery v1.7.1+
 * @link http://vast-eng.github.com/jquery-popup-overlay/
 * @author Ivan Lazarevic, Vladimir Siljkovic, Branko Sekulic, Marko Jankovic
 * Christy Buckholdt - Modified to only have tooltips and to to not position tooltip beyond the browser window. It was cutting it off on the left side and making you scroll to the right side.
 */

;(function($) {

    var level = [];
    var lastclicked = [];

    $.fn.popup = $.fn.popup = function(customoptions) {

        var $body = $('body'),
            $window = $(window),
            $document = $(document),
            $el,
            $newel,
            $wrapper,
            options = {},
            blurhandler,
            focushandler,
            defaults = {
                type: 'tooltip',
                action: 'click',
                color: 'black',
                opacity: '0.4',
                horizontal: 'center',
                vertical: 'center',
                escape: true,
                blur: true,
                fade: 250,
                opensufix: '_open',
                closesufix: '_close',
                reposition: false,
                autozindex: false
            };

        var init = function(el) {

                if(!$(el).attr('id')){
                    $(el).attr('id', 'j-popup-' + parseInt(Math.random() * 100000000));
                }
                lastclicked[el.id] = false;
                level[el.id] = 0;
                $el = $(el);
                options = $.extend({}, defaults, customoptions);

                /**
                 * Repositioningtion parameter
                 */
                if (options.reposition === true) {
                    // @TODO - not so DRY...
                    $newel = $el;
                    $el = $wrapper = $('#' + el.id + '_wrapper');
                    positionpopup(el);
                    return false;
                }

                // initialize on only once
                if ($el.attr('data-popup-initialized')) {
                    return false;
                }
                $el.attr('data-popup-initialized', 'true');

                /**
                 * Set variables
                 */
                var triggerelement = '.' + el.id + options.opensufix; // class that will open popup

                /**
                 * Hide popups that aren't already hidden with CSS and move it to the top or bottom of the <body> tag
                 */
                $el.css({
                    display: 'none'
                });
                // append instead of prepend if document is ready
                // if (((document.readyState === 'interactive') || (document.readyState === 'complete')) && !($.browser.msie && parseFloat($.browser.version) < 8)) {
                //  $body.append(el);
                // } else {
                $body.prepend(el);
                // }

                              
                /**
                 * add data-popup-order attribute
                 */
                $(triggerelement).each(function(i, item) {
                    $(item).attr('data-popup-order', i);
                });

                /**
                 * Defining on which event to open/close popup
                 */
                if (options.action == 'click') { 
					// Check if the item was dragged instead of clicked
					var isDragging, wasDragging = false; 		 
					$(triggerelement)              
					 	.mousedown(function() {
							$(window).mousemove(function() {
								isDragging = true;
								$(window).unbind("mousemove");
							});
						})
						.mouseup(function() {
							wasDragging = isDragging;
							isDragging = false;
							$(window).unbind("mousemove");
						});
					
							
                    // open popup only if item was clicked and not dragged					
						$(triggerelement).on('click', function(e) {
							if ($el.is(':hidden')) 
							{
								var or = $(this).attr('data-popup-order');
								if (!wasDragging) 
								{
									dopopup(el, or);
									e.preventDefault();
									setTimeout(function(){ window.scrollTo(0,cursorTop); },5);
								}
							}					
						});		
						
                    //
                    $('.' + el.id + options.closesufix).click(function(e) {
                        hidePopUp(el);
                        e.preventDefault();
                    });
                } else if (options.action == 'hover') {
                    $(triggerelement).mouseenter(

                    function() {
                        dopopup(el, $(this).attr('data-popup-order'));
                    });
                    $(triggerelement).mouseleave(

                    function() {
                        hidePopUp(el);
                    });
                } else {
                    $(triggerelement).mouseover(

                    function() {
                        dopopup(el, $(this).attr('data-popup-order'));
                    });
                    $(triggerelement).mouseout(

                    function() {
                        hidePopUp(el);
                    });
                }

                /**
                 * Close popup on ESC key (binded only if a popup is open)
                 */
                if (options.escape) {
                    $(document).keydown(function(e) {
                        if (e.keyCode == 27 && $el.css('display') == 'block') {
                            hidePopUp(el);
                        }
                    });
                }

                /**
                 * Repositioning popup when window resize
                 */
                $(window).bind('resize', function() {
                        positionpopup(el);
                });


                /**
                 * Z-index calculation
                 */
                if (options.autozindex === true) {
                    var elements = document.getElementsByTagName("*"),
				        len = elements.length,
				        maxZIndex = 0;

                    for(var i=0; i<len; i++){
                    	
                    	var elementZIndex = $(elements[i]).css("z-index");
                    	
                        if(elementZIndex !== "auto"){

                          elementZIndex = parseInt(elementZIndex);
                          
                          if(maxZIndex < elementZIndex){
                            maxZIndex = elementZIndex;
                          }
                        }
                    }
                    
                    level[el.id] = maxZIndex;
                    
                    // add z-index to the wrapper
                    if (level[el.id] > 0) {
                        $el.css({
                            zIndex: (level[el.id] + 2)
                        });
                    }
                    
                }

                /**
                 * Automaticaly open popup on start, if autoopen option is set
                 */
                if (options.autoopen) {
                    dopopup(el, 0);
                }

            }; // init
        /**
         * Popup method
         *
         * @param el - popup element
         * @param order - element which triggered this method
         */
        var dopopup = function(el, order) {
                var clickplace = order;

                /**
                 * beforeopen Callback
                 */
                callback(options.beforeopen, clickplace);

                // remember last clicked place
                lastclicked[el.id] = clickplace;

                // show popup
                if (options.fade) 
				{
                    $el.fadeIn(options.fade, function() {
                        $(document).on('click', blurhandler);
                        $(document).on('focusin', focushandler);
                    });
					
                } 
				else 
				{
                    $el.show();
                    setTimeout(function() {
                        $(document).on('click', blurhandler);
                        $(document).on('focusin', focushandler);
                    }, 0);
                }

                // position
                positionpopup(el, clickplace);

              
                // Fix issue with iPad keyboard that breaks the position of the popup in Safari
                // https://github.com/vast-eng/jquery-popup-overlay/issues/4
                setTimeout(function() {
                    window.scrollTo(document.body.scrollLeft, document.body.scrollTop);
                }, 0);

                /**
                 * onOpen Callback
                 */
                callback(options.onOpen, clickplace);

                /**
                 * Close popup on blur
                 */
                if (options.blur) {
                    blurhandler = function(e) {
                        if (!$(e.target).parents().andSelf().is('#' + el.id)) {
                            hidePopUp(el);
                        }
                    };
                }

            };

        /**
         * Position popup
         *
         * @param el
         */
        var positionpopup = function(el, clickplace) {
                clickplace = clickplace || 0;
               
				$el.css({
					'position': 'absolute'
				});
				var $link = $('.' + el.id + options.opensufix + '[data-popup-order="' + clickplace + '"]');
				var linkOffset = $link.offset();

				// tooltip horizontal
				if (options.horizontal == 'right') {
					$el.css('left', linkOffset.left + $link.outerWidth());
				} else if (options.horizontal == 'left') {
					$el.css('right', $(window).width() - linkOffset.left);
				} else {
					//alert('linkOffset left = '+linkOffset.left+'$link.outerWidth = '+$link.outerWidth()+'el.outerwith = '+$(el).outerWidth()+' marginleft = '+parseFloat($(el).css('marginLeft')));
					// Do not display the popup left of the browser window (if it is negative, display it at position 0//
					if (linkOffset.left + ($link.outerWidth() / 2) - ($(el).outerWidth() / 2) - parseFloat($(el).css('marginLeft')) > 0)
						// Do not display the popup right of the browswer window
						if (linkOffset.left + ($link.outerWidth() / 2) + ($(el).outerWidth() / 2) > $( window ).width())
							{
								var fixOffset = linkOffset.left + ($link.outerWidth() / 2) + ($(el).outerWidth() / 2) - $( window ).width();
								$el.css('left', linkOffset.left + ($link.outerWidth() / 2) - ($(el).outerWidth() / 2) - fixOffset - parseFloat($(el).css('marginLeft')) );
							}
						else
							$el.css('left', linkOffset.left + ($link.outerWidth() / 2) - ($(el).outerWidth() / 2) - parseFloat($(el).css('marginLeft')) );
					else
						$el.css('left', 0);
				}

				// tooltip vertical
				if (options.vertical == 'bottom') {
					$el.css('top', linkOffset.top + $link.outerHeight());
				} else if (options.vertical == 'top') {
					$el.css('bottom', $(window).height() - linkOffset.top);
				} else {
					$el.css('top', linkOffset.top + ($link.outerHeight() / 2) - ($(el).outerHeight() / 2) - parseFloat($(el).css('marginTop')) );
				}

            };

        /**
         * Hide popup
         *
         * @param {DOM Object} el
         */
        var hidePopUp = function(el) {

                
                // unbind event for blur when popup closes
                if (options.blur) {
                    $(document).off('click', blurhandler);
                }
               
                // hide popup
                if (options.fade) {
                    $el.fadeOut(options.fade);
                } else {
                    $el.hide();
                }

                /**
                 * onClose callback
                 */
                callback(options.onClose, lastclicked[el.id]);
            };

        /**
         * Callbacks calls
         *
         * @param func - callback function
         * @param clickplace
         */
        var callback = function(func, clickplace) {
                var cp = $('.' + $el.attr('id') + options.opensufix + '[data-popup-order="' + clickplace + '"]');
                if (typeof func == 'function') {
                    func(cp);
                }
            };

        this.each(function() {
            init(this);
        });

        //return reference to hide popup
        return hidePopUp;

    }; // fn.popup

})(jQuery);
