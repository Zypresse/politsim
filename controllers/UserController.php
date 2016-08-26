<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\User,
    app\models\Ideology,
    app\models\Religion;

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
    
    public function actionChooseIdeology()
    {
        return $this->render('choose-ideology', [
            'ideologies' => Ideology::findAll(),
            'user' => $this->user
        ]);
    }
    
    public function actionSaveIdeology($ideologyId)
    {
        if (intval($ideologyId) <= 0) {
            return $this->_r(Yii::t('app', 'No valid ideology'));
        }
        
        $this->user->ideologyId = $ideologyId;
        $this->user->save();
        
        return $this->_rOk();
    }
    
    public function actionChooseReligion()
    {
        return $this->render('choose-religion', [
            'religions' => Religion::findAll(),
            'user' => $this->user
        ]);
    }
    
    public function actionSaveReligion($religionId)
    {
        if (intval($religionId) <= 0) {
            return $this->_r(Yii::t('app', 'No valid religion'));
        }
        
        $this->user->religionId = $religionId;
        $this->user->save();
        
        return $this->_rOk();
    }
}
