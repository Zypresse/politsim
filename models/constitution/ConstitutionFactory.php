<?php

namespace app\models\constitution;

use app\models\articles\proto\ArticleProto,
    app\models\articles\Article;

/**
 * Description of ConstitutionFactory
 *
 * @author ilya
 */
abstract class ConstitutionFactory {

    /**
     * 
     * @param string $constitutionPrototype
     * @param int $stateId
     */
    public static final function generate($constitutionPrototype, $stateId) {
        $constitutionPrototype = '\\app\\models\\constitution\\proto\\'.$constitutionPrototype.'Proto';
        
        $articlePrototypes = ArticleProto::find()->all();
        foreach ($articlePrototypes as $articleProto) {
            $article = new Article([
                'state_id'  => $stateId,
                'proto_id'   => $articleProto->id,
                'value'     => (string)$constitutionPrototype::getArticleValue($articleProto)
            ]);
            $article->save();
        }
    }
    
}
