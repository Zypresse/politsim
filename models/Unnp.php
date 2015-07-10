<?php

namespace app\models;

use app\components\MyModel;

/**
 * Универсальный ИНН для всех платежей. Таблица "unnp".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $holding_id
 * @property integer $state_id
 * @property integer $factory_id
 * @property integer $org_id
 * @property integer $party_id
 * @property integer $pop_id
 * @property integer $post_id
 * @property integer $region_id
 * 
 * @property app\components\NalogPayer $master Владелец
 */
class Unnp extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unnp';
    }
    
    public function getMaster()
    {
        if ($this->user_id) {
            return $this->hasOne('app\models\User', ['id' => 'user_id']);
        } elseif ($this->holding_id) {
            return $this->hasOne('app\models\Holding', ['id' => 'holding_id']);
        } elseif ($this->state_id) {
            return $this->hasOne('app\models\State', ['id' => 'state_id']);
        } elseif ($this->factory_id) {
            return $this->hasOne('app\models\Factory', ['id' => 'factory_id']);
        } elseif ($this->org_id) {
            return $this->hasOne('app\models\Org', ['id' => 'org_id']);
        } elseif ($this->party_id) {
            return $this->hasOne('app\models\Party', ['id' => 'party_id']);
        } elseif ($this->pop_id) {
            return $this->hasOne('app\models\Population', ['id' => 'pop_id']);
        } elseif ($this->post_id) {
            return $this->hasOne('app\models\Post', ['id' => 'post_id']);
        } elseif ($this->region_id) {
            return $this->hasOne('app\models\Region', ['id' => 'region_id']);
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'holding_id', 'state_id', 'factory_id', 'org_id', 'party_id', 'pop_id', 'post_id', 'region_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'holding_id' => 'Holding ID',
            'state_id' => 'State ID',
            'factory_id' => 'Factory ID',
            'org_id' => 'Org ID',
            'party_id' => 'Party ID',
            'pop_id' => 'Pop ID',
            'post_id' => 'Post ID',
            'region_id' => 'Region ID',
        ];
    }
}