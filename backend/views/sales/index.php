<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use dixonstarter\pdfprint\Pdfprint;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SalesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-index">


 <h4 style="border-bottom:1px dotted black; width: 50px;"><?= Html::encode($this->title) ?></h4>
    

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'job_card_number',
            'transaction_number',
           // 'cash_sale_number',
           // 'customer_id',
           'payment',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',

                [

                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => "{print}",
                'buttons' => [
 
                   'print' => function ($url, $model) {
                  
                    return Html::a('<i class="fa fa-print" aria-hidden="true"> Print', Url::to(['print', 'id' => $model->id]), [
                                'class' => 'btn-pdfprint btn btn-warning btn-xs btns',
                                //'title'=>'Update',

                    ]);
                  
                },
              
 
                ],
              
              ],
        ],
    ]); ?>


</div>
