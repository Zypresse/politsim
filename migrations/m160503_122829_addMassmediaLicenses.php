<?php

use yii\db\Migration,
    app\models\licenses\proto\LicenseProto;

class m160503_122829_addMassmediaLicenses extends Migration
{
    public function safeUp()
    {
        $license1 = new LicenseProto([
            'code' => 'newspapers',
            'name' => 'Выпуск прессы'
        ]);        
        $license1->save();
        $license2 = new LicenseProto([
            'code' => 'radio',
            'name' => 'Радиовещание'
        ]);        
        $license2->save();
        $license3 = new LicenseProto([
            'code' => 'tv',
            'name' => 'Телевещание'
        ]);        
        $license3->save();
    }

    public function safeDown()
    {
        LicenseProto::deleteAll('code IN (:code1, :code2, :code3)', [
            ':code1' => 'newspapers',
            ':code2' => 'radio',
            ':code3' => 'tv'
        ]);
    }
}
