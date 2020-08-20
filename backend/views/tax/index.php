<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TaxSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Taxes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tax-index">

     <h4 style="border-bottom:1px dotted black; width: 60px;"><?= Html::encode($this->title) ?></h4>

 <?php
       echo ModalAjax::widget([
        'id' => 'createnewtax',
        'header' => 'Add New Tax',
        'toggleButton' => [
            'label' => 'Add New Tax',
            'class' => 'btn btn-success btn-xs'
        ],
        'url' => Url::to(['create']), // Ajax view with form to load
        'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
        'size' => ModalAjax::SIZE_DEFAULT,
        'options' => ['class' => 'header-success'],
        'autoClose' => true,
        'pjaxContainer' => '#grid-menu-pjax',

    ]);
    ?>
    <?php
    echo ModalAjax::widget([
    'id' => 'update',
    'selector' => 'a.btns', // all buttons in grid view with href attribute

    'options' => ['class' => 'header-success'],
    'pjaxContainer' => '#grid-menu-pjax',
    'autoClose' => true,
    'ajaxSubmit' => true,
    'size' => ModalAjax::SIZE_DEFAULT,
    'events'=>[
        ModalAjax::EVENT_MODAL_SHOW => new \yii\web\JsExpression("
            function(event, data, status, xhr, selector) {
                selector.addClass('warning');
            }
       "),
       
     
    ]
]);

 
?>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'tax_name',
            [
               'attribute'=> 'tax_amount',
               'format'=>'raw',
               'value'=>function($model){
                return $model->tax_amount.'%';
               }
            ],   
            //'created_at',
           // 'created_by',
            //'updated_at',
            //'updated_by',
          [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'status', 
                'format'=>'raw',
                  'value'=>function($model){
                    if($model->status==10){
                      return "<span class='label label-info'>ACTIVE</span>";

                    }
                    else if($model->status==9){
                      return "<span class='label label-warning'>INACTIVE</span>";
                    }
                    else if($model->status==0){
                        return "<span class='label label-danger'>DELETED</span>";
                    } 
                    else{
                        return 'null';
                    }
                   },
                'readonly' => function($model, $key, $index, $widget) {
                    return (!$model->status); // do not allow editing of inactive records
                },
                'editableOptions' => [
                    'header' => ' Status', 

                   
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => [10 => 'ACTIVE', 9 => 'INACTIVE',],
            'options' => ['class'=>'form-control'],
                ],
           
        ],
        

         
         [
             
             
              

                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:rgb(0, 183, 255)'],
                'template' => "{update}&nbsp{remove}&nbsp{restore};",
                'buttons' => [
 
                'update' => function ($url, $model) {
                    if($model->status ==9 || $model->status==10){
                    return Html::a('<i class="glyphicon glyphicon-pencil"></i>', Url::to(['update', 'id' => $model->id]), [
                                'class' => ' btn btn-warning btn-xs btns',

                                'title'=>'Update',

                    ]);
                }else{

                  }
                },
            
                'remove' => function ($url, $model) {
                   
                  if($model->status==9){

                    return Html::a('<i class="glyphicon glyphicon-trash"></i>', Url::to(['disable', 'id' => $model->id]), [
                        'class' => ' btn btn-danger btn-xs',
                        'title'=>'Delete',
                        'data' => [
                            'confirm' => 'Are You sure you want to delete',
                        ],
                    ]);
                  }
                },
                 'restore' => function ($url, $model) {
                   
                  if($model->status==0){

                    return Html::a('<i class="glyphicon glyphicon-retweet"></i>', Url::to(['restore', 'id' => $model->id]), [
                        'class' => ' btn btn-danger btn-xs',
                        'title'=>'Restore',
                        'data' => [
                            'confirm' => 'Are You sure you want to Restore',
                        ],
                    ]);
                  }
                },
              
              
 
                ],
              
              ],


        ],
    ]); ?>


</div>
