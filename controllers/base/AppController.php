<?php

namespace app\controllers\base;

use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Description of AppController
 *
 * @author ilya
 */
class AppController extends Controller
{
    
    public $layout = 'app';
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
	return [
	    'access' => [
		'class' => AccessControl::className(),
		'rules' => [
		    [
			'allow' => true,
			'roles' => ['@'],
		    ],
		],
	    ],
	];
    }
    
}
