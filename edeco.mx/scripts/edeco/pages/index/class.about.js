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
Edeco.Pages.Index.About = (function() {
    
    /**
     * @retun void
     */
    var _constructor = function(){
        
        /**
         * @return void
         */
        this.init = function() {
            $('.slideShow').slideShow({slideSize : {width: 320,height: 445}});
        };
    };
    
    return _constructor;
})();