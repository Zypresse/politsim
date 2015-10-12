<?php

namespace app\components\widgets;

use yii\base\Widget;

/**
 * Виджет для вывода списка законопроектов
 *
 * @author Илья
 */
class BillListWidget extends Widget {
    
    public $bills = [];
    public $id = false;
    public $style = '';
    public $showVoteButtons = false;
    public $user;

    public function init(){
        parent::init();
    }
    
    public function run(){
        return $this->render("bill-list", ['user'=>$this->user, 'bills'=>$this->bills, 'id'=>$this->id ? $this->id : uniqid(),'style'=>$this->style,'showVoteButtons'=>$this->showVoteButtons]);
    }
}
