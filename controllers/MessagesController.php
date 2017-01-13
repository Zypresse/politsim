<?php

namespace app\controllers;

use Yii,
    yii\filters\VerbFilter,
    app\components\MyController,
    app\models\Message,
    app\models\MessageType;

/**
 * 
 */
class MessagesController extends MyController
{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'send'  => ['post'],
                ],
            ],
        ];
    }
    
    public function actionSend()
    {
        $text = Yii::$app->request->post('text');
        $typeId = Yii::$app->request->post('typeId');
        $recipientId = Yii::$app->request->post('recipientId');
        
        if (!$text || !$typeId || !$recipientId) {
            return $this->_r(Yii::t('app', 'Invalid message parametres'));
        }
        
        if (!MessageType::isCanSendTo($typeId, $recipientId, $this->user)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $message = new Message([
            'typeId' => $typeId,
            'recipientId' => $recipientId,
            'senderId' => $this->user->id,
            'text' => $text,
        ]);
        if ($message->save()) {
            $this->result = $message->attributes;
            $this->result['sender'] = $this->user->getPublicAttributes();
            return $this->_r();
        } else {
            return $this->_r($message->getErrors());
        }
    }
    
    public function actionGet(int $typeId, int $recipientId, int $lastUpdateTime)
    {
        if (!$typeId || !$recipientId) {
            return $this->_r(Yii::t('app', 'Invalid parametres'));
        }
        
        $messages = Message::find()
                ->where(['typeId' => $typeId, 'recipientId' => $recipientId])
                ->andWhere(['>', 'dateCreated', $lastUpdateTime])
                ->orderBy(['dateCreated' => SORT_ASC])
                ->asArray()
                ->all();
        
        $this->result = $messages;
        return $this->_r();
    }
    
}
