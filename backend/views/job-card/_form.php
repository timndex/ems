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
use app\models\Discount;

      

$service_types = array();
$models = JobCardSub::find()->all();
if(!empty($models)){
  foreach ($models as  $value) {
     $service_type []= $value['service_type'];
     $service_types = array_unique($service_type);
     

 }   
$data = array_values($service_types);
}else {
    $data =[];
}


          

/* @var $this yii\web\View */
/* @var $model app\models\JobCard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="job-card-form">

    <?php $form = ActiveForm::begin(['id'=>'dynamic-form']); ?>
    <div class="block">
   <div class="block-title">
        <h2>Job Card <strong>Information</strong></h2>
     </div>
<div class="row">
    <div class="col-sm-6">
   <?= $form->field($model, 'customer_id')->widget(Select2::className(), [
    'data' => ArrayHelper::map(Customers::find()->all(), 'customer_id', 'customer_names'),
    'options' => ['multiple' => false, 'placeholder' => 'Select Customer ...'],
   
    ])->label('Customer Name');
    ?>     
</div>
<div class="col-sm-6">
 <?= $form->field($model, 'job_card_number')->textInput(['maxlength' => true, 'placeholder'=>'Enter Job Card Number...'])->label()?>
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
                'discount',
                'totals'
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
            <th>Discount %</th>
            <th>Totals</th>
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
            <td>
           <?= $form->field($modelsSubcategorys, "[{$i}]quantity")->textInput(['maxlength' => true, 'style'=>'border:none; height:30px', 'type'=>'number', 'class'=>'form-control quantity'])->label(false)?>
            </td>  
             <td>
             <?=$form->field($modelsSubcategorys, "[{$i}]service_type")->widget(AutoComplete::className(), [
                 'options' => [
                    'class' => 'form-control service_type',
                    'style'=>'border:none; height:20px',
                    'id' => 'service_type',

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
             <?= $form->field($modelsSubcategorys, "[{$i}]description")->textInput(['maxlength' => true, 'style'=>'border:none; height:30px', 'id'=>'description', ])->label(false)?>
            </td>
               <td class="cost_qtys">
             <?= $form->field($modelsSubcategorys, "[{$i}]cost_qty")->textInput(['maxlength' => true, 'style'=>'border:none; height:30px', 'id'=>'cost_qty', 'class'=>'form-control cost_qty', 'type'=>'number', 'for'=>1])->label(false)?>
            </td>
              <td class="discounts">
                 <?= $form->field($modelsSubcategorys, "[{$i}]discount")->dropDownList(
                     ArrayHelper::map(Discount::find()->all(), 'discount', 'discount'),
                   ['prompt'=>'Select Discount Amount..', 'options'=>['id'=>'discount'],'class'=>'form-control discount'],)->label(false);?>
            </td>
              <td class="total">
             <?= $form->field($modelsSubcategorys, "[{$i}]totals")->textInput(['maxlength' => true, 'style'=>'border:none; height:30px', 'id'=>'totals', 'class'=>'form-control totals'])->label(false)?>
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
        <td colspan="5"></td>
        <td><button type="button" class="add-item btn btn-primary btn-xs"><i class="fa fa-plus"></i>Add Row</button> </td>
    </tr>
</tfoot>
</table>
<?php DynamicFormwidget::end();?>
</div>
</div>


<!-- Mode of Payment -->
<div class="block">
    <div class="block-title">
 <h2>Expense</h2>
</div>
 <div class="row">
     <div class="col-sm-5">
     <?= $form->field($model, 'transaction_type')->dropDownList([
        'cash_sale' => 'Cash Sales', 
        'profoma_invoice'=>'Profam Invoice',
       // 'Mpesa'=>'Mpesa',
      //'23' => 'ATM', 
    ],
                 ['prompt'=>'Select repair type..', 'options'=>['id'=>'transaction_type'],],
                             
               );?>
</div>
</div>
 
<!-- Cost of work -->
<div class="row">
 <div class="col-sm-5">
 <?= $form->field($model, 'tax_amount')->widget(Select2::className(), [
                'data' => ArrayHelper::map(Tax::find()->all(), 'tax_amount', 'tax_amount'),
                'options' => ['multiple' => false, 
                 'placeholder' => 'Select Tax Amount...', 
                 'class'=>'form-control tax',
                     ],
                ])->label('VAT %');
              ?>
</div>
</div>



<div class="row">
 <div class="col-sm-5" style="float:right; margin-top: -140px;">
<?= $form->field($model, 'cost')->textInput(['maxlength' => true,  'id'=>'cost', 'readonly'=>true, 'class'=>'form-control cost'])->label('Total')?>
</div>
</div>




<div class="row">
 <div class="col-sm-5" style="float:right; margin: auto; margin-top: -73px;">
<?= $form->field($model, 'sales_tax')->textInput(['maxlength' => true,  'id'=>'sales_tax' , 'readonly'=>true, 'class'=>'form-control sales_tax'])->label('VAT Amount')?>
</div>
</div>

<div class="row">
 <div class="col-sm-5" style="float:right; margin: auto; margin-top: 13px;">
<?= $form->field($model, 'total_amount')->textInput(['maxlength' => true,  'id'=>'total_amount' , 'readonly'=>true, 'class'=>'form-control total_amount'])->label('Total')?>
</div>
</div>

<div class="row">
 <div class="col-sm-5" style="float:right; margin-top:10px; " hidden="hidden">
<?= $form->field($model, 'payment')->textInput(['maxlength' => true,  'id'=>'payment'])->label('Payment')?>
</div>
</div>
<div class="row">
 <div class="col-sm-5" style="float:right; margin-top:20px;" hidden="hidden">
<?= $form->field($model, 'balance')->textInput(['maxlength' => true,  'id'=>'balance', 'readonly'=>true])?>
</div>
</div>


 <div class="row">
 <div class= 'pull-left'>
      <div class="form-group">
    <div class="col-md-5">
        <?= Html::submitButton($modelsSubcategorys->isNewRecord ? 'Save Job Card':'Update Job Card',['class'=>'btn btn-success btn-sm']) ?>
    </div>
    </div>

</div>


  <div class="form-group" >
<div class="col-md-4" hidden="" id="print" style="margin: -15px;">

        <input type='button' class="btn btn-primary btn-sm" id="print" value=" Print" class="fa fa-print">
    </div>

</div>
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



$(document).ready(function(){
 $('.tax').change(function(){
     var tax = $(this). children("option:selected").val()
      var cost = $('.cost').val();
      var cost_amount = parseFloat(cost.replace(/,/g,''));
     // var payment = $('#payment').val();
      //var DepositNum = parseFloat(payment.replace(/,/g,'')); 
      var sales_tax = (tax * cost_amount)/ 100;
      $('.sales_tax').val(sales_tax);
      var totalamount = sales_tax + cost_amount;
      //var balance =  (sales_tax + cost_amount) - DepositNum;
      $('.total_amount').val(commaSeparateNumber(totalamount.toFixed(2)));
       // $('#balance').val(commaSeparateNumber(balance.toFixed(2)));
});
})

// $(document).ready(function(){
//  $('.discount').change(function(){
//      var discount = $('.discount').val();
//       var cost_qty = $('.cost_qty').val();
//       var amount = (discount * cost_qty)/ 100;
//       var total_amount = cost_qty - amount;
//         $('.totals').val(total_amount);
// });
// })


//sum of dynamic table

 $("#tableId").on("click", "button.remove-item", function(_event) {
  $(this).closest("tr").remove();
  evaluateTotal()
});

$('body').on('change', '.discount', function() {
  evaluateTotal();
});

$('body').on('change', '.cost_qty', function() {
  evaluateTotal();
});
$('body').on('change', '.quantity', function() {
  evaluateTotal();
});


// $('body').on('change', 'select.discount', function() {
//     discountAmount();
// });

// function discountAmount(){
//     var total = 0;
//     $('select.discount').each(function(value){
//         var discount = $(value).val();

//     });
//     $('input.cost_qty').each(function(){
//         var cost_qty = $(value).val();
//     });
//     var amount = (discount * cost_qty)/ 100;
//      var total_amount = cost_qty - amount;
//      $('input.totals').each(function(){
//         $(value).val(total_amount);
//      });
        
// }

// $('#tableId').on('change', 'select.discount', function() {
//   getTotalCost();
// });


// function calculateRow() {

//     $('input.cost_qty').each(function(_i, e) {
//         var cost_amount = parseFloat(e.value.replace(/,/g,''));
//     })
//      $('select.discount').each(function(_i, e) {
//         var discount_amount = parseFloat(e.value.replace(/,/g,''));
//     })
    
//     var discount_value = (discount_amount * cost_amount) / 100;

//     // changed the following line to only look within the current row
//     var cost = cost_amount - discount_value ;
//     console.log(cost);
//     $('input.totals').each(function() {
//      $('.totals').val(cost);
//     })
 
//     calculateSum();
// }

// function getTotalCost() {
//     var total = 0;
//    $('option.discount').each(function(_i, e) {
//     var discount_amount = parseFloat(e.value.replace(/,/g,''));
//   });
//      $('input.cost_qty').each(function(_i, e) {
//     var cost_amount = parseFloat(e.value.replace(/,/g,''));
//     if (!isNaN(val))
//       total += val;
//   });
//   var totNumber = (discount * cost_qty) / 100;
//   var tot = cost_qty - totNumber;
//   $('.totals').val(tot);
//   //calculateSubTotal();
// }

$("#tableId").on("change", ".discount", function () {  //use event delegation
  var tableRow = $(this).closest("tr");  //from input find row
  var qty = Number(tableRow.find(".quantity").val());
  var one = Number(tableRow.find(".cost_qty").val());  //get first textbox
  var two = tableRow.find(".discount").val();  //get second textbox
  var total_discount = (one * two) / 100;  //calculate total
  var discount = one - total_discount;
  var total = discount * qty;
  tableRow.find(".totals").val(total);  //set value
});

$("#tableId").on("change", ".quantity", function () {  //use event delegation
  var tableRow = $(this).closest("tr");  //from input find row
  var qty = Number(tableRow.find(".quantity").val());
  var one = Number(tableRow.find(".cost_qty").val());  //get first textbox
  var two = tableRow.find(".discount").val();  //get second textbox
  var total_discount = (one * two) / 100;  //calculate total
  var discount = one - total_discount;
  var total = discount * qty;
  tableRow.find(".totals").val(total);  //set value
});

$("#tableId").on("change", ".cost_qty", function () {  //use event delegation
  var tableRow = $(this).closest("tr");  //from input find row
  var qty = Number(tableRow.find(".quantity").val());
  var one = Number(tableRow.find(".cost_qty").val());  //get first textbox
  var two = tableRow.find(".discount").val();  //get second textbox
  var total_discount = (one * two) / 100;  //calculate total
  var discount = one - total_discount;
  var total = discount * qty;
  tableRow.find(".totals").val(total);  //set value
});





function evaluateTotal() {
  var total = 0;
  $('.totals').each(function(_i, e) {
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
        var cost  = $('#cost').val();
        var tax = $('.tax').val();
        var payment = $('#payment').val();
        var DepositNum = parseFloat(payment.replace(/,/g,'')); 
        var CostNum = parseFloat(cost.replace(/,/g,''));
        var sales_tax = (tax * CostNum)/ 100;
        var balance =  (sales_tax + CostNum) - DepositNum;
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




</script>
