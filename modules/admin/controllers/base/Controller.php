<?php

namespace app\modules\admin\controllers\base;

use Yii;
use yii\web\Controller as YiiController;
use app\models\auth\Account;
use app\exceptions\NotAllowedHttpException;

/**
 * Description of Controller
 *
 * @author ilya
 */
abstract class Controller extends YiiController
{
    
    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role != Account::ROLE_ADMIN) {
            throw new NotAllowedHttpException('YOU HAVE BEEN TROLLED 8==D');
        }
        
        return parent::beforeAction($action);
    }
    
}
