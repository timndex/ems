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
use app\models\SalesSearch;
use yii\filters\AccessControl;

class SalesReportsController extends \yii\web\Controller
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
      $modelSales = Sales::find()->all();
       $modelCustomer = Customers::find()->all();  
	           

	           return $this->render('index', [
					'modelSales'=>$modelSales,
					'modelCustomer'=>$modelCustomer
				]);
    
    }


}
