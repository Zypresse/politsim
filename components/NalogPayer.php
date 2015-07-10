<?php

namespace app\components;

use app\models\Unnp;

/**
 * Description of NalogPayer
 *
 * @author ilya
 * 
 * @property int $unnp ИНН
 */
abstract class NalogPayer extends MyModel {
    
    private $unnp;
    
    abstract protected function getField();

    public function getUnnp()
    {
        if (!$this->unnp) {
            $this->unnp = Unnp::find()->where([$this->getField().'_id' => $this->id])->one()->id;
            if (!$this->unnp) {
                $u = new Unnp([$this->getField().'_id' => $this->id]);
                $u->save();
                $this->unnp = $u->id;
            }
        } 
        return $this->unnp;
    }
    
}
