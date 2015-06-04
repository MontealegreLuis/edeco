/**
 * Class for pages add property and update property
 *
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 * @subpackage Pages
 */
var Edeco = {};
Edeco.Pages = {};
Edeco.Pages.Property = {};
Edeco.Pages.Property.Detail = (function() {
    
    var onStart = function() {
        $('object').css('visibility', 'hidden');
        $.fancybox.showActivity();
    };
    
    var onComplete = function() {
        $.fancybox.hideActivity();
    };
    
    var onClosed = function() {
      $('object').css('visibility', 'visible');
    };
    
    var formatTitle = function (title, currentArray, currentIndex, currentOpts) {
        return '<strong>' + title + '</strong>  '  
               + '(Imagen ' + (currentIndex + 1) + ' de ' + currentArray.length + ')';
    };
    
    var _constructor = function(){

        /**
         * Initialize the page
         *
         * @return void
         */
        this.init = function(property) {
            if ($('#google-map').length > 0) {
                $.googleMap(property, 'google-map');
            }
            if ($('.slideShow').length > 0) {
                $('.slideShow').slideShow(
                    {slideSize : {width: 275,height: 190}}
                );
                $('.dialog').fancybox({
                    'titlePosition': 'inside', 
                    'onStart': onStart, 
                    'onComplete': onComplete,
                    'onClosed': onClosed,
                    'titleFormat': formatTitle
                });
            }
            $('.social-link').click(function() {
    		    var newWindow = window.open(
    			    $(this).attr('href'), '', 'height=380,width=600'
    		    );
    		    if (window.focus) {
    			    newWindow.focus();
    		    }
    		    return false;
    		});
        };
    };

    return _constructor;
})();