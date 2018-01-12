<?php

namespace app\models\politics\elections;

use app\models\politics\constitution\ConstitutionOwner;

/**
 * 
 * @property string $name
 * @property string $nameShort
 * 
 */
abstract class ElectionOwner extends ConstitutionOwner
{
    
    abstract public function getNextElection();
    
}
