<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Office */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="mail-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="row">
<div class="col-md-12">
    <?= $form->field($modelMail, 'subject')->textInput(['maxlength' => true]) ?>
</div>
<div class="col-md-12">
    <?= $form->field($modelMail, 'message')->textArea(['maxlength' => true, 'style'=>'height:150px;']) ?>
</div>
    <div class="form-group pull-right">
        <?= Html::submitButton('Send', ['class' => 'btn btn-success', 'id'=>'send']) ?>
    </div>
</div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
	$(document).ready(function(){
    $('#send').click(function(){
        var id = <?=$model->id?>;
        // var package_id = $('#package_cost').val();
      $.ajax({
            type: 'get',
            data: {id:id},
            url: 'mail',
         beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
              $.LoadingOverlay("show");
            },
        success: function(data){
           // console.log(data);
           if(data == 'success'){
             $.LoadingOverlay("hide");
         }
             // $('#numberVerify').modal('hide');
             // $('#amount').val(data);
          //  console.log(data);

        },
        complete : function() {
           //remove loading gif
        }
    });
  
  });
});
</script>