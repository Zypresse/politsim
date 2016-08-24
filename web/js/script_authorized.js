

$.ajaxSetup({
    cache: true
});

//$("#spinner").fadeIn('fast');

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
    window.onhashchange = loadPageFromHash;
}

var MAP_DATA = $.parseJSON(localStorage.getItem("MAP_DATA"));
var MAP_VERSION = parseInt(localStorage.getItem("MAP_VERSION"));

if (MAP_DATA && MAP_VERSION >= 5) {
    jQuery.fn.vectorMap('addMap', 'map5', MAP_DATA);
    init_app();
} else {
    $.getScript("/js/maps/map5.js", init_app);
}

function loadPageFromHash() {
    var ar = document.location.hash.split('&');
    page = ar.shift().substr(2);
    params = {};
    for (var i = 0, l = ar.length; i < l; i++) {
        var ar2 = ar[i].split('=');            
        params[ar2[0]] = ar2[1];
    }
    load_page(page, params);
}

