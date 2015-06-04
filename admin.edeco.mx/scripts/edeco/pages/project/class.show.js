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
Edeco.Pages.Project = {};
Edeco.Pages.Project.Show = (function() {
    
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
                message: '¿Desea eliminar este proyecto de inversión?',
                ok: 'Sí',
                cancel: 'No'
            };
            $('.delete').confirm(optionsConfirm);
        };
    };

    return _constructor;
})();