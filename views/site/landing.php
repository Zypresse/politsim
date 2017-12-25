<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */

$this->registerJs("init();");

?>
<div class="page-loader">
    <div class="loader">Loading...</div>
</div>
<div class="wrapper">
    <section id="hero" class="module-hero overlay-dark">
	<div class="hero-caption">
	    <div class="hero-text">

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

		<h1 class="m-b-40">Political Simulator</h1>
		<p class="lead m-b-60">
		    Строй бизнес. Управляй государством. Пиши в твиттер.
		    <br class="hidden-xs" />
		    Бета-релиз 15 апреля 2018.
		</p>
		<p class="lead m-b-60">
		    <?= Html::a('Вход', ["login"], ['class' => 'btn btn-success btn-sm']) ?> <?= Html::a('Регистрация', ["register"], ['class' => 'btn btn-primary btn-sm']) ?>
		</p>

		<div id="countdown" class="m-b-100" data-countdown="2018/04/15"></div>

	    </div>
	</div>
    </section>
    <footer class="footer p-t-0">
	<div class="container">

	    <!--	    <div class="row">

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

			<hr class="divider">-->

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
