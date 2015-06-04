/**
 * Show Google map for a given address
 *
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 * @subpackage Pages
 */
var Edeco = {};
Edeco.Pages = {};
Edeco.Pages.GoogleMap = {};
Edeco.Pages.GoogleMap.Show = (function() {
    
    /**
     * @param Object results
     * @return void
     */
    var _constructor = function(){

        /**
         * Initialize the page
         *
         * @return void
         */
        this.init = function(property) {
            $('body').unload(GUnload);
            $.googleMap(property, 'google-map');
        };
    };

    return _constructor;
})();