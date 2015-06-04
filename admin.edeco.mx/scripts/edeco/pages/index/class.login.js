/**
 * Class for pages user login
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
Edeco.Pages.User.Login = (function() {
	
	var _buildErrorMessage =  function(message) {
		return '<ul><li>' + message + '</li></ul>';
	};
	
	var _setupValidator = function() {
 
        var validator = $("#login").validate({
            errorElement : 'div',
            rules: {
                username: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                }
            },
            messages: {
                username: {
                    required: _buildErrorMessage(
                        'Por favor ingrese su nombre de usuario'
                    ),
                    email: _buildErrorMessage( 
                        'Ingrese una dirección de correo electrónico válida'
                    )
                },
                password: {
                    required: _buildErrorMessage(
                        'Por favor ingrese su contraseña'
                    ),
                    minlength: jQuery.format(
                        _buildErrorMessage(
                            "Su contraseña debe tener al menos {0} caracteres"
                        )
                    ),
                    maxlength: jQuery.format(
                        _buildErrorMessage(
                            'Su contraseña debe tener máximo {0} caracteres'
                        )
                    )
                }
            }
        });
        $('#username').focus();
	};
	
	var _constructor = function(){
		
		this.init = function() {
		    _setupValidator();
		};
	};
	return _constructor;
})();