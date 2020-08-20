<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Office */

$this->title = 'Update Office: ' . $model->office_id;
$this->params['breadcrumbs'][] = ['label' => 'Offices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->office_id, 'url' => ['view', 'id' => $model->office_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="office-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
