<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
use dixonstarter\pdfprint\Pdfprint;
use yii\widgets\Pjax;
use kartik\datetime\DateTimePicker;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
//use kartik\widgets\DatePicker;

//use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JobCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Job Cards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-card-index">

   <h4 style="border-bottom:1px dotted black; width: 80px;"><?= Html::encode($this->title) ?></h4>

    <p>
        <?= Html::a('Create Job Card', ['create'], ['class' => 'btn btn-success btn-xs']) ?>
    </p>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
   
 <?php //Pjax::begin(['id'=>'grid-data'])?>
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
                'header'=>'Total Cost',
                'value'=>function($model){
                    return 'Ksh'.' '. $model->cost;
                }
            ],
             [
                'attribute'=>'tax_amount',
                'format'=>'raw',
                'width'=>'5px',
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
                'attribute'=> 'transaction_type',
                'format'=>'raw',
                'header'=>'Transaction Type',
                'value'=>function($model){
                    $transaction_type = $model->transaction_type;
                    if($transaction_type == 'cash_sale'){
                        return "Cash";
                    }if($transaction_type == 'profoma_invoice'){
                        return 'Profoma Invoice';
                    }
                    
                }
            ],
           
            //'transaction_id',
            //'cheque_number',
            //'created_at',
            //'created_by',
            //'updated_at',

             
               

                [

                    'class' => 'kartik\grid\EditableColumn',

                    'attribute' => 'created_at',
                    'value'=>function($model){
                        return date('Y-m-d', strtotime($model->created_at));
                    },
                    'header'=>'Date',

                    'editableOptions' => [

                        'inputType' => \kartik\editable\Editable::INPUT_DATETIME,

                        'name' => 'created_at',

                       'options' => [
                        //'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                        'type'=>DateTimePicker::TYPE_INPUT,
                        //'autoWidget'=>false,
                        // 'displayFormat' => 'php:D, d-M-Y H:i:s A',
                        // 'saveFormat' => 'php:U',
                        //'displayFormat' => 'dd.MM.yyyy',
                        //'saveFormat' => 'php:Y-m-d',
                
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy:mm:dd H:i:s'
                            ]
                        
                    ]

                    ],

                ],

         

            //'updated_by',
            [
                'attribute'=>'status',
                'header'=>'Status',
                 'format'=>'raw',
                'value'=>function($model){
                     if($model->status==11){
                      return "<span class='label label-info'>WORK IN PROGRESS</span>";


                    }if($model->status==12){
                        return "<span class='label label-primary'>COMPLETE</span>";
                    }
                    if($model->status==13){
                        return "<span class='label label-success'>PICKED</span>";
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
                'template' => "{update}&nbsp;{print}&nbsp;{canceled}&nbsp;{complet}&nbsp;{picked}",
                'buttons' => [
 
                'update' => function ($url, $model) {
                    if($model->status == 11 || $model->status == 12){
                    return Html::a('EDIT', Url::to(['update', 'id' => $model->id]), [
                                'class' => ' btn btn-primary btn-xs btns',
                                //'title'=>'Update',

                    ]);
                  }
                },
                   'print' => function ($url, $model) {
                  
                    return Html::a('<i class="fa fa-print" aria-hidden="true"> Print</i>', Url::to(['print', 'id' => $model->id]), [
                                'class' => 'btn-pdfprint btn btn-warning btn-xs btns',
                                //'title'=>'Update',

                    ]);
                  
                },
                  'canceled' => function ($url, $model) {
                    if($model->status == 11){
                  //if($model->balance >0){
                    return Html::a('CANCEL', Url::to(['cancel', 'id' => $model->id]), [
                                'class' => ' btn btn-danger btn-xs ',
                                //'title'=>'Update',

                    ]);
               
                  
                }
           // }
            },
              'complet' => function ($url, $model) {
                    // if($model->transaction_type == 'cash_sale'){
                    if($model->status == 11){
                    return Html::a('COMPLETE', Url::to(['complete', 'id' => $model->id]), [
                                'class' => ' btn btn-success btn-xs ',
                                //'title'=>'Update',

                    ]);
               
                  
                
            }
        //}
            },
              'picked' => function ($url, $model) {
                   if($model->balance ==0){
                    if($model->status == 12){
                    return Html::a('PICKED', Url::to(['picked', 'id' => $model->id]), [
                                'class' => ' btn btn-info btn-xs ',
                                //'title'=>'Update',

                    ]);
               
                  
                }
            }
            },
              
 
                ],
              
              ],


        ],
    ]); ?>

<?php //Pjax::end()?>
</div>

<script type="text/javascript">
    
</script>