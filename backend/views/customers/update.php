<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Customers */

// $this->title = 'Update Customers: ' . $model->customer_id;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->customer_id, 'url' => ['view', 'id' => $model->customer_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="customers-update">

 

    <?= $this->render('_form', [
        'model' => $model,
         'modelsMail' => (empty($modelsMail)) ? [new MailingList] : $modelsMail
    ]) ?>

</div>
