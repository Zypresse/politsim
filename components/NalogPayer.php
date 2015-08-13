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
    
    private $unnp = null;
    
    abstract protected function getUnnpType();

    public function getUnnp()
    {
        if (is_null($this->unnp)) {
            $u = Unnp::findOneOrCreate(['p_id' => $this->id, 'type' => $this->getUnnpType()]);
            $this->unnp = ($u) ? $u->id : 0;
        } 
        return $this->unnp;
    }
    
    public function getStocks()
    {
        return $this->hasMany('app\models\Stock', array('unnp' => 'unnp'));
    }
    
    public function isGoverment()
    {
        return false;
    }
    
}
