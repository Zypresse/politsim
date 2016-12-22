<?php

namespace app\models\politics\elections;

use app\models\politics\AgencyPost,
    app\models\population\Pop,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\politics\constitution\ConstitutionArticleType;

/**
 * 
 */
abstract class ElectionManager
{
    
    const DAY_LENGTH = 60*60*24;
    
    /**
     * 
     * @param AgencyPost $post
     * @return Election
     */
    public static function createPostElection(AgencyPost &$post)
    {
        
        /* @var $article DestignationType */
        $destignationTypeArticle = $post->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
        $type = (int) $destignationTypeArticle->value;
        $whoId = (int) $destignationTypeArticle->value2;
        $settings = (int) $destignationTypeArticle->value3;
        $termsOfOfficeArticle = $post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE);
        $officeDays = (int) $termsOfOfficeArticle->value;
        $termsOfElectionArticle = $post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION);
        $registrationDays = (int) $termsOfElectionArticle->value;
        $waitingDays = (int) $termsOfElectionArticle->value2;
        $electionDays = (int) $termsOfElectionArticle->value3;
        
        /* @var $lastElection Election */
        $lastElection = $post->getElections()->orderBy(['id' => SORT_DESC])->one();
        if (is_null($lastElection)) {
            $start = time();
        } else {
            if ($lastElection->status != ElectionStatus::ENDED) {
                return $lastElection;
            }
            $start = ((int) $lastElection->dateVotingEnd) + $officeDays*static::DAY_LENGTH;
        }
        
        $election = new Election([
            'whomType' => ElectionWhomType::POST,
            'whomId' => $post->id,
            'settings' => $settings,
            'dateRegistrationStart' => $start,
            'dateRegistrationEnd' => $start+$registrationDays*static::DAY_LENGTH,
            'dateVotingStart' => $start+$registrationDays*static::DAY_LENGTH+$waitingDays*static::DAY_LENGTH,
            'dateVotingEnd' => $start+$registrationDays*static::DAY_LENGTH+$waitingDays*static::DAY_LENGTH+$electionDays*static::DAY_LENGTH,
        ]);
        
        switch ($type){
            case DestignationType::BY_STATE_ELECTION:
                $election->whoType = ElectionWhoType::STATE;
                $election->whoId = $post->stateId;
                break;
            case DestignationType::BY_DISTRICT_ELECTION:
                $election->whoType = ElectionWhoType::ELECTORAL_DISTRICT;
                $election->whoId = $whoId;
                break;
            case DestignationType::BY_AGENCY_ELECTION:
                $election->whoType = ElectionWhoType::AGENCY_MEMBERS;
                $election->whoId = $whoId;
                break;
            default:
                return null;
        }
        
        $election->save();
        return $election;
    }
    
    
    public static function calculateResults(Election &$election)
    {
        /* @var $post AgencyPost */
        $post = $election->whom;
        $requests = $election->requests;
        $who = $election->who;
        $votesByUsers = $election->votesByUsers;
        $pops = [];
        
        /* @var $pops \app\models\population\Pop[] */
        
        switch ($election->whoType) {
            case ElectionWhoType::STATE:
            case ElectionWhoType::ELECTORAL_DISTRICT: // TODO: add field ElectoralDistrict::$usersFame
                /* @var $who ElectoralDistrict */
                /* @var $who \app\models\politics\State */
                $sumRequestsFame = 0;
                foreach ($requests as $request) {
                    $sumRequestsFame += $request->object->fame;
                }
                $turnout = $who->usersFame > 0 ? $sumRequestsFame/$who->usersFame : 1;
                $tiles = $who->tiles;
                foreach ($tiles as $tile) {
                    $pops = array_merge($pops, $tile->pops);
                }
                foreach ($pops as $i => $pop) {
                    echo $pop->ideologies.PHP_EOL;
                    if ($i > 10) break;
                }
                break;
            case ElectionWhoType::AGENCY_MEMBERS:
                /* @var $who \app\models\politics\Agency */
                $turnout = count($votesByUsers) / $who->getPosts()->count();
                break;
        }
        
        echo "Turnout: ".$turnout.PHP_EOL;
        echo "NPC count all: ".count($pops).PHP_EOL;
        $votersCount = round($turnout*count($pops));
        if ($votersCount > 0) {
            $pops = array_rand($pops, $votersCount);
            echo "NPC count voted: ".count($pops).PHP_EOL;
            
            $npcCount = 0;
            var_dump($pops[0]); die();
            foreach ($pops as $pop) {
                $npcCount += $pop->count;
                $tmpPops = [];
                $ideologies = json_decode($pop->ideologies);
                foreach ($ideologies as $ideologyId => $percents) {
                    $tmpPops[] = ['ideologyId' => $ideologyId, 'count' => $pop->count*$percents/100];
                }
                $tmpPopsWithReligions = [];
                $religions = json_decode($pop->religions);
                foreach ($religions as $religionId => $percents) {
                    foreach ($tmpPops as $tmpPop) {
                        $tmpPopsWithReligions[] = [
                            'ideologyId' => $tmpPop['ideologyId'],
                            'religionId' => $religionId,
                            'count' => $tmpPop['count']*$percents/100
                        ];
                    }
                }
                unset($tmpPops);
                $tmpPopsWithGenders = [];
                $genders = json_decode($pop->genders);
                foreach ($genders as $genderId => $percents) {
                    foreach ($tmpPopsWithReligions as $tmpPop) {
                        $tmpPopsWithGenders[] = [
                            'ideologyId' => $tmpPop['ideologyId'],
                            'religionId' => $tmpPop['religionId'],
                            'gender' => $genderId,
                            'count' => $tmpPop['count']*$percents/100
                        ];
                    }
                }
                unset($tmpPopsWithReligions);
                $tmpPops = [];
                $ages = json_decode($pop->ages);
                foreach ($ages as $age => $percents) {
                    foreach ($tmpPopsWithGenders as $tmpPop) {
                        $tmpPops[] = [
                            'ideologyId' => $tmpPop['ideologyId'],
                            'religionId' => $tmpPop['religionId'],
                            'gender' => $tmpPop['gender'],
                            'age' => $age,
                            'count' => $tmpPop['count']*$percents/100
                        ];
                    }
                }
                print_r($tmpPops); die();
                $vote = new ElectionVotePop([
                    'electionId' => $election->id,
                    'count' => $pop->count,
                    'tileId' => $pop->tileId,
                    'classId' => $pop->classId,
                    'nationId' => $pop->nationId,
                ]);
            }
            
        }
        
        
    }
    
}
