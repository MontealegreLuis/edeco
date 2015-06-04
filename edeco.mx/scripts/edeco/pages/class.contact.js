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
Edeco.Pages.Contact = (function() {
    
    /**
     * @return string
     */
    var _buildErrorMessage =  function(message) {
        return '<ul><li>' + message + '</li></ul>';
	};
	
	/**
	 * @return void
	 */
	var _setupFormValidation = function() {
	    var validator = $("#contact").validate({
            errorElement : 'div',
            rules: {
                name: {
                    required: true,
                    minlength: 4,
                    maxlength: 100
                },
                emailAddress: {
                    required: true,
                    email: true
                },
                message: {
                    required: true,
                    minlength: 6,
                    maxlength: 1500
                }
                
            },
            messages: {
                name: {
                    required: _buildErrorMessage('Por favor ingrese su nombre'),
                    minlength: $.format(
                        _buildErrorMessage(
                            'Su nombre debería tener al menos {0} caracteres'
                        )
                    ),
                    maxlength: $.format(
                        _buildErrorMessage(
                            'Su nombre debe tener máximo {0} caracteres'
                        )
                    )
                },
                emailAddress: {
                    required: _buildErrorMessage(
                        'Por favor ingrese su dirección de correo electónico'
                    ),
                    email: $.format(
                        _buildErrorMessage(
                          'Ingrese una dirección de correo electrónico válida'
                        )
                    )
                },
                message: {
                    required: _buildErrorMessage(
                        'Por favor ingrese su mensaje'
                    ),
                    minlength: $.format(
                        _buildErrorMessage(
                            'Su mensaje debe tener al menos {0} caracteres'
                        )
                    ),
                    maxlength: $.format(
                        _buildErrorMessage(
                            'Su mensaje debe tener máximo {0} caracteres'
                        )
                    )
                }
            }
        });
        $('#name').focus();
	};
	
	/**
	 * @return void
	 */
	var _constructor = function(){

	    /**
	     * @return void
	     */
		this.init = function() {
		    if ($("#contact").length > 0) {
		        _setupFormValidation();
		    }
		};
	};
	
	return _constructor;
})();