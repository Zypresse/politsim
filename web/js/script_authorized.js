
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

$(init_app);