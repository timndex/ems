<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tax */

$this->title = 'Create Tax';
$this->params['breadcrumbs'][] = ['label' => 'Taxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tax-create">

  

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
