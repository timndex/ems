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
use app\models\PrefomaInvoiceSub;


	$customer_id = $modelPrefoma->customer_id;
	$total_amount = $modelPrefoma->total_amount;
	$tax_amount = $modelPrefoma->tax_amount;
	$prefoma_id = $modelPrefoma->id;
	$created_at = date('d/m/Y', strtotime($modelPrefoma->created_at));

$modelCustomer = Customers::find()->where(['customer_id'=>$customer_id])->all();
foreach ($modelCustomer as  $value) {
	$customer_name = $value['customer_names'];
}

$service_types = array();
$models = JobCardSub::find()->all();
if(!empty($models)){
  foreach ($models as  $value) {
     $service_type []= $value['service_type'];
     $service_types = array_unique($service_type);
     

 }   
$data = array_values($service_types);
}
// $this->params['breadcrumbs'][] = ['label' => 'Prefoma Invoices', 'url' => ['index']];
?>

<div class="preview-form">

    <?php $form = ActiveForm::begin(['id'=>'dynamic-form']);?>
    <div class="block">
   <div class="block-title">
        <h2>Prefoma Invoice <strong>Information</strong></h2>
     </div>
     <div >
  	<h5><strong>Customer Name: <?=$customer_name?></strong><strong style="float: right;">Date : <?=$created_at?></strong></h5>
  		
  </div>
 


<table id="dataTables" style="width:100%"  class="table table-striped table-bordered">
        <thead>
            <tr>
            	
                <th>QUANTITY</th>
                <th>SERVICE TYPE</th>
                <th>DESCRIPTION</th>
                <th>COST</th>
            </tr>
        </thead>
        <tbody>
        	
       <?php
foreach ($modelPrefomaSub  as  $value) {
	$quantitys = $value['quantity'];
	$service_type = $value['service_type'];
	$descriptions = $value['description'];
	$cost = $value['cost_qty'];

  ?>
  <tr>
  <td><?=$quantitys;?></td>
  <td><?=$service_type;?></td>
  <td><?=$descriptions;?></td>
  <td><?=$cost;?></td>
</tr>
<?php
}
  ?>

</tbody>
</table>
</div>
 <div class="block" style="margin-top: -30px;">
   <div class="block-title">
        <h2>Detailed Quatation</h2>
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
                'items',
                'description',
                'cost_qty',
                'totals'
            ],
        ]);
?>
<table class="table table-bordered " id='tableId'>
    <thead>
        <tr>
            <th>Quantity</th>
            <th>Items</th>
            <th>Description</th>
            <th>Cost</th>
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
            	<?= $form->field($modelsSubcategorys, "[{$i}]items")->widget(Select2::className(), [
                'data' => ArrayHelper::map(PrefomaInvoiceSub::find()->where(['prefoma_number'=>$prefoma_id])->all(), 'id', 'description'),
                'options' => ['multiple' => false, 'placeholder' => 'Select Item ...', 'class'=>'form-control items'],])->label(false);
              ?>
        </td>
          
             
            <td class="description">
             <?= $form->field($modelsSubcategorys, "[{$i}]description")->textInput(['maxlength' => true, 'style'=>'border:none; height:30px', 'id'=>'description', ])->label(false)?>
            </td>
               <td >
             <?= $form->field($modelsSubcategorys, "[{$i}]cost_qty")->textInput(['maxlength' => true, 'style'=>'border:none; height:30px', 'id'=>'cost_qty', 'class'=>'form-control cost_qty', 'type'=>'number'])->label(false)?>
            </td>
              <td class="cost_qty">
             <?= $form->field($modelsSubcategorys, "[{$i}]totals")->textInput(['maxlength' => true, 'style'=>'border:none; height:30px', 'id'=>'totals', 'class'=>'form-control totals', 'type'=>'number', 'readonly'=>true])->label(false)?>
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

<div class="block">
	<div class="row">
	 <div class="col-sm-5" style="float:right; width: 350px;">
	<?= $form->field($modelsSubcategoryamount, 'total')->textInput(['maxlength' => true,  'id'=>'cost', 'class'=>'form-control cost','readonly'=>true])->label('Total')?>
	</div>
	</div>

<div class="row">
    <div class="col-md-5">
    <div class="form-group">
       <?= Html::submitButton($modelsSubcategorys->isNewRecord ? 'Save Profoma ':'Update Profoma ',['class'=>'btn btn-warning btn-xs ']) ?>
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

 $("#tableId").on("click", "button.remove-item", function(_event) {
  $(this).closest("tr").remove();
 evaluateTotal()
});

function evaluateTotal() {
  var total = 0;
  $('.totals').each(function(_i, e) {
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
   $('.cost').val(commaSeparateNumber(total));
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


function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

 $("#tableId").on("click", "button.remove-item", function(_event) {
  $(this).closest("tr").remove();
  evaluateTotal()
});

$('body').on('keyup', '.quantity', function() {
  evaluateTotal();
});
$('body').on('keyup', '.cost_qty', function() {
  evaluateTotal();
});


$("#tableId").on("keyup", ".cost_qty", function () {  //use event delegation
  var tableRow = $(this).closest("tr");  //from input find row
  var qty = Number(tableRow.find(".quantity").val());
  var one = Number(tableRow.find(".cost_qty").val());  //get first textbox
  var total = one * qty;
  tableRow.find(".totals").val(total); 

});

</script>


