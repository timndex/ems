<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\icons\Icon;
use kartik\select2\Select2;
use dominus77\iconpicker\IconPicker;
use app\models\Departments;


/* @var $this yii\web\View */
/* @var $model app\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">
<?php $form = ActiveForm::begin(['id'=>'dynamic-form']); ?>
<div class="row">
    <div class="col-md-12">
    <?= $form->field($model, 'menu_name')->textInput(['maxlength' => true]) ?>
</div>
</div>
<div class="row">
<div class="col-md-5">
    <?= $form->field($model, 'menu_active_department')->widget(Select2::className(), [
    'data' => ArrayHelper::map(Departments::find()->where(['status'=>10])->all(), 'departments_name', 'departments_name'),
    'options' => ['multiple' => true, 'placeholder' => 'Select states ...'],
   
    ])?>

</div>
<div class="col-md-3">
    <?= $form->field($model, 'menu_icon')->widget(IconPicker::className(), []); ?>
</div>
<div class="col-md-3">
<?= $form->field($model, "menu_url")->textInput(['maxlength' => true]) ?>
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
            'model'=>$modelsSubcategory[0],
            'formId'=>'dynamic-form',
            'formFields'=>[
                'subcategory',
                'subcategory_url',
                'status',
            ],
        ]);

?>
<table class="table table-bordered table-striped" >
    <thead>
        <tr>
            <th>SubMenus</th>
            <th>Link</th>
            <th>Status</th>
        </tr>
    </thead>
<tbody class="container-items" >  
    <?php foreach ($modelsSubcategory  as $i => $modelsSubcategorys):?> 
        <tr class="item panel panel-default">
      <!--   <div class="panel-heading">
            <div class="pull-right">
                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
            </div>
            <div class="clearfix"></div>
        </div> -->
      
            <?php
            if(!$modelsSubcategorys->isNewRecord){
                echo Html::activeHiddenInput($modelsSubcategorys,"[{$i}]id");
            }
            ?>
            <td>
           <?= $form->field($modelsSubcategorys, "[{$i}]subcategory")->textInput(['maxlength' => true])->label(false)?>
            </td>  <td>
           <?= $form->field($modelsSubcategorys, "[{$i}]subcategory_url")->textInput(['maxlength' => true])->label(false)?>
            </td>
               <td>
             <?= $form->field($modelsSubcategorys, "[{$i}]status")->dropDownList(['10' => 'ACTIVE', '9'=>'INACTIVE',],
                 ['prompt'=>'Select To'],
               )->label(false)?>
            </td>
            <td>
            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button> 
           </td>

          
 </tr>


<?php 
endforeach;
?>
</tbody>
<tfoot>
    <tr>
        <td colspan="2"></td>
        <td><button type="button" class="add-item btn btn-primary btn-xs"><i class="fa fa-plus"></i>Add Row</button> </td>
    </tr>
</tfoot>
</table>
<?php DynamicFormwidget::end();?>
</div>
</div>

    <div class="row">
        <div class="pull-right">
    <div class="form-group">
    <div class="col-md-5">
        <?= Html::submitButton($modelsSubcategorys->isNewRecord ? 'Save':'Update',['class'=>'btn btn-success btn-ms']) ?>
    </div>
    </div>
</div>
</div>

    <?php ActiveForm::end(); ?>







</div>
