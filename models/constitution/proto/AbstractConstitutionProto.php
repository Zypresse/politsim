<?php

namespace app\models\constitution\proto;

use app\models\articles\proto\ArticleProto;

/**
 * Description of AbstractConstitutionProto
 *
 * @author ilya
 */
abstract class AbstractConstitutionProto {
    
    /**
     * 
     * @param ArticleProto $articleProto
     * @return string
     */
    public static function getArticleValue(ArticleProto $articleProto) {
        return $articleProto->default_value;
    }
    
}
