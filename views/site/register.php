<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */

$this->registerJs("
    $('input').iCheck({
	checkboxClass: 'icheckbox_square-blue',
	radioClass: 'iradio_square-blue',
	increaseArea: '20%' // optional
    });
");

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
				<circle cx="50" cy="50" r="35" fill="none" stroke="#444" stroke-width="3"/>
				<ellipse cx="50" cy="50" fill="none" ry="35" stroke="#444" stroke-width="3" rx="25">
				</ellipse>
				<ellipse cx="50" cy="50" fill="none" ry="35" stroke="#444" stroke-width="3" rx="13">
				</ellipse>
				<ellipse cx="50" cy="50" fill="none" ry="35" stroke="#444" stroke-width="3" rx="0.1">
				</ellipse>
				<ellipse cx="50" cy="30" ry="0.1" fill="none" rx="28" stroke="#444" stroke-width="3"/>
				<ellipse cx="50" cy="50" ry="0.1" fill="none" rx="34" stroke="#444" stroke-width="3"/>
				<ellipse cx="50" cy="70" ry="0.1" fill="none" rx="28" stroke="#444" stroke-width="3"/>
			    </g>
			</svg>
			Political Simulator
		    </a>
		</div>

		<div class="register-box-body">
		    <p class="login-box-msg"><?= Yii::t('app', 'Register a new membership') ?></p>

		    <form action="../../index.html" method="post">
			<div class="form-group has-feedback">
			    <input type="text" class="form-control" placeholder="Full name">
				<span class="glyphicon glyphicon-user form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
			    <input type="email" class="form-control" placeholder="Email">
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
			    <input type="password" class="form-control" placeholder="Password">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
			    <input type="password" class="form-control" placeholder="Retype password">
				<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
			</div>
			<div class="row">
			    <div class="col-xs-8">
				<div class="checkbox icheck">
				    <label>
					<input type="checkbox"> I agree to the <a href="#">terms</a>
				    </label>
				</div>
			    </div>
			    <!-- /.col -->
			    <div class="col-xs-4">
				<button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
			    </div>
			    <!-- /.col -->
			</div>
		    </form>

		    <div class="social-auth-links text-center">
			<p>- OR -</p>
			<a href="#" class="btn btn-block btn-social btn-vk btn-flat"><i class="fa fa-vk"></i> Sign up using
			    VK</a>
			<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using
			    Facebook</a>
			<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign up using
			    Google+</a>
		    </div>

		    <a href="login.html" class="text-center">I already have a membership</a>
		</div>
		<!-- /.form-box -->
	    </div>
	    <!-- /.register-box -->
	</div>
    </section>
</div>