<?php

namespace app\models\bills\proto;

use app\models\articles\Article;

/**
 * Внести поправку в конституцию
 *
 * @author ilya
 */
class ConstitutionUpdate extends BillProto {
    
    public $id = 7;
    public $name = "Внести поправку в конституцию";
    
    public function accept($bill)
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
        if (!$article->save()) {
            var_dump($article->getErrors());
            return false;
        }
        $article->syncronize();
        
        return parent::accept($bill);
    }
    
    public function isVisible($state)
    {
        return true;
    }
    
}