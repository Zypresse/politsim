<?php

namespace app\models\politics\constitution\articles\statesonly;

use Yii,
    app\models\politics\constitution\articles\base\PairUnsignedIntegerArticle;

/**
 * 
 */
class Bills extends PairUnsignedIntegerArticle
{
    use \app\models\politics\constitution\articles\base\NoSubtypesArticle;
    
    public function getName()
    {
        return Yii::t('app', 'Voting time: {0} hours, count of active bills by post: {1}', [
            $this->value,
            $this->value2,
        ]);
    }
    
}
