/**
 * Class for the list of projects page
 *
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 * @subpackage Pages
 */
var Edeco = {};
Edeco.Pages = {};
Edeco.Pages.User = {};
Edeco.Pages.User.List = (function() {
    
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
                message: '¿Seguro que desea eliminar este usuario?',
                ok: 'Sí',
                cancel: 'No'
            };
            $('.delete').confirm(optionsConfirm);
        };
    };
    
    return _constructor;
})();