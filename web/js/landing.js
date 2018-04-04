
function init() {
    $('.page-loader').delay(250).fadeOut('fast');
    imagesLoaded('.glitch__img', {background: true}, () => {
        document.body.classList.add('imgloaded');
    });
}
