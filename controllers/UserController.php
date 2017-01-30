<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\components\MyController,
    app\components\LinkCreator,
    app\models\User,
    app\models\Ideology,
    app\models\Religion,
    app\models\politics\Region,
    app\models\politics\City;

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
    
    public function actionFind($name, $stateId = null)
    {
        $query = User::find()
                ->filterWhere(['like', 'name', $name]);
        
        if ($stateId) {
            $query = $query->joinWith('citizenships')
                    ->andWhere(['citizenships.stateId' => $stateId])
                    ->andWhere(['IS NOT', 'citizenships.stateId', NULL]);
        }
        
        $models = $query->all();
        $this->result = [];
        /* @var User $model */
        foreach ($models as $model) {
            $this->result[] = [
                'id' => $model->id,
                'name' => LinkCreator::userLink($model),
                'fame' => $model->fame,
                'trust' => $model->trust,
                'success' => $model->success,
            ];
        }
        
        return $this->_r();
    }
    
    public function actionInfo(int $id)
    {
        $user = User::findByPk($id);
        if (is_null($user)) {
            return $this->_r(Yii::t('app', "User not found"));
        }
        
        $this->result = $user->getPublicAttributes();
        return $this->_r();
    }
    
    public function actionRelocateForm($type, $id)
    {
        switch ($type) {
            case 'region':
                $object = Region::findByPk($id);
                break;
            case 'city':
                $object = City::findByPk($id);
                break;
            default:
                return $this->_r(Yii::t('app', 'Invalid params'));
        }
        
        if (is_null($object)) {
            throw new NotFoundHttpException(Yii::t('app', '{0} not found', [$type]));
        }
        
        return $this->render('relocate',[
            'user' => $this->user,
            'object' => $object,
        ]);
    }
    
    public function actionRelocate()
    {
        $tileId = (int) Yii::$app->request->post('tileId');
        $this->user->tileId = $tileId;
        if ($this->user->save()) {
            return $this->_rOk();
        } else {
            return $this->_r($this->user->getErrors());
        }
    }
}
