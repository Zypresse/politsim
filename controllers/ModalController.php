<?php

namespace app\controllers;

use yii;
use app\models\Region;
use app\models\Post;
use app\models\User;
use app\components\MyController;

class ModalController extends MyController
{

    public function actionCreateStateDialog($code)
    {
        if ($code) {
            $region = Region::findByCode($code);
            if (is_null($region))
                return $this->_r("Region not found");

            $forms = [['id'=>4,'name'=>'Диктатура']];

            return $this->render("create_state_dialog",['region'=>$region,'forms'=>$forms]); 
        } else 
            return $this->_r("Invalid code");
    }

    public function actionNaznach($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $post = Post::findByPk($id);
            if (is_null($post)) 
                return $this->_r("Post not found");

            if (!($post->org->dest === 'dest_by_leader' && intval($post->org->leader->user->id) === $this->viewer_id && $id !== $post->org->leader_post))
                return $this->_r("No access");

            $people = User::find()->where(['state_id'=>$post->org->state_id,'post_id'=>0])->orderBy('`star` + `heart` + `chart_pie` DESC')->all();

            return $this->render("naznach",['post'=>$post,'people'=>$people]);
        } else 
            return $this->_r("Invalid post ID");
    }

}
