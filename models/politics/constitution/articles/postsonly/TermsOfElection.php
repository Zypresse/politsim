<?php

namespace app\models\politics\constitution\articles\postsonly;

use Yii,
    app\models\politics\constitution\articles\base\TripleUnsignedIntegerArticle;

/**
 * Сроки выборов
 */
class TermsOfElection extends TripleUnsignedIntegerArticle
{
    
    use \app\models\politics\constitution\articles\base\NoSubtypesArticle;
    
    public function getName()
    {
        return Yii::t('app', 'Registration: {0}, time between registration end and voting start: {1}, voting: {2}', [
            $this->value,
            $this->value2,
            $this->value3,
        ]);
    }
    
}
