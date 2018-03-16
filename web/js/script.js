Theme = (function() {

    function Theme() {}

    Theme.colors = {
      darkGreen: "#779148",
      red: "#C75D5D",
      green: "#96c877",
      blue: "#6e97aa",
      orange: "#ff9f01",
      gray: "#6B787F",
      lightBlue: "#D4E5DE"
    };

    return Theme;

})();

var docCookies = {
    getItem: function (sKey) {
        return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
    },
    setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
        if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return false; }
        var sExpires = "";
        if (vEnd) {
            switch (vEnd.constructor) {
                case Number:
                    sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
                    break;
                case String:
                    sExpires = "; expires=" + vEnd;
                    break;
                case Date:
                    sExpires = "; expires=" + vEnd.toUTCString();
                    break;
            }
        }
        document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
        return true;
    },
    removeItem: function (sKey, sPath, sDomain) {
        if (!sKey || !this.hasItem(sKey)) { return false; }
        document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + ( sDomain ? "; domain=" + sDomain : "") + ( sPath ? "; path=" + sPath : "");
        return true;
    },
    hasItem: function (sKey) {
        return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
    },
    keys: /* optional method: you can safely remove it! */ function () {
        var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
        for (var nIdx = 0; nIdx < aKeys.length; nIdx++) { aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]); }
        return aKeys;
    }
};

$(function () {
    $.widget( "custom.autocompleteUsersSearch", $.ui.autocomplete, {
        _renderItem: function( ul, item ) {
            return $( "<li>" )
                .attr( "data-value", item.value )
                .append( item.label )
                .appendTo( ul );
        }
    });
    
    $('input').iCheck({
	checkboxClass: 'icheckbox_square-blue',
	radioClass: 'iradio_square-blue',
	increaseArea: '20%' // optional
    });
});

function roundd(x, n) {
    return parseFloat(x.toFixed(n));
}

function number_format(number, decimals, dec_point, thousands_sep) {  // Format a number with grouped thousands
    // 
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     bugfix by: Michael White (http://crestidg.com)

    var i, j, kw, kd, km, minus = '';

    if (number < 0) {
        minus = "-";
        number = number * -1;
    }

    // input sanitation & defaults
    if (isNaN(decimals = Math.abs(decimals))) {
        decimals = 2;
    }
    if (dec_point === undefined) {
        dec_point = ",";
    }
    if (thousands_sep === undefined) {
        thousands_sep = ".";
    }

    i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

    j = i.length;
    if (j > 3) {
        j = j % 3;
    } else {
        j = 0;
    }

    km = (j ? i.substr(0, j) + thousands_sep : "");
    kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
    //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
    kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


    return minus + km + kw + kd;
}

// Функция которая возвращает правильное русское форматирование слов, стоящие после чисел
// Например 0 комментариев, 1 комментарий, 2 комментария
// На вход подается число и 3 варианта написание соответствующие 0,1 и 2
// На выходе - строка в правильного вида.
function formate_numberword(n, s1, s2, s3) {
    pref = (n < 0) ? '-' : '';
    n = Math.abs(n);
    if (n === 0) {
        return "0 " + s1;
    } else if (n === 1 || (n % 10 === 1 && n % 100 !== 11 && n !== 11)) {
        return pref + number_format(n, 0, '', ' ') + " " + s2;
    } else if (n > 100 && n % 100 >= 12 && n % 100 <= 14) {
        return pref + number_format(n, 0, '', ' ') + " " + s1;
    } else if ((n % 10 >= 2 && n % 10 <= 4 && n > 20) || (n >= 2 && n <= 4)) {
        return pref + number_format(n, 0, '', ' ') + " " + s3;
    } else {
        return pref + number_format(n, 0, '', ' ') + " " + s1;
    }
}

function name_time(time) {
    switch (true) {
        case time < 60:
            return formate_numberword(time, 'секунд', 'секунду', 'секунды');
            break;
        case time < 60 * 60:
            return formate_numberword(Math.round(time / 60), 'минут', 'минуту', 'минуты');
            break;
        default:
            return formate_numberword(Math.round(time / 60 / 60), 'часов', 'час', 'часа');
            break;
    }
}

function escapeHtml(text) {
    return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
}

function prettyDates() {
    $('.prettyDate').each(function (idx, elem) {
        $(elem).text($.format.prettyDate(new Date($(elem).data('unixtime') * 1000)));
    });
    $('.formatDate').each(function (idx, elem) {
        $(elem).text($.format.date(new Date($(elem).data('unixtime') * 1000), 'HH:mm dd.MM.yyyy'));
    });
    $('.formatDateCustom').each(function (idx, elem) {
        $(elem).text($.format.date(new Date($(elem).data('unixtime') * 1000), $(elem).data('timeformat')));
    });
}
