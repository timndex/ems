<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Customers;
use yii\helpers\Url;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\CreditNote */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="credit-note-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-8">
   <?= $form->field($model, 'customer_id')->widget(Select2::className(), [
    'data' => ArrayHelper::map(Customers::find()->all(), 'customer_id', 'customer_names'),
    'value'=>'customer_name',
    'options' => ['multiple' => false, 'placeholder' => 'Select Customer ...', 'disabled'=>true],
    
    ])?>
    </div>
        <div class="col-md-4">
        <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'disabled'=>true]) ?>
    </div>
</div>

    
    
    <?php ActiveForm::end(); ?>

</div>
