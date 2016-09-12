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
    $(document).on('click','a',function(){
        if ($(this).attr('href') == "#") {
            return false;
        }
    });
//    $("#spinner").ajaxSend(function (event, xhr, options) {
//        $(this).fadeIn("fast");
//    }).ajaxStop(function () {
//        $(this).fadeOut("fast");
//    });
    $(document).ajaxStart(function() { Pace.restart(); }); 
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
//    $("#spinner").fadeOut("fast");
}
function show_custom_error(text) {
    //console.log(e);
    $('.modal-backdrop').hide();
    $('.modal').modal('hide');
    $('#error_text').html(text);
    $('#error_block').slideDown();
    $("html, body").animate({scrollTop: 0}, "fast");
//    $("#spinner").fadeOut("fast");
}

function request(pageUrl,postParams,requestType,callback,noError,method)
{
    method = method || 'GET';
    noError = noError || false;
    postParams = postParams || {};
//    postParams.viewer_id = viewer_id;
//    postParams.auth_key = auth_key;
    
//    $("#spinner").fadeIn("fast");
    $('#error_block').slideUp();
    $.ajax({
        method: method,
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
//            $("#spinner").fadeOut("fast");
        },
        error: (noError) ? function(e) {console.log(e);$("#spinner").fadeOut("fast");} : show_error
    });
}

function update_header() {
    get_json('notifications/get-updates',{},function(d){
        var d = d.result;

        $('.autoupdated-fame').text(d.fame);
        $('.autoupdated-trust').text(d.trust);
        $('.autoupdated-success').text(d.success);
//        $('.autoupdated-money').text(number_format(d.money, 0, '', ' '));

        $('.autoupdated-notifications').text(d.notificationsCount);
        $('#new_notifications_list').empty();
        if (d.notificationsCount > 0) {
            $('#new_notifications_count').removeClass('hide');
            for (var i in d.notifications) {
                var n = d.notifications[i];
                $('#new_notifications_list').append('<li><a href="#!notifications&id='+n.id+'">'+n.icon+' '+n.textShort+'</a></li>');
            }
        } else {
            $('#new_notifications_count').addClass('hide');
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
        $('.' + current_page.replace('/','-') + '_page').addClass('active');

        hash = '!' + page;
        for (var i in params) {
            if (i !== 'viewer_id' && i !== 'auth_key')
                hash += '&' + i + '=' + encodeURIComponent(params[i]);
        }
        document.location.hash = hash;
        $('#page_content').empty();
        request('/'+page,params,'html',function(d){
            $('#page_content').html(d);
            prettyDates();
        })
    }
}

function reload_page(time) {
    time = time || 0;
//    $("#spinner").fadeIn("fast");
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


function json_request(page, params, noReload, noError, callback, method) {
    params = params || {};
    noReload = noReload || false;
    noError = noError || false;
    callback = (typeof callback === 'function') ? callback : function (e) {
        console.log(e);
    };

    request('/'+page,params,'json',function(result){
        if (result.result !== 'error') {
            if (!noReload) {
                reload_page(100);
            }
            if (callback && typeof callback === "function") {
                callback(result);
            }
        }
    },noError,method);
}

function json_post_request(page, params, noReload, noError, callback) {
    return json_request(page, params, noReload, noError, callback, 'POST');
}

function get_json(page, params, callback, noError) {
    params = params || {};
    noError = noError || false;
    callback = (typeof callback === 'function') ? callback : function (e) {
        console.log(e);
    };
    
    request('/'+page,params,'json',callback,noError);
}
function get_html(page, params, callback, noError) {
    params = params || {};
    noError = noError || false;
    callback = (typeof callback === 'function') ? callback : function (e) {
        console.log(e);
    };
    
    request('/'+page,params,'html',callback,noError);
}

function load_modal(page, params, modalId, bodyId) {
    bodyId = bodyId ? bodyId : modalId + '-body';
    $('#'+bodyId).html('<br><br><br>Загрузка...<br><br><br><br><br>');
    get_html(page,params,function(d){
        $('#'+bodyId).html(d);
        $('#'+modalId).modal();        
        prettyDates();
        subscribeLinksInModal(modalId, bodyId);
    })
}

function subscribeLinksInModal(modalId, bodyId) {
    
    $('#'+bodyId).off('submit');
    $('#'+bodyId).off('click');
    
    $('#'+bodyId).on('submit','form', function(){
        var action = $(this).attr('action'),
            actionType = $(this).data('actionType');
        return makeActionInModal(actionType, action, $(this).serializeObject(), modalId, bodyId);
    });
    
    $('#'+bodyId).on('click','a', function(){
        var action = $(this).attr('href'),
            actionType = $(this).data('actionType');
        if (action !== '#')
            return makeActionInModal(actionType, action, {}, modalId, bodyId);
    });
}

function makeActionInModal(actionType, action, data, modalId, bodyId) {
    actionType = actionType ? actionType : 'html';
    switch (actionType) {
        case 'modal':
            load_modal(action,data,modalId,bodyId);
            break;
        case 'html':
            load_page(action,data);
            break;
        case 'json':
            json_request(action,data);
            break;
    }

    return false;
}


function show_region(region) {
    load_modal('region-info',{'id':region},'region_info','region_info_body');
}

function createAjaxModal(action, params, title, buttons, modalId, bodyId) {
    modalId = modalId ? modalId : action.replace('/', '-') + '-modal';
    bodyId = bodyId ? bodyId : modalId + '-body';
    buttons = buttons ? buttons : '';
    if ($('#'+modalId)[0]) {
        $('#'+bodyId).html('<br><br><br>Загрузка...<br><br><br><br><br>');
    } else {
        $(document.body).append(
            '<div style="display:none" class="modal fade" id="'+modalId+'" tabindex="-1" role="dialog" aria-labelledby="'+modalId+'-label" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="'+modalId+'-label">'+title+'</h3></div><div id="'+bodyId+'" class="modal-body"><br><br><br>Загрузка...<br><br><br><br><br></div><div class="modal-footer">'+buttons+'</div></div></div></div>'
        );        
    }
    get_html(action,params,function(d){
        $('#'+bodyId).html(d);
        $('#'+modalId).modal();        
        prettyDates();
        subscribeLinksInModal(modalId, bodyId);
    });
}
