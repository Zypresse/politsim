
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
    
    $('.icheck').iCheck({
	checkboxClass: 'icheckbox_square-blue',
	radioClass: 'iradio_square-blue',
	increaseArea: '20%' // optional
    });
    
    prettyDates();
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

function showError(e) {
    
    if ($("#last-error")[0]) {
        $("#last-error").slideUp(400, function(){
            $(this).remove();
            showError(e);
        });
        return;
    }
    
    var message = 'Unknown error';
    if (typeof e === "object") {
        if (e.result === "error") {
            message = e.error;
        } else if (e.responseJSON) {
            message = e.responseJSON.error;
        } else {
            message = e.statusText;
        }
    } else {
        message = e;
    }
    
    $('#error-block').append("<div id='last-error' class='alert alert-danger alert-dismissible' style='display:none; width:100%; margin-bottom: 5px; border-radius: 0;' >" +
        "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
        "<span aria-hidden='true'>&times;</span>" +
        "</button>" +
        "<i class='fa fa-warning'></i>" +
        "&nbsp;" +
        message +
    "</div>");
    $("#last-error").slideDown();
}

function ajaxModal(action, params, title, footer, modalId, bodyId, modalClass) {
    modalId = modalId ? modalId : 'automodal' + action.replace(/\//g, '-') + '-modal';
    bodyId = bodyId ? bodyId : modalId + '-body';
    modalClass = modalClass ? modalClass : '';
    footer = footer ? footer : '';
    if ($('#'+modalId)[0]) {
        $('#'+bodyId).html('<div class="text-center"><br><br><br>Загрузка...<br><br><br><br><br></div>');
        if (footer) {
            $('#'+modalId+'-footer').html(footer);
        } else {
            $('#'+modalId+'-footer').hide();
        }
        $('#'+modalId+'-label').text(title);
    } else {
        $(document.body).append(
            '<div style="display:none" class="modal fade" id="'+modalId+'" tabindex="-1" role="dialog" aria-labelledby="'+modalId+'-label" aria-hidden="true"><div class="modal-dialog '+modalClass+'"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="'+modalId+'-label">'+title+'</h3></div><div id="'+bodyId+'" class="modal-body"><div class="text-center"><br><br><br>Загрузка...<br><br><br><br><br></div></div><div id="'+modalId+'-footer" class="modal-footer" '+(footer ? '' : 'style="display:none"')+' >'+footer+'</div></div></div></div>'
        );        
    }
    $.ajax({
        method: 'GET',
        url: action,
        data: params,
        success: function(d) {
            $('#'+bodyId).html(d);
            $('#'+modalId).modal();
            prettyDates();
            $('#'+bodyId).find('[autofocus]').focus();
        },
        error: showError
    });
}


var datatable_language = {
    paginate: {
        first:    '«',
        previous: '‹',
        next:     '›',
        last:     '»'
    },
    aria: {
        paginate: {
            first:    'К первой',
            previous: 'Назад',
            next:     'Далее',
            last:     'К последней'
        },
        sortAscending: '- нажмите чтобы отсортировать по возрастанию',
        sortDescending: '- нажмите чтобы отсортировать по убыванию'
    },
    decimal: ',',
    thousands: '.',
    emptyTable: 'Нет данных',
    info: 'Отображаются элементы от _START_ до _END_ (_TOTAL_ всего)',
    infoEmpty: 'Нет данных для отображения',
    infoFiltered: '(отфильтровано из _MAX_ элементов)',
    lengthMenu: 'Показывать <select><option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="40">40</option><option value="50">50</option><option value="-1">Все</option></select> элементов на странице',
    processing: 'Обработка...',
    search: 'Поиск:',
    searchPlaceholder: 'Введите что-нибудь',
    zeroRecords: 'Ничего не найдено'
};