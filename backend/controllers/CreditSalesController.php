<?php

namespace backend\controllers;


use Yii;
use app\models\Invoice;
use app\models\InvoiceSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CreditNote;
use app\models\Customers;
use yii\filters\AccessControl;

class CreditSalesController extends \yii\web\Controller
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
        	$modelJobCard = Invoice::find()->all();
        	$modelCustomer = Customers::find()->all();     
    		
	           return $this->render('index', [
					'modelJobCard'=>$modelJobCard,
					 'modelCustomer' =>$modelCustomer,
				]);
    
    }
//         public function actionView(){
//         if(isset($_GET['id'])){
//         	$customer_id = $_GET['id'];
//         	$modelJobCard = Invoice::find()->where(['customer_id'=>$customer_id])->all();
//         	// $modelCreditNote = CreditNote::find()->where(['customer_id'=>$customer_id])->all();
//         	$modelCustomer = Customers::find()->where(['customer_id'=>$customer_id])->all();     
//     		}
// 	           return $this->render('view', [
// 					'modelJobCard'=>$modelJobCard,
// 					// 'modelCreditNote'=>$modelCreditNote,
// 					 'modelCustomer' =>$modelCustomer,
// 				]);
	         
// }


}
