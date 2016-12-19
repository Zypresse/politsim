<?php

namespace app\components\widgets;

use yii\base\Widget,
    app\models\politics\elections\Election;

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
        ]);
    }
    
    private function getViewFile()
    {
        switch ($this->election->status) {
            case Election::STATUS_REGISTRATION:
                return 'registration';
        }
    }
    
}
