<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
use app\models\Customers;


foreach ($modelCashSale as $value) {
    $amount = $value['cost'];
    $created_at = date('d-m-Y', strtotime($value['created_at']));
    $customer_id = $value['customer_id'];
    $payment = $value['payment'];
    $tax_amount = $value['tax_amount'];
    $total_amount = $value['total_amount'];
    $sales_tax = ceil($value['sales_tax']);
    $balance = $value['balance'];

}
$modelCustomer = Customers::find()->where(['customer_id'=>$customer_id])->all();


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
                    Cash Sale
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

                <table style="margin-top: -30px; width: 200px">
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
        <table  cellspacing="0">
              <tr class="heading">
                <td style="width: 100px">
                    QTY
                </td>
                
                <td style="width: 300px">
                    PARTICULARS
                </td>
                 <td style="width: 100px">
                    @
                </td>
                 <td>
                    KSH
                </td>
            </tr>
            <?php

            foreach ($modelCashSaleSub as  $vals) {
            $quantity = $vals['quantity'];
            $service_type = $vals['service_type'];
            $description = $vals['description'];
            $cost_qty = $vals['cost_qty'];

            ?>
             <tr class="item" >
                <td >
                    <?= $quantity; ?>
                </td>
                 <td >
                    <?= $description. " ".$service_type; ?>
                </td>
                 <td >
               
                </td>
                <td>
                   <?= "Ksh"." ".$cost_qty; ?>
                </td>
            </tr>
            
            <?php
        }
            ?>

             <tfoot >
                <tr>
                <th colspan="3" style="text-align:right;">VAT <?= $tax_amount. "%"; ?>:</th>
                <th style="border:1px solid black;"><?= "Ksh"." ".$sales_tax; ?></th>
            </tr>
               <tr>
                <th colspan="3" style="text-align:right">Total Amount:</th>
                <th style="border:1px solid black;"><?= "Ksh"." ".$total_amount; ?></th>
            </tr>
                <tr>
                <th colspan="3" style="text-align:right">Total Paid:</th>
                <th style="border:1px solid black;"><?= "Ksh"." ".$payment; ?></th>
            </tr>
            <tr>
                <th colspan="3" style="text-align:right">Balance:</th>
                <th style="border:1px solid black;"><?= "Ksh"." ".$balance; ?></th>
            </tr>
        </tfoot>
            
        </table>
<div style="text-align: center; font-size: 10px; color: red;"><span >Goods Once Sold are not returnable</span></div>
    </div>
