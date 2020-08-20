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
/* @var $searchModel app\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

   <h4 style="border-bottom:1px dotted black; width: 80px;"><?= Html::encode($this->title) ?></h4>

   

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
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
            'customerName.customer_names',
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
                'header'=> 'Payments',
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

            //  [
            //     'attribute'=> 'mode_of_payment',
            //     'format'=>'raw',
            //     'header'=>'Payment',
            //     'value'=>function($model){
            //         $mode_of_payment = $model->mode_of_payment;
            //         if($mode_of_payment == 'profoma_invoice'){
            //             return "AWAITING";
            //         }
                    
            //     }
            // ],
          
             [
            //10- work in progress 
            //12 - completed 
            // 9 - canceled
            //13- arrived
        'attribute' => 'status', 
        'header'=>'Progress',
        'format'=>'raw',
          'value'=>function($model){
            if($model->status==11){
              return "<span class='label label-info'>WORK IN PROGRESS</span>";

            }if($model->status==12){
                return "<span class='label label-warning'>COMPLETED</span>";
            }
             if($model->status==9){
                return "<span class='label label-danger'>CANCELED</span>";
            }
            else{
                return 'null';
            }
           },
       
    ],

            [
             
             
              

                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => "{completed}&nbsp;{payment}&nbsp;{canceled}&nbsp{print}&nbsp{mail}",
                'buttons' => [
 
                'completed' => function ($url, $model) {
                  if($model->status == 11){
                    return Html::a('COMPLET', Url::to(['complete', 'id' => $model->id]), [
                                'class' => ' btn btn-primary btn-xs',
                                //'title'=>'Update',

                    ]);
                  
                }
            },
                  'payment' => function ($url, $model) {
                    if($model->balance > 0){
                        if($model->status == 11 || $model->status == 12 || $model->status != 9){
                    return Html::a('PAYMENT', Url::to(['payments', 'id' => $model->id]), [
                                'class' => ' btn btn-info btn-xs btns',
                                //'title'=>'Update',

                    ]);
                  
                }
            }
            },
                
                 'canceled' => function ($url, $model) {
                  if($model->status==11){
                    return Html::a('CANCEL', Url::to(['cancel', 'id' => $model->id]), [
                                'class' => ' btn btn-danger btn-xs ',
                                //'title'=>'Update',

                    ]);
               
                  
                }
            },

                   'print' => function ($url, $model) {
                  
                    return Html::a('<i class="fa fa-print" aria-hidden="true"> Print</i>', Url::to(['print', 'id' => $model->id]), [
                                'class' => 'btn-pdfprint btn btn-warning btn-xs',
                                //'title'=>'Update',

                    ]);
                  
                },
              
 
                ],
              
              ],
        ],
    ]); ?>


</div>
