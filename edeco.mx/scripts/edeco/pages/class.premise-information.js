/**
 * Class for pages add property and update property
 *
 * @author     MMA <misraim.mendoza@mandragora-web.systems>
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 * @subpackage Pages
 */
var Edeco = {};
Edeco.Pages = {};
Edeco.Pages.PremiseInformation = (function() {
	
    /**
     * @return void
     */
	var _buildErrorMessage =  function(message) {
        return '<ul><li>' + message + '</li></ul>';
	};
	
	/**
	 * @retrurn void
	 */
	var _setupFormValidation = function() {
		var validator = $('#premiseinformation').validate({
			errorElement : 'div',
			rules: {
				name: {
					required: true,
					minlength: 4,
					maxlength: 100
				},
				telephone: {
				    required: false,
					minlength: 16,
					maxlength: 16
				},
				emailAddress: {
					required: true,
					email: true
				},
				zone: {
					required: true,
					minlength: 4,
					maxlength: 100
				},
				minPrice: {
					required: false,
					number: true,
					range: [1,100000000]
					
				},
				maxPrice: {
					required: true,
					number: true,
					range: [1,100000000]
					
				},
				surface: {
					required: false,
					number: true,
					range: [1,100000000]
				},
				characteristics: {
					required: false,
					minlength: 6,
					maxlength: 1500
				}
			},
			messages: {
				name: {
					required: _buildErrorMessage(
						'Por favor ingrese su nombre'
					),
					minlength: $.format(
						_buildErrorMessage(
							'Su nombre debería tener al menos {0} caracteres'
						)
					),
					maxlength: $.format(
						_buildErrorMessage(
							'Su nombre debería tener maximo {0} caracteres'
						)
					)
				},
				telephone: {
					minlength: $.format(
						_buildErrorMessage(
						 '- Su numero telefónico debe tener 10 digitos'
						)
					),
					maxlength: $.format(
						_buildErrorMessage(
							'+ Su numero telefónico debe de tener 10 digitos'
						)
					)
				},
				emailAddress: {
					required: _buildErrorMessage(
						'Por favor ingrese su dirección de correo electrónico'
					),
					email: jQuery.format(
						_buildErrorMessage(
							'Ingrese una dirección de correo electrónico válido'
						)
					)
				},
				zone: {
					required: _buildErrorMessage(
						'Por favor ingrese una zona'
					),
					minlength: $.format(
						_buildErrorMessage(
							'La zona debería tener al menos {0} caracteres'
						)
					),
					maxlength: $.format(
						_buildErrorMessage(
							'La zona debe tener maximo {0} caracteres'
						)
					)
				},
				minPrice: {
					number: $.format(
						_buildErrorMessage(
							'Solo puede ingresar numeros'
						)
					),
					range: $.format(
						_buildErrorMessage(
							'El precio mínimo debe estar entre 1 y 100000000'
						)
					)
				},
				maxPrice: {
					required: _buildErrorMessage(
						'Por favor ingrese un precio máximo'
					),
					number: $.format(
						_buildErrorMessage(
							'Solo puede ingresar numeros'
						)
					),
					range: $.format(
						_buildErrorMessage(
							'El precio máximo debe estar entre 1 y 100000000'
						)
					)
				},
				surface: {
					number: $.format(
						_buildErrorMessage(
							'Solo puede ingresar numeros'
						)
					),
					range: $.format(
						_buildErrorMessage(
							'La superficie debe estar entre 1 y 100000000'
						)
					)
				},
				characteristics: {
					minlength: $.format(
						_buildErrorMessage(
						 'Las características deben tener al menos {0} caracteres'
						)
					),
					maxlength: $.format(
						_buildErrorMessage(
					  	 'Las caracterísitcas deben tener maximo {0} caracteres'
						)
					)
				}
			}
		});
		$('#telephone').mask('(999) 9-99-99-99', {placeholder : ' '});
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
		    if ($('#premiseinformation').length > 0) {
		        _setupFormValidation();
		    }
		};
	};
	
	return _constructor;
})();