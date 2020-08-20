<?php

namespace backend\controllers;
use Yii;
use app\models\CashSale;
use app\models\CashSaleSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CreditNote;
use app\models\Customers;
use app\models\Sales;
use yii\filters\AccessControl;


class CashSalesController extends \yii\web\Controller
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
       $searchModel = new CashSaleSearch();
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
        	$modelCashSale = CashSale::find()->where(['customer_id'=>$customer_id])->all();
        	foreach ($modelCashSale as  $value) {
        		$job_card_number = $value['job_card_number'];
        	}
        	$modelSales= Sales::find()->where(['job_card_number'=>$job_card_number])->all();
        	$modelCustomer = Customers::find()->where(['customer_id'=>$customer_id])->all();     
    		}
	           return $this->render('view', [
					'modelCashSale'=>$modelCashSale,
					'modelSales'=>$modelSales,
					 'modelCustomer' =>$modelCustomer,
				]);
	         
}

}
