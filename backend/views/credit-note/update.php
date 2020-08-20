<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CreditNote */

$this->title = 'Update Credit Note: ' . $model->credit_note_id;
$this->params['breadcrumbs'][] = ['label' => 'Credit Notes', 'url' => ['index']];

$this->params['breadcrumbs'][] = 'Update';
?>
<div class="credit-note-update">



    <?= $this->render('_form', [
        'model' => $model,
         'modelsSubcategory' => (empty($modelsSubcategory)) ? [new CreditNoteSub] : $modelsSubcategory
    ]) ?>

</div>
