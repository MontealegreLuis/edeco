/**
 * Class for pages add property and update property
 *
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 * @subpackage Pages
 */
if (typeof Edeco === 'undefined') {
    var Edeco = {};
}
if (typeof Edeco.Pages === 'undefined') {
    Edeco.Pages = {};
}
Edeco.Pages.Property = {};
Edeco.Pages.Property.Show = (function() {
    
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
                message: '¿Desea eliminar este inmueble?',
                ok: 'Sí',
                cancel: 'No'
            };
            $('.delete', '#actions-property').confirm(optionsConfirm);
        };
    };

    return _constructor;
})();