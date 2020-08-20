<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use borales\extensions\phoneInput\PhoneInput;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Customers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customers-form">

       <?php $form = ActiveForm::begin(['id'=>'dynamic-form']); ?>  

   <div class="row">
    <div class="col-md-12">
    <?= $form->field($model, 'customer_names')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
     <div class="col-md-6">   
         <?= $form->field($model, 'customer_phone')->widget(PhoneInput::className(), [
            'jsOptions' => [
                'preferredCountries' => ['KE', 'UG', 'RW'],
            ]])->label('Phone Number')?>
    </div>
    <div class="col-md-6">
     <?= $form->field($model, 'customer_contact_number')->widget(PhoneInput::className(), [
    'jsOptions' => [
        'preferredCountries' => ['KE', 'UG', 'RW'],
    ]])->label('Office Number');?>
    </div>
</div>
<div class="row">
     <div class="panel panel-default">
        <?php DynamicFormwidget::begin([
            'widgetContainer'=> 'dynamicform_wrapper',
            'widgetBody'=>'.container-items',
            'widgetItem'=>'.item',
            'min'=>1,
            'insertButton'=>'.add-item',
            'deleteButton'=>'.remove-item',
            'model'=>$modelsMail[0],
            'formId'=>'dynamic-form',
            'formFields'=>[
                'email',
            ],
        ]);
?>
<h4>Mailing List <span style="color:red; font-size: 11px;">(optional)</span></h4>
<table class="table table-bordered " id='tableId'>
    <thead>
        <tr>
             <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
<tbody class="container-items" >  
    <?php foreach ($modelsMail  as $i => $modelsMails):?> 
        <tr class="item panel panel-default" >

            <?php
            if(!$modelsMails->isNewRecord){
                echo Html::activeHiddenInput($modelsMails,"[{$i}]id");
            }
            ?>
                <td >
           <?= $form->field($modelsMails, "[{$i}]email")->textInput(['maxlength' => true, 'style'=>'border:none; height:20px;','class'=>'form-control quantity'])->label(false)?>
            </td> 
            <td >
            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button> 
           </td>

         
 </tr>


<?php 
endforeach;
?>
</tbody>
<tfoot>
    <tr>
        <td colspan="1"></td>
        <td><button type="button" class="add-item btn btn-primary btn-xs"><i class="fa fa-plus"></i>Add Row</button> </td>
    </tr>
</tfoot>
</table>
<?php DynamicFormwidget::end();?>
</div>

 <div class= 'pull-right'>
      <div class="form-group">
    <div class="col-md-5">
        <?= Html::submitButton($modelsMails->isNewRecord ? 'Save':'Update',['class'=>'btn btn-success btn-sm']) ?>
    </div>
    </div>

</div>
</div>



    <?php ActiveForm::end(); ?>

</div>




