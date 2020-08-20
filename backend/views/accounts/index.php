<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="routes-index">

     <h4 style="border-bottom:1px dotted black; width: 100px;"><?= Html::encode($this->title) ?></h4>
    <?php
    

    echo ModalAjax::widget([
        'id' => 'createUser',
        'header' => 'Create User',
        'toggleButton' => [
            'label' => 'Create User',
            'class' => 'btn btn-success btn-xs'
        ],
        'url' => Url::to(['create']), // Ajax view with form to load
        'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
        'size' => ModalAjax::SIZE_LARGE,
        'options' => ['class' => 'header-success'],
        'autoClose' => true,
        'pjaxContainer' => '#grid-route-pjax',

    ]);
    ?>

       <?php
        echo ModalAjax::widget([
    'id' => 'update',
    'selector' => 'a.btns', // all buttons in grid view with href attribute

    'options' => ['class' => 'header-success'],
    'pjaxContainer' => '#grid-account-pjax',
    'autoClose' => true,
    'ajaxSubmit' => true,
    'size' => ModalAjax::SIZE_LARGE,
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
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            'Fullnames',
            //'auth_key',
          //  'password_hash',
            //'password_reset_token',
            'email:email',

                   [
              'attribute'=>'user_type',
              'format'=>'raw',
              'value'=>function($model){
                if($model->user_type == 11){
                    return "USER";
                }
                else if($model->user_type==20){
                    return "ADMIN";
                }
                else{
                    return null;
                }
              }
          ],
          'user_phone_no',
            
           [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'status', 
        'format'=>'raw',
          'value'=>function($model){
            if($model->status==10){
              return "<span class='label label-info'>ACTIVE</span>";

            }else if($model->status==9){
              return "<span class='label label-danger'>INACTIVE</span>";
            }else{
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
