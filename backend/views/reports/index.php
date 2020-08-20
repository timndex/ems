<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JobCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-card-index">

   <h4 style="border-bottom:1px dotted black; width: 70px;"><?= Html::encode($this->title) ?></h4>

    

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'customer_id',
            'customerName.customer_names',

            [
             
             
              

                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => "{view}&nbsp;{remove}",
                'buttons' => [
 
                'view' => function ($url, $model) {
                  
                    return Html::a('VIEW', Url::to(['view', 'id' => $model->customer_id]), [
                                'class' => ' btn btn-primary btn-xs btns',
                                //'title'=>'Update',

                    ]);
                  
                },
              
 
                ],
              
              ],

        ],
    ]); ?>


</div>
