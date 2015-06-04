/**
 * Class for the list of recommended properties page
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
Edeco.Pages.RecommendedProperty.List = (function() {
    
    /**
     * @return void
     */
    var _constructor = function(){
        
        /**
         * @return void
         */ 
        this.init = function() {
            var optionsConfirm = {
                title: 'Confirmar eliminación',
                message: '¿Desea eliminar esta recomendación?',
                ok: 'Sí',
                cancel: 'No'
            };
            $('.delete').confirm(optionsConfirm);
        };
    };
    
    return _constructor;
})();