<?php

use yii\web\YiiAsset;

/* @var $this \yii\web\View */

$this->registerCssFile('/css/landing.css');
$this->registerJsFile('/js/jquery.countdown.min.js', ['depends' => YiiAsset::className()]);
$this->registerJs("init();");

?>

<div class="page-loader" style="background: black url(/img/bg.jpg); background-position: center top;">
    <div class="loader">Loading...</div>
</div>

<div class="wrapper">
    <section id="hero" class="module-hero overlay-dark" style="background: black url(/img/bg.jpg); background-position: center top;">
	<div class="hero-caption">
	    <div class="hero-text">

		<!-- YOUR LOGO -->
		<svg xmlns="http://www.w3.org/2000/svg" width="300px" height="300px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="m-b-100">
		    <g>
			<circle cx="50" cy="50" r="45" fill="none" stroke="none" />
			<circle cx="50" cy="50" r="35" fill="none" stroke="#fff" stroke-width="3"/>
			<ellipse cx="50" cy="50" fill="none" ry="35" stroke="#fff" stroke-width="3" rx="29.5448">
			    <animate attributeName="rx" calcMode="linear" values="35;0;35" keyTimes="0;0.6;1" dur="1.5s" begin="0s" repeatCount="indefinite"/>
			</ellipse>
			<ellipse cx="50" cy="50" fill="none" ry="35" stroke="#fff" stroke-width="3" rx="17.8898">
			    <animate attributeName="rx" calcMode="linear" values="35;0;35" keyTimes="0;0.6;1" dur="1.5s" begin="-0.5s" repeatCount="indefinite"/>
			</ellipse>
			<ellipse cx="50" cy="50" fill="none" ry="35" stroke="#fff" stroke-width="3" rx="12.0448">
			    <animate attributeName="rx" calcMode="linear" values="35;0;35" keyTimes="0;0.6;1" dur="1.5s" begin="-1s" repeatCount="indefinite"/>
			</ellipse>
			<ellipse cx="50" cy="30" ry="0.1" fill="none" rx="28" stroke="#fff" stroke-width="3"/>
			<ellipse cx="50" cy="50" ry="0.1" fill="none" rx="34" stroke="#fff" stroke-width="3"/>
			<ellipse cx="50" cy="70" ry="0.1" fill="none" rx="28" stroke="#fff" stroke-width="3"/>
		    </g>
		</svg>

		<!-- HERO CONTENT -->
		<h1 class="m-b-40">Political Simulator</h1>
		<p class="lead m-b-60">Строй бизнес. Управляй государством. Пиши в твиттер. <br class="hidden-xs"> Бета-релиз 15 апреля 2018.</p>

		<!-- COUNTDOWN: YOUR DATE HERE -->
		<div id="countdown" class="m-b-100" data-countdown="2018/04/15"></div>

	    </div>
	</div>
    </section>
    <footer class="footer p-t-0">
	<div class="container">

	    <div class="row">

		<div class="col-sm-12">

		    <div class="social-icons social-icons-animated m-b-40">
			<a href="#" target="_blank" class="fa fa-facebook facebook wow fadeInUp"></a>
			<a href="#" target="_blank" class="fa fa-twitter twitter wow fadeInUp"></a>
			<a href="#" target="_blank" class="fa fa-google-plus google-plus wow fadeInUp"></a>
			<a href="#" target="_blank" class="fa fa-instagram instagram wow fadeInUp"></a>
			<a href="#" target="_blank" class="fa fa-behance behance wow fadeInUp"></a>
			<a href="#" target="_blank" class="fa fa-dribbble dribbble wow fadeInUp"></a>
			<a href="#" target="_blank" class="fa fa-flickr flickr wow fadeInUp"></a>
			<a href="#" target="_blank" class="fa fa-foursquare foursquare wow fadeInUp"></a>
		    </div>

		</div>

	    </div>

	    <hr class="divider">

		<div class="row">

		    <div class="col-sm-12">

			<div class="copyright text-center m-t-40">
			    © Разработка <a href="https://lazzyteam.pw"><b>LazzyTeam</b></a>.
			</div>

		    </div>

		</div>

	</div>
    </footer>
</div>
<script>
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

</script>
