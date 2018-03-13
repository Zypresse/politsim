<?php

namespace app\models\economy;

use yii\base\Behavior;
use app\models\base\ActiveRecord;
use yii\base\Event;
use app\models\economy\Utr;
use app\models\economy\TaxPayerInterface;

/**
 * Description of TaxPayerBehavior
 *
 * @property TaxPayerInterface $owner
 */
class TaxPayerBehavior extends Behavior
{

    /**
     * @inheritdoc
     */
    public function events()
    {
	return [
	    ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
	];

    }

    /**
     * Create UTR before insert
     * @param Event $event
     */
    public function beforeInsert(Event $event)
    {
	$model = new Utr([
	    'objectType' => $this->owner->utrType,
	    'objectId' => $this->owner->id,
	]);
	$model->save();
	$this->owner->utr = $model->id;
	return true;

    }

}
