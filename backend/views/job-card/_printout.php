<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Alert;
use app\models\Customers;

foreach ($modelJobCard as $value) {
    $amount = $value['cost'];
    $job_card_number = $value['job_card_number'];
    $created_at = date('d-m-Y', strtotime($value['created_at']));
    $transaction_type = $value['transaction_type'];
    $customer_id = $value['customer_id'];
    $payment = $value['payment'];
    $tax_amount = $value['tax_amount'];
    $balance  = $value['balance'];
    $sales_tax = ceil($value['sales_tax']);
    $total_amount = $value['total_amount'];

}
$modelCustomer = Customers::find()->where(['customer_id'=>$customer_id])->all();

if($transaction_type == 'cash_sale'){
    $type = 'CASH SALE';
}else if($transaction_type == 'profoma_invoice'){
    $type = 'Profoma Invoice';
}

foreach ($modelCustomer as $val) {
    $customer_name = $val['customer_names'];
    $phone = $val['customer_phone'];
    $email = $val['customer_email'];
}

?>


<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td >
                    <table style="margin-bottom: -85px ">
                        <tr>
                            <td class="title" style="">
                                <img src="../assets/proui/img/logoprint.jpg" alt="avatar" style="max-width:75px;">
                                
                            </td>
                        </tr>
                    </table>
                </td>
                <table style="margin-top: -15px; margin-left: 50px">
                        <tr>
                       <td style="color: red; font-size:25px; text-align: center; margin-left: -50px;">
                               <span style="border-bottom: 1px solid red">JOB CARD</span>
                            </td>
                        </tr>
                    </table>
                  <table style="margin-top: -30px; margin-left: 70px">
                        <tr>
                        <td style="color: red; font-size:28px; text-align: center;">
                                <b>Cesscolinna East Arica Ltd.</b>
                            </td>
                        </tr>
                    </table>
                    <table style="margin-top: -30px; margin-left: 80px">
                        <tr>
                        <td style="color: red; font-size:20px; text-align: center;">
                               (Let the Experst do it. Yes we can!)
                            </td>
                        </tr>
                    </table>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table style="margin-top: -60px; width:100px;">
                        <tr>
                            <td style="color: red; font-size:9px;">
                                <b>TONONOKA ROAD, OFF KENYATTA AVENUE<br>
                                P.O.BOX 698-80100
                                MOMBASA KENYA</b>
                            </td>
                           
                        </tr>
                    </table>

                    <table style="margin-top: -160px; margin-right:-15px ">
                        <tr> 
                            <td style="color: red; font-size:9px; text-align: right; ">
                                <b>TEL: 020 5265471<br>
                                0710-990376 / 0733-813288<br>
                                TELE/FAX: 041 2490636<br>
                                E-mail:cesscolina@yahoo.com<br>
                                info@cesscolina.co.ke<br>
                                www.cesscolina.co.ke</b>
                               
                            </td>
                        </tr>
                    </table>

            <table style="margin-top: -10px; margin-left: -490px; text-align: center;">
                                <tr >
                                    <td >
                           Date : <span style="border-bottom: 1px dotted black;"><?= $created_at; ?></span>
                        </td>
                    </tr>
                </table>

                  <table style="margin-top: -67px; margin-left: 390px; text-align: center; ">
                        <tr >
                            <td style="border: 1px solid black; padding:1px;">
                    <span>Job Card Number : 54242</span>
                </td>
            </tr>
                </table>

                <table style="margin-top: -5px; border:1px solid black; width: 600px;" >
                        <tr>
                <td style="text-align: left; font-size:9px;" style="border-right: 1px solid black; padding-right:-92px">
                    Customer: <?= $customer_name; ?><br>
                    Email : <?= $email; ?><br>
                    Phone Number: <span style="font-size: 12px;"><?= $phone; ?>
                </td>
                    <td style="font-size:15px; text-align: left" >
                    Job Required/Parts Used: <br>
                           <?php
                  foreach ($modelJobCardSub as  $vals) {
                $quantity = $vals['quantity'];
                $description = $vals['description'];
                $cost_qty = $vals['cost_qty'];

                ?>
                     <?= "Quantity"." ".$quantity. " ".$description; ?><br> 
                      <?php
                     }
            ?>
                     
                </td>
            </tr>
                </table>
                   <table style="margin-top: 0px;  margin-left: 0px; width: 600px; border:1px solid black">
                        <tr style="">
                <td style="text-align: left; font-size:15px; border-right: 1px solid black; padding-right:-10px" >
                    Customer Instruction: <br>
                    <?php
                       foreach ($modelJobCardSub as  $values) {
               
                $service_type = $values['service_type'];
               
                ?>
                     <?= $service_type?><br> 
                      <?php
                     }
            ?>
                    <br>
                </td>
            
           
                 <td style="text-align: left;  font-size:15px;">
                    
                    Agreed Price: <span style="border-bottom: 1px dotted black; "> <?=$total_amount; ?></span><br>
                    Deposit Paid: <span style="">..................</span><br>
                    Balance : <span style="">..........................</span><br>
                    Techician Signature: <span style="">............................</span><br>
                </td>
            </tr>
                </table>
                          

                   <table style="margin-top: -2px;  margin-left: -2px; width: 605px; height: 300px;">
                        <tr style="">
                <td style="text-align: center; font-size:15px;" style="border: 1px solid black;">
                    
                    <span style="text-align: center; border-bottom: 1px solid black; margin-left: 500px;"> <b>TERMS AND CONDITIONS</b></span> <br>
                    A) All goods, equipments, machinery, vehicles etc are repaired at owners risk <br>
                    B) Uncollected items ( A above) within 14 days from the repaired date above shall be scrapped or sold to recover our expenses and do not blame us thereafter.<br>
                    B) This job card must be produced by the collector of items be it the owner or his subject.<br>
                    
                    Customer Signature..................................................
                </td>
            </tr>
                </table>


                </td>
            </tr>
            
        </table>
    </div>
