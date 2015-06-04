/*
* jQuery RTE plugin 0.5.1 - create a rich text form for Mozilla, Opera, Safari 
* and Internet Explorer
*
* Copyright (c) 2009 Batiste Bieler
* Distributed under the GPL Licenses.
* Distributed under the MIT License.
*/

// define the rte light plugin
(function($) {

if(typeof $.fn.rte === 'undefined') {

    var defaults = {
        mediaUrl: '',
        cssUrl: 'rte.css',
        dotNetButtonClass: null,
        maxHeight: 350
    };

    $.fn.rte = function(options) {

    $.fn.rte.html = function(iframe) {
        return iframe.contentWindow.document.getElementsByTagName("body")[0]
                     .innerHTML;
    };

    // build main options before element iteration
    var opts = $.extend(defaults, options);

    // iterate and construct the RTEs
    return this.each( function() {
        var textarea = $(this);
        var iframe;
        var elementId = textarea.attr("id");

        // enable design mode
        function enableDesignMode() {

            var content = textarea.val();

            // Mozilla needs this to display caret
            if($.trim(content)=='') {
                content = '<br />';
            }

            // already created? show/hide
            if(iframe) {
                //console.log("already created");
                textarea.hide();
                $(iframe).contents().find("body").html(content);
                $(iframe).show();
                $("#toolbar-" + elementId).remove();
                textarea.before(toolbar());
                return true;
            }

            // for compatibility reasons, need to be created this way
            iframe = document.createElement("iframe");
            iframe.frameBorder=0;
            iframe.frameMargin=0;
            iframe.framePadding=0;
            iframe.height=200;
            if(textarea.attr('class')) {
                iframe.className = textarea.attr('class');
            }
            if(textarea.attr('id')) {
                iframe.id = elementId;
            }
            if(textarea.attr('name')) {
                iframe.title = textarea.attr('name');
            }
            textarea.after(iframe);

            var css = "";
            if(opts.cssUrl) {
                css = "<link type='text/css' rel='stylesheet' href='" 
                	+ opts.cssUrl + "' />";
            }

            var doc = "<html><head>" + css 
            	+ "</head><body class='frameBody'>"
            	+ content 
            	+ "</body></html>";
            
            tryEnableDesignMode(doc, function() {
                $("#toolbar-" + elementId).remove();
                textarea.before(toolbar());
                // hide textarea
                textarea.hide();
            });

        }

        function tryEnableDesignMode(doc, callback) {
            if(!iframe) { 
            	return false; 
        	}

            try {
                iframe.contentWindow.document.open();
                iframe.contentWindow.document.write(doc);
                iframe.contentWindow.document.close();
            } catch(error) {
                //console.log(error);
            }
            if (document.contentEditable) {
                iframe.contentWindow.document.designMode = "On";
                callback();
                return true;
            } else if (document.designMode != null) {
                try {
                    iframe.contentWindow.document.designMode = "on";
                    callback();
                    return true;
                } catch (error) {
                    //console.log(error);
                }
            }
            setTimeout(function(){tryEnableDesignMode(doc, callback);}, 500);
            return false;
        }

        function disableDesignMode(submit) {
            var content = $(iframe).contents().find("body").html();

            if($(iframe).is(":visible")) {
                textarea.val(content);
            }

            if(submit !== true) {
                textarea.show();
                $(iframe).hide();
            }
        }

        // create toolbar and bind events to it's elements
        function toolbar() {
            var tb = $("<div class='rte-toolbar' id='toolbar-"+ elementId +"'>\
                <p class='toolbar'>\
                	<a href='#' class='p'><img src='"+opts.mediaUrl+"paragraph.gif' alt='Párrafo' title='Párrafo' /></a>\
                    <a href='#' class='bold'><img src='"+opts.mediaUrl+"bold.gif' alt='Negrita' title='Negrita' /></a>\
                    <a href='#' class='italic'><img src='"+opts.mediaUrl+"italic.gif' alt='Cursiva' title='Cursiva' /></a>\
                    <a href='#' class='unorderedlist'><img src='"+opts.mediaUrl+"unordered.gif' alt='Viñetas' title='Viñetas' /></a>\
                </p></div>");

            $('.p', tb).click(function() { 
            	formatText('formatblock', '<p>');
            	return false; 
        	});
            $('.bold', tb).click(function() { 
            	formatText('bold');
            	return false; 
        	});
            $('.italic', tb).click(function() { 
        		formatText('italic');
        		return false; 
    		});
            $('.unorderedlist', tb).click(function() { 
            	formatText('insertunorderedlist');
            	return false; 
        	});
            
            // .NET compatability
            if(opts.dotNetButtonClass) {
                var dotNetButton = 
                		$(iframe).parents('form').find(opts.dotNetButtonClass);
                dotNetButton.click(function() {
                    disableDesignMode(true);
                });
            // Regular forms
            } else {
                $(iframe).parents('form').submit(function(){
                    disableDesignMode(true);
                });
            }

            var iframeDoc = $(iframe.contentWindow.document);

            var select = $('select', tb)[0];
            iframeDoc.mouseup(function() {
                return true;
            });

            iframeDoc.keyup(function() {
                var body = $('body', iframeDoc);
                if(body.scrollTop() > 0) {
                    var iframeHeight = parseInt(iframe.style['height']);
                    if(isNaN(iframeHeight)) {
                        iframeHeight = 0;
                    }
                    var h = 
                    	Math.min(opts.maxHeight, iframeHeight+body.scrollTop()) 
                    	+ 'px';
                    iframe.style['height'] = h;
                }
                return true;
            });

            return tb;
        };

        function formatText(command, option) {
            iframe.contentWindow.focus();
            try{
                iframe.contentWindow
                      .document.execCommand(command, false, option);
            }catch(e){
                //console.log(e)
            }
            iframe.contentWindow.focus();
        };

        function getSelectionElement() {
            if (iframe.contentWindow.document.selection) {
                // IE selections
                selection = iframe.contentWindow.document.selection;
                range = selection.createRange();
                try {
                    node = range.parentElement();
                }
                catch (e) {
                    return false;
                }
            } else {
                // Mozilla selections
                try {
                    selection = iframe.contentWindow.getSelection();
                    range = selection.getRangeAt(0);
                }
                catch(e){
                    return false;
                }
                node = range.commonAncestorContainer;
            }
            return node;
        };
        
        // enable design mode now
        enableDesignMode();

    }); //return this.each
    
    }; // rte

} // if

})(jQuery);
