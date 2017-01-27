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
    
    /**
     *
     * @var ConstitutionArticle[]
     */
    protected $_articles = null;
    
    public static function create($ownerType, $ownerId)
    {
        $model = new static([
            'ownerType' => $ownerType, 
            'ownerId' => $ownerId
        ]);
        $model->loadArticles();
        return $model;
    }

    public function loadArticles()
    {
        $this->_articles = ConstitutionArticle::find()
                ->where([
                    'ownerType' => $this->ownerType,
                    'ownerId' => $this->ownerId,
                ])
                ->all();
    }


    /**
     * 
     * @return ConstitutionArticle[]
     */
    public function getArticles()
    {
        if (is_null($this->_articles)) {
            $this->loadArticles();
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
        
        foreach ($this->_articles as $article) {
            if ($article->type == $type && $article->subType == $subType) {
                return $article;
            }
        }
        
        return ConstitutionArticle::find()->where([
            'type' => $type,
            'subType' => $subType,
            'ownerType' => $this->ownerType,
            'ownerId' => $this->ownerId,
        ])->one();
    }
    
    /**
     * 
     * @param integer $type
     * @param integer $subType
     * @return ConstitutionArticle
     */
    public function getArticleByTypeOrEmptyModel($type, $subType = null)
    {
        $article = $this->getArticleByType($type, $subType);
        if (is_null($article)) {
            $article = ConstitutionArticle::instantiate([
                'type' => $type,
                'subType' => $subType,
                'ownerType' => $this->ownerType,
                'ownerId' => $this->ownerId
            ]);
        }
        
        return $article;
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
        $className = ConstitutionArticleType::getClassNameByType($type);
        $article = $className::find()->where([
            'type' => $type,
            'subType' => $subType,
            'ownerType' => $this->ownerType,
            'ownerId' => $this->ownerId,
        ])->one();
        if (is_null($article)) {
            $article = ConstitutionArticle::instantiate([
                'type' => $type,
                'subType' => $subType,
                'ownerType' => $this->ownerType,
                'ownerId' => $this->ownerId
            ]);
        }
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
    
    public function isCanCreateNewBill() : bool
    {
        $article = $this->getArticleByType(ConstitutionArticleType::POWERS, articles\postsonly\Powers::BILLS);
        return $article->isSelected(articles\postsonly\powers\Bills::CREATE);
    }
    
}
