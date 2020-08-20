<?php

namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\MpesaTransaction;
use app\models\MpesaTransactionSearch;
use yii\filters\AccessControl;

class MpesaController extends Controller
{

	 public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
             'access' => [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],

                // ...
            ],
        ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new MpesaTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAccesstoken()
    {
        $consumer_key="aY4iNJJn68lN8hlSaZkOWtEcaa7zoqcs";
        $consumer_secret="Ny3E1YSzmEpizMyV";
        $credentials = base64_encode($consumer_key.":".$consumer_secret);
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$credentials));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
       	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $access_token =json_decode($curl_response);
        return $access_token->access_token;

       

    }
      public function generateAccessToken()
    {
        $consumer_key="aY4iNJJn68lN8hlSaZkOWtEcaa7zoqcs";
        $consumer_secret="Ny3E1YSzmEpizMyV";
        $credentials = base64_encode($consumer_key.":".$consumer_secret);
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$credentials));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
       	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $access_token =json_decode($curl_response);
        return $access_token->access_token;

       

    }
    /**
     * Lipa na M-PESA password
     * */
    public function lipaNaMpesaPassword()
    {
        $lipa_time = date('Ymdhis');
        $passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        $BusinessShortCode = 174379;
        $timestamp =$lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode.$passkey.$timestamp);
        return $lipa_na_mpesa_password;
    }
    /**
     * Lipa na M-PESA STK Push method
     * */
    public function actionMpesastkpuck()
    {
       	  $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
		  $curl = curl_init();
		  $BusinessShortCode = '174379';
		  $passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
		  $timestamp =  date('YmdHis');
		  curl_setopt($curl, CURLOPT_URL, $url);
		  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization: Bearer '. $this->generateAccessToken()));//setting custom header
		    $password = base64_encode($BusinessShortCode.$passkey.$timestamp);

		  $curl_post_data = array(
		    //Fill in the request parameters with valid values
		    'BusinessShortCode' => '174379',
		    'Password' => $password,
		    'Timestamp' => $timestamp,
		    'TransactionType' => 'CustomerPayBillOnline',
		    'Amount' => '5',
		    'PartyA' => '254710339728', //254702730727 254710339728
		    'PartyB' => '174379',
		    'PhoneNumber' => '254702730727',
		    'CallBackURL' => 'https://e-dooenergy.org/api/confirmation.php',
		    'AccountReference' => 'Lipa na Mpesa',
		    'TransactionDesc' => 'Test Lipa na Mpesa Transaction'
		  );
		  
		  $data_string = json_encode($curl_post_data);
		  
		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($curl, CURLOPT_POST, true);
		  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		  
		  $curl_response = curl_exec($curl);
		  print_r($curl_response);
		  
		  echo $curl_response;
    }
   
    /**
     * M-pesa Transaction confirmation method, we save the transaction in our databases
     */
    public function mpesaConfirmation($jsonResponse)
    {

    	 $Mpesadata = json_decode($jsonResponse, true);
    	
        foreach ($Mpesadata as $value) {
         $mpesa_transaction = new MpesaTransaction();	
         $mpesa_transaction->TransactionType  =  $value['TransactionType'];
		 $mpesa_transaction->TransID  =   $value['TransID'];
		 $mpesa_transaction->TransTime =   $value['TransTime'];
		 $mpesa_transaction->TransAmount = $value['TransAmount'];
		 $mpesa_transaction->BusinessShortCode  =  $value['BusinessShortCode'];
		 $mpesa_transaction->BillRefNumber  =  $value['BillRefNumber'];
		 $mpesa_transaction->InvoiceNumber =  $value['InvoiceNumber'];
		 $mpesa_transaction->OrgAccountBalance  =  $value['OrgAccountBalance'];
		 $mpesa_transaction->ThirdPartyTransID =  $value['ThirdPartyTransID'];
		 $mpesa_transaction->MSISDN  =  $value['MSISDN'];
		 $mpesa_transaction->FirstName  =   $value['FirstName'];
		 $mpesa_transaction->MiddleName =   $value['MiddleName'];
		 $mpesa_transaction->LastName  =  $value['LastName'];
		 $mpesa_transaction->save(false);
        }
        
    }
    /**
     * M-pesa Register Validation and Confirmation method
     */
    public function actionRegisterurl()
    {      
      $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
  
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization: Bearer '. $this->generateAccessToken()));//setting custom header
      
      
      $curl_post_data = array(
        //Fill in the request parameters with valid values
        'ShortCode' => '600000',
        'ResponseType' => 'Completed',
        'ConfirmationURL' => 'https://e-dooenergy.org/api/confirmation.php',
        'ValidationURL' => 'https://e-dooenergy.org/api/validation.php',
      );
      
      $data_string = json_encode($curl_post_data);
      
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
      
      $curl_response = curl_exec($curl);
    
      echo $curl_response;
 
    }

    public function actionMpesac2b(){

    $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
  
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization: Bearer '. $this->generateAccessToken()));//setting custom header
  
  
    $curl_post_data = array(
            //Fill in the request parameters with valid values
           'ShortCode' => '600000',
           'CommandID' => 'CustomerPayBillOnline',
           'Amount' => '108',
           'Msisdn' => '254708374149',
           'BillRefNumber' => 'test51'
    );
  
    $data_string = json_encode($curl_post_data);
  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
  
    $curl_response = curl_exec($curl);

  
    //echo $curl_response;
   $json = file_get_contents('https://e-dooenergy.org/api/mpesaData.txt');
   //$data = $json);
  $this->mpesaConfirmation($json);


    }
    public function actionMpesab2c(){
    	 $url = 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
  
		  $curl = curl_init();
		  curl_setopt($curl, CURLOPT_URL, $url);
		  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization: Bearer '. $this->generateAccessToken()));//setting custom header
		  
		  
		  $curl_post_data = array(
		    //Fill in the request parameters with valid values
		    'InitiatorName' => ' ',
		    'SecurityCredential' => ' ',
		    'CommandID' => ' ',
		    'Amount' => ' ',
		    'PartyA' => ' ',
		    'PartyB' => ' ',
		    'Remarks' => ' ',
		    'QueueTimeOutURL' => 'http://your_timeout_url',
		    'ResultURL' => 'http://your_result_url',
		    'Occasion' => ' '
		  );
		  
		  $data_string = json_encode($curl_post_data);
		  
		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($curl, CURLOPT_POST, true);
		  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		  
		  $curl_response = curl_exec($curl);
		  print_r($curl_response);
		  
		  echo $curl_response;
    }
    public function actionMpesab2b(){
    	 $url = 'https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
		  
		  $curl = curl_init();
		  curl_setopt($curl, CURLOPT_URL, $url);
		  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization: Bearer '. $this->generateAccessToken()));//setting custom header
		  
		  
		  $curl_post_data = array(
		    //Fill in the request parameters with valid values
		    'Initiator' => ' ',
		    'SecurityCredential' => ' ',
		    'CommandID' => ' ',
		    'SenderIdentifierType' => ' ',
		    'RecieverIdentifierType' => ' ',
		    'Amount' => ' ',
		    'PartyA' => ' ',
		    'PartyB' => ' ',
		    'AccountReference' => ' ',
		    'Remarks' => ' ',
		    'QueueTimeOutURL' => 'http://your_timeout_url',
		    'ResultURL' => 'http://your_result_url'
		  );
		  
		  $data_string = json_encode($curl_post_data);
		  
		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($curl, CURLOPT_POST, true);
		  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		  
		  $curl_response = curl_exec($curl);
		  print_r($curl_response);
		  
		  echo $curl_response;
    }
}

