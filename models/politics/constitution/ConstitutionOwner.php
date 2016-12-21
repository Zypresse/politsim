<?php

namespace app\models\politics\constitution;

use app\models\economics\TaxPayerModel;

/**
 * 
 */
abstract class ConstitutionOwner extends TaxPayerModel
{

    /**
     * 
     * @return integer
     */
    abstract public static function getConstitutionOwnerType();

    /**
     *
     * @var Constitution
     */
    protected $_constitution = null;
    
    /**
     * 
     * @return Constitution
     */
    public function getConstitution()
    {
        if (is_null($this->_constitution)) {
            $this->_constitution = Constitution::create(static::getConstitutionOwnerType(), $this->id);
        }
        return $this->_constitution;
    }

}
