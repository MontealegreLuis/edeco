/**
 * Class for pages add Address and update Address
 *
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 * @subpackage Pages
 */
var Edeco = {};
Edeco.Pages = {};
Edeco.Pages.Index = {};
Edeco.Pages.Index.Construction = (function() {
    
    /**
     * @retun void
     */
    var _constructor = function(){
        
        /**
         * @return void
         */
        this.init = function() {
        	$('#construction-slides').jqFancyTransitions({ 
        		width: 259, height: 186, direction: 'random'
    		});
        };
    };
    
    return _constructor;
})();