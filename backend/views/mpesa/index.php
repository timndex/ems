<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use kartik\icons\Icon;


/* @var $this yii\web\View */
/* @var $searchModel app\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mpesa Statements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">
<!-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"> -->

 <h4 style="border-bottom:1px dotted black; width: 170px;"><?= Html::encode($this->title) ?></h4>



    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
                          
                'TransactionType',
                 'TransID', 
        		 'TransTime',
                 'TransAmount',
                 'MSISDN',
                 'FirstName',
                 'MiddleName',
                 'LastName',
        ],
    ]); ?>


</div>
