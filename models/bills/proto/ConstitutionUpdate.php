<?php

namespace app\models\bills\proto;

use app\models\articles\Article,
    app\models\articles\proto\ArticleProto;

/**
 * Внести поправку в конституцию
 *
 * @author ilya
 */
class ConstitutionUpdate extends BillProto {
    
    public static $id = 7;
    public static $name = "Внести поправку в конституцию";
    
    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }
        
        $data = json_decode($bill->data);
        
        $article = Article::findOrCreate([
            'state_id' => $bill->state_id,
            'proto_id' => $data->article_proto_id
        ], false, [
            'value' => $data->article_value
        ]);
        
        $article->value = $data->article_value;
        $article->save();
        $article->syncronize();
        
        return parent::accept($bill);
    }
    
    public static function isVisible($state)
    {
        return true;
    }
    
}