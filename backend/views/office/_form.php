<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Office */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="office-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12">
      <?= $form->field($model, 'office_name')->textInput(['maxlength' => true]) ?>
</div>
   <div class="col-md-12">
     <?= $form->field($model, 'office_location')->textInput(['maxlength' => true]) ?>
</div>
</div>
<div class="row">
    <div class="col-md-5">
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
</div>
</div>
  

    <?php ActiveForm::end(); ?>

</div>
