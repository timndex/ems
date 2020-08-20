<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrefomaInvoice */

// $this->title = 'Update Prefoma Invoice: ' . $modelPrefoma->id;
$this->params['breadcrumbs'][] = ['label' => 'Prefoma Invoices', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];

?>
<div class="prefoma-invoice-update">

    <!-- -->

    <?= $this->render('preview', [
          'modelsSubcategory' => (empty($modelsSubcategory)) ? [new PrefomaInvoiceDetails] : $modelsSubcategory,
          'modelPrefoma' =>$modelPrefoma,
          'modelPrefomaSub'=>$modelPrefomaSub,
          'modelsSubcategoryamount'=>$modelsSubcategoryamount
    ]) ?>

</div>
