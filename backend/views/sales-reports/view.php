<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
use kartik\date\DatePicker;



$this->title = 'Cash Sales';
$this->params['breadcrumbs'][] = ['label' => 'Cash Sales Report', 'url' => ['index']];


foreach ($modelCustomer as  $value) {
	$customer_name = $value['customer_names'];
}


?>


   <h5><strong>Customer Name : <?= $customer_name;?></strong></h5>
     

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
     <button class="btn-pdfprint btn btn-warning btn" id="print" onclick="print()"><i class="fa fa-print" aria-hidden="true">Print</i></button>
</div>
</div>
</p>


<table id="dataTables" style="width:100%"  class="table table-striped table-bordered">
        <thead>
            <tr>
            	<td><strong>DATE OF TRANSACTION</strong></td>
                <th>RECEIPT NUMBER</th>
                <th>TRANSACTION TYPE</th>
                <th>PAYMENTS</th>
               <!--  <th>CASH SALE NUMBER</th>
                <th>DATE OF ISSUE</th>
                <th>ALL TRANSACTIONS</th> -->
            </tr>
        </thead>
        <tbody>
        
                <?php
                foreach ($modelSales as $value) {
                //$customer_id = $value['customer_id'];
                //$customer_instruction = $value['customer_instruction'];
                  $created_at = $value['created_at'];
                $transaction_number = $value['transaction_number'];
                $payment = $value['payment'];

                ?>
                  <tr>
            		<td><?= date('m/d/yy', strtotime($created_at));?></td>
            		<td><?= $transaction_number;?></td>
                <td>CASH PAYMENT</td>
                <td><?= $payment?></td>
                 </tr>
                  <?php 
                }
                ?>
        	

</tbody>
<tfoot>
            <tr>
                <th colspan="3" style="text-align:right">Total (Ksh)</th>
                <th id='Total'></th>
            </tr>
        </tfoot>

</table>

<script type="text/javascript">


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
// $(document).ready(function() {
//       var table = $('#dataTables').DataTable();
 
//       // Add event listeners to the two range filtering inputs
     
//   } );
    


$(document).ready(function() {
    $('#dataTables').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
    

 
    // Event listener to the two range filtering inputs to redraw on input
   // Event listener to the two range filtering inputs to redraw on input

            // Remove the formatting to get integer data for summation
             // $('#filter_From').keyup( function() { table.draw(); } );
             // $('#filter_To').keyup( function() { table.draw(); } );
          

            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,-]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                

            // Update footer
            var total = pageTotal;

        
             $('#Total').html(commaSeparateNumber(total));


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


 $('#print').click(function(){
 var divToPrint = document.getElementById('dataTables');
    var htmlToPrint = '' +
        '<style type="text/css">' +
        'table {'+
            'border: solid #000 !important;'+
           ' border-width: 1px 0 0 1px !important;'+
        '}'+
        'th, td {'+
           ' border: solid #000 !important;'+
            'border-width: 0 1px 1px 0 !important;'+
        '}'+
        '</style>'+
        '<div style="margin-left:20px; padding-bottom:-17px;"><img src="../assets/proui/img/logoprint.jpg" alt="avatar" style="width:90px; height:90px;"></div>'+
                               '<div style="color:red; text-align:center; margin-top:-70px; font-size:28px"><b>CESSCOLINA EAST AFRICA LTD.</b></div>'+
                               '<div style="color:red; text-align:center;font-size:20px">SALES AND SERVICE OF</div>'+
                               '<div style="color:red; text-align:center;font-size:15px">Hydraulic equipment: Automotive components, High pressure pipes & fittings<br></div>'+
                               '<div style="color:red; text-align:center;font-size:15px">Petroleum equipment parts and Industrial hardware</div>'+

                                '<div style="color:red; text-align:left;font-size:12px">CESSCOLINA EAST AFRRICA LIMITED<br>'+
                                'TONONOKA ROAD, OFF KENYATTA AVENUE<br>'+
                                'P.O.BOX 698-80100'+
                                'MOMBASA KENYA</div>'+

                                '<div style="color:red; text-align:right;font-size:12px; margin-left-300px; margin-top:-100px">TEL: 020 5265471<br>'+
                                '0710-990376 / 0733-813288<br>'+
                                'TELE/FAX: 041 2490636<br>'+
                                'E-mail:cesscolina@yahoo.com<br>'+
                                'info@cesscolina.co.ke<br>'+
                                'www.cesscolina.co.ke</div>'+

                              '<div style="color:red; text-align:center;font-size:15px">PIN NO. P0513940591</div>'+
                              '<div style="color:black; text-align:left;font-size:18px">AUDIT REPORT</div>';
    htmlToPrint += divToPrint.outerHTML;
    newWin = window.open("");
    newWin.document.write(htmlToPrint);
    newWin.print();
    newWin.close();
});
</script>