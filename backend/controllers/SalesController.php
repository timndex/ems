<?php

namespace backend\controllers;

use Yii;
use app\models\Sales;
use app\models\SalesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CashSale;
use app\models\CashSaleSub;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * SalesController implements the CRUD actions for Sales model.
 */
class SalesController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * Lists all Sales models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SalesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->groupBy('job_card_number');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sales model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


  public function actionPrint() {
      
    if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelSale = Sales::find()->where(['id'=>$id])->all();
            foreach ($modelSale as  $value) {
                $job_card_number = $value['job_card_number'];
            }
            $modelCashSale = CashSale::find()->where(['job_card_number'=>$job_card_number])->all();
            foreach ($modelCashSale as  $value) {
                $cash_sale_id = $value['id'];
            }
            $modelCashSaleSub = CashSaleSub::find()->where(['cash_sale_id'=>$cash_sale_id])->all();
           

    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
        'destination' => Pdf::DEST_BROWSER,
        'cssFile' => \Yii::getAlias('@webroot') .'/assets/proui/css/printcss.css',
        'content' =>  $this->renderPartial('createprint', ['modelCashSale' => $modelCashSale,
            'modelCashSaleSub'=>$modelCashSaleSub]),
       // 'content' => $this->renderPartial('createprint'),
        'options' => [
            // any mpdf options you wish to set
        ],
        'methods' => [
            'SetTitle' => 'Receipt',
            //'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
            //'SetHeader' => ['Krajee Privacy Policy||Generated On: ' . date("r")],
            //'SetFooter' => ['|Page {PAGENO}|'],
            //'SetAuthor' => 'Kartik Visweswaran',
            //'SetCreator' => 'Kartik Visweswaran',
            //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
        ]
    ]);
    return $pdf->render('createprint', [
            'modelCashSale' => $modelCashSale,
            'modelCashSaleSub'=>$modelCashSaleSub,
            'modelSale'=>$modelSale
           
        ]);
}
      
}
   


    public function actionCreateprint(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelSale = Sales::find()->where(['id'=>$id])->all();
            foreach ($modelSale as  $value) {
                $job_card_number = $value['job_card_number'];
            }
            $modelCashSale = CashSale::find()->where(['job_card_number'=>$job_card_number])->all();
            foreach ($modelCashSale as  $value) {
                $cash_sale_id = $value['id'];
            }
            $modelCashSaleSub = CashSaleSub::find()->where(['cash_sale_id'=>$cash_sale_id])->all();
           
            
        }
        return $this->render('createprint', [
            'modelCashSale' => $modelCashSale,
            'modelCashSaleSub'=>$modelCashSaleSub,
            'modelSale'=>$modelSale
        ]);
    }

    /**
     * Creates a new Sales model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sales();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Sales model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sales model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sales::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
