<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CreditNote */

$this->title = 'Create Credit Note';
$this->params['breadcrumbs'][] = ['label' => 'Credit Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-note-create">


    <?= $this->render('_form', [
        'model' => $model,
         'modelsSubcategory'=>$modelsSubcategory,
    ]) ?>

</div>
