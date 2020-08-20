<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrefomaInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Prefoma Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prefoma-invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Prefoma Invoice', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'job_card_number',
            'customer_id',
            'cost',
            'deposit_paid',
            //'tax_amount',
            //'sales_tax',
            //'total_amount',
            //'balance',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            //'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
