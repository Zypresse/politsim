
function init_app() {
    $('.show_on_load').show();

    $.ajaxSetup({
        cache: false
    });
    update_header();

    if (document.location.hash) {
        loadPageFromHash();
    } else {
        load_page('profile');
    }
    $(window).on('hashchange',loadPageFromHash);
}

function loadPageFromHash() {
    
    var ar = document.location.hash.split('&');
    var page = ar.shift().substr(2),
        params = {};
    for (var i = 0, l = ar.length; i < l; i++) {
        var ar2 = ar[i].split('=');            
        params[ar2[0]] = ar2[1];
    }
    
    if (page !== current_page || JSON.stringify(params) !== JSON.stringify(current_page_params)) {
        load_page(page, params);
    }
}

$(init_app);