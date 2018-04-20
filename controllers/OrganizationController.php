<?php

namespace app\controllers;

use Yii;
use app\controllers\base\AppController;
use app\models\politics\Organization;
use app\models\politics\OrganizationMembership as Membership;
use app\models\politics\OrganizationPost as Post;
use app\models\variables\Ideology;

/**
 * Description of OrganizationController
 *
 * @author ilya
 */
class OrganizationController extends AppController
{
    
    public function actionIndex()
    {
        return $this->render('index', [
            'approved' => Membership::findByUserId($this->user->id)->andWhere(['is not', 'dateApproved', null])->with('org')->all(),
            'requested' => Membership::findByUserId($this->user->id)->andWhere(['dateApproved' => null])->with('org')->all(),
            'user' => $this->user,
        ]);
    }
    
    public function actionProfile(int $id)
    {
        return $this->render('profile', [
            'model' => $this->getModel($id),
            'user' => $this->user,
        ]);
    }
    
    public function actionCreate()
    {
        $model = new Organization([]);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = $model->getDb()->beginTransaction();
            if ($model->save() && $model->saveFlag()) {
                $membership = new Membership([
                    'orgId' => $model->id,
                    'userId' => $this->user->id,
                ]);
                if (!$membership->approve()) {
                    var_dump($membership->getErrors()); die();
                }
                $post = new Post(Post::DEFAULT_LEADER_DATA);
                $post->orgId = $model->id;
                $post->userId = $this->user->id;
                if (!$post->save()) {
                    var_dump($post->getErrors()); die();
                }
                $transaction->commit();
                return $this->redirect(['organization/profile', 'id' => $model->id]);
            } else {
                $transaction->rollBack();
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'ideologies' => Ideology::find()->all(),
        ]);
    }
    
    /**
     * 
     * @param integer $id
     * @return Organization
     * @throws NotFoundHttpException
     */
    private function getModel(int $id): Organization
    {
        $model = Organization::findOne($id);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
    
}
