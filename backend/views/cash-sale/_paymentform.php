<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use app\models\Customers;
// use kartik\depdrop\DepDrop;
// use yii\helpers\Url;
use kartik\select2\Select2;
// use borales\extensions\phoneInput\PhoneInput;
// use kartik\number\NumberControl;
// use app\models\JobCard;
// use app\models\JobCardSub;
// use yii\jui\AutoComplete;
// use yii\web\JsExpression;
// use app\models\Tax;        

/* @var $this yii\web\View */
/* @var $model app\models\JobCard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-sale-paymentform">

    <?php $form = ActiveForm::begin(); ?>
   

<div class="row">
    <div class="col-sm-12">   
<?= $form->field($modelSale, 'transaction_number')->textInput(['maxlength' => true, 'id'=>'transaction_number'])->label()?>
</div>
</div>

<div class="row">
    <div class="col-sm-12">
   <?= $form->field($model, 'customer_id')->widget(Select2::className(), [
    'data' => ArrayHelper::map(Customers::find()->all(), 'customer_id', 'customer_names'),
    'options' => ['multiple' => false, 'placeholder' => 'Select Customer ...', 'class'=>'form-control customer_id'],
     'pluginOptions' => [
            'disabled' => true
        ],
   
    ])->label('Customer Name');

    ?>
         
</div>

 </div>
<div class="row">

 <div class="col-sm-6" style="margin: auto; margin-top: 0px;">
<?= $form->field($model, 'total_amount')->textInput(['maxlength' => true,  'id'=>'total_amount' , 'readonly'=>true, 'class'=>'form-control total_amount', 'style'=>'text-align:right;'])->label('Total Sale')?>
</div>
 <div class="col-sm-6" style="margin-top:0px; ">
<?= $form->field($model, 'balance')->textInput(['maxlength' => true,  'id'=>'balance', 'readonly'=>true, 'style'=>'text-align:right;'])?>
</div>
</div>
</div>

<div class="row">
 <div class="col-sm-12" style=" margin-top:0px; ">
<?= $form->field($modelSale, 'payment')->textInput(['maxlength' => true,  'id'=>'payment', 'style'=>'text-align:right;'])->label('Payment')?>
</div>
</div>


 <div class="row">
 <div class= 'pull-left'>
      <div class="form-group">
    <div class="col-md-5">
        <?= Html::submitButton('Pay', ['class'=>'btn btn-warning btn-sm', 'id'=>'print']) ?>
    </div>
    </div>

</div>

</div>


    <?php ActiveForm::end(); ?>
</div>



<script type="text/javascript">

// $(document).ready(function(){
//     $('#payment').keyup(function(){
//         var total_amount = $('#total_amount').val();
//         var deposit_paid = $('#payment').val();
//         var balanceprevious = $('#balance').val();
//         var DepositNum = parseFloat(deposit_paid.replace(/,/g,'')); 
//         var BalanceNum = parseFloat(balanceprevious.replace(/,/g,'')); 
//         var balance =  BalanceNum - DepositNum;
//         if( balance < 0){
//         balance = 0
//        } 
//          $('#balance').val(commaSeparateNumber(balance));
// })
// })



//allow only numbers entry
$('#payment').keyup(function(e)
                                {
  if (/\D/g.test(this.value))
  {
    // Filter non-digits from input value.
    this.value = this.value.replace(/\D/g, '');
  }
});

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

$(document).ready(function(){
  $('#print').click(function(){
    var id = <?=$model->id;?>;
    var payment = $('#payment').val();
    var total_amount = $('#total_amount').val();
    var balance = $('#balance').val();
    var customer_id = $('.customer_id').val();
    var transaction_number = $('#transaction_number').val();
    $.ajax({
      type: 'post',
      url :"<?php echo Yii::$app->getUrlManager()->createUrl('cash-sale/makepayment')  ; ?>",
      data: {payment:payment, total_amount:total_amount, balance:balance, customer_id:customer_id, id:id, transaction_number:transaction_number},
      success: function(data){

      }
    });
  });
});




</script>
