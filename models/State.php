<?php

namespace app\models;

use Yii,
    app\components\MyModel,
    app\components\TaxPayer,
    app\components\LinkHelper;

/**
 * Государство
 * 
 * @property integer $id 
 * @property string $name
 * @property string $nameShort
 * @property string $nameShort
 * @property string $flag
 * @property string $anthem
 * @property integer $cityId
 * @property string $mapColor
 * @property integer $govermentFormId
 * @property integer $stateStructureId
 * @property integer $population
 * @property integer $usersCount
 * @property integer $usersFame
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $utr
 * 
 * @property City $city
 * @property Constitution $constitution
 * @property GovermentForm $govermentForm
 * @property StateStructure $stateStructure
 *
 * @author ilya
 */
class State extends MyModel implements TaxPayer
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'nameShort'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['nameShort', 'mapColor'], 'string', 'max' => 6],
            [['flag', 'anthem'], 'string'],
            [['cityId', 'govermentFormId', 'stateStructureId', 'population', 'usersCount', 'usersFame', 'dateCreated', 'dateDeleted', 'utr'], 'integer', 'min' => 0],
            [['anthem'], 'validateAnthem'],
            [['flag'], 'validateFlag'],
        ];
    }
    
    /**
     * Возвращает константное значение типа налогоплательщика
     * @return integer
     */
    public function getUtrType()
    {
        return Utr::TYPE_STATE;
    }
    
    /**
     * Возвращает ИНН
     * @return int
     */
    public function getUtr()
    {
        if (is_null($this->utr)) {
            $u = Utr::findOneOrCreate(['objectId' => $this->id, 'objectType' => $this->getUtrType()]);
            if ($u) {
                $this->utr = $u->id;
                $this->save();
            }
        } 
        return $this->utr;
    }
    
    /**
     * Является ли плательщик правительством страны
     * @param integer $stateId
     * @return boolean
     */
    public function isGoverment($stateId)
    {
        return $this->id === $stateId;
    }
    
    /**
     * Возвращает баланс плательщика в у.е.
     * @param integer $currencyId
     * @return double
     */
    public function getBalance($currencyId)
    {
        $money = resources\Resource::findOrCreate([
            'protoId' => 1, // деньги
            'subProtoId' => $currencyId,
            'masterId' => $this->getUtr()
        ], false, [
            'count' => 0
        ]);
        return $money->count;
    }
    
    /**
     * Меняет баланс плательщика
     * @param integer $currencyId
     * @param double $delta
     */
    public function changeBalance($currencyId, $delta)
    {
        $money = resources\Resource::findOrCreate([
            'protoId' => 1, // деньги
            'subProtoId' => $currencyId,
            'masterId' => $this->getUtr()
        ], false, [
            'count' => 0
        ]);
        $money->count += $delta;
        return $money->save();
    }
        
    /**
     * ID страны, в которой он платит налоги
     * @return integer
     */
    public function getTaxStateId()
    {
        return $this->id;
    }
    
    /**
     * Является ли налогоплательщиком государства
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState($stateId)
    {
        return $this->id === $stateId;
    }
    
    /**
     * ID юзера, который управляет этим налогоплательщиком
     */
    public function getUserControllerId()
    {
        return false;
    }
    
    /**
     * Может ли юзер управлять этим налогоплательщиком
     * @param integer $userId
     * @return boolean
     */
    public function isUserController($userId)
    {
        return false;
    }    
    
    public function validateAnthem()
    {
        if (!LinkHelper::isSoundCloudLink($this->anthem)) {
            $this->addError('anthem', Yii::t('app', 'Anthem are not valid SoundCloud link'));
            return false;
        }
        return true;
    }
    
    public function validateFlag()
    {
        if (!LinkHelper::isImageLink($this->flag)) {
            $this->addError('flag', Yii::t('app', 'Flag are not valid image link'));
            return false;
        }
        return true;
    }
    
    private $_govermentForm = null;
    public function getGovermentForm()
    {
        if (is_null($this->_govermentForm)) {
            $this->_govermentForm = GovermentForm::findOne($this->govermentFormId);
        }
        return $this->_govermentForm;
    }
    
    private $_stateStructure = null;
    public function getStateStructure()
    {
        if (is_null($this->_stateStructure)) {
            $this->_stateStructure = StateStructure::findOne($this->stateStructureId);
        }
        return $this->_stateStructure;
    }
    
    public function getCity()
    {
        return $this->hasOne(City::classname(), ['id' => 'cityId']);
    }
    
    public function getConstitution()
    {
        return $this->hasOne(Constitution::classname(), ['stateId' => 'id']);
    }
}
