<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
use kartik\date\DatePicker;



$this->title = 'Audit Report: ';
$this->params['breadcrumbs'][] = ['label' => 'Audit Report', 'url' => ['index']];




foreach ($modelCustomer as  $value) {
    $customer_name = $value['customer_names'];
}
$credit_note_num = '';
$amount = '';
$description = '';
$created_ats = '';


?>
<div class="job-card-index ">
     <div class="block">
     	<div class="block-title">
        <h2>Customer<strong>Information</strong></h2>
     </div>
   <h5><strong>Customer Name : <?= $customer_name;?></strong></h5>
   
     </div>


<p id="date_filter">
     <div class="row">
 <div class="col-sm-3">
   Filter From<input placeholder="Selected starting date" type="date" id="filter_From" class="form-control datepicker">
</div>
<div class="col-sm-3">
    Filter To  <input placeholder="Selected starting date" type="date" id="filter_To" class="form-control datepicker">
 </div>

 <div class="col-sm-3">
<br>
    <button class="btn" id="reset_btn">Search</button>
</div>
</div>
</p>


<table id="dataTables" style="width:100%"  class="table table-striped table-bordered">
        <thead>
            <tr>
                <td><strong>DATE OF TRANSACTION</strong></td>
                <th>TRANSACTION REF</th>
                <th>TRANSACTION TYPE</th>
                <th>Cost</th>
                <th>PAYMENTS</th>
                <th>Amount</th>
                <th class="sum" >Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            	<?php
            	foreach ($modelJobCard as $value) {
			    $customer_id = $value['customer_id'];
			    $job_card_num = $value['job_card_number'];
			    $cost = $value['cost'];
			    $payment = $value['payment'];
			    $balance = $value['balance'];
			    $created_at = $value['created_at'];
			    $sale_type = $value['sale_type'];
			    //echo  $balance;
			

            	?>
                <td><?= date('m/d/yy', strtotime($created_at));?></td>
                <td><?= $job_card_num;?></td>
                <?php
                if($sale_type == 'Cash_sale'){
                    ?>
                    <td>Cash Sale</td>
                <?php
            }else if($sale_type == 'Invoive'){
                    ?>
                    <td>Invoice</td>
           <?
            }else{

            }

                ?>
                
                <td><?= 'ksh'. ' '.$cost;?></td>
                <td><?= 'ksh'. ' '.$payment;?></td>
                <td></td>
                <td><?= $balance;?></td>
             </tr>
             <tr>
                <?php 
            }
                if(!empty($modelCreditNote)){
                    foreach ($modelCreditNote as  $val) {
                        $credit_note_num = $val['credit_note_num'];
                    
                
                // if(!empty($modelCreditNoteSub)){
                // foreach ($modelCreditNoteSub as $value) {
                    //$credit_note_num = $value['credit_note_num'];
                    $amount = $val['amount'];
                    // $description = $value['description'];
                    $created_ats = $val['created_at'];
               // }

                ?>

                <td><?= date('m/d/yy', strtotime($created_ats));?></td>
                <td><?= $credit_note_num;?></td>
                <td>Credit Note</td>
                <td> - </td>
                <td> - </td>
                <td><?= 'ksh'. ' '. $amount;?></td>
                <td><?= $amount;?></td>
             </tr>

<?php 
}
}
?>
</tbody>
<tfoot>
            <tr>
                <th colspan="6" style="text-align:right">Total:</th>
                <th id='Total'></th>
            </tr>
        </tfoot>

</table>

</div>

<script type="text/javascript">
    // $(document).ready(function() {
 //    $('#dataTables').DataTable();
//} );


     // var table = $('#dataTables').DataTable();
  //    table.column( 8 ).data().sum();

  //     $('#dataTables').DataTable( {
  //   drawCallback: function () {
  //     var api = this.api();
  //     $( api.table().footer() ).html(
  //       api.column( 4, {page:'current'} ).data().sum()
  //     );
  //   }
  // } );

$(document).ready(function() {
    $('#dataTables').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,-]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            var total = pageTotal;
        
             $('#Total').html(commaSeparateNumber('Ksh'+ ' '+total));
        }
    } );
});

 function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
    }
    return val;
  }
$(document).ready(function(){
$('#reset_btn').click(function(){
    var table = $('#dataTables').DataTable();
   $.fn.dataTable.ext.search.push(
  function(settings, data, dataIndex) {
    var min = $('#filter_From').val();
    var max = $('#filter_To').val();
    var createdAt = data[0] || 0; // Our date column in the table

    if (
      (min == "" || max == "") ||
      (moment(createdAt).isSameOrAfter(min) && moment(createdAt).isSameOrBefore(max))
    ) {
      return true;
    }
    return false;
  }
);
    table.draw();
});
});


</script>