<?php
    use yii\helpers\Html,
        app\components\MyHtmlHelper;
?>
<div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Тип обьекта</h3>
            <span class="pull-right">
                <!-- Tabs -->
                <ul class="nav panel-tabs">
                    <? foreach ($factoryCategories as $i => $factoryCat) { ?>
                    <li class="<?=($i != 5)?'':'active'?>"><a href="#tab<?=$i?>" data-toggle="tab"><?=Html::img("/img/factory-types/{$factoryCat->id}.png",['alt'=>$i,'title'=>$factoryCat->name])?></a></li>
                    <? } ?>
                </ul>
            </span>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <? foreach ($factoryCategories as $i => $factoryCat) { ?>
                    <div class="tab-pane <?=($i != 5)?'':'active'?>" id="tab<?=$i?>">
                        <h3><?=$factoryCat->name?></h3>
                        <? foreach($factoryCat->protos as $facType) { ?>
                            <p>
                                <input data-workersSize="<?=$facType->sumNeedWorkers?>" data-buildCost="<?=$facType->build_cost?>" class="elect_vote_radio" type="radio" name="new_factory_type" value="<?=$facType->id?>" id="new_factory_type<?=$facType->id?>">
                                <label style="display: inline-block;" for="new_factory_type<?=$facType->id?>"><?=$facType->name?></label>
                            </p>
                        <? } ?>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
<div class="control-group" id="new_factory_add_info" style="display:none;">
      <label class="control-label" for="#new_factory_name">Название</label>
      <div class="controls">
          <input type="text" id="new_factory_name" value="">
      </div>
      <label class="control-label" for="#factory_new_size">Размер</label>
      <div class="controls">
          <button class="btn btn-sm btn-default" onclick="if ($('#factory_new_size').val()>1) $('#factory_new_size').val(parseInt($('#factory_new_size').val()) - 1); updateCost();">-</button><input class="btn btn-sm btn-default" type="range" id="factory_new_size" min="1" max="127" step="1" value="1"><button class="btn btn-sm btn-default" onclick="if ($('#factory_new_size').val() < 127) $('#factory_new_size').val(parseInt($('#factory_new_size').val()) + 1); updateCost()">+</button>
      </div>
      
      <p>Число работников: <span id="workers_size">0</span> <i class="icon-user"></i></p>
      <p>Стоимость строительства: <span id="build_cost">0</span> <?=MyHtmlHelper::icon('coins')?></p>
</div>

<script>



function updateCost(){
    var size = parseInt($('#factory_new_size').val());
    var workers = $('#new_factory_type'+new_factory_type).attr("data-workersSize");
    var cost = $('#new_factory_type'+new_factory_type).attr("data-buildCost");

    $('#workers_size').text(size*workers);
    $('#build_cost').text(size*cost);
    return true;
}

function start_build() {
    var cost = parseInt($('#factory_new_size').val()) * parseInt($('#new_factory_type'+new_factory_type).attr("data-buildCost"));
    if (cost > <?=$holding->balance?>) {
        alert("На счету фирмы недостаточно денег для строительства");            
    } else {
        json_request('new-holding-decision',{
            'holding_id':<?=$holding->id?>,
            'type':5,
            'name':$('#new_factory_name').val(),
            'region_id':<?=$region->id?>,
            'factory_type':new_factory_type,
            'size': $('#factory_new_size').val()
        });
    }
}

$(function(){
    
    $('#build-factory-modal-main-btn').off('click');
    $('#build-factory-modal-main-btn').attr('disabled','disabled');
        
    $('.elect_vote_radio').iCheck({
        checkboxClass: 'icheckbox_square',
        radioClass: 'iradio_square',
        increaseArea: '20%' // optional
    }).on('ifChecked', function(event){
      
        new_factory_type = $(this).val();
        $('#new_factory_add_info').show();
        $('#start_build').show();
        updateCost();

        $('#build-factory-modal-main-btn').removeAttr('disabled');
        $('#build-factory-modal-main-btn').click(start_build);
    });;
    
    $('#factory_new_size').change(updateCost);
    $('#factory_new_size').click(updateCost);
    
});

</script>