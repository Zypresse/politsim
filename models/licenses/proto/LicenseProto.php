<?php

namespace app\models\licenses\proto;

use app\components\MyModel,
    app\components\MyHtmlHelper,
    app\models\State,
    app\models\Holding;

/**
 * Типы лицензий (напр. банковское дело или добыча нефти). Таблица "licenses_prototypes".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 */
class LicenseProto extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'licenses_prototypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['name', 'code'], 'string', 'max' => 255],
            [['code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'   => 'ID',
            'name' => 'Name',
            'code' => 'Code',
        ];
    }
    
    /**
     * 
     * @param State $state
     * @param Holding $holding
     * @return boolean
     */
    public function isAllowed($state, $holding)
    {
        $allowed = true;
        foreach ($holding->licenses as $hl) {
            if ($licenseProto->id === $hl->proto_id && $hl->state_id === $state->id) {
                $allowed = false;
                $break;
            }
        }
        
        $stateLicense = $state->getLicenseRuleByPrototype($this);
        if ($stateLicense && $stateLicense->is_only_goverment) {
            if (!$holding->isGosHolding() || $holding->state_id !== $state->id) {
                $allowed = false;
            }
        }
        
        return $allowed;
    }
    
    /**
     * 
     * @param State $state
     * @param Holding $holding
     * @return string
     */
    public function getText($state, $holding)
    {
        $stateLicense = $state->getLicenseRuleByPrototype($this);
        $text = "Получение лицензии бесплатно";
        if (!(is_null($stateLicense))) {
            if ($holding->state_id === $state->id) {
                if ($stateLicense->cost) {
                    $text = number_format($stateLicense->cost, 0, '', ' ') . ' ' . MyHtmlHelper::icon('money');
                }
                if ($stateLicense->is_need_confirm) {
                    $text .= "<br>Необходимо подтверждение министра";
                }
            } else {
                if ($stateLicense->cost_noncitizens) {
                    $text = number_format($stateLicense->cost_noncitizens, 0, '', ' ') . ' ' . MyHtmlHelper::icon('money');
                }
                if ($stateLicense->is_need_confirm_noncitizens) {
                    $text .= "<br>Необходимо подтверждение министра";
                }
            }
        }
        
        return $text;
    }

}
