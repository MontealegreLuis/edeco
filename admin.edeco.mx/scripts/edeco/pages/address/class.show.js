/**
 * Show address class
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
if (typeof Edeco.Pages.Address === 'undefined') {
    Edeco.Pages.Address = {};
}
Edeco.Pages.Address.Show = (function() {
    
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
                message: '¿Desea eliminar esta dirección?',
                ok: 'Sí',
                cancel: 'No'
            };
            $('.delete', '#address-actions').confirm(optionsConfirm);
        };
    };

    return _constructor;
})();