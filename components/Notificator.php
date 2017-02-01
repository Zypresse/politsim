<?php

namespace app\components;

use Yii,
    yii\base\Component,
    yii\helpers\Html,
    app\components\LinkCreator,
    app\models\Notification,
    app\models\NotificationProto,
    app\models\politics\State,
    app\models\politics\Party,
    app\models\politics\PartyPost,
    app\models\politics\AgencyPost,
    app\models\economics\CompanyDecision,
    app\models\economics\License;

/**
 * 
 */
class Notificator extends Component
{
    
    /**
     * 
     * @param integer $userId
     * @param integer $type
     * @param array $params
     * @param boolean $readed
     * @return boolean
     */
    public function notify(int $userId, int $type, $params = [], $readed = false)
    {
        $proto = NotificationProto::findOne($type);
        
        $n = new Notification([
            'userId' => $userId,
            'protoId' => $type,
            'text' => Yii::t('app/notification', $proto->text, $params),
            'textShort' => Yii::t('app/notification', $proto->textShort, $params),
            'dateReaded' => $readed ? time() : null,
        ]);
        return $n->save();
    }
    
    /**
     * 
     * @return integer
     */
    public function markReaded()
    {
        return Notification::updateAll([
            'dateReaded' => time()
        ], [
            'userId' => Yii::$app->user->id,
            'dateReaded' => null
        ]);
    }
    
    
    /**
     * Игрок — пидор
     * @param integer $userId
     * @param boolean $readed
     * @return boolean
     */
    public function pidor(int $userId, $readed = false)
    {
        return $this->notify($userId, NotificationProto::PIDOR, [], $readed);
    }
    
    /**
     * Прекращено гражданство
     * @param integer $userId
     * @param State $state
     * @param boolean $readed
     * @return boolean
     */
    public function citizenshipLost(int $userId, State $state, $readed = false)
    {
        return $this->notify($userId, NotificationProto::CITIZENSHIP_LOST, [
            LinkCreator::stateLink($state),
        ], $readed);
    }
    
    /**
     * 
     * @param integer $userId
     * @param State $state
     * @param boolean $readed
     * @return boolean
     */
    public function citizenshipApprouved(int $userId, State $state, $readed = false)
    {
        return $this->notify($userId, NotificationProto::CITIZENSHIP_APPROUVED, [
            LinkCreator::stateLink($state),
        ], $readed);
    }
    
    /**
     * Партия создана
     * @param integer $userId
     * @param Party $party
     * @param boolean $readed
     * @return boolean
     */
    public function partyCreated(int $userId, Party $party, $readed = false)
    {
        return $this->notify($userId, NotificationProto::PARTY_CREATED, [
            LinkCreator::partyLink($party),
        ], $readed);
    }
    
    /**
     * Прекращено членство в партии
     * @param integer $userId
     * @param Party $party
     * @param boolean $readed
     * @return boolean
     */
    public function membershipLost(int $userId, Party $party, $readed = false)
    {
        return $this->notify($userId, NotificationProto::MEMBERSHIP_LOST, [
            LinkCreator::partyLink($party),
        ], $readed);
    }
    
    /**
     * Заявка в партию принята
     * @param integer $userId
     * @param Party $party
     * @param boolean $readed
     * @return boolean
     */
    public function membershipApprouved(int $userId, Party $party, $readed = false)
    {
        return $this->notify($userId, NotificationProto::MEMBERSHIP_APPROUVED, [
            LinkCreator::partyLink($party),
        ], $readed);
    }
    
    /**
     * Назначен на пост
     * @param integer $userId
     * @param PartyPost $partyPost
     * @param boolean $readed
     * @return boolean
     */
    public function settedToPartyPost(int $userId, PartyPost $partyPost, $readed = false)
    {
        return $this->notify($userId, NotificationProto::SETTED_TO_PARTY_POST, [
            Html::encode($partyPost->name),
            LinkCreator::partyLink($partyPost->party),
        ], $readed);
    }
    
    /**
     * Смещён с поста
     * @param integer $userId
     * @param PartyPost $partyPost
     * @param boolean $readed
     * @return boolean
     */
    public function droppedFromPartyPost(int $userId, PartyPost $partyPost, $readed = false)
    {
        return $this->notify($userId, NotificationProto::DROPPED_FROM_PARTY_POST, [
            Html::encode($partyPost->name),
            LinkCreator::partyLink($partyPost->party),
        ], $readed);
    }

    /**
     * назначен наследником на пост
     * @param integer $userId
     * @param PartyPost $partyPost
     * @param boolean $readed
     * @return boolean
     */
    public function settedAsSuccessorToPartyPost(int $userId, PartyPost $partyPost, $readed = false)
    {
        return $this->notify($userId, NotificationProto::SETTED_AS_SUCCESSOR_TO_PARTY_POST, [
            Html::encode($partyPost->name),
            LinkCreator::partyLink($partyPost->party),
        ], $readed);
    }

    /**
     * партийный пост удалён
     * @param integer $userId
     * @param PartyPost $partyPost
     * @param boolean $readed
     * @return boolean
     */
    public function deletedPartyPost(int $userId, PartyPost $partyPost, $readed = false)
    {
        return $this->notify($userId, NotificationProto::DELETED_PARTY_POST, [
            Html::encode($partyPost->name),
            LinkCreator::partyLink($partyPost->party),
        ], $readed);
    }


    /**
     * Заявка на выборы принята
     * @param integer $userId
     * @param AgencyPost $post
     * @param boolean $readed
     * @return boolean
     */
    public function registeredForPostElections(int $userId, AgencyPost $post, $readed = false)
    {
        return $this->notify($userId, NotificationProto::REGISTERED_FOR_POST_ELECTIONS, [
            Html::encode($post->name),
            LinkCreator::stateLink($post->state),
        ], $readed);
    }
    
    /**
     * Вы выиграли выборы на пост
     * @param integer $userId
     * @param AgencyPost $post
     * @param boolean $readed
     * @return boolean
     */
    public function winnedPostElections(int $userId, AgencyPost $post, $readed = false)
    {
        return $this->notify($userId, NotificationProto::WINNED_POST_ELECTION, [
            Html::encode($post->name),
            LinkCreator::stateLink($post->state),
        ], $readed);
    }
    
    /**
     * Новое решение на голосовании компании
     * @param integer $userId
     * @param CompanyDecision $decision
     * @param boolean $readed
     * @return boolean
     */
    public function newCompanyDecision(int $userId, CompanyDecision $decision, $readed = false)
    {
        return $this->notify($userId, NotificationProto::NEW_COMPANY_DECISION, [
            $decision->render(),
            LinkCreator::companyLink($decision->company),
            Html::encode($decision->company->name),
        ], $readed);
    }
    
    /**
     * Решение компании принято
     * @param integer $userId
     * @param CompanyDecision $decision
     * @param boolean $readed
     * @return boolean
     */
    public function companyDecisionAccepted(int $userId, CompanyDecision $decision, $readed = false)
    {
        return $this->notify($userId, NotificationProto::COMPANY_DECISION_ACCEPTED, [
            $decision->render(),
            LinkCreator::companyLink($decision->company),
            Html::encode($decision->company->name),
        ], $readed);
    }
    
    /**
     * Решение компании отклонено
     * @param integer $userId
     * @param CompanyDecision $decision
     * @param boolean $readed
     * @return boolean
     */
    public function companyDecisionDeclined(int $userId, CompanyDecision $decision, $readed = false)
    {
        return $this->notify($userId, NotificationProto::COMPANY_DECISION_DECLINED, [
            $decision->render(),
            LinkCreator::companyLink($decision->company),
            Html::encode($decision->company->name),
        ], $readed);
    }
    
    /**
     * получена новая лицензия
     * @param integer $userId
     * @param License $license
     * @param boolean $readed
     * @return boolean
     */
    public function licenseGranted(int $userId, License $license, $readed = false)
    {
        return $this->notify($userId, NotificationProto::LICENSE_GRANTED, [
            $license->proto->name,
            LinkCreator::companyLink($license->company),
            Html::encode($license->company->name),
            LinkCreator::stateLink($license->state),
        ], $readed);
    }
    
    /**
     * лицензия истекла
     * @param integer $userId
     * @param License $license
     * @param boolean $readed
     * @return boolean
     */
    public function licenseExpired(int $userId, License $license, $readed = false)
    {
        return $this->notify($userId, NotificationProto::LICENSE_EXPIRED, [
            $license->proto->name,
            LinkCreator::companyLink($license->company),
            Html::encode($license->company->name),
            LinkCreator::stateLink($license->state),
        ], $readed);
    }
    
    /**
     * Лицензия отозвана
     * @param integer $userId
     * @param License $license
     * @param boolean $readed
     * @return boolean
     */
    public function licenseRevoked(int $userId, License $license, $readed = false)
    {
        return $this->notify($userId, NotificationProto::LICENSE_REVOKED, [
            $license->proto->name,
            LinkCreator::companyLink($license->company),
            Html::encode($license->company->name),
            LinkCreator::stateLink($license->state),
        ], $readed);
    }
    
}
