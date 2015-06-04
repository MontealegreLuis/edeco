/**
 * Plugin for confirmations messages
 * 
 * Apply plugin to <a> elements
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
(function($) {
    $.fn.confirm = function(options) {
        var defaults = {
            title: 'Confirm',
            message: 'Are you sure to continue?',
            ok: 'Ok',
            cancel: 'Cancel'
        };
        var options = $.extend(defaults, options);
        
        return this.each(function() {
            var container = $('<div></div>').append(options.message);
            var ok = options.ok;
            var cancel = options.cancel;
            var targetUrl = $(this).attr('href');
            var buttons = {};
            buttons[ok] = function() {
                $(this).dialog('close');
                window.location.href = targetUrl;
            };
            buttons[cancel] = function() {
                $(this).dialog('close');
            };
            $(this).click(function(e) {
                e.preventDefault();
                container.dialog({
                    autoOpen : false,
                    title : options.title,
                    modal: true,
                    buttons: buttons,
                    resizable: false,
                    show: 'slide',
                    hide: 'scale'
                });
                container.dialog('open');
            });
         });    
    };
})(jQuery);