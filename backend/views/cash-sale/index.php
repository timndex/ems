<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
use dixonstarter\pdfprint\Pdfprint;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JobCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Sale';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index-sale-index">

   <h4 style="border-bottom:1px dotted black; width: 80px;"><?= Html::encode($this->title) ?></h4>

<?php

    echo ModalAjax::widget([
    'id' => 'update',
    'selector' => 'a.btns', // all buttons in grid view with href attribute

    'options' => ['class' => 'header-success'],
    'pjaxContainer' => '#grid-account-pjax',
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
        //'filterModel' => $searchModel,
               'rowOptions' => function ($model) {
        if ($model->status == 9) {
            return ['class' => 'danger'];
        }
    },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'job_card_number',
            [
             'attribute' => 'customer_id',
             'value' => 'customerName.customer_names',
             ],
            //'phone_number',
            // 'customer_instruction',
            //'job_being_carried_out',
           
            //'description',
            [
                'attribute'=>'cost',
                'format'=>'raw',
                'value'=>function($model){
                    return 'Ksh'.' '. $model->cost;
                }
            ],
             [
                'attribute'=>'tax_amount',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->tax_amount. '%';
                }
            ],

            [
                'attribute'=>'sales_tax',
                'format'=>'raw',
                'value'=>function($model){
                    return 'Ksh'.' '. $model->sales_tax;
                }
            ],

              [
                'attribute'=>'total_amount',
                'format'=>'raw',
                'value'=>function($model){
                    return 'Ksh'.' '. $model->total_amount;
                }
            ],

             [
                'attribute'=>'payment',
                'format'=>'raw',
                'header'=> 'Amount Paid',
                'value'=>function($model){
                    return 'Ksh'.' '. $model->payment;
                }
            ],
            [
                'attribute'=>'balance',
                'format'=>'raw',
                'value'=>function($model){
                    return 'Ksh'.' '. $model->balance;
                }
            ],


            [
             
             
              

                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => "{payment}&nbsp;{view}&nbsp;{print}",
                'buttons' => [
                    
                   'payment' => function ($url, $model) {
                     if($model->status != 9){
                    if($model->balance > 0){
                    return Html::a('Payment', Url::to(['payments', 'id' => $model->id]), [
                                'class' => 'btn btn-warning btn-xs btns',
                                //'title'=>'Update',

                    ]);
                }
            }
                  
                },
                  'view' => function ($url, $model) {
                  
                    return Html::a('view', Url::to(['preview', 'id' => $model->id]), [
                                'class' => ' btn btn-primary btn-xs btn',
                                //'title'=>'Update',

                    ]);
                  
                },
                   'print' => function ($url, $model) {
                  
                    return Html::a('<i class="fa fa-print" aria-hidden="true"> Print</i>', Url::to(['print', 'id' => $model->id]), [
                                'class' => 'btn-pdfprint btn btn-danger btn-xs btn',
                                //'title'=>'Update',


                    ]);
                  
                },
              
 
                ],
              
              ],


        ],
    ]); ?>


</div>
