<?php

namespace app\models;

use app\components\NalogPayer,
    app\components\MyModel,
    app\models\User,
    app\models\Unnp;

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
 * @property \app\models\User[] $members Члены партии
 * @property \app\models\ElectRequest[] $requests Заявки на выборы участников организаций
 * @property \app\models\ElectRequest[] $lrequests Заявки на выборы лидеров организаций
 * @property \app\models\User $leaderInfo Лидер
 * @property \app\models\State $state Государство
 * @property \app\models\Ideology $ideologyInfo Идеология
 * @property \app\models\Post[] $postsReserved 
 */
class Party extends MyModel implements NalogPayer
{

    protected function getUnnpType()
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
        return $this->hasMany('app\models\User', array('party_id' => 'id'))->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC');
    }

    public function getLeaderInfo()
    {
        return $this->hasOne('app\models\User', array('id' => 'leader'));
    }

    public function getRequests()
    {
        return $this->hasMany('app\models\ElectRequest', array('party_id' => 'id'))->where(['leader' => 0]);
    }

    public function getLrequests()
    {
        return $this->hasMany('app\models\ElectRequest', array('party_id' => 'id'))->where(['leader' => 1]);
    }

    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }

    public function getIdeologyInfo()
    {
        return $this->hasOne('app\models\Ideology', array('id' => 'ideology'));
    }
    
    public function getPostsReserved()
    {
        return $this->hasMany('app\models\Post', array('party_reserve' => 'id'));
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

}
