<?php

namespace app\components\widgets;

use yii\base\Widget;

/**
 * Description of DealingsListWidget
 *
 * @author ilya
 */
class DealingsListWidget extends Widget
{    
    public $dealings = [];
    public $id = false;
    
    public function init(){
        parent::init();
    }
    
    public function run(){
        return $this->render("dealings-list", [
            'dealings' => $this->dealings,
            'id' => $this->id ? $this->id : uniqid(),
        ]);
    }
}
