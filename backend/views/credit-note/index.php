<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
use dixonstarter\pdfprint\Pdfprint;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CreditNoteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Credit Notes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-note-index">

     <h4 style="border-bottom:1px dotted black; width: 120px;"><?= Html::encode($this->title) ?></h4>
      
    <?php
    

    echo ModalAjax::widget([
        'id' => 'createcreditnote',
        'header' => 'Create Credit Note',
        'toggleButton' => [
            'label' => 'Create Credit Note',
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

      
<?php echo $this->render('_search', ['model' => $searchModel])?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'credit_note_id',
          'customerName.customer_names',
            //'job_card_number',
           // 'customerName.customer_names',
            //'description',
            
            [
                'attribute'=>'amount',
                // 'format'=>'raw',
                // 'value'=>function($model){
                //     return 'Ksh'.' '. number_format($model->amount);
                // }
            ],
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            //'status',

           [
             
             
              

                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => "{view}&nbsp;{print}",
                'buttons' => [
 
                'view' => function ($url, $model) {
                  
                    return Html::a('view', Url::to(['update', 'id' => $model->credit_note_id]), [
                                'class' => ' btn btn-primary btn-xs btns',
                                //'title'=>'Update',

                    ]);
                  
                },
                    'print' => function ($url, $model) {
                  
                    return Html::a('<i class="fa fa-print" aria-hidden="true"> Print', Url::to(['print', 'id' => $model->credit_note_id]), [
                                'class' => 'btn-pdfprint btn btn-success btn-xs btns',
                                //'title'=>'Update',

                    ]);
                  
                },
              
 
                ],
              
              ],
        ],
    ]); ?>


</div>
