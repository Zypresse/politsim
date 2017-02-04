<?php

namespace app\models;

use app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\powers\Bills,
    app\models\politics\bills\Bill,
    app\models\economics\CompanyDecision;

/**
 * 
 */
class MessageType
{
    
    /**
     * Личное сообщение
     */
    const PRIVAT = 1;
    
    /**
     * Обсуждение законопроекта
     */
    const BILL_DISQUSSION = 2;
    
    /**
     * Обсуждение законопроекта
     */
    const DECISION_DISQUSSION = 3;
    
    /**
     * 
     * @param integer $typeId
     * @param integer $recipientId
     * @param User $who
     */
    public static function isCanSendTo(int $typeId, int $recipientId, $who) : bool
    {
        switch ($typeId) {
            case static::BILL_DISQUSSION:
                $bill = Bill::findByPk($recipientId);
                if (is_null($bill) || is_null($bill->state)) {
                    return false;
                }
                $posts = $who->getPostsByState($bill->stateId)->all();
                /* @var $post \app\models\politics\AgencyPost */
                foreach ($posts as $post) {
                    /* @var $article Bills */
                    $article = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS);
                    if ($article->isSelected(Bills::DISCUSS)) {
                        return true;
                    }
                }
                return false;
            case static::DECISION_DISQUSSION:
                $decision = CompanyDecision::findByPk($recipientId);
                if (is_null($decision) || is_null($decision->company)) {
                    return false;
                }
                return $decision->company->isShareholder($who->getUtr());
        }
        return true;
    }
    
}
