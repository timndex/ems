<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
use kartik\date\DatePicker;
use app\models\Customers;



$this->title = 'Client Statement';
$this->params['breadcrumbs'][] = ['label' => 'Client Statement', 'url' => ['index']];

foreach($modelCustomer as $value){
    $customer_name = $value['customer_names'];
}


?>


   <h5><strong>Customer: <?=$customer_name?></strong></h5>
     

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
                <td>DATE OF TRANSACTION</td>
                <th>INVOICE NUMBER</th>
                <th>TRANSACTION TYPE</th>
                <th>COST</th>
                <th>Tax</th>
                <th>Sales Tax</th>
                <th>Total</th>
                <th>PAYMENTS</th>
                <th>CREDIT NOTE AMOUNT</th>
                <th class="sum" >BALANCE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                foreach ($modelCashSale as $value) {
                $customer_id = $value['customer_id'];
                //$customer_instruction = $value['customer_instruction'];
                $job_card_num = $value['job_card_number'];
                $cost = $value['cost'];
                $payment = $value['payment'];
                $balance = $value['balance'];
                $created_at = $value['created_at'];
                 $total = $value['total_amount'];
                $tax_amount = $value['tax_amount'];
                $sales_tax = ceil($value['sales_tax']);

              
                ?>
                <td><?= date('m/d/yy', strtotime($created_at));?></td>
                <td><?= $job_card_num;?></td>
                <td>CASH SALE</td>
                <td><?= $cost;?></td>
                <td><?= $tax_amount."%";?></td>
                <td><?= $sales_tax;?></td>
                <td><?= $total;?></td>
                <td><?= $payment?></td>
                <td>0</td>
                <td><?= $balance;?></td>
             </tr>
            

<?php 
   
}

?>          
                <tr>
                <?php
                foreach ($modelInvoice as $value) {
                $customer_id = $value['customer_id'];
                //$customer_instruction = $value['customer_instruction'];
                $job_card_num = $value['job_card_number'];
                $cost = $value['cost'];
                $payment = $value['payment'];
                $balance = $value['balance'];
                $created_at = $value['created_at'];
                 $total = $value['total_amount'];
                $tax_amount = $value['tax_amount'];
                $sales_tax = ceil($value['sales_tax']);

                ?>
                <td><?= date('m/d/yy', strtotime($created_at));?></td>
                <td><?= $job_card_num;?></td>
                <td>INVOICE</td>
                <td><?= $cost;?></td>
                <td><?= $tax_amount."%";?></td>
                <td><?= $sales_tax;?></td>
                <td><?= $total;?></td>
                <td><?= $payment?></td>
                <td>0</td>
                <td><?= $balance;?></td>
             </tr>
            

<?php 
   
}

?>
                   <?php
                foreach ($modelCreditNote as $value) {
                     $credit_note_num = $value['credit_note_num'];
                    $amount = $value['amount'];
                    // $description = $value['description'];
                    $created_ats = $value['created_at'];
               // }

                ?>

                <td><?= date('m/d/yy', strtotime($created_ats));?></td>
                <td><?= $credit_note_num;?></td>
                <td>Credit Note</td>
                <td> 0 </td>
                <td> 0 </td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td><?= $amount;?></td>
                <td>0</td>
             </tr>

<?php 
   
}

?>
</tbody>
<tfoot>
            <tr>
                <th colspan="5" style="text-align:right">Total (Ksh)</th>
                <th id='Total'></th>
                <th id='Total1'></th>
                <th id='Total2'></th>
                <th id='Total3'></th>
                <th id='Total4'></th>
            </tr>
        </tfoot>

</table>

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
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
               total1 = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal1 = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

               total2 = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal2 = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

               total3 = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal3 = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

             total4 = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal4 = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer
            var total = pageTotal;
            var total1 = pageTotal1;
            var total2 = pageTotal2;
            var total3 = pageTotal3;
            //var total4 = pageTotal4;
        
             $('#Total').html(commaSeparateNumber(total));
              $('#Total1').html(commaSeparateNumber(total1));
              $('#Total2').html(commaSeparateNumber(total2));
              $('#Total3').html(commaSeparateNumber(total3));
              $('#Total4').html(commaSeparateNumber(total4));

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