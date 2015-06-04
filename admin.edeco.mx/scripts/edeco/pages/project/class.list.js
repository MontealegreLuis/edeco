/**
 * Class for the list of projects page
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web.systems>
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @author     MMA <misraim.mendoza@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 * @subpackage Pages
 */
var Edeco = {};
Edeco.Pages = {};
Edeco.Pages.Project = {};
Edeco.Pages.Project.List = (function() {
    
    /**
     * @return void
     */
    var _constructor = function(project){
        
    	/**
         * @return void
         */ 
        this.init = function() {
            var optionsConfirm = {
                title: 'Confirmar eliminación',
                message: '¿Seguro que desea eliminar este proyecto?',
                ok: 'Sí',
                cancel: 'No'
            };
            $('.delete').confirm(optionsConfirm);
        };
    };
    
    return _constructor;
})();
