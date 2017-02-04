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
    app\models\politics\AgencyPost;

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
     * @param State $state
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
}
