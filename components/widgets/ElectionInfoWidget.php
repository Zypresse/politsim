<?php

namespace app\components\widgets;

use Yii,
    yii\base\Widget,
    app\models\politics\elections\Election,
    app\models\politics\elections\ElectionStatus;

/**
 * 
 */
class ElectionInfoWidget extends Widget
{
    /**
     * 
     * @var Election 
     */
    public $election;
        
    public function run()
    {
        return $this->render('@app/views/widgets/election-info/'.$this->getViewFile(), [
            'election' => $this->election,
            'viewer' => Yii::$app->user->identity,
            'showRegisterButton' => $this->election->canSendRequest(Yii::$app->user->identity),
        ]);
    }
    
    private function getViewFile()
    {
        switch ($this->election->status) {
            case ElectionStatus::NOT_STARTED:
                return 'not-started';
            case ElectionStatus::REGISTRATION:
                return 'registration';
            case ElectionStatus::REGISTRATION_ENDED:
                return 'registration-ended';
            case ElectionStatus::VOTING:
                return 'voting';
            case ElectionStatus::CALCULATING:
                return 'calculating';
            case ElectionStatus::ENDED:
                return 'ended';
        }
    }
    
}
