<?php

namespace app\models\constitution\proto;

/**
 * Description of AbstractConstitutionProto
 *
 * @author ilya
 */
abstract class AbstractConstitutionProto {
    
    /**
     * 
     * @param \app\models\GovermentFieldType $articleProto
     * @return string
     */
    public static function getArticleValue($articleProto) {
        return $articleProto->default_value;
    }
    
}
