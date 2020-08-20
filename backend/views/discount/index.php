<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DiscountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Discounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discount-index">

   <h4 style="border-bottom:1px dotted black; width: 100px;"><?= Html::encode($this->title) ?></h4>

<?php
      echo ModalAjax::widget([
        'id' => 'createDiscount',
        'header' => 'Create Discount',
        'toggleButton' => [
            'label' => 'Create Discount',
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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
           
               [
               'attribute'=> 'discount',
               'format'=>'raw',
               'value'=>function($model){
                return $model->discount.'%';
               }
            ],
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            //'updated_by',
            //'status',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
