<?php

namespace backend\controllers;

use Yii;
use app\models\CashSale;
use app\models\JobCard;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class DashboardController extends \yii\web\Controller
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
        return $this->render('index');

    }
    public function actionGraph(){
    	$data = array();
		$tableau = array();
		$average = array();
		$previousmonth=date('m', strtotime('-1 month'));
		$year = date('Y');
 

    	$graph = CashSale::find()->where(['MONTH(created_at)'=>$previousmonth, 'YEAR(created_at)'=>$year])->groupBy(['created_at'])->all();
    	$tableau['name'] = 'Sales';
    	foreach ($graph as $value) {
    		$tableau['data'][]= array(strtotime($value['created_at'])*1000, (int)($value['payment']));
    	}

    	array_push($data, $tableau);
        print json_encode($data);
    }
    public function actionActivejobs(){
    	$model = JobCard::find()->where(['status'=>11,'DAY(created_at)'=>date('d')])->all();
    	echo count($model);
    }

     public function actionCompletejobs(){
    	$model = JobCard::find()->where(['status'=>13, 'DAY(created_at)'=>date('d')])->all();
    	echo count($model);
    }
    public function actionCancledjobs(){
    	$model = JobCard::find()->where(['status'=>9, 'DAY(created_at)'=>date('d')])->all();
    	echo count($model);
    }

    public function actionSales(){
    	$model = CashSale::find()->where(['DAY(created_at)'=>date('d')])->all();
    	$sum_payment = 0;
    	 foreach ($model as  $value) {
              $payments_all = $value['payment'];

          
          $total =array($payments_all); 

          for ($i=0; $i <count($total); $i++) { 
              $sum_payment += $payments_all;
          
          }
      }
    	
    	echo $sum_payment;
    }

}

 
                  
