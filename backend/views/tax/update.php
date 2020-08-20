<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tax */

$this->title = 'Update Tax: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Taxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tax-update">

 

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
