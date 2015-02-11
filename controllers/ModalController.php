<?php

namespace app\controllers;

use yii;
use app\models\Region;
use app\models\Post;
use app\models\User;
use app\models\Org;
use app\models\ElectRequest;
use app\models\ElectVote;
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

    public function actionTweetAboutHuman()
    {
        return $this->render("tweet_about_human");
    }

    public function actionElectExitpolls($org_id,$leader = 0)
    {
        $org_id = intval($org_id);
        $leader = intval($leader);
        if ($org_id > 0) {
            $org = Org::findByPk($org_id);
            if (is_null($org))
                return $this->_r("Organisation not found");

            $elect_requests = ElectRequest::find()->where(["org_id"=>$org_id,"leader"=>$leader])->all();
            $results = [];
            $requests = [];
            $sum_a_r = 0;
            $sum_star = 0;
            if (is_array($elect_requests)) { 
            foreach ($elect_requests as $request) {

                if (($leader && is_null($request->user)) || is_null($request->party))
                    return $this->_r("Trololo");

                $abstract_rating = $leader ? $request->user->heart + $request->user->chart_pie/10 + ($request->party->heart + $request->party->chart_pie/10)/10 : $request->party->heart + $request->party->chart_pie/10;
                $votes = ElectVote::find()->where(["request_id"=>$request->id])->all();
                if (is_array($votes)) foreach ($votes as $vote) {
                    $abstract_rating += ($vote->user->star + $vote->user->heart/10 + $vote->user->chart_pie/100)/10;
                }
                $results[] = ['id'=>$request->id,'rating'=>$abstract_rating];
                $requests[$request->id] = $request;
                $sum_a_r += $abstract_rating;
                $sum_star += $leader ? $request->user->star : $request->party->star;
            }
            $yavka_time = ($org->next_elect-time())/(24*60*60);
            if ($yavka_time > 1) $yavka_time = 1;
            $yavka_star = $sum_star / $org->state->sum_star;
            $yavka = $yavka_time * $yavka_star;

            return $this->render("elect_exitpolls",['requests'=>$requests,'results'=>$results,'sum_a_r'=>$sum_a_r,'org'=>$org,'yavka'=>$yavka,'leader'=>$leader]);

            } else
                return $this->_r("No requests on elections");
        } else 
            return $this->_r("Invalid organisation ID");
    }

}
