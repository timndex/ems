<?php

namespace backend\controllers;

use Yii;
use app\models\CashSale;
use app\models\Invoice;
use app\models\JobCardSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CreditNote;
use app\models\Customers;
use yii\filters\AccessControl;


class AuditReportController extends \yii\web\Controller
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
            [
                'class' => PhoneInputBehavior::className(),
                'countryCodeAttribute' => 'countryCode',
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
        $modelCashSale = CashSale::find()->all();
        $modelInvoice = Invoice::find()->all();
	    $modelCreditNote = CreditNote::find()->all();
	    foreach ($modelCashSale as $value) {
	    	$customer_id = $value['customer_id'];
	    }
	    //$modelCustomer = Customers::find()->all();  

	           return $this->render('index', [
					'modelCashSale'=>$modelCashSale,
					'modelInvoice'=>$modelInvoice,
					// 'modelCustomer' =>$modelCustomer,
					'modelCreditNote'=>$modelCreditNote,
				]);
    }
//     public function actionView(){
       
//       if(isset($_GET['created_at'])){
// 	    $created_at = $_GET['created_at'];
// 	    $year = date('Y', strtotime($created_at));
// 	    $month = date('m', strtotime($created_at));
	        
// 	       //$searchModel=""; 

// 	    $modelJobCard = JobCard::find()->all();
// 	    $modelCreditNote = CreditNote::find()->all();
// 	    foreach ($modelJobCard as $value) {
// 	    	$customer_id = $value['customer_id'];
// 	    }
// 	    $modelCustomer = Customers::find()->where(['customer_id'=>$customer_id])->all();  

// 	           return $this->render('view', [
// 					'modelJobCard'=>$modelJobCard,
// 					'modelCreditNote'=>$modelCreditNote,
// 					'modelCustomer' =>$modelCustomer,
// 				]);
	         
// }
// }



}
