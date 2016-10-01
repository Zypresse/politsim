<?php

use yii\db\Migration;

class m161001_101907_autoactivated_users extends Migration
{
    
    public function safeUp()
    {
        $this->delete('accounts');
        $this->delete('users');
        
        $data = json_decode(file_get_contents(Yii::$app->basePath.'/data/default/vk-users.json'));
        
        foreach ($data as $uid) {
            $user = new app\models\User([
                'name' => '...',
                'avatar' => 'https://placeholdit.imgix.net/~text?txtsize=13&txt=avatar&w=50&h=50',
                'avatarBig' => 'https://placeholdit.imgix.net/~text?txtsize=33&txt=avatar&w=350&h=350',
                'genderId' => 0,
                'cityId' => 1,
                'dateLastLogin' => 0,
                'isInvited' => true,
            ]);
            $user->save();
            $this->insert('accounts', [                
                'userId' => $user->id,
                'sourceType' => 3,
                'sourceId' => $uid,
            ]);
        }
        
        
    }

    public function safeDown()
    {
        $this->delete('accounts');
        $this->delete('users');
    }
}
