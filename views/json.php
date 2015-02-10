<?php
/* @var $this yii\web\View */
	
	header('Content-Type: application/json; charset='.Yii::$app->charset);
	echo json_encode((@$error) ? ['result'=>$result,'error'=>$error] : ['result'=>$result],JSON_UNESCAPED_UNICODE);
	Yii::$app->end();

?>