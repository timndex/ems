<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';

?>
<div class="site-login">
    <img src="../assets/proui/img/icon.jpg" width="150px" height="150px">
    <div class="row">
        <div class="col-lg-5">
           <!--  <div class="center"><span><strong><p>Cesscolina  </strong>EMS</p></span></div> -->
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder'=>'Enter username'])->label(false) ?>

                <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Enter Password'])->label(false)?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group clearfix">
                 
                    <?= Html::submitButton('Login', ['class' => 'btn  btn-primary btn-block pull-right', 'name' => 'login-button']) ?>
               
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
