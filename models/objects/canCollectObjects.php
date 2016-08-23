<?php

namespace app\models\objects;

/**
 *
 * @author ilya
 */
interface canCollectObjects {

    public function getContent();
    
    public function getId();
    
    public function getPlaceType();
    
    public function getIAmPlace();

}
