/**
 * class to display the confirmation message to delete an image
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web.systems>
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @author     MMA <misraim.mendoza@mandragora-web.systems>
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
Edeco.Pages.Picture = {};
Edeco.Pages.Picture.List = (function() {
    
    /**
     * @return void
     */
    var _constructor = function(picture){
        
    	/**
         * @return void
         */ 
        this.init = function() {
            var optionsConfirm = {
                title: 'Confirmar eliminación',
                message: '¿Seguro que desea eliminar esta fotografía?',
                ok: 'Sí',
                cancel: 'No'
            };
            $('.delete', '#picture-list').confirm(optionsConfirm);
        };
    };
    
    return _constructor;
})();