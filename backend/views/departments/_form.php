<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Departments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="departments-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12">
    <?= $form->field($model, 'departments_name')->textInput(['maxlength' => true]) ?>
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
