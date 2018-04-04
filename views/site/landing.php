<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */

$this->registerJsFile("https://tympanus.net/Tutorials/CSSGlitchEffect/js/imagesloaded.pkgd.min.js");
$this->registerJs("init();");

?>
<div class="page-loader">
    <div class="loader">Loading...</div>
</div>
<main class="landing">
    <div class="content">
        <div class="glitch">
            <div class="glitch__img"></div>
            <div class="glitch__img"></div>
            <div class="glitch__img"></div>
            <div class="glitch__img"></div>
            <div class="glitch__img"></div>
        </div>
        <div class="content__title">
            <svg xmlns="http://www.w3.org/2000/svg" width="300px" height="300px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="m-b-100">
                <g>
                    <circle cx="50" cy="50" r="45" fill="none" stroke="none" />
                    <circle cx="50" cy="50" r="35" fill="none" stroke="#eeeefa" stroke-width="4"/>
                    <ellipse cx="50" cy="50" fill="none" ry="35" stroke="#eeeefa" stroke-width="3" rx="29.5448">
                        <animate attributeName="rx" calcMode="linear" values="35;0;35" keyTimes="0;0.6;1" dur="1.5s" begin="0s" repeatCount="indefinite"/>
                    </ellipse>
                    <ellipse cx="50" cy="50" fill="none" ry="35" stroke="#eeeefa" stroke-width="3" rx="17.8898">
                        <animate attributeName="rx" calcMode="linear" values="35;0;35" keyTimes="0;0.6;1" dur="1.5s" begin="-0.5s" repeatCount="indefinite"/>
                    </ellipse>
                    <ellipse cx="50" cy="50" fill="none" ry="35" stroke="#eeeefa" stroke-width="3" rx="12.0448">
                        <animate attributeName="rx" calcMode="linear" values="35;0;35" keyTimes="0;0.6;1" dur="1.5s" begin="-1s" repeatCount="indefinite"/>
                    </ellipse>
                    <ellipse cx="50" cy="30" ry="0.1" fill="none" rx="28" stroke="#eeeefa" stroke-width="3"/>
                    <ellipse cx="50" cy="50" ry="0.1" fill="none" rx="34" stroke="#eeeefa" stroke-width="3"/>
                    <ellipse cx="50" cy="70" ry="0.1" fill="none" rx="28" stroke="#eeeefa" stroke-width="3"/>
                </g>
            </svg>
            <br>
            Political Simulator
            <p class="lead">
                <?= Html::a('Вход', "/login", ['class' => 'btn btn-success btn-sm']) ?> <?= Html::a('Регистрация', "/registration", ['class' => 'btn btn-primary btn-sm']) ?>
            </p>
        </div>
    </div>
</main>
