<?php

use yii\helpers\Html;
use app\assets\LandingAsset;

/* @var $this yii\web\View */
/* @var $code integer */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name . ' | Political Simulator';

LandingAsset::register($this);

?>


<div class="error-page">
    <section id="hero" class="module-hero overlay-dark">
	<div class="hero-caption">
	    <div class="hero-text">
		<h1 class="headline text-red"><?= $code ?> <?= Html::encode($name) ?></h1>

		<div class="error-content">
		    <h2><i class="fa fa-warning text-red"></i> <?= nl2br(Html::encode($message)) ?></h2>

		    <?php if ($code !== 404): ?>
    		    <p>
			    <?= Yii::t('app', 'The above error occurred while the Web server was processing your request.') ?>
    		    </p>
    		    <p>
			    <?= Yii::t('app', 'Please <a href="/contacts">contact</a> us if you think this is a server error. Thank you.') ?>
    		    </p>
		    <?php endif ?>
		</div>
	    </div>
	</div>
    </section>
</div>
