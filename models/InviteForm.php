<?php

namespace app\models;

use Yii,
    yii\base\Model,
    yii\web\UploadedFile;

/**
 * Загрузчик инвайтов
 *
 * @author ilya
 */
class InviteForm  extends Model {
    
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'imageFile' => Yii::t('app', 'Invite picture'),
        ];
    }

    /**
     * 
     * @return app\models\Invite
     */
    public function getInvite()
    {
        $hash = md5_file($this->imageFile->tempName);
        $invite = Invite::findByCode($hash);

        if (!is_null($invite) && !$invite->isUsed()) {
            return $invite;
        } else {
            return null;
        }
    }
}
