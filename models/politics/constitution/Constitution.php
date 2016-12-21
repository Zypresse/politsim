<?php

namespace app\models\politics\constitution;

use yii\base\Model;

/**
 * 
 */
class Constitution extends Model
{
    
    public $ownerType;
    public $ownerId;
    
    public static function create($ownerType, $ownerId)
    {
        return new static([
            'ownerType' => $ownerType, 
            'ownerId' => $ownerId
        ]);
    }
    
    /**
     *
     * @var ConstitutionArticle[]
     */
    protected $_articles = null;

    /**
     * 
     * @return ConstitutionArticle[]
     */
    public function getArticles()
    {
        if (is_null($this->_articles)) {
            $this->_articles = ConstitutionArticle::find()
                    ->where([
                        'ownerType' => $this->ownerType,
                        'ownerId' => $this->ownerId,
                    ])
                    ->all();
        }
        return $this->_articles;
    }
    
    /**
     * 
     * @param integer $type
     * @param integer $subType
     * @return ConstitutionArticle
     */
    public function getArticleByType($type, $subType = null)
    {
        return ConstitutionArticle::findOrCreate([
            'type' => $type,
            'subType' => $subType,
            'ownerType' => $this->ownerType,
            'ownerId' => $this->ownerId,
        ], false);
    }
    
    /**
     * 
     * @param integer $type
     * @param integer $subType set to NULL
     * @param string $value
     * @param string $value2
     * @param string $value3
     * @return boolean
     */
    public function setArticleByType($type, $subType, $value, $value2 = null,  $value3 = null)
    {
        $article = $this->getArticleByType($type, $subType);
        $article->value = $value;
        $article->value2 = $value2;
        $article->value3 = $value3;
        if ($article->save()) {
            return true;
        } else {
            $this->addErrors($article->getErrors());
            return false;
        }
    }
}
