<?php
/* @var $this yii\web\View */
	
	header('Content-Type: application/json; charset='.Yii::$app->charset);
	$ar = ['result'=>$result];
	if (isset($error) && $error) {
		$ar['error'] = $error;
	}
	if (isset($addFields) && count($addFields)) {
		foreach ($addFields as $key => $value) {
			$ar[$key] = $value;
		}
	}
	echo json_encode($ar,JSON_UNESCAPED_UNICODE);
	Yii::$app->end();

?>