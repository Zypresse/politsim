
function formateNumberword(n, s1, s2, s3) {
    pref = (n < 0) ? '-' : '';
    n = Math.abs(n);
    number = pref + n;

    if (!s2) {
        s2 = s1;
    }
    if (!s3) {
        s3 = s1;
    }

    if (n == 0) {
        return s1;
    } else if (n === 1 || (n % 10 === 1 && n % 100 != 11 && n != 11)) {
        return s2;
    } else if (n > 100 && n % 100 >= 12 && n % 100 <= 14) {
        return s1;
    } else if ((n % 10 >= 2 && n % 10 <= 4 && n > 20) || (n >= 2 && n <= 4)) {
        return s3;
    } else {
        return s1;
    }
}

function init() {

    $('.page-loader').delay(350).fadeOut('slow');

    var cdDate = $('#countdown').attr('data-countdown');
    $('#countdown').countdown(cdDate, function (event) {
//            console.log(event.offset);
        var days = formateNumberword(event.offset.totalDays, 'дней', 'день', 'дня');
        var hours = formateNumberword(event.offset.hours, 'часов', 'час', 'часа');
        var minutes = formateNumberword(event.offset.minutes, 'минут', 'минута', 'минуты');
        var seconds = formateNumberword(event.offset.seconds, 'секунд', 'секунда', 'секунды');
        $(this).html(event.strftime(''
                + '<div><div>%D</div><i>' + days + '</i></div>'
                + '<div><div>%H</div><i>' + hours + '</i></div>'
                + '<div><div>%M</div><i>' + minutes + '</i></div>'
                + '<div><div>%S</div><i>' + seconds + '</i></div>'
                ));
    });
}
