<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
use dixonstarter\pdfprint\Pdfprint;
use app\models\PrefomaInvoiceDetails;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JobCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Prefoma Invoice';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prefoma-invoice-index">

   <h4 style="border-bottom:1px dotted black; width: 150px;"><?= Html::encode($this->title) ?></h4>

   <?php

    echo ModalAjax::widget([
    'id' => 'update',
    'selector' => 'a.btnmail', // all buttons in grid view with href attribute
    'header' => 'Create Company',

    'options' => ['class' => 'header-primary'],
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
        if ($model->approval == 9) {
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
                'header'=> 'Amount Due',
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
                //11- awiting approval 
                //12 - picked
                //13 - payent complete
                // 14 approved prefoma
               
                'attribute' => 'approval', 
                'header' =>'Aproval Status',
                'format'=>'raw',
                  'value'=>function($model){
                    if($model->approval==11){
                      return "<span class='label label-info'>AWAITING APPROVAL</span>";

                    }else if($model->approval==14){
                        return "<span class='label label-warning'>APPROVED</span>";
                   
                    }
                    else if($model->approval==9){
                        return "<span class='label label-danger'>CANCELED</span>";
                   
                    }else{
                        return 'null';
                    }
                   },
       
    ],

            [

                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => "{update}&nbsp;{approve}&nbsp{print}&nbsp{mail}&nbsp;{canceled}",
                'buttons' => [
 
                'update' => function ($url, $model) {
                  $modelPrefomaInvoiceDetails = PrefomaInvoiceDetails::find()->where(['prefoma_number'=>$model->id])->all();
                  if($model->approval ==11){
                  if(!empty($modelPrefomaInvoiceDetails)){
                    return Html::a('EDIT', Url::to(['update', 'id' => $model->id]), [
                                'class' => ' btn btn-primary btn-xs btns',
                                //'title'=>'Update',

                    ]);
                 }
                else if(empty($modelPrefomaInvoiceDetails)){
                     return Html::a('EDIT', Url::to(['createquotation', 'id' => $model->id]), [
                                'class' => ' btn btn-primary btn-xs btns',
                                //'title'=>'Update',

                    ]);
                 }
             }
                  
                 },
                 'approve' => function ($url, $model) {
                  if($model->approval == 11){
                    return Html::a('Approve', Url::to(['approve', 'id' => $model->id]), [
                                'class' => ' btn btn-success btn-xs btns',
                                //'title'=>'Update',

                    ]);
                }else{

                }
                  
                },
                
                'mail' => function ($url, $model) {
                    if($model->approval == 11 ){
                        if($model->approval != 14){
                            if($model->approval != 9){
                  
                    return Html::a('<i class="fa fa-envelope"> Mail </i>', Url::to(['mail', 'id' => $model->id]), [
                                'class' => ' btn btn-info btn-xs btnmail',
                                //'title'=>'Update',

                    ]);
                }
                }
                    }
                },
                   'print' => function ($url, $model) {
                  
                    return Html::a('<i class="fa fa-print" aria-hidden="true"> Print</i>', Url::to(['print', 'id' => $model->id]), [
                                'class' => 'btn-pdfprint btn btn-warning btn-xs btns',
                                //'title'=>'Update',

                    ]);
                  
                },

                 'canceled' => function ($url, $model) {
                  if($model->approval == 11){
                    return Html::a('CANCEL', Url::to(['cancel', 'id' => $model->id]), [
                                'class' => ' btn btn-danger btn-xs ',
                                //'title'=>'Update',

                    ]);
               
                  
                }
            },
           
            
              
 
                ],
              
              ],



        ],
    ]); ?>


</div>

