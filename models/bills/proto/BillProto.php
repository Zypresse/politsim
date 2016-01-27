<?php

namespace app\models\bills\proto;

use app\components\MyModel,
    app\models\Notification,
    app\models\bills\proto\BillProtoField;

/**
 * Тип законопроекта. Таблица "bills_prototypes".
 *
 * @property integer $id
 * @property string $name
 * @property string $class_name
 * @property integer $only_auto
 * @property integer $only_dictator
 * 
 * @property BillProtoField[] $fields
 */
abstract class BillProto extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bills_prototypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class_name', 'only_auto', 'only_dictator'], 'required'],
            [['only_auto', 'only_dictator'], 'integer'],
            [['name'], 'string', 'max' => 1000],
            [['class_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'name'          => 'Name',
            'class_name'    => 'Class Name',
            'only_auto'     => 'Only Auto',
            'only_dictator' => 'Only Dictator',
        ];
    }

    public function getFields()
    {
        return $this->hasMany(BillProtoField::className(), array('proto_id' => 'id'));
    }
    
    public static function instantiate($row)
    {
        $className = "app\\models\\bills\\proto\\{$row['class_name']}";
        return new $className;
    }

    /**
     * Переименование государства
     */
    const TYPE_RENAME_STATE = 1;

    /**
     * Перенос столицы государства
     */
    const TYPE_CHANGE_CAPITAL = 2;

    /**
     * Переименование региона
     */
    const TYPE_RENAME_REGION = 3;

    /**
     * Переименование города
     */
    const TYPE_RENAME_CITY = 4;

    /**
     * Дать региону независимость
     */
    const TYPE_INDEPENDENCE_REGION = 5;

    /**
     * Смена флага
     */
    const TYPE_CHANGE_FLAG = 6;

    /**
     * Поправка в конституцию
     */
    const TYPE_CONSTITUTION_UPDATE = 7;

    /**
     * Смена цвета государства
     */
    const TYPE_CHANGE_COLOR = 8;

    /**
     * Сформировать законодательную власть
     */
    const TYPE_FORM_LEGISLATURE = 9;

    /**
     * провести перевыборы
     */
    const TYPE_MAKE_REELECTS = 10;

    /**
     * Сменить порядок выдачи лицензий
     */
    const TYPE_CHANGE_STATELICENSE = 11;

    /**
     * Переименовать организацию
     */
    const TYPE_RENAME_ORG = 12;

    /**
     * Создание сателлита
     */
    const TYPE_CREATE_SATELLITE = 13;
    
    /**
     * Отправить главу страны в отставку
     */
    const TYPE_DROP_STATELEADER = 14;

    /**
     * Принятие законопроекта
     * @param \app\models\bills\Bill $bill
     * @return boolean
     */
    public static function accept($bill)
    {
        
        $bill->accepted = time();

        if ($bill->creatorUser) {
            Notification::send($bill->creatorUser->id, "Предложенный вами законопроект, предлагающий «" . $bill->proto->name . "» одобрен и вступил в силу");
        }
        
        foreach ($bill->votes as $vote) {
            $vote->delete();
        }
        
        return $bill->save();
    }
    
    /**
     * @param \app\models\State $state
     */
    abstract public function isVisible($state);

}
