<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashSale */

$this->title = 'Create Cash Sale';
$this->params['breadcrumbs'][] = ['label' => 'Cash Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-sale-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
         'model' => $model,
        'modelsSubcategory'=>$modelsSubcategory,
    ]) ?>

</div>
