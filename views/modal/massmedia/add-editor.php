<?php

use yii\helpers\Html,
    yii\widgets\ActiveForm,
    yii\data\ActiveDataProvider,
    yii\grid\GridView,
    app\models\User,
    app\models\UserSearch,
    app\models\massmedia\Massmedia,
    app\components\MyHtmlHelper;

/* @var $this yii\web\View */
/* @var $searchModel UserSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $massmedia Massmedia */

/* @var $form ActiveForm */

$dataProvider->pagination = [
    'pageSize' => 5
]

?>
<h5>Назначение нового редактора СМИ <?=$massmedia->getHtmlName()?></h5>
<div class="massmedia-add-editor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['modal/add-editor'],
        'method' => 'get',
        'options' => [
            'class' => 'form form-horizontal'
        ]
    ]); ?>
    
    <?= Html::input('hidden', 'massmediaId', $massmedia->id) ?>
    
    <div class="input-group">
        <input id="add-editor-search-input" autofocus name="UserSearch[name]" class="form-control" placeholder="Введите имя..." type="text" value="<?=$searchModel->name?>" >
        <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-info btn-flat">
                <i class="fa fa-search"></i>
            </button>
        </span>
    </div>    

    <?php ActiveForm::end(); ?>

</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'showHeader' => false,
    'tableOptions' => [
        'class' => 'table table-bordered table-hover table-striped text-center massmedia-add-editor-table'
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'format' => 'raw',
            'value' => function(User $user) {
                return 
                    $user->htmlName.'<br>'.
                    '<span class="star">'.
                        $user->star.' '.MyHtmlHelper::icon('star').' '.
                        $user->heart.' '.MyHtmlHelper::icon('heart').' '.
                        $user->chart_pie.' '.MyHtmlHelper::icon('chart_pie').
                    '</span>';
            }
        ],
        'post.htmlName:raw',
        'state.htmlName:raw',
        [
            'format' => 'raw',
            'value' => function (User $user) use ($massmedia) {
                return $massmedia->isEditor($user->id) ? '' :
                    '<button class="btn btn-primary add-editor-button2" data-user-id="'.$user->id.'" title="Назначить">
                        <i class="fa fa-check"></i>
                    </button>';
            }
        ]
    ],
]); ?>

<script>
    $(function(){
       $('#add-editor-search-input').focus();
       
       $('.add-editor-button2').click(function(){
           load_modal('rule-editor', {'massmediaId':<?=$massmedia->id?>, 'userId':$(this).data('userId')}, 'rule-editor-modal', 'rule-editor-modal-body');
       });
    });
</script>