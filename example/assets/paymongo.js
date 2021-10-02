
/**
 * Paymongo Sapmle Wrapper
 *
 * <code>
 *      var params = [
 *      ];
 *      Paymongo.createPaymentMethod(params, function(response) {
 *          console.log(response);
 *      });
 * </code>
 */
window.Paymongo = {
    config: {
        apiKey: '',
    }
};
(function($) {
    var win=window, xhrs = [
            function () { return new XMLHttpRequest(); },
            function () { return new ActiveXObject("Microsoft.XMLHTTP"); },
            function () { return new ActiveXObject("MSXML2.XMLHTTP.3.0"); },
            function () { return new ActiveXObject("MSXML2.XMLHTTP"); }
        ],
        _xhrf = null;

    $.xhr = function () {
        if (_xhrf != null) return _xhrf();
        for (var i = 0, l = xhrs.length; i < l; i++) {
            try {
                var f = xhrs[i], req = f();
                if (req != null) {
                    _xhrf = f;
                    return req;
                }
            } catch (e) {
                continue;
            }
        }
        return function () { };
    };
    $._xhrResp = function (xhr) {
        switch (xhr.getResponseHeader("Content-Type").split(";")[0]) {
            case "text/xml":
                return xhr.responseXML;
            case "text/json":
            case "application/json":
            case "text/javascript":
            case "application/javascript":
            case "application/x-javascript":
                return win.JSON ? JSON.parse(xhr.responseText) : eval(xhr.responseText);
            default:
                return xhr.responseText;
        }
    };
    $.send = function (o) {
        var xhr = $.xhr(), timer, n = 0;
        if (o.timeout) timer = setTimeout(function () { xhr.abort(); }, o.timeout);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (timer) clearTimeout(timer);
                if (xhr.status < 300) {
                    if (o.success) o.success($._xhrResp(xhr), xhr.status);
                }
                else if (o.error) o.error(xhr, xhr.status, xhr.statusText);
                if (o.complete) o.complete(xhr, xhr.status, xhr.statusText);
            }
            else if (o.progress) o.progress(++n);
        };
        xhr.open('POST', o.url);
        xhr.setRequestHeader("Authorization", 'Basic ' +btoa(o.apiKey+ ':'));
        xhr.setRequestHeader("Content-Type", 'application/json');
        xhr.setRequestHeader("Accept", 'application/json');
        xhr.send(JSON.stringify(o.data));
    };
})(window.Paymongo);

(function(Paymongo) {
    Paymongo.setApiKey = function(apiKey) {
        this.config.apiKey = apiKey;
    };

    Paymongo.createPaymentMethod = function(params, callback) {
        this.send({
            data: params,
            url: 'https://api.paymongo.com/v1/payment_methods',
            apiKey: this.config.apiKey,
            success: callback,
            complete: callback,
        });
    };
})(window.Paymongo);
