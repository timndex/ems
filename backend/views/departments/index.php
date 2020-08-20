<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
//use kartik\widgets\Growl;
use yii\widgets\Alert;


/* @var $this yii\web\View */
/* @var $searchModel app\models\DepartmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Departments';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="departments-index">

    <h4 style="border-bottom:1px dotted black; width: 100px;"><?= Html::encode($this->title) ?></h4>
    <?php
    

    echo ModalAjax::widget([
        'id' => 'createCompany',
        'header' => 'Create Department',
        'toggleButton' => [
            'label' => 'Create Department',
            'class' => 'btn btn-success btn-xs'
        ],
        'url' => Url::to(['create']), // Ajax view with form to load
        'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
        'size' => ModalAjax::SIZE_SMALL,
        'options' => ['class' => 'header-success'],
        'autoClose' => true,
        'pjaxContainer' => '#grid-department-pjax',

    ]);
    ?>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

  <?php 
        echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'bootstrap'=>true,
        'bordered' => true,
        'resizableColumns'=>true,
        'striped' =>true,
        'pjax'=>true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'options'=>[
                'id'=>'kv-unique-id-1',
            ]
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'departments_id',


        [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'departments_name', 
        'editableOptions' => [
            'header' => 'Department Name', 
            
           
            'options' => [
               ['class'=>'form-control', 'placeholder'=>'Department name...']
            ]
        ],
   
],
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
        ],
    ]); ?>


</div>
