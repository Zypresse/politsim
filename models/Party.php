<?php

namespace app\models;

use app\components\TaxPayer,
    app\components\MyModel,
    app\components\MyHtmlHelper,
    app\models\User,
    app\models\Unnp,
    app\models\ElectRequest,
    app\models\State,
    app\models\Post,
    app\models\Ideology;

/**
 * Партия. Таблица "parties".
 *
 * @property integer $id
 * @property string $name Название
 * @property string $short_name Короткое название (2-3 буквы)
 * @property string $image Ссылка на логотип
 * @property integer $state_id ID государства
 * @property integer $leader ID лидера
 * @property integer $ideology ID идеологии
 * @property integer $star Известность
 * @property integer $heart Доверие
 * @property integer $chart_pie Успешность
 * 
 * @property User[] $members Члены партии
 * @property ElectRequest[] $requests Заявки на выборы участников организаций
 * @property ElectRequest[] $lrequests Заявки на выборы лидеров организаций
 * @property User $leaderInfo Лидер
 * @property State $state Государство
 * @property Ideology $ideologyInfo Идеология
 * @property Post[] $postsReserved 
 */
class Party extends MyModel implements TaxPayer
{

    public function getUnnpType()
    {
        return Unnp::TYPE_PARTY;
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
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_name', 'state_id', 'leader', 'ideology'], 'required'],
            [['state_id', 'leader', 'ideology', 'star', 'heart', 'chart_pie'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['short_name'], 'string', 'max' => 4],
            [['image'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'name'       => 'Name',
            'short_name' => 'Short Name',
            'image'      => 'Image',
            'state_id'   => 'State ID',
            'leader'     => 'Leader',
            'ideology'   => 'Ideology',
            'star'       => 'Star',
            'heart'      => 'Heart',
            'chart_pie'  => 'Chart Pie',
        ];
    }

    public function getMembers()
    {
        return $this->hasMany(User::className(), array('party_id' => 'id'))->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC');
    }

    public function getLeaderInfo()
    {
        return $this->hasOne(User::className(), array('id' => 'leader'));
    }

    public function getRequests()
    {
        return $this->hasMany(ElectRequest::className(), array('party_id' => 'id'))->where(['leader' => 0]);
    }

    public function getLrequests()
    {
        return $this->hasMany(ElectRequest::className(), array('party_id' => 'id'))->where(['leader' => 1]);
    }

    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }

    public function getIdeologyInfo()
    {
        return $this->hasOne(Ideology::className(), array('id' => 'ideology'));
    }
    
    public function getPostsReserved()
    {
        return $this->hasMany(Post::className(), array('party_reserve' => 'id'));
    }

    /**
     * Возвращает число членов
     * @return integer
     */
    public function getMembersCount()
    {
        return intval(User::find()->where(['party_id' => $this->id])->count());
    }
    
    
    private $_isParlamentarian = null;
    public function isParlamentarian()
    {
        if (is_null($this->_isParlamentarian)) {
            $this->_isParlamentarian = false;
            foreach ($this->postsReserved as $post) {
                if ($post->org_id === $this->state->legislature) {
                    $this->_isParlamentarian = true;
                    break;
                }
            }
        }
        
        return $this->_isParlamentarian;
    }

    /**
     * Подчистка после удаления
     */
    public function afterDelete()
    {
        foreach ($this->requests as $request) {
            $request->delete();
        }
        foreach ($this->lrequests as $request) {
            $request->delete();
        }
    }

    public function changeBalance($delta)
    {
        
    }

    public function getBalance()
    {
        return 0;
    }

    public function getHtmlName()
    {
        return MyHtmlHelper::a($this->name, "load_page('party-info',{'id':{$this->id}})");
    }

    public function getTaxStateId()
    {
        return $this->state_id;
    }

    public function isTaxedInState($stateId)
    {
        return $this->state_id == $stateId;
    }

    public function getUserControllerId()
    {
        return $this->leader;
    }

    public function isUserController($userId)
    {
        return $this->leader === $userId;
    }

}
