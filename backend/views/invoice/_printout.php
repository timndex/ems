<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
use app\models\Customers;


foreach ($modelInvoice as $value) {
    $amount = $value['cost'];
    $job_card_number = $value['job_card_number'];
    $created_at = date('d-m-Y', strtotime($value['created_at']));
    $customer_id = $value['customer_id'];
    $payment = $value['payment'];
    $tax_amount = $value['tax_amount'];
    $sales_tax = ceil($value['sales_tax']);
    $total_amount = $value['total_amount'];
    $balance = $value['balance'];
    $cost = $value['cost'];

}
$modelCustomer = Customers::find()->where(['customer_id'=>$customer_id])->all();

// if($mode_of_payment == 'cash_sale'){
//     $type = 'CASH SALE';
// }else if($mode_of_payment == 'profoma_invoice'){
//     $type = 'Profoma Invoice';
// }

foreach ($modelCustomer as $val) {
    $customer_name = $val['customer_names'];
    //$email = $val['email'];
}

?>


<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table style="margin-bottom: -80px">
                        <tr>
                            <td class="title">
                                <img src="../assets/proui/img/logoprint.jpg" alt="avatar" style="max-width:75px;">
                                
                            </td>
                            
                            <td style="color: red; font-size:30px; text-align: center;">
                               CESSCOLINA EAST AFRICA LTD.
                            </td>
                        </tr>
                    </table>
                    <table style="margin-top: 10px;">
                        <tr>
                        <td style="color: red; font-size:20px; text-align: center;">
                               SALES AND SERVICE OF
                            </td>
                        </tr>
                    </table>
                    <table style="margin-top: -30">
                        <tr>
                    <td style="color: red; font-size:13px; text-align: center;">
                               Hydraulic equipment: Automotive components, High pressure pipes & fittings<br>
                               Petroleum equipment parts and Industrial hardware
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td style="color: red; font-size:10px;">
                                CESSCOLINA EAST AFRRICA LIMITED<br>
                                TONONOKA ROAD, OFF KENYATTA AVENUE<br>
                                P.O.BOX 698-80100
                                MOMBASA KENYA
                            </td>
                           
                        </tr>
                    </table>

                    <table style="margin-top: -100px; margin-right:10px ">
                        <tr> 
                            <td style="color: red; font-size:10px; text-align: right; ">
                                TEL: 020 5265471<br>
                                0710-990376 / 0733-813288<br>
                                TELE/FAX: 041 2490636<br>
                                E-mail:cesscolina@yahoo.com<br>
                                info@cesscolina.co.ke<br>
                                www.cesscolina.co.ke
                               
                            </td>
                        </tr>
                    </table>

                <table style="margin-top: -70px">
                     <tr>
                            <td style="color: red; font-size:13px;"><span>INVOICE NUMBER: <?=$job_card_number?></span></td>
                        </tr>
                </table>
                     <table style="margin-top: -70px">
                        <tr>
                    <td style="color: red; font-size:13px; text-align: center;">
                              PIN NO. P0513940591
                            </td>
                        </tr>
                    </table>

                      <table style="margin-top: -50px; width:600px; ">
                        <tr style="margin-left: -50px; ">
                            <td>
                    <hr style="color: red;" >
                </td>
            </tr>
                </table>

                      <table style="margin-top: -80px; width:600px; ">
                        <tr>
                            <td>
                    <hr style="color: red;" >
                </td>
            </tr>
                </table>

                  <table style="margin-top: -50px; text-align: center;">
                        <tr>
                            <td>
                    <h4>INVOICE </h4>
                </td>
            </tr>
                </table>

                  <table style="margin-top: -65px; margin-left: 460px; text-align: center;">
                        <tr >
                            <td >
                   Date : <?= $created_at; ?>
                </td>
            </tr>
                </table>

                <table style="margin-top: -50px; width: 400px">
                        <tr>
                            <td>
                    M/s : 
                </td>
                <td style="text-align: center; font-size:15px;">
                    <?= $customer_name; ?>
                </td>
            </tr>
                </table>
                   <table style="margin-top: -60px; width: 500px; margin-left: 40px;">
                        <tr>
                            <td>
                                <hr>
                </td>
            </tr>
                </table>

                </td>
            </tr>
            
                   
        </table>
        <table  cellspacing="0" style="margin-top: -50px;">
              <tr class="heading">
                <td style="width: 100px">
                    QTY
                </td>
                
                <td style="width: 200px">
                    PARTICULARS
                </td>
                 <td style="width: 100px">
                    SERVICE TYPE
                </td>
                <td style="width: 100px">
                    @(each)
                </td>
                <td style="width: 10px">
                    DISCOUNT
                </td>
                 <td style="width: 200px">
                    KSH
                </td>
            </tr>
            <?php

            foreach ($modelInvoiceSub as  $vals) {
            $quantity = $vals['quantity'];
            $service_type = $vals['service_type'];
            $description = $vals['description'];
            $cost_qty = $vals['cost_qty'];
             $discount = $vals['discount'];
            ?>

                   ?>
                </td>

             <tr class="item" >
                <td style="border: 1px solid black; text-align: left;"><?= $quantity; ?></td>
                <td style="border: 1px solid black; text-align: left;"><?=$service_type;?></td>
                 <td style="border: 1px solid black; text-align: left;"><?= $description?> </td>
                 <td style="border: 1px solid black; text-align: left;"><?= $cost_qty?> </td>
                 <td rowspan="1" style="border: 1px solid black; text-align: center;"><?=$discount.' '.'%'?></td>
                 <td style="border: 1px solid black; text-align: right;"><?php 
                                 $newdiscount = ($cost_qty * $discount) /100;
                                 $newcost_qty = $cost_qty - $newdiscount;
                                 $newvalue = $newcost_qty * $quantity;
                                 echo "Ksh"." ".number_format($newvalue);?></td>
            </tr>
            
            <?php
        }
            ?>

      
             <tfoot >
                 <tr>
                <th colspan="5" style="text-align:right;">Total:</th>
                <th style="border:1px solid black; text-align:right;"><?= "Ksh"." ".$cost; ?></th>
            </tr>
                <tr>
                <th colspan="5" style="text-align:right;">VAT <?= $tax_amount. "%"; ?>:</th>
                <th style="border:1px solid black; text-align:right;"><?= "Ksh"." ".$sales_tax; ?></th>
            </tr>
               <tr>
                <th colspan="5" style="text-align:right">Subtotal Amount:</th>
                <th style="border:1px solid black; text-align:right;"><?= "Ksh"." ".$total_amount; ?></th>
            </tr>
                <tr>
                <th colspan="5" style="text-align:right">Total Paid:</th>
                <th style="border:1px solid black; text-align:right;"><?= "Ksh"." ".$payment; ?></th>
            </tr>
            <tr>
                <th colspan="5" style="text-align:right">Balance:</th>
                <th style="border:1px solid black; text-align:right;"><?= "Ksh"." ".$balance; ?></th>
            </tr>
        </tfoot>
            <!-- <tr class="total" >
              
                
                <td colspan="2">
                   Total: $385.00
                </td>
            </tr> -->
        </table>
        <?php
    if(!empty($modelInvoiceDetails)){
?>
 <table  style="text-align: center; margin-top: 30px;">
                        <tr>
                            <td>
                    <h4>DETAILED INVOICE</h4>
                </td>
            </tr>
                </table>
        <table cellspacing="0">
             <tr class="heading">
                <td>QUANITY</td>
                <td>DESCRIPTION OF GOODS</td>
                <td>@</td>
                <td>Shs</td>
            </tr>
            <tr>
                <?php
               
                            foreach ($modelInvoiceDetails as  $val) {
                             $item = $val['items'];
                              $quantity_sub = $val['quantity'];
                              $description_sub = $val['description'];
                              $cost_sub = $val['cost_qty'];
                              $totals = $val['totals'];
                             
                            ?>

                            
                        <tr >
                            <td style="border: 1px solid black; text-align: center;"><?=$quantity_sub?> Qty</td>
                            <td style="border: 1px solid black; text-align: center;"><?=$description_sub;?></td>
                            <td style="border: 1px solid black; text-align: right;"><?=$cost_sub;?></td>
                            <td style="border: 1px solid black; text-align: right;"><?=$totals;?></td>
                            <!-- <td style="border: 1px solid black"></td> -->
                             </tr>
                             <?php
                            
                        }
                        foreach ($modelInvoiceTotal as  $value) {
                            $totalamount = $value['total'];
                        

                            ?> 
                            
                                <tfoot>
                                <tr>
                               <th colspan="3" style="text-align:right">SubTotal:</th>
                                <th style="border:1px solid black; text-align:right;"><?= "Ksh"." ".$totalamount; ?></th>
                                </tr>  
                                <?php
                            }
                                ?>          
                                </tfoot>
                          
            </tr>



        </table>
        <?php
    }

        ?>



    </div>
