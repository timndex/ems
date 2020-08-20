<?php

namespace backend\controllers;

use Yii;
use app\models\JobCard;
use app\models\Invoice;
use app\models\CashSale;
use app\models\JobCardSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CreditNote;
use app\models\CreditNoteSub;
use app\models\Customers;
use yii\filters\AccessControl;

class StatementsController extends \yii\web\Controller
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
       $searchModel = new JobCardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->groupBy(['customer_id'])->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    
    }
        public function actionView(){
        if(isset($_GET['id'])){
        	$customer_id = $_GET['id'];
        	$modelInvoice = Invoice::find()->where(['customer_id'=>$customer_id])->all();
        	$modelCashSale = CashSale::find()->where(['customer_id'=>$customer_id])->all();
        	$modelCreditNote = CreditNote::find()->where(['customer_id'=>$customer_id])->all();
        	
        	$credit_note_id = '';
        	foreach ($modelCreditNote as  $value) {
        		$credit_note_id = $value['credit_note_id'];
        	}
        	$modelCreditNoteSub = CreditNoteSub::find()->where(['credit_note_id'=>$credit_note_id])->all();
        
        	$modelCustomer = Customers::find()->where(['customer_id'=>$customer_id])->all();     
    		}
	           return $this->render('view', [
					'modelInvoice'=>$modelInvoice,
					'modelCashSale'=>$modelCashSale,
					'modelCreditNoteSub'=>$modelCreditNoteSub,
					'modelCreditNote' =>$modelCreditNote,
					'modelCustomer' =>$modelCustomer,
				]);
	         
}

}
