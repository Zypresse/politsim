<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\User;

/**
 * Description of UserController
 *
 * @author ilya
 */
class UserController extends MyController
{
        
    public function actionProfile($id = false)
    {        
        if ($id === false) {
            $id = $this->user->id;
        }

        if ($id > 0) {
            $user = User::findByPk($id);
            if (is_null($user)) {
                return $this->_r(Yii::t('app', "User not found"));
            }

            return $this->render("profile", [
                'user' => $user,
                'isOwner' => ($this->user->id === $user->id)
            ]);
        } else {
            return $this->_r(Yii::t('app', "Invalid user ID"));
        }
    }
    
}
