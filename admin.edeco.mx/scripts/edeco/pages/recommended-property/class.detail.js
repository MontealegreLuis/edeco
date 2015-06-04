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
Edeco.Pages.RecommendedProperty = {};
Edeco.Pages.RecommendedProperty.Detail = (function() {
  
    /**
     * @return void
     */
    var _constructor = function(){

        /**
         * @return void
         */
        this.init = function() {
            $('#recommended-property').submit(function() {
            	var properties = '';
            	$('input[type="checkbox"]:checked').each(function() {
            		properties += $(this).val() + ',';
            	});
            	$('#properties').val(properties
            					.substring(0, properties.length - 1));
            });
        };
    };

    return _constructor;
})();