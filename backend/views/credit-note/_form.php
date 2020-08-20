<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Customers;
use yii\helpers\Url;
use kartik\select2\Select2;
use app\models\JobCard;
use kartik\depdrop\DepDrop;
use wbraganca\dynamicform\DynamicFormWidget;
/* @var $this yii\web\View */
/* @var $model app\models\CreditNote */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="credit-note-form">
   
    <?php $form = ActiveForm::begin(['id'=>'dynamic-form']); ?>  

        <div class="row">
        <div class="col-md-12">
              <?= $form->field($model, "credit_note_num")->textInput(['maxlength' => true, 'class'=>'form-control credit_note_num'])->label('CREDIT NUMBER REF')?>

    </div>
</div>

      <div class="row">
        <div class="col-md-6">
               <?= $form->field($model, "customer_id")->widget(Select2::className(), [
                'data' => ArrayHelper::map(Customers::find()->all(), 'customer_id', 'customer_names'),
                'options' => ['multiple' => false, 'placeholder' => 'Select Customer ...', 'class'=>'form-control customer_id'],])->label('CUSTOMER NAME');
              ?>

    </div>
    <div class="col-md-6">
                  <?= $form->field($model, "job_card_number")->widget(DepDrop::classname(), [
                    'options'        => [
                    'multiple' => false,
                    'class'=> 'form-control job_card_number',
                    'id'=>"id",
                    //'style'=>'height:20px',
                ],
                    'pluginOptions'=>[
                        'depends'=>[Html::getInputId($model, "customer_id")],
                        'initialize' => $model->isNewRecord ? false : true,
                        'placeholder'=>false,
                        'style'=>'border:none;',
                        'url'=>Url::to(['customerinvoice']),
                    ]
            ])->label('REF NUMBER'); 
            ?>
          
    </div>
</div>


 
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
                'quantity',
                'description',
                'cost_qty',
                'total_credit',
            ],
        ]);
?>
<table class="table table-bordered " id='tableId'>
    <thead>
        <tr>
             <th>Quantity</th>
            <th>Description</th>
            <th>Cost</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
    </thead>
<tbody class="container-items" >  
    <?php foreach ($modelsSubcategory  as $i => $modelsSubcategorys):?> 
        <tr class="item panel panel-default" >

            <?php
            if(!$modelsSubcategorys->isNewRecord){
                echo Html::activeHiddenInput($modelsSubcategorys,"[{$i}]id");
            }
            ?>
                <td style='width:130px'>
           <?= $form->field($modelsSubcategorys, "[{$i}]quantity")->textInput(['maxlength' => true, 'style'=>'border:none; height:20px;', 'type'=>'number', 'class'=>'form-control quantity'])->label(false)?>
            </td> 
     
            </td>
            <td style='width:300px'>
             <?= $form->field($modelsSubcategorys, "[{$i}]description")->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    //'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'options'        => [
                    // 'class'=>'form-control description',
                    'multiple' => false,
                    //'id'=>$i."--description",
                    //'style'=>'height:20px',
                ],
                    'pluginOptions'=>[
                        'depends'=>["id"],
                        'initialize' => $modelsSubcategorys->isNewRecord ? false : true,
                        'placeholder'=>false,
                        'style'=>'border:none;',
                        'url'=>Url::to(['description']),
                    ]
            ])->label(false); ?>  

            </td style='width:250px'>
               <td>
             <?= $form->field($modelsSubcategorys, "[{$i}]cost_qty")->textInput(['maxlength' => true, 'style'=>'border:none;', 'class'=>'form-control cost_qty', 'type'=>'number'])->label(false)?>
            </td>

             <td>
             <?= $form->field($modelsSubcategorys, "[{$i}]total_credit")->textInput(['maxlength' => true, 'style'=>'border:none;', 'class'=>'form-control total_credit', 'type'=>'number'])->label(false)?>
            </td>

            <td style='width:5px'>
            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button> 
           </td>

         
 </tr>


<?php 
endforeach;
?>
</tbody>
<tfoot>
    <tr>
        <td colspan="4"></td>
        <td><button type="button" class="add-item btn btn-primary btn-xs"><i class="fa fa-plus"></i>Add Row</button> </td>
    </tr>
</tfoot>
</table>
<?php DynamicFormwidget::end();?>
</div>



   <div class="row">
        <div class="col-md-4" style="float:right;">
        <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'id'=>'amount', 'readOnly'=>true, 'class'=>'form-control amount'])->label('Total Amount') ?>
    </div>

    </div>
   <div class="row">
 <div class= 'pull-left'>
      <div class="form-group">
    <div class="col-md-5">
        <?= Html::submitButton($modelsSubcategorys->isNewRecord ? 'Save Credit Note':'Update Credit Note',['class'=>'btn btn-success btn-sm']) ?>
    </div>
    </div>

</div>

    <?php ActiveForm::end(); ?>

</div>
</div>
<script type="text/javascript">


    
 $("#tableId").on("click", "button.remove-item", function(_event) {
  $(this).closest("tr").remove();
 evaluateTotal()
});

$('body').on('keyup', '.cost_qty', function() {
  evaluateTotal();
});


function evaluateTotal() {
  var total = 0;
  $('.total_credit').each(function(_i, e) {
    var val = parseFloat(e.value.replace(/,/g,''));
    if (!isNaN(val))
      total += val;
  });
  // var deposit_paids = 0;
  // var deposit_paid = $('#deposit_paid').val();
  //  if(deposit_paid == ''){
  //       $('#deposit_paid').val(0)
  //       }else{
  //          deposit_paid = $('#deposit_paid').val(); 
  //           var DepositNum = parseFloat(deposit_paid.replace(/,/g,'')); 
  //       }
       
  //$('#cost').val(total.toFixed(2));
   $('.amount').val(commaSeparateNumber(total));
   // var cost = $('#cost').val();
   // var CostNum = parseFloat(cost.replace(/,/g,''));
   // var balance =  CostNum - DepositNum;
   //  $('#balance').val(commaSeparateNumber(balance.toFixed(2)));
    //alert(balance);
}


 function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
    }
    return val;
  }
$(document).ready(function(){
$('.job_card_number').change(function(){
var description = $('.job_card_number').val();
console.log(description);
})
})


$("#tableId").on("keyup", ".cost_qty", function () {  //use event delegation
  var tableRow = $(this).closest("tr");  //from input find row
  var qty = Number(tableRow.find(".quantity").val());
  var one = Number(tableRow.find(".cost_qty").val());  //get first textbox
  var total = (one * qty);  //calculate total
  tableRow.find(".total_credit").val(total);  //set value
});


</script>