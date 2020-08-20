<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Departments;
use app\models\Office;
use kartik\select2\Select2;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model app\models\Accounts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accounts-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
    <div class="col-md-5">
    <?= $form->field($model, 'Fullnames')->textInput(['maxlength' => true, 'readonly'=>true]) ?>
    </div>
        <div class="col-md-3">
        <?= $form->field($model, 'username')->textInput(['maxlength' => true, ]) ?>
    </div>
    <div class="col-md-4">
          <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly'=>true]) ?>
    </div>
</div>
    <div class="row">
     <div class="col-md-3">
           <?= $form->field($model, 'user_department')->widget(Select2::className(), [
    'data' => ArrayHelper::map(Departments::find()->all(), 'departments_name', 'departments_name'),
    'options' => ['multiple' => false, 'placeholder' => 'Select Department ...'],
    'pluginOptions' => [
            'disabled' => true
        ],
   
    ])?>
    </div>
      <div class="col-md-3">
           <?= $form->field($model, 'office_location')->widget(Select2::className(), [
    'data' => ArrayHelper::map(Office::find()->all(), 'office_location', 'office_location'),
    'options' => ['multiple' => false, 'placeholder' => 'Select Office ...', ],
     'pluginOptions' => [
            'disabled' => true
        ],
   
    ])?>
    </div>
     <div class="col-md-3">
        <?= $form->field($model, 'user_phone_no')->widget(PhoneInput::className(), [
    'jsOptions' => [
        'preferredCountries' => ['KE', 'UG', 'RW'], 
   ],
   'options'=>[
    'readonly'=>true,
   ]
]); ?>
    </div>
     <div class="col-md-3">
        <?= $form->field($model, "user_type")->dropDownList(['11' => 'USER', '20'=>'ADMIN',],
                 ['prompt'=>'Select To', 'readonly'=>true],
                //  'options'=>[
                // 'readonly'=>true,
               //]
               )?>
    </div>
    
    </div>
    <div class="row">
          <div class="col-md-12">
        <?= $form->field($model, 'password_hash')->passwordInput() ?>
    </div>
</div>

<div class="row">
            <div class="pull-right">
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
</div>
</div>
    <?php ActiveForm::end(); ?>

</div>
