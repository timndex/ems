<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use app\models\Customers;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\select2\Select2;
use borales\extensions\phoneInput\PhoneInput;
use kartik\number\NumberControl;
use app\models\JobCard;
use app\models\JobCardSub;
// use kartik\typeahead\TypeaheadBasic;
// use kartik\typeahead\Typeahead;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\Tax;

      

$service_types = array();
$models = JobCardSub::find()->all();
if(!empty($models)){
  foreach ($models as  $value) {
     $service_type []= $value['service_type'];
     $service_types = array_unique($service_type);
     

 }   
$data = array_values($service_types);
}


          

/* @var $this yii\web\View */
/* @var $model app\models\JobCard */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">

</style>

<div class="cash-sale-form">

    <?php $form = ActiveForm::begin(['id'=>'dynamic-form']); ?>
    <div class="block">
   <div class="block-title">
        <h2>Cash Sale <strong>Details</strong></h2>
     </div>
<div class="row">


    <div class="col-sm-12">
   <?= $form->field($model, 'customer_id')->widget(Select2::className(), [
    'data' => ArrayHelper::map(Customers::find()->all(), 'customer_id', 'customer_names'),
    'options' => ['multiple' => false, 'placeholder' => 'Select Customer ...',],
     'pluginOptions' => [
            'disabled' => true
        ],
   
    ])->label('Customer Name');

    ?>
         
</div>

 </div>
</div>
<div class="block">
       <div class="block-title">
        <h2><strong>Task</strong></h2>
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
                'service_type',
                'description',
                'cost_qty',
            ],
        ]);
?>
<table class="table table-bordered " id='tableId'>
    <thead>
        <tr>
            <th>Quantity</th>
            <th>Service Type</th>
            <th>Description</th>
            <th>Cost</th>
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
            <td>
           <?= $form->field($modelsSubcategorys, "[{$i}]quantity")->textInput(['maxlength' => true, 'style'=>'border:none; height:30px', 'type'=>'number', 'readonly'=>true])->label(false)?>
            </td>  
             <td>
             <?=$form->field($modelsSubcategorys, "[{$i}]service_type")->widget(AutoComplete::className(), [
                 'options' => [
                    'class' => 'form-control service_type',
                    'style'=>'border:none; height:20px',
                    'id' => 'service_type',
                    'disabled'=>true,
                ],
               // 'name' => 'description',    
                'clientOptions' => [
                    //'source' => $data, 
                    'autoFill'=>true,
                     'select' => new JsExpression("function( event, ui ) {
                        
                     $('.service_type').val(ui.item.id);//#City-state_name is the id of hiddenInput.
             
                 }")],
            ])->label(false);

                ?>
            </td>
            <td class="description">
             <?= $form->field($modelsSubcategorys, "[{$i}]description")->textInput(['maxlength' => true, 'style'=>'border:none; height:30px', 'id'=>'description', 'readonly'=>true])->label(false)?>
            </td>
               <td class="cost_qty">
             <?= $form->field($modelsSubcategorys, "[{$i}]cost_qty")->textInput(['maxlength' => true, 'style'=>'border:none; height:30px; text-align:right;', 'id'=>'cost_qty', 'class'=>'form-control cost_qty', 'type'=>'number','readonly'=>true])->label(false)?>
            </td>
            

         
 </tr>


<?php 
endforeach;
?>
</tbody>

</table>
<?php DynamicFormwidget::end();?>
</div>
</div>


<!-- Mode of Payment -->
<div class="block container" style="width:290px; margin-left: 820px; margin-top: -20px;"> 
<!-- Cost of work -->



<div class="row">
 <div class="col-sm-12" style="margin-top: 0px;">
<?= $form->field($model, 'cost')->textInput(['maxlength' => true,  'id'=>'cost', 'readonly'=>true, 'class'=>'form-control cost', 'style'=>'text-align:right;'])->label('SubTotal')?>
</div>
</div>




<div class="row">
 <div class="col-sm-12" style=" margin: auto; margin-top: 0px;">
<?= $form->field($model, 'sales_tax')->textInput(['maxlength' => true,  'id'=>'sales_tax' , 'readonly'=>true, 'class'=>'form-control sales_tax', 'style'=>'text-align:right;'])->label('Sales Tax')?>
</div>
</div>

<div class="row">
 <div class="col-sm-12" style="margin: auto; margin-top: 0px;">
<?= $form->field($model, 'total_amount')->textInput(['maxlength' => true,  'id'=>'total_amount' , 'readonly'=>true, 'class'=>'form-control total_amount', 'style'=>'text-align:right;'])->label('Total Sale')?>
</div>
</div>

<div class="row">
 <div class="col-sm-12" style=" margin-top:0px; ">
<?= $form->field($model, 'payment')->textInput(['maxlength' => true,  'id'=>'payment', 'style'=>'text-align:right;'])->label('Amount Paid')?>
</div>
</div>
<div class="row">
 <div class="col-sm-12" style="margin-top:0px; ">
<?= $form->field($model, 'balance')->textInput(['maxlength' => true,  'id'=>'balance', 'readonly'=>true, 'style'=>'text-align:right;'])?>
</div>
</div>


 <div class="row">
 <div class= 'pull-left'>
      <div class="form-group">
    <div class="col-md-5">
        <?= Html::submitButton($modelsSubcategorys->isNewRecord ? 'Save Job Card':'Print Receipt',['class'=>'btn btn-warning btn-sm']) ?>
    </div>
    </div>

</div>

    <?php
    $modelJobCard = JobCard::find()->where(['job_card_number'=>$model->job_card_number])->all();
    if($modelJobCard == null){
    ?>
 
    <div class="form-group">
<div class="col-md-4" hidden="" id="approve">
        <input type='button' class="btn btn-warning btn-sm" id="approve" value="Approve Work">
    </div>

</div>

<?php
}else{
    foreach ($modelJobCard as $value) {
        $status = $value['status'];
    }
if($status == 11){
    ?>
    <div class="form-group">
<div class="col-md-4">
        <input type='button' class="btn btn-warning btn-sm" id="approve" value="Approve Work">
    </div>

</div>

 <?php   
}
}
?>


</div>

</div>

    <?php ActiveForm::end(); ?>
</div>



<script type="text/javascript">

var json = <?php echo json_encode($data); ?>;
var options = {
    source: json,
    minLength: 1
};
$(document).on('keydown.autocomplete', 'input.service_type', function() {
    $(this).autocomplete(options);
});

$('#cashsale-mode_of_payment').prop('disabled', true);

$(document).ready(function(){
 $('.tax').change(function(){
     var tax = $('.tax').val();
      var cost = $('.cost').val();
      var cost_amount = parseFloat(cost.replace(/,/g,''));
      var payment = $('#payment').val();
      var DepositNum = parseFloat(payment.replace(/,/g,'')); 
      var sales_tax = (tax * cost_amount)/ 100;
      $('.sales_tax').val(sales_tax);
      var totalamount = sales_tax + cost_amount;
      var balance =  (sales_tax + cost_amount) - DepositNum;
      $('.total_amount').val(commaSeparateNumber(totalamount.toFixed(2)));
        $('#balance').val(commaSeparateNumber(balance.toFixed(2)));
});
})


//sum of dynamic table

 $("#tableId").on("click", "button.remove-item", function(_event) {
  $(this).closest("tr").remove();
  evaluateTotal()
});

$('body').on('keyup', 'input.cost_qty', function() {
  evaluateTotal();
});


function evaluateTotal() {
  var total = 0;
  $('input.cost_qty').each(function(_i, e) {
    var val = parseFloat(e.value.replace(/,/g,''));
    if (!isNaN(val))
      total += val;
  });
  var tax = $('.tax').val();

  var payment = $('#payment').val();
   if(payment == ''){
        $('#payment').val(0)
        }else{
           payment = $('#payment').val(); 
            var DepositNum = parseFloat(payment.replace(/,/g,'')); 
        }
  
  
   $('#cost').val(commaSeparateNumber(total));
   var cost = $('#cost').val();
   var CostNum = parseFloat(cost.replace(/,/g,''));
   var sales_tax = (tax * CostNum)/ 100;
   var totalamount = sales_tax + CostNum;
   $('.totalamount').val(commaSeparateNumber(totalamount.toFixed(2)));
   var balance =  (sales_tax + CostNum) - DepositNum;
    $('.sales_tax').val(sales_tax);
    $('.total_amount').val(commaSeparateNumber(totalamount.toFixed(2)));
    $('#balance').val(commaSeparateNumber(balance.toFixed(2)));
    
}

$(document).ready(function(){
    $('#payment').keyup(function(){
        var total_amount = $('#total_amount').val();
        var payment = $('#payment').val();
        var DepositNum = parseFloat(payment.replace(/,/g,'')); 
        var TotalAmount = parseFloat(total_amount.replace(/,/g,'')); 
        var balance =  TotalAmount - DepositNum;
        if( balance < 0){
        balance = 0
       } 
         $('#balance').val(commaSeparateNumber(balance));
})
})



$('#payment').keyup(function(event) {

  // skip for arrow keys
  if(event.which >= 37 && event.which <= 40){
   event.preventDefault();
  }

  $(this).val(function(index, value) {
      value = value.replace(/,/g,'');
      return numberWithCommas(value);
  });
});
 function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
    }
    return val;
  }


function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

// //allow only numbers entry
// $('#deposit_paid').keyup(function(e)
//                                 {
//   if (/\D/g.test(this.value))
//   {
//     // Filter non-digits from input value.
//     this.value = this.value.replace(/\D/g, '');
//   }
// });

 



</script>
