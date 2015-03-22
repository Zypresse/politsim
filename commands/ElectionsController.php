<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Org;
use app\models\ElectResult;
use app\models\Notification;

/**
 * Calculate elect results
 *
 * Cron hourly
 */
class ElectionsController extends Controller {

    public function actionIndex() {
        $used_uids = [];
        $orgs = Org::find()->where('next_elect <= ' . time())->all();

        foreach ($orgs as $org) {
            if ($org->isLeaderElected() || $org->isElected()) {
                foreach ($org->posts as $post) {
                    if ($post->user)
                        $post->unlink('user', $post->user);
                    if ($post->party_reserve) {
                        $post->party_reserve = 0;
                        $post->save();
                    }
                }
            }

            if ($org->isLeaderElected()) {
                // Выборы лидера организации
                if (sizeof($org->lrequests)) {
                $results = [];
                $sumStar = 0;
                $sumRatings = 0;
                foreach ($org->lrequests as $request) {
                    if ($request->user) {
                        $ar = $request->user->heart + $request->user->chart_pie / 10;
                        if ($request->user->party)
                            $ar += $request->user->party->heart / 10 + $request->user->party->chart_pie / 100;
                        foreach ($request->votes as $vote) {
                            $ar += ($vote->user->star + $vote->user->heart / 10 + $vote->user->chart_pie / 100) / 10;
                        }
                        $sumStar += $request->user->star;
                        $sumRatings += $ar;
                        $results[] = ['req' => $request, 'rating' => $ar];
                    }
                }

                $yavkaTime = 1 - ($org->next_elect - time()) / (24 * 60 * 60);
                if ($yavkaTime > 1)
                    $yavkaTime = 1;
                $yavkaStar = ($org->state->sum_star) ? $sumStar / $org->state->sum_star : 0;
                $yavka = $yavkaTime * $yavkaStar;


                usort($results, function($a, $b) {
                    switch (true) {
                        case $b['rating'] < $a['rating']:
                            return -1;
                        case $b['rating'] > $a['rating']:
                            return 1;
                        default:
                            return 0;
                    }
                });

                foreach ($results as $result) {
                    echo "{$result['req']->user->name} — {$result['rating']}" . PHP_EOL;
                }

                $first = $results[0];
                $first['req']->user->post_id = $org->leader_post;
                $first['req']->user->chart_pie += ceil($first['req']->user->state->sum_star / 10);
                $first['req']->user->star += ceil($first['req']->user->state->sum_star / 50);
                $first['req']->user->save();
                if ($org->leader_dest === 'nation_party_vote') {
                    $org->leader->party_reserve = $first['req']->user->party_id;
                }
                
                Notification::send($first['req']->user->id, "Вы победили на выборах и заняли должность «".$org->leader->name."»");                

                foreach ($results as $i => $result) {
                    if ($i) {
                        $result['req']->user->chart_pie -= 1;
                        $first['req']->user->star += ceil($first['req']->user->state->sum_star / 100);
                        $first['req']->user->save();
                    }
                }

                $used_uids[] = $first['req']->user->id;
                // TODO Уведомления, новости
                $resultToSave = [
                    'yavka_percents' => round($yavka * 100, 2),
                    'yavka_people' => round($yavka * $org->state->population),
                    'results' => []
                ];
                foreach ($results as $result) {
                    $resultToSave['results'][] = [                        
                        'uid' => $result['req']->user->id,
                        'name' => $result['req']->user->name,
                        'party_id' => $result['req']->user->party_id,
                        'party_name' => $result['req']->user->party ? $result['req']->user->party->name : ($result['req']->user->sex === 1 ? 'Беспартийная' : 'Беспартийный'),
                        'party_short_name' => $result['req']->user->party ? $result['req']->user->party->short_name : ($result['req']->user->sex === 1 ? 'бесп.' : 'бесп.'),
                        'votes_percents' => $sumRatings ? round(100 * $result['rating'] / $sumRatings, 2) : 0,
                        'votes_population' => $sumRatings ? round(round($yavka * $org->state->population) * $result['rating'] / $sumRatings) : 0
                    ];
                }


                $electResult = new ElectResult();
                $electResult->org_id = $org->id;
                $electResult->leader = 1;
                $electResult->date = time();
                $electResult->data = json_encode($resultToSave,JSON_UNESCAPED_UNICODE);
                $electResult->save();
                }
            }
            if ($org->isElected()) {
                // Выборы членов организации
                if (sizeof($org->requests)) {
                $results = [];
                $sumStar = 0;
                $sumRatings = 0;
                foreach ($org->requests as $request) {
                    if ($request->party) {
                        $ar = $request->party->heart + $request->party->chart_pie / 10;
                        foreach ($request->votes as $vote) {
                            $ar += ($vote->user->star + $vote->user->heart / 10 + $vote->user->chart_pie / 100) / sizeof($request->votes);
                        }
                        $sumStar += $request->party->star;
                        $sumRatings += $ar;
                        $results[] = ['req' => $request, 'rating' => $ar];
                    }
                }

                $yavkaTime = 1 - ($org->next_elect - time()) / (24 * 60 * 60);
                if ($yavkaTime > 1)
                    $yavkaTime = 1;
                $yavkaStar = $org->state->sum_star ? $sumStar / $org->state->sum_star : 0;
                $yavka = $yavkaTime * $yavkaStar;


                usort($results, function($a, $b) {
                    switch (true) {
                        case $b['rating'] < $a['rating']:
                            return -1;
                        case $b['rating'] > $a['rating']:
                            return 1;
                        default:
                            return 0;
                    }
                });

                foreach ($results as $result) {
                    echo "{$result['req']->party->name} — {$result['rating']}" . PHP_EOL;
                }

                $count = $org->getPostsCount() - 1;
                $posts = [];
                foreach ($org->posts as $i => $post) {
                    if ($org->leader_post !== $post->id)
                        $posts[] = $post;
                }
                foreach ($results as $result) {
                    $thisCount = $sumRatings ? round($result['rating'] / $sumRatings * $count) : 0;
                    $list = $result['req']->party->members;

                    for ($i = 0; $i < $thisCount; $i++) {
                        $post = array_shift($posts);
                        do {
                            $member = array_shift($list);
                            if (!$member) break;
                        } while (in_array($member->id, $used_uids) || $member->post);
                        
                        $post->party_reserve = $result['req']->party->id;
                        $post->save();
                        if ($member) {
                            $member->link('post', $post);
                            Notification::send($member->id, "По результатам выборов вы заняли должность «".$post->name."»");
                        }
                    }
                }

                // TODO уведомления, новости

                $resultToSave = [
                    'yavka_percents' => round($yavka * 100, 2),
                    'yavka_people' => round($yavka * $org->state->population),
                    'results' => []
                ];
                foreach ($results as $result) {
                    $resultToSave['results'][] = [                        
                        'id' => $result['req']->party->id,
                        'name' => $result['req']->party->name,
                        'short_name' => $result['req']->party->short_name,
                        'posts' => $sumRatings ? round($result['rating'] / $sumRatings * $count) : 0,
                        'votes_percents' => $sumRatings ? round(100 * $result['rating'] / $sumRatings, 2) : 0,
                        'votes_population' => $sumRatings ? round(round($yavka * $org->state->population) * $result['rating'] / $sumRatings) : $sumRatings
                    ];
                }


                $electResult = new ElectResult();
                $electResult->org_id = $org->id;
                $electResult->leader = 0;
                $electResult->date = time();
                $electResult->data = json_encode($resultToSave,JSON_UNESCAPED_UNICODE);
                $electResult->save();
                }
            }

            $org->next_elect = time() + $org->elect_period * 24 * 60 * 60;
            foreach ($org->requests as $request) {
                $request->delete();
            }
            foreach ($org->lrequests as $request) {
                $request->delete();
            }
            $org->save();
        }
    }

}
