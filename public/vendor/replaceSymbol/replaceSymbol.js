
    (function ($) {
        /**
            * Will replace the symbol of default ':id' to anything else
            *
            * @param attr
            * @param replaceTo
            * @param replaceFrom
            * @returns {*|void}
            */
        $.fn.replaceSymbol = function (attr, replaceTo, replaceFrom = ':id') {
            let value;
    
            if (typeof replaceTo === "function") {
                return replaceTo(this);
            }
    
            value = $(this).attr('data-' + attr).replace(replaceFrom, replaceTo);
    
            $(this).attr(attr, value);
    
            return this;
        };
    })(jQuery);
    
    let replaceSymbol = function (selector, attr, replaceTo, replaceFrom = ':id') {
        let val = $(selector).attr(attr);
    
        if (typeof selector === 'string') {
            selector = $(selector);
        }
    
        if (typeof replaceTo === "function") {
            return val.replace(replaceFrom, replaceTo(this));
        }
    
        return val.replace(replaceFrom, replaceTo);
    };