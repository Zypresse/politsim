<?php

namespace app\models;

use app\components\MyModel;
use app\models\Twitter;
use app\models\Dealing;

/**
 * Пользователь игры. Таблица "users".
 *
 * @property integer $id 
 * @property integer $uid_vk ID в вк для авторизации @todo Переделать в таблицу accounts
 * @property string $name Имя
 * @property string $photo Маленькая фотография
 * @property string $photo_big Большая фотография
 * @property integer $last_vote Дата последнего "высказывания" о другом игроке
 * @property integer $last_tweet Дата последнего твита
 * @property integer $last_salary Дата последнего получения зарплаты
 * @property integer $party_id ID партии
 * @property integer $state_id ID государства
 * @property integer $post_id ID поста
 * @property integer $region_id ID региона
 * @property double $money Деньги на личном счету
 * @property integer $sex Пол: 0 - неопр., 1 - женский, 2 - мужской
 * @property integer $star Известность
 * @property integer $heart Доверие
 * @property integer $chart_pie Успешность
 * 
 * @property \app\models\State $state Государство
 * @property \app\models\Party $party Партия
 * @property \app\models\Post $post Пост
 * @property \app\models\Region $region Регион
 * @property \app\models\Medales[] $medales Значки
 * @property \app\models\ElectVote[] $votes Голоса этого юзера на выборах
 * @property \app\models\getStocks[] $stocks Акции
 * @property \app\models\ElectRequest[] $requests Заявки на выборы
 * @property \app\models\Notification[] $notifications Уведомления
 */
class User extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid_vk', 'last_vote', 'last_tweet', 'last_salary', 'party_id', 'state_id', 'post_id', 'region_id', 'sex', 'star', 'heart', 'chart_pie'], 'integer'],
            [['money'], 'number'],
            [['name', 'photo', 'photo_big'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'uid_vk'      => 'Uid Vk',
            'name'        => 'Name',
            'photo'       => 'Photo',
            'photo_big'   => 'Photo Big',
            'last_vote'   => 'Last Vote',
            'last_tweet'  => 'Last Tweet',
            'last_salary' => 'Last Salary',
            'party_id'    => 'Party ID',
            'state_id'    => 'State ID',
            'post_id'     => 'Post ID',
            'region_id'   => 'Region ID',
            'money'       => 'Money',
            'sex'         => 'Sex',
            'star'        => 'Star',
            'heart'       => 'Heart',
            'chart_pie'   => 'Chart Pie',
        ];
    }

    public function setPublicAttributes()
    {
        return [
            'id',
            'uid_vk',
            'name',
            'photo',
            'photo_big',
            'party_id',
            'state_id',
            'post_id',
            'region_id',
            'sex',
            'star',
            'heart',
            'chart_pie',
        ];
    }

    public function getParty()
    {
        return $this->hasOne('app\models\Party', array('id' => 'party_id'));
    }

    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }

    public function getPost()
    {
        return $this->hasOne('app\models\Post', array('id' => 'post_id'));
    }

    public function getRegion()
    {
        return $this->hasOne('app\models\Region', array('id' => 'region_id'));
    }

    public function getMedales()
    {
        return $this->hasMany('app\models\Medale', array('uid' => 'id'));
    }

    public function getVotes()
    {
        return $this->hasMany('app\models\ElectVote', array('uid' => 'id'));
    }

    public function getStocks()
    {
        return $this->hasMany('app\models\Stock', array('user_id' => 'id'));
    }

    public function getRequests()
    {
        return $this->hasMany('app\models\ElectRequest', array('candidat' => 'id'));
    }

    public function getNotifications()
    {
        return $this->hasMany('app\models\Notification', array('uid' => 'id'));
    }

    /**
     * Список сделок юзера
     * @return \app\models\Dealing[]
     */
    public function getMyDealingsList()
    {
        return Dealing::getMyList($this->id);
    }

    /**
     * Список непринятых сделок юзера
     * @return \app\models\Dealing[]
     */
    public function getNotAcceptedDealingsList()
    {
        return Dealing::find()->where(['to_uid' => $this->id, 'time' => -1])->all();
    }

    /**
     * Число непринятых сделок
     * @return integer
     */
    public function getNotAcceptedDealingsCount()
    {
        return intval(Dealing::find()->where(['to_uid' => $this->id, 'time' => -1])->count());
    }

    /**
     * Является ли лидером партии
     * @return boolean
     */
    public function isPartyLeader()
    {
        return ($this->party && intval($this->party->leader) === intval($this->id));
    }

    /**
     * Является ли лидером организации
     * @return boolean
     */
    public function isOrgLeader()
    {
        return ($this->post && $this->post->org && intval($this->post->org->leader_post) === intval($this->post_id));
    }

    /**
     * Является ли лидером государства
     * @return boolean
     */
    public function isStateLeader()
    {
        return ($this->isOrgLeader() && $this->post->org->isExecutive());
    }

    /**
     * Является ли держателем акций переданного АО
     * @return boolean
     */
    public function isShareholder(Holding $holding)
    {
        foreach ($holding->stocks as $stock) {
            if ($stock->user_id === $this->id || $stock->post_id === $this->post_id)
                return true;
        }
        return false;
    }

    /**
     * Получить стопку акций этого предприятия, принадлежащую юзеру
     * @param \app\models\Holding $holding
     * @return \app\models\Stock
     */
    public function getShareholderStock(Holding $holding)
    {
        foreach ($holding->stocks as $stock) {
            if ($stock->user_id === $this->id || $stock->post_id === $this->post_id)
                return $stock;
        }
        return null;
    }

    /**
     * Имеет ли контрольный пакет акций переданого АО
     * @param \app\models\Holding $holding
     * @return boolean
     */
    public function isHaveControllingStake(Holding $holding)
    {
        foreach ($holding->stocks as $stock) {
            if ($stock->user_id === $this->id && $stock->getPercents() > 50.0)
                return true;
        }
        return false;
    }

    /**
     * Число подписчиков
     * @return integer
     */
    public function getTwitterSubscribersCount()
    {
        $count = $this->star * 100 + $this->chart_pie * 10 + abs($this->heart);
        return $count > 0 ? $count : 0;
    }

    /**
     * Число твитов
     * @return integer
     */
    public function getTweetsCount()
    {
        return intval(Twitter::find()->where(['uid' => $this->id])->count());
    }

    /**
     * Покинуть партию и обсчитать нужные после этого дела
     * @return boolean
     */
    public function leaveParty()
    {
        if ($this->party) {
            if (intval($this->party->getMembersCount()) === 1) {
                $this->party->delete();
            }
        }
        $this->party_id = 0;
        if ($this->post && $this->post->party_reserve) {
            $this->post_id = 0;
        }

        return $this->save();
    }

    /**
     * Покинуть государство и обсчитать нужные после этого дела
     * @return boolean
     */
    public function leaveState()
    {
        foreach ($this->requests as $request) {
            $request->delete();
        }

        $this->state_id = 0;
        $this->post_id  = 0;

        return $this->leaveParty();
    }

}
