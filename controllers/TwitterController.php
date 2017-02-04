<?php

namespace app\controllers;

use app\controllers\base\MyController,
    app\models\TwitterProfile;

/**
 * 
 */
final class TwitterController extends MyController
{
    
    public function actionIndex()
    {
        $profile = $this->user->profile;
        if (is_null($profile)) {
            return $this->render('create-profile-form');
        }
    }
    
}
