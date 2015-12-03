<?php

namespace app\models;

use Yii,
    yii\web\IdentityInterface,
    app\components\TaxPayer,
    app\components\MyModel,
    app\components\MyHtmlHelper,
    yii\helpers\Html,
    app\models\Twitter,
    app\models\Unnp,
    app\models\Dealing;

/**
 * Пользователь игры. Таблица "users".
 *
 * @property integer $id 
 * @property integer $uid 
 * @property string $name Имя
 * @property string $photo Маленькая фотография 50x50
 * @property string $photo_big Большая фотография 400xn
 * @property integer $last_vote Дата последнего "высказывания" о другом игроке
 * @property integer $last_tweet Дата последнего твита
 * @property integer $party_id ID партии
 * @property integer $state_id ID государства
 * @property integer $post_id ID поста
 * @property integer $region_id ID региона
 * @property double $money Деньги на личном счету
 * @property string $sex Пол: 0 - неопр., 1 - женский, 2 - мужской
 * @property integer $star Известность
 * @property integer $heart Доверие
 * @property integer $chart_pie Успешность
 * @property string $twitter_nickname
 * @property integer $invited Флаг есть ли инвайт у юзера
 * @property integer $ideology_id ID Идеологии
 * 
 * @property string $authKey Авторизационный ключ
 * 
 * @property \app\models\State $state Государство
 * @property \app\models\Party $party Партия
 * @property \app\models\Post $post Пост
 * @property \app\models\Region $region Регион
 * @property \app\models\Medales[] $medales Значки
 * @property \app\models\ElectVote[] $votes Голоса этого юзера на выборах
 * @property \app\models\Stock[] $stocks Акции
 * @property \app\models\ElectRequest[] $requests Заявки на выборы
 * @property \app\models\Notification[] $notifications Уведомления
 * @property \app\models\factories\Factory[] $factories 
 * @property \app\models\Auth[] $accounts
 * @property \app\models\Holding[] $holdings Компании, директором которых является
 * @property app\models\Ideology $ideology Идеология
 */
class User extends MyModel implements TaxPayer, IdentityInterface {

    public function getUnnpType()
    {
        return Unnp::TYPE_USER;
    }
    
    private $_unnp;
    public function getUnnp() {
        if (is_null($this->_unnp)) {
            $u = Unnp::findOneOrCreate(['p_id' => $this->id, 'type' => $this->getUnnpType()]);
            $this->_unnp = ($u) ? $u->id : 0;
        } 
        return $this->_unnp;
    }
    
    public function isGoverment($stateId)
    {
        return false;
    }

    public static function findIdentity($id)
    {
        return static::findByPk($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUid()
    {
        return $this->id;
    }

    public function validateAuthKey($authKey)
    {
        return $authKey == $this->authKey;
    }

    const SEX_UNDEFINED = 0;
    const SEX_FEMALE = 1;
    const SEX_MALE = 2;

    public static function stringGenderToSex($gender)
    {
        switch ($gender) {
            case 'male':
                return static::SEX_MALE;
            case 'female':
                return static::SEX_FEMALE;
            default:
                return static::SEX_UNDEFINED;
        }
    }

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
            [['sex', 'last_vote', 'last_tweet', 'party_id', 'state_id', 'post_id', 'region_id', 'star', 'heart', 'chart_pie'], 'integer'],
            [['party_id', 'state_id', 'post_id', 'region_id'], 'required'],
            [['money'], 'number'],
            [['name', 'photo', 'photo_big'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'photo' => 'Photo',
            'photo_big' => 'Photo Big',
            'last_vote' => 'Last Vote',
            'last_tweet' => 'Last Tweet',
            'party_id' => 'Party ID',
            'state_id' => 'State ID',
            'post_id' => 'Post ID',
            'region_id' => 'Region ID',
            'money' => 'Money',
            'sex' => 'Sex',
            'star' => 'Star',
            'heart' => 'Heart',
            'chart_pie' => 'Chart Pie',
        ];
    }

    public function setPublicAttributes()
    {
        return [
            'id',
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
    
    public function getIdeology()
    {
        return $this->hasOne('app\models\Ideology', array('id' => 'ideology_id'));
    }

    public function getMedales()
    {
        return $this->hasMany('app\models\Medale', array('uid' => 'id'));
    }

    public function getVotes()
    {
        return $this->hasMany('app\models\ElectVote', array('uid' => 'id'));
    }

    public function getRequests()
    {
        return $this->hasMany('app\models\ElectRequest', array('candidat' => 'id'));
    }

    public function getNotifications()
    {
        return $this->hasMany('app\models\Notification', array('uid' => 'id'));
    }

    public function getFactories()
    {
        return $this->hasMany('app\models\factories\Factory', array('manager_uid' => 'id'));
    }

    public function getAccounts()
    {
        return $this->hasMany('app\models\Auth', array('user_id' => 'id'));
    }

    public function getHoldings()
    {
        return $this->hasMany('app\models\Holding', array('director_id' => 'id'));
    }
    
    public function getStocks()
    {
        return $this->hasMany('app\models\Stock', array('unnp' => 'unnp'));
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
        return Dealing::find()->where(['to_unnp' => $this->unnp, 'time' => -1])->all();
    }

    /**
     * Число непринятых сделок
     * @return integer
     */
    public function getNotAcceptedDealingsCount()
    {
        return intval(Dealing::find()->where(['to_unnp' => $this->unnp, 'time' => -1])->count());
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
            if ($stock->unnp === $this->unnp || ($this->post && $stock->unnp === $this->post->unnp)) {
                return true;
            }
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
            if ($stock->unnp === $this->unnp || ($this->post && $stock->unnp === $this->post->unnp)) {
                return $stock;
            }
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
        $stock = $this->getShareholderStock($holding);
        if ($stock && $stock->getPercents() > 50.0) {
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
        $this->post_id = 0;

        return $this->leaveParty();
    }

    public function getAuthKey()
    {
        return static::getRealKey($this->id);
    }

    public static function getRealKey($viewer_id)
    {
        return md5($viewer_id . Yii::$app->params['AUTH_KEY_SECRET']);
    }

    public function changeBalance($delta)
    {
        $this->money += $delta;
        $this->save();
    }

    public function getBalance()
    {
        return $this->money;
    }

    public function getHtmlName()
    {
        return MyHtmlHelper::a(Html::img($this->photo,['style'=>'width:20px']), "load_page('profile',{'id':{$this->id}})")." ".MyHtmlHelper::a($this->name, "load_page('profile',{'id':{$this->id}})");
    }

    public function getTaxStateId()
    {
        return $this->state_id;
    }

    public function isTaxedInState($stateId)
    {
        return $this->state_id === $stateId;
    }

    public function getUserControllerId()
    {
        return $this->id;
    }

    public function isUserController($userId)
    {
        return $this->id === $userId;
    }

}
