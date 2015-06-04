/**
 * Class for pages add Address and update Address
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web.systems>
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

Edeco.Pages.Address.Detail = (function() {
    var _citiesUrl = null;
    
    /**
     * @return string
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
    var _createOptions = function(options) {
        var select = $('<select></select>');
        select.attr('id', 'cityId')
              .attr('name', 'cityId')
              .append('<option value="">-- Seleccionar --</option>');
        for(var i = 0; i < options.length; i++) {
            var htmlOption = $('<option></option>');
            htmlOption.attr('value', options[i].id);
            htmlOption.append(options[i].name);
            select.append(htmlOption);
        }
        $('#cityId').replaceWith(select);
    };

    var _createAjaxDialog = function() {
        var canvas = $('<div class="ajax"></div>').append(
            '<img alt="" src="/images/ajax.gif" /> Cargando ciudades...'
        );
        canvas.dialog({
            title : '',
            modal: true,
            resizable: false
        });
        $('.ui-dialog-titlebar').hide();
        return canvas;
    };

    /**
     * @return void
     */
    var _findCitiesByStateId = function() {
        var id = $('#state').val();
        if ($.trim(id) == '') {
        	var select = $('<select></select>');
        	select.attr('id', 'cityId')
             	  .attr('name', 'cityId')
              	  .append('<option value="">-- Seleccionar --</option>');
        	$('#city').replaceWith(select);
            return;
        }
        var dialog = _createAjaxDialog();
        $(dialog).ajaxStart(function(){
            $(this).dialog('open');
        });
        $(dialog).ajaxComplete(function(event, request, settings){
            $(this).dialog('close');
        });
        var url = _citiesUrl;
        url = url.replace('stateId', id);
        var data = {};
        $.ajax({
            url: url,
            data: data,
            success: function(response) {
                _createOptions(response);
            },
            dataType: 'json'
        });
    };

    /**
     * @return void
     */
    var initValidator = function() {
        $('#address').validate({
            submitHandler: function(form) {
                $('.rte-zone').each(function() {
                    if ($(this).is('iframe')) {
                        var id = $(this).attr('id');
                        var textAreaId = 'textarea[id="' + id + '"]';
                        var html = $(this).contents().find('body').html(); 
                        var iframeContent = $('<div>' + html + '</div>');
                        $.rteTidy.tidy(iframeContent);
                        $(textAreaId).val(iframeContent.html());
                    }
                });
                form.submit();
            },
            errorElement : 'div',
            rules: {
                streetAndNumber: {
                    required: true,
                    minlength: 5,
                    maxlength: 150
                },
                neighborhood: {
                    required: true,
                    minlength: 5,
                    maxlength: 150
                },
                state: {
                    required: true
                },
                city: {
                    required: true
                },
                zipCode: {
                    required: false,
                    minlength: 5,
                    maxlength: 5,
                    number: true
                },
                addressReference: {
                    rterequired: false,
                    rteminlength: 15,
                    rtemaxlength: 1500
                }
            },
            messages: {
                streetAndNumber: {
                    required: wrap(
                        'Por favor ingrese la calle y número'
                    ),
                    minlength: $.format(
                        wrap(
                        'La calle y número deberían tener mínimo {0} caracteres'
                        )
                    ),
                    maxlength: $.format(
                        wrap(
                        'La calle y número deberían tener máximo {0} caracteres'
                        )
                    )
                },
                neighborhood: {
                    required: wrap(
                        'Por favor ingrese una colonia'
                    ),
                    minlength: $.format(
                        wrap(
                            'La colonia deberia tener mínimo {0} caracteres'
                        )
                    ),
                    maxlength: $.format(
                        wrap(
                            'La colonia deberia tener máximo {0} caracteres'
                        )
                    )
                },
                state: {
                    required: wrap(
                        'Por favor seleccione un estado'
                    )
                },
                city: {
                    required: wrap(
                        'Por favor seleccione una ciudad'
                    )
                },
                zipCode: {
                    minlength: $.format(
                        wrap(
                            'El código postal debe tener minimo {0} dígitos'
                        )
                    ),
                    maxlength: $.format(
                        wrap(
                            'El código postal debe tener maximo {0} dígitos'
                        )
                    ),
                    number: $.format(
                        wrap(
                            'El código postal solo contiene números'
                        )
                    )
                },
                addressReference: {
                    rteminlength: $.format(
                        wrap(
                            'La referencia debe tener mínimo {0} caracteres'
                        )
                    ),
                    rtemaxlength: $.format(
                        wrap(
                            'La referencia debe tener máximo {0} caracteres'
                        )
                    )
                }
            }
        });
    };

    /**
     * @return void
     */
    var _constructor = function(){

        /**
         * @return void
         */
        this.init = function(citiesUrl, cssUrl, imagesUrl) {
            _citiesUrl = citiesUrl;
            initValidator();
            $('.rte-zone').rte({cssUrl: cssUrl, mediaUrl: imagesUrl});
            
            /*
             * Move the textarea elements after the iframe, so that error
             * messages display correctly
             */
            $('textarea.rte-zone').each(function() {
                var dd = $(this).parent();
                var textArea = $(this).remove();
                textArea.appendTo(dd);
            });
            $('#state').change(_findCitiesByStateId);
            $('.delete', '#address-actions').confirm({
                title: 'Confirmar eliminación',
                message: '¿Desea eliminar esta dirección?',
                ok: 'Sí',
                cancel: 'No'
            });
            $('#streetAndNumber').focus();
        };
    };

    return _constructor;
})();
