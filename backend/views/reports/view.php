<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
use kartik\date\DatePicker;



$this->title = 'Reports';
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['index']];


foreach ($modelJobCard as $value) {
	$customer_id = $value['customer_id'];
	$customer_instruction = $value['customer_instruction'];
	$job_card_num = $value['job_card_number'];
	$cost = $value['cost'];
	$deposit_paid = $value['deposit_paid'];
	$balance = $value['balance'];
	$created_at = $value['created_at'];
	//echo  $balance;
}

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
   <h5><strong>Customer Name : <?= $customer_name;?></strong></h5>
     </div>


<p id="date_filter">
	 <div class="row">
 <div class="col-sm-3">
   Filter From<input placeholder="Selected starting date" type="date" id="datepicker_from" class="form-control datepicker">
</div>
<div class="col-sm-3">
    Filter To  <input placeholder="Selected starting date" type="date" id="datepicker_to" class="form-control datepicker">
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
            	<td>Date</td>
                <th>Number</th>
                <th>Description</th>
                <th>Task</th>
                <th>Reason</th>
                <th>Cost</th>
                <th>Deposit Paid</th>
                <th>Amount</th>
                <th class="sum" >Balance</th>
            </tr>
        </thead>
        <tbody>
        	<tr>
        		<td><?= date('m/d/yy', strtotime($created_at));?></td>
        		<td><?= $job_card_num;?></td>
        		<td>Invoice</td>
        		<td><?= $customer_instruction?></td>
        		<td> - </td>
        		<td><?= 'ksh'. ' '.$cost;?></td>
        		<td><?= 'ksh'. ' '.$deposit_paid;?></td>
        		<td> - </td>
        		<td><?= $balance;?></td>
        	 </tr>
        	 <tr>
        	 	<?php 

        	 	if(!empty($modelCreditNote)){
				foreach ($modelCreditNote as $value) {
					$credit_note_num = $value['credit_note_num'];
					$amount = $value['amount'];
					$description = $value['description'];
					$created_ats = $value['created_at'];
				}
				?>

        	 	<td><?= date('m/d/yy', strtotime($created_ats));?></td>
        	 	<td><?= $credit_note_num;?></td>
        	 	<td>Credit Note</td>
        	 	<td> - </td>
        	 	<td><?= $description;?></td>
        	 	<td> - </td>
        	 	<td> - </td>
        	 	<td><?= 'ksh'. ' '.number_format($amount);?></td>
        	 	<td> </td>
        	 </tr>

<?php 
}
?>
</tbody>
<tfoot>
            <tr>
                <th colspan="8" style="text-align:right">Total:</th>
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
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 8, { page: 'current'} )
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


</script>