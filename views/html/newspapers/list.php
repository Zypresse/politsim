<?php

use app\models\State,
    app\models\massmedia\Massmedia,
    app\components\MyHtmlHelper,
    yii\helpers\Html,
    yii\helpers\ArrayHelper;

/* @var $states State[] */
/* @var $newspapers Massmedia[] */
/* @var $selectedState State */

$statesArray = ArrayHelper::map($states, 'id', 'name');
$statesArray[0] = 'Все страны';

?>
<section class="content">
    <div class="row" style="margin-top: 10px">
        <div class="col-md-7">
            <h4><?=($selectedState ? "Обзор прессы:<br>".$selectedState->getHtmlName() : "Обзор мировой прессы")?></h4>
        </div>
        <div class="col-md-5 ui-widget">
            <label to="combobox">По стране: </label>
            <?=Html::dropDownList('stateId', $selectedState ? $selectedState->id : 0, $statesArray, ['id' => 'combobox'])?>
        </div>
    </div>
    <div class="row">
        <?php foreach ($newspapers as $newspaper): ?>
        <div class="col-md-6">
            <div class="info-box bg-gray-light">
                <span class="info-box-icon"><i class="fa fa-newspaper-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">
                        <span class="label-info" style='border-radius: 5px' title="Охват аудитории" >
                            &nbsp;<?=number_format($newspaper->coverage, 0, '', ' ')?> <i class="fa fa-user"></i>&nbsp;
                        </span>
                        &nbsp;
                        <span class="label-success" style='border-radius: 5px' title="Рейтинг" >
                            &nbsp;<?=number_format($newspaper->rating, 0, '', ' ')?> <i class="fa fa-star"></i>&nbsp;
                        </span>
                    </span>
                    <span class="info-box-number"><?=$newspaper->name?></span>
                    <span class="progress-description"><?=$newspaper->holding ? $newspaper->holding->name : 'Независимая газета'?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        <?php endforeach ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button id="create-newspaper-button" class="btn btn-primary btn-flat">Создать газету</button>
        </div>
    </div>
</section>
<div style="display:none" class="modal fade" id="create-newspaper-modal" tabindex="-1" role="dialog" aria-labelledby="create-newspaper-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="create-newspaper-modal-label">Создание газеты</h3>
            </div>
            <div id="create-newspaper-modal-body" class="modal-body">
                <p>Загрузка…</p>
            </div>
            <div class="modal-footer">
                <button id="create-newspaper-send-button" class="btn btn-primary">Создать</button>
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<script>
  (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
  $(function() {
    $( "#combobox" ).combobox({
        select: function(){
            load_page('newspapers', {'stateId':$(this).val()});
        }
    });
    
    $("#create-newspaper-button").click(function(){
        load_modal('create-newspaper',{},'create-newspaper-modal','create-newspaper-modal-body');
    });
    
    $('#create-newspaper-send-button').click(function(){
        json_request('create-massmedia', $('#create-newspaper-form').serializeObject());
    });
  });
</script>