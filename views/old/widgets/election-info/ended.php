<?php

use app\components\widgets\PieChartWidget,
    app\models\politics\elections\ElectionRequestType,
    app\components\LinkCreator;

/* @var $election \app\models\politics\elections\Election */
/* @var $viewer \app\models\User */

$data = json_decode($election->results);
$data->results = (array) $data->results;
uasort($data->results, function($a, $b){
    return $b <=> $a;
});

$requests = $election->getRequests()->all();

$table = [];
$sumVotes = 0;
$i = 0;
foreach ($data->results as $variant => $votes) {
    $sumVotes += $votes;
    
    foreach ($requests as $request) {
        if ($request->variant == $variant) {
            if ($request->type == ElectionRequestType::USER_SELF) {
                $name = LinkCreator::userLink($request->object);
            } else {
                $name = Yii::t('app', 'None of the above');
            }
            break;
        }
    }
    
    $table[] = [
        'name' => $name,
        'color' => ["#ff0000", "#ffff00", "#008000", "#ff8000", "#0000ff", "#4b0082", "#9400d3"][$i++],
        'percents' => $votes
    ];
}
foreach ($table as &$el) {
    $el['percents'] = $sumVotes > 0 ? round($el['percents']/$sumVotes * 100,2) : 0;
}


?>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <p><strong><?=Yii::t('app', 'Current status:')?></strong> <?=Yii::t('app', 'ended')?></p>
        <p><strong><?=Yii::t('app', 'Voting finish:')?></strong> <span class="formatDate" data-unixtime="<?=$election->dateVotingEnd?>"><?=date('d-m-y', $election->dateVotingEnd)?></span></p>
    </div>
    <div class="col-md-6 col-sm-12">
        <?=PieChartWidget::widget(['data' => $data->results, 'colors' => ["#ff0000", "#ffff00", "#008000", "#ff8000", "#0000ff", "#4b0082", "#9400d3"], 'table' => $table])?>
    </div>
</div>