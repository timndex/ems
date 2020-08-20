<?php

namespace backend\controllers;

use Yii;
use app\models\Invoice;
use app\models\InvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\InvoiceSub;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use app\models\Model;
use app\models\InvoicePayments;
use app\models\InvoiceDetails;
use yii\filters\AccessControl;
use app\models\InvoiceTotal;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
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
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



 public function actionPayments($id){
        $model = $this->findModel($id);
        $modelPayments = new InvoicePayments;

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('payments', [
                    'model' => $model,
                   'modelPayments'=>$modelPayments,
                ]);
            } else {
                return $this->renderAjax('payments', [
                    'model' => $model,
                    'modelPayments'=>$modelPayments,
                ]);
            }

    }


    public function actionMakepayment(){
       
         if(isset($_POST['id'])){
          $modelPayments = new InvoicePayments;
          date_default_timezone_set("Africa/Nairobi");
          $customer_id = $_POST['customer_id'];
          $total_amount = $_POST['total_amount'];
          $balance = floatval(preg_replace('/[^\d.]/', '', $_POST['balance']));
          $payment = floatval(preg_replace('/[^\d.]/', '', $_POST['payment']));
          $transaction_number = $_POST['transaction_number'];
          $id = $_POST['id'];
          $modelInvoice = Invoice::find()->where(['id'=>$id])->all();
          foreach ($modelInvoice as  $value) {
              $job_card_number = $value['job_card_number'];
          }

          $modelPayments->job_card_number = $job_card_number;
          $modelPayments->invoice_id = $id;
          $modelPayments->customer_id = $customer_id;
          $modelPayments->payment = $payment;
          $modelPayments->created_at = date('Y:m:d H:i:s');
          $modelPayments->created_by = \Yii::$app->user->identity->id;
          $modelPayments->transaction_number = $transaction_number;
          $newbalance = $balance - $payment;
          $modelPayments->save(false);

           $sum_payment = 0;
          $modelPaymentsBalance = InvoicePayments::find()->where(['job_card_number'=>$job_card_number])->all();
          foreach ($modelPaymentsBalance as  $value) {
              $payments_all = $value['payment'];

          
          $total =array($payments_all); 

          for ($i=0; $i <count($total); $i++) { 
              $sum_payment += $payments_all;
          
          }
      }
      

           Yii::$app->db->createCommand()

            ->update('table_prefoma_invoice', ['payment'=>number_format($sum_payment),'balance'=>number_format($newbalance),'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_number])

                 ->execute(false);

             Yii::$app->db->createCommand()

            ->update('table_invoice', ['payment'=>number_format($sum_payment),'balance'=>number_format($newbalance),'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['id'=> $id])

                 ->execute(false);


           Yii::$app->db->createCommand()

            ->update('table_job_card', ['payment'=>number_format($sum_payment),'balance'=>number_format($newbalance),'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_number])

                 ->execute(false);
                 
                  return $this->redirect(['index']); 
                    }

                               
   }

        



    /**
     * Displays a single Invoice model.
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

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Invoice();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionComplete(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];

             Yii::$app->db->createCommand()

             ->update('table_invoice', ['status' => 12],  ['id'=> $id])

             ->execute();

              Yii::$app->db->createCommand()

             ->update('table_job_card', ['status' => 12],  ['id'=> $id])

             ->execute();

             return $this->redirect('/invoice/index');
        }
    }
    
      public function actionCancel(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];

             Yii::$app->db->createCommand()

             ->update('table_invoice', ['status' => 9],  ['id'=> $id])

             ->execute();

               Yii::$app->db->createCommand()

             ->update('table_job_card', ['status' => 9],  ['id'=> $id])

             ->execute();

             return $this->redirect('/invoice/index');
        }
    }

    

    /**
     * Updates an existing Invoice model.
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



 public function actionPrint() {

        //return $this->render('print');
    if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelInvoice = Invoice::find()->where(['id'=>$id])->all();
            $modelInvoiceSub = InvoiceSub::find()->where(['invoice_number'=>$id])->all();
            $modelInvoiceDetails =InvoiceDetails::find()->where(['invoice_number'=>$id])->all();
            $modelInvoiceTotal = InvoiceTotal::find()->where(['invoice_number'=>$id])->all();
           

    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
        'destination' => Pdf::DEST_BROWSER,
        'cssFile' => \Yii::getAlias('@webroot') .'/assets/proui/css/printcss.css',
        'content' =>  $this->renderPartial('createprint', ['modelInvoice' => $modelInvoice,
            'modelInvoiceSub'=>$modelInvoiceSub, 'modelInvoiceDetails'=>$modelInvoiceDetails, 'modelInvoiceTotal'=>$modelInvoiceTotal]),
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
            'modelInvoice' => $modelInvoice,
            'modelInvoiceSub'=>$modelInvoiceSub,
            'modelInvoiceDetails'=>$modelInvoiceDetails,
            'modelInvoiceTotal'=>$modelInvoiceTotal
           
        ]);
}
      
}
   


    public function actionCreateprint(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
             $modelInvoice = Invoice::find()->where(['id'=>$id])->all();
            $modelInvoiceSub = InvoiceSub::find()->where(['invoice_number'=>$id])->all();
             $modelInvoiceDetails =InvoiceDetails::find()->where(['invoice_number'=>$id])->all();
              $modelInvoiceTotal = InvoiceTotal::find()->where(['invoice_number'=>$id])->all();
            
        }
        return $this->render('createprint', [
            'modelInvoice' => $modelInvoice,
            'modelInvoiceSub'=>$modelInvoiceSub,
            'modelInvoiceDetails'=>$modelInvoiceDetails,
            'modelInvoiceTotal'=>$modelInvoiceTotal
        ]);
    }


    /**
     * Deletes an existing Invoice model.
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
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
