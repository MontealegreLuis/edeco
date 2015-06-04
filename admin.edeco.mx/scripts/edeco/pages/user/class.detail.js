/**
 * Class for pages add Address and update Address
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
Edeco.Pages.User = {};
Edeco.Pages.User.Detail = (function() {
	
	/**
     * @return void
     */
	var wrap =  function(message) {
        return $.format(
            '<ul class="error-list"><li class="error-message">{0}</li></ul>', 
            message
        );
    };
	
	/**
	 * @return void
	 */
	var _initValidator = function() {
        var validator = $("#user").validate({
            errorElement : 'div',
            rules: {
                username: {
                    required: true,
                    email: true
                },
                state: {
                    required: true
                }
            },
            messages: {
                username: {
                    required: wrap(
                        'Por favor ingrese su nombre de usuario'
                    ),
                    email: wrap( 
                        'Ingrese una dirección de correo electrónico válida'
                    )
                },
                state: {
                    required: wrap(
                      'Por favor seleccione un estado para la cuenta de usuario'
                    )
                } 
            }
        });
        var state = $('#state');
        if (state.length > 0) {
            state.focus();
        } else {
            $('#username').focus();
        }
	};

	/**
	 * @return void
	 */
	var _constructor = function(){
	    
		this.init = function() {
	        var optionsConfirm = {
                title: 'Confirmar eliminación',
                message: '¿Desea eliminar este usuario?',
                ok: 'Sí',
                cancel: 'No'
            };
	        if ($('.delete').length > 0) {
	            $('.delete').confirm(optionsConfirm);
	        }
		    _initValidator();
		};
	};
	
	return _constructor;
})();
