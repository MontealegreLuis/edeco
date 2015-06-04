/**
 * Custom methods for validating rte plugin elements
 *
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 * @subpackage Pages
 */
jQuery.validator.addMethod(
    'rterequired', 
    function(value, element, param) {
        var plainText = value.replace(/(<.*?>)/ig, '');
        var isValid = $.trim(plainText) != '';
        return this.optional(element) || isValid;
    }, 
    jQuery.format('Este campo es obligatorio')
);

jQuery.validator.addMethod(
    'rtemaxlength', 
    function(value, element, param) {
        var plainText = value.replace(/(<.*?>)/ig, '');
        var isValid = plainText.length <= param;
        return this.optional(element) || isValid;
    }, 
    jQuery.format('Ingrese máximo {0} caracteres')
);

jQuery.validator.addMethod(
    'rteminlength', 
    function(value, element, param) {
        var plainText = value.replace(/(<.*?>)/ig, '');
        var isValid = plainText.length >= param;
        return this.optional(element) || isValid;
    }, 
    jQuery.format('Ingrese mínimo {0} caracteres')
);