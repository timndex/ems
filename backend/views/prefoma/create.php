<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrefomaInvoice */

$this->title = 'Create Prefoma Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Prefoma Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prefoma-invoice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
