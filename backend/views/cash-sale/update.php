<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashSale */

$this->title = 'Update Cash Sale: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cash Sales', 'url' => ['index']];

?>
<div class="cash-sale-update">

    

    <?= $this->render('_form', [
        'model' => $model,
         'modelsSubcategory' => (empty($modelsSubcategory)) ? [new CashSaleSub] : $modelsSubcategory
    ]) ?>

</div>
