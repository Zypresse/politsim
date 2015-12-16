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

if (typeof VK !== 'undefined') {
    VK.init({apiId: 4540646});
}
            
var current_page, current_page_params;


$(function () {
    $("#spinner").ajaxSend(function (event, xhr, options) {
        $(this).fadeIn("fast");
    }).ajaxStop(function () {
        $(this).fadeOut("fast");
    });
    if (typeof VK !== 'undefined') {
        setInterval(function () {
            var newheight = $('#bigcontainer').height() + 200;
            if (newheight < 600) newheight = 600;

            VK.callMethod("resizeWindow", 1000, newheight);
        }, 500);
    }
    if (self.parent.frames.length === 0) {
        $('#account_settings_button').show();
    }
})


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
    })
    $('.formatDate').each(function (idx, elem) {
        $(elem).text($.format.date(new Date($(elem).data('unixtime') * 1000), 'HH:mm dd-MM-yyyy'));
    })
    $('.formatDateCustom').each(function (idx, elem) {
        $(elem).text($.format.date(new Date($(elem).data('unixtime') * 1000), $(elem).data('timeformat')));
    })
}

function show_error(e) {
    console.log(e);
    var text;
    try {
        var r = $.parseJSON(e.responseText);

        if (r) {
            text = JSON.stringify(r.error);
        } else {
            text = e.responseText;
        }
    } catch (aaa) {
        text = e.responseText;
    }
    $('.modal-backdrop').hide();
    $('.modal').modal('hide');
    $('#error_text').html('Ошибка при соединении с сервером<br>' + text + '<br><strong>Статус</strong> — ' + e.status + ': ' + e.statusText);
    $('#error_block').slideDown();
    $("html, body").animate({scrollTop: 0}, "fast");
    $("#spinner").fadeOut("fast");
}
function show_custom_error(text) {
    //console.log(e);
    $('.modal-backdrop').hide();
    $('.modal').modal('hide');
    $('#error_text').html(text);
    $('#error_block').slideDown();
    $("html, body").animate({scrollTop: 0}, "fast");
    $("#spinner").fadeOut("fast");
}

function request(pageUrl,postParams,requestType,callback,noError)
{
    noError = noError || false;
    postParams = postParams || {};
    postParams.viewer_id = viewer_id;
    postParams.auth_key = auth_key;
    
    $("#spinner").fadeIn("fast");
    $('#error_block').slideUp();
    $.ajax({
        dataType: requestType,
        url: pageUrl,
        data: postParams,
        success: function (d) {
            if (typeof d === 'string') {
                try {
                    if (JSON.parse(d)) {
                        d = JSON.parse(d);
                    }
                } catch (eee) {

                }
            }
            if (typeof d === 'object' && d !== null) {
                
                if (!noError) {
                    if (!d.error) {
                        callback(d)
                    } else {
                        if (d.error === 'timeout') {
                            show_custom_error('Вы сможете совершить это действие через ' + name_time(d.time));
                        } else {
                            show_custom_error(d.error);
                        }
                    }
                } else {
                    callback(d);
                }
            } else {
                callback(d);
            }
            $("#spinner").fadeOut("fast");
        },
        error: (noError) ? function(e) {console.log(e);$("#spinner").fadeOut("fast");} : show_error
    });
}

function update_header() {
    get_json('header-info-updates',{},function(d){
        var d = d.result;

        $('#head_star').text(d.star);
        $('#head_heart').text(d.heart);
        $('#head_chart_pie').text(d.chart_pie);
        $('#head_money').text(number_format(d.money, 0, '', ' '));
        
        var new_dealings_count = d.new_dealings_count ? parseInt(d.new_dealings_count) : 0
        if (new_dealings_count) {
            $('#new_dealings_count').text(new_dealings_count);
            $('#new_dealings_count').show();
        } else {
            $('#new_dealings_count').hide();
        }
        var profile_badge = new_dealings_count;
        if (profile_badge) {
            $('#profile_badge').text(profile_badge);
            $('#profile_badge').show();
        } else {
            $('#profile_badge').hide();
        }
    })
}

function load_page(page, params, time) {

    params = params || {};
    time = time || 0;

    if (time) {
        setTimeout(function () {
            load_page(page, params, 0);
        }, time);
    } else {
        $('.modal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');

        current_page = page;
        current_page_params = params;

        $('#topmenu>li').removeClass('active');
        $('.' + current_page + '_page').addClass('active');

        url = '/#!' + page;
        for (var i in params) {
            if (i !== 'viewer_id' && i !== 'auth_key')
                url += '&' + i + '=' + encodeURIComponent(params[i]);
        }
        history.pushState({}, page, url);
        $('#page_content').empty();
        request('/html/'+page,params,'html',function(d){
            $('#page_content').html(d);
            prettyDates();
        })
    }
}

function reload_page(time) {
    time = time || 0;
    $("#spinner").fadeIn("fast");
    if (time) {
        setTimeout(function () {
            load_page(current_page, current_page_params);
            update_header();
        }, time);
    } else {
        load_page(current_page, current_page_params);
        update_header();
    }
}


function json_request(page, params, noReload, noError, callback) {
    params = params || {};
    noReload = noReload || false;
    noError = noError || false;
    callback = (typeof callback === 'function') ? callback : function (e) {
        console.log(e);
    };

    request('/json/'+page,params,'json',function(result){
        if (result.result === 'ok') {
            if (!noReload) {
                reload_page(100);
            }
            if (callback && typeof callback === "function") {
                callback(result);
            }
        }
    },noError);
}

function get_json(page, params, callback, noError) {
    params = params || {};
    noError = noError || false;
    callback = (typeof callback === 'function') ? callback : function (e) {
        console.log(e);
    };
    
    request('/json/'+page,params,'json',callback,noError);
}
function get_html(page, params, callback, noError) {
    params = params || {};
    noError = noError || false;
    callback = (typeof callback === 'function') ? callback : function (e) {
        console.log(e);
    };
    
    request('/modal/'+page,params,'html',callback,noError);
}

function load_modal(page,params,modalId,bodyId) {
    $('#'+bodyId).html('<br><br><br>Загрузка...<br><br><br><br><br>');
    get_html(page,params,function(d){
        $('#'+bodyId).html(d);
        $('#'+modalId).modal();        
        prettyDates();
    })
}


function show_region(region) {
    load_modal('region-info',{'id':region},'region_info','region_info_body');
}