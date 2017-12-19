<?php

namespace app\components\widgets;

use Yii,
    yii\base\Widget,
    yii\helpers\Html;

/**
 * 
 */
class BusinessViewAsWidget extends Widget {
    
    /**
     *
     * @var \app\models\User
     */
    public $user;
    public $selectedUtr;
    public $utrs;

    public function init(){
        parent::init();
        
        $this->user = Yii::$app->user->identity;
        $this->selectedUtr = Yii::$app->request->cookies->get('viewAsUtr');
        $this->initUtrs();
    }
    
    private function initUtrs()
    {
        $this->utrs = [
            $this->user->getUtr() => Html::encode($this->user->name).' '.Yii::t('app', '(individual)'),
        ];
        if (count($this->user->getPosts()->count())) {
            foreach ($this->user->getPosts()->with('state')->all() as $post) {
                $this->utrs[$post->getUtr()] = Html::encode($post->name).' ('.Html::encode($post->state->name).')';
            }
        }
        if (is_null($this->selectedUtr)) {
            $this->selectedUtr = $this->user->getUtr();
        }
    }


    public function run(){
        return $this->render("@app/views/widgets/view-as", [
            'user' => $this->user,
            'selectedUtr' => $this->selectedUtr,
            'utrs' => $this->utrs,
        ]);
    }
}
