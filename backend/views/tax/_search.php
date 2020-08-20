<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TaxSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tax-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

  <div class="row">
        <div class="col-sm-6">
    <?= $form->field($model, 'tax_name')->textInput(['maxlength' => true, 'placeholder'=>'Filter by Tax Name...'])->label(false) ?>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
       
    </div>
</div>
</div>

    <?php ActiveForm::end(); ?>

</div>
<