<?php

use yii\helpers\Html;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RefundSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Refunds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="refund-index">

      <h4 style="border-bottom:1px dotted black; width: 80px;"><?= Html::encode($this->title) ?></h4>

  
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'job_card_number',
            'customerName.customer_names',
            'amount',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            //'status',
             [

                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => "{print}",
                'buttons' => [
 
                   'print' => function ($url, $model) {
                  
                    return Html::a('<i class="fa fa-print" aria-hidden="true"> Print</i>', Url::to(['print', 'id' => $model->id]), [
                                'class' => 'btn-pdfprint btn btn-warning btn-xs btns',
                                //'title'=>'Update',

                    ]);
                  
                },
              
 
                ],
              
              ],

           
        ],
    ]); ?>


</div>
