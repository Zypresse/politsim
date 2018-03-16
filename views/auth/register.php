<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \app\models\auth\RegistrationForm */

?>
<div class="wrapper">
    <section id="hero" class="module-hero overlay-dark">
	<div class="hero-caption">
	    <div class="register-box">
		<div class="register-logo">
		    <a href="/">
			<svg xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" style="vertical-align: top;" >
			    <g>
				<circle cx="50" cy="50" r="45" fill="none" stroke="none" />
				<circle cx="50" cy="50" r="35" fill="none" stroke="#a5a5a5" stroke-width="5"/>
				<ellipse cx="50" cy="50" fill="none" ry="35" stroke="#a5a5a5" stroke-width="3" rx="25">
				</ellipse>
				<ellipse cx="50" cy="50" fill="none" ry="35" stroke="#a5a5a5" stroke-width="3" rx="13">
				</ellipse>
				<ellipse cx="50" cy="50" fill="none" ry="35" stroke="#a5a5a5" stroke-width="3" rx="0.1">
				</ellipse>
				<ellipse cx="50" cy="30" ry="0.1" fill="none" rx="28" stroke="#a5a5a5" stroke-width="3"/>
				<ellipse cx="50" cy="50" ry="0.1" fill="none" rx="34" stroke="#a5a5a5" stroke-width="3"/>
				<ellipse cx="50" cy="70" ry="0.1" fill="none" rx="28" stroke="#a5a5a5" stroke-width="3"/>
			    </g>
			</svg>
			Political Simulator
		    </a>
		</div>

		<div class="register-box-body">
		    <p class="login-box-msg">Регистрация нового аккаунта</p>

                    <?php $form = ActiveForm::begin([]) ?>
                        <?= $form->field($model, 'email', ['options' => ['class' => 'form-group has-feedback']])->input('email', ['placeholder' => 'Email'])->label(false)->hint('<span class="glyphicon glyphicon-envelope form-control-feedback"></span>') ?>
                        <?= $form->field($model, 'password', ['options' => ['class' => 'form-group has-feedback']])->passwordInput(['placeholder' => 'Пароль'])->label(false)->hint('<span class="glyphicon glyphicon-lock form-control-feedback"></span>') ?>
                        <?= $form->field($model, 'passwordConfirm', ['options' => ['class' => 'form-group has-feedback']])->passwordInput(['placeholder' => 'Ещё раз пароль'])->label(false)->hint('<span class="glyphicon glyphicon-log-in form-control-feedback"></span>') ?>
			
			<div class="row">
			    <div class="col-xs-8">
				<div class="checkbox icheck">
                                    <?= $form->field($model, 'agreeTerms')->checkbox(['value' => 1]) ?>
				</div>
			    </div>
			    <!-- /.col -->
			    <div class="col-xs-4">
                                <?= Html::submitButton("Поехали", ['class' => "btn btn-primary btn-block btn-flat"]) ?>
			    </div>
			    <!-- /.col -->
			</div>
                    <?php ActiveForm::end() ?>

		    <div class="social-auth-links text-center">
			<a href="#" class="btn btn-block btn-social btn-vk btn-flat"><i class="fa fa-vk"></i> Войти через
			    VK</a>
			<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Войти через
			    Facebook</a>
			<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Войти через
			    Google+</a>
		    </div>

		    <a href="/login" class="text-center">У меня уже есть аккаунт</a>
		</div>
		<!-- /.form-box -->
	    </div>
	    <!-- /.register-box -->
	</div>
    </section>
</div>
