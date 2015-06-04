(function($) {
  $.rteTidy = {};
  
  $.rteTidy.replace = function(iframeContent, pattern, tag) {
      var elems = $('span', iframeContent).filter(function() {
          if ($(this).attr('style') != undefined) {
              var styleContent = $(this).attr('style');
              var regex = new RegExp(pattern);
              var match = regex.exec(styleContent);
              if (match != null) {
                  return $(this);
              }
          }
      });

      elems.each(function() {
          var content = $(this).html();
          $(this).replaceWith('<' + tag + '>' + content + '</' + tag + '>');
      });
  };
  
  $.rteTidy.replaceBoth = function(iframeContent, pattern, tag1, tag2) {
      var elems = $('span', iframeContent).filter(function() {
          if ($(this).attr('style') != undefined) {
              var styleContent = $(this).attr('style');
              var regex = new RegExp(pattern);
              var match = regex.exec(styleContent);
              if (match != null) {
                  return $(this);
              }
          }
      });

      elems.each(function() {
          var content = $(this).html();
          $(this).replaceWith('<' + tag1 + '>' + '<' + tag2 + '>' + content
              + '</' + tag2 + '>' + '</' + tag1 + '>');
      });
  };
  
  $.rteTidy.tidy = function(iframeContent) {
      var pattern = new RegExp(/font\-weight: bold; font\-style: italic/ig);
      $.rteTidy.replaceBoth(iframeContent, pattern, 'strong', 'em');
      pattern = new RegExp(/font\-style: italic; font\-weight: bold/ig);
      $.rteTidy.replaceBoth(iframeContent, pattern, 'em', 'strong');
      pattern = /font\-weight: bold/ig;
      $.rteTidy.replace(iframeContent, pattern, 'strong');
      pattern = /font\-style: italic/ig;
      $.rteTidy.replace(iframeContent, pattern, 'em');
  };
  
})(jQuery);