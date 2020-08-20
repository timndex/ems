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


// foreach ($modelPrefoma as $value) {
	$customer_id = $model->customer_id;
  $total_cost = $model->cost;
	$total_amount = $model->total_amount;
	$tax_amount = $model->tax_amount;
	$created_at = date('d/m/Y', strtotime($model->created_at));
  $sales_tax = $model->sales_tax;
  $balance = $model->balance;
  $payment = $model->payment;
// }
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

$this->title = 'Cash Sale';
$this->params['breadcrumbs'][] = ['label' => 'Cash Sales', 'url' => ['index']];


?>

<div class="preview-form">
    <div class="block">
   <div class="block-title">
        <h2>Prefoma Invoice <strong>Information</strong></h2>
     </div>
     <div >
  	<h5><strong>Customer Name: <?=$customer_name?></strong><strong style="float: right;">Date : <?=$created_at?></strong></h5>
  		
  </div>
 

  <h5><strong>Purchases</strong></h5>
<table id="dataTables" style="width:100%"  class="table table-striped table-bordered">
        <thead>
            <tr>
            	
                <th>QUANTITY</th>
                <th>SERVICE TYPE</th>
                <th>DESCRIPTION</th>
                <th>@(each)</th>
                <th>DISCOUNT</th>
                <th>COST</th>
            </tr>
        </thead>
        <tbody>
        	
       <?php
foreach ($modelCashSaleSub  as  $value) {
	$quantitys = $value['quantity'];
	$service_type = $value['service_type'];
	$descriptions = $value['description'];
	$cost = $value['cost_qty'];
   $discount = $value['discount'];

  ?>
  <tr>
  <td><?=$quantitys;?></td>
  <td><?=$service_type;?></td>
  <td><?=$descriptions;?></td>
  <td><?=$cost;?></td>
  <td><?=$discount.' '.'%';?></td>
  <td style="text-align:right;">
    <?php 
     $newdiscount = ($cost * $discount) /100;
     $newcost_qty = $cost - $newdiscount;
     $newvalue = $newcost_qty * $quantitys;
     echo "Ksh"." ".number_format($newvalue);
                   ?>
                </td>
</tr>

<?php
}
  ?>

</tbody>
<tfoot>
   <tr>
     <tr>
                <th colspan="5" style="text-align:right;">Total:</th>
                <th style="border:1px solid black; text-align:right; "><?= "Ksh"." ".$total_cost; ?></th>
            </tr>
           <tr> 
         <th colspan="5" style="text-align:right;">VAT <?= $tax_amount. "%"; ?>:</th>
          <th style="border:1px solid black; text-align:right"><?= "Ksh"." ".$sales_tax; ?></th>
            </tr>
            <tr>
                <th colspan="5" style="text-align:right">Total Amount:</th>
                <th style="border:1px solid black; text-align:right"><?= "Ksh"." ".$total_amount; ?></th>
            </tr>
               <tr>
                <th colspan="5" style="text-align:right">Total Payment:</th>
                <th style="border:1px solid black; text-align:right"><?= "Ksh"." ".$payment; ?></th>
            </tr>
                <tr>
                <th colspan="5" style="text-align:right">Balance:</th>
                <th style="border:1px solid black; text-align:right"><?= "Ksh"." ".$balance; ?></th>
            </tr>
          </tfoot>
</table>
</div>
</div>
