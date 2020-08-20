<?php

namespace backend\controllers;

use Yii;
use app\models\Accounts;
use app\models\AccountsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\RegisterUser;
use common\models\User;
use borales\extensions\phoneInput\PhoneInputBehavior;
use yii\helpers\Json;
use yii\filters\AccessControl;
   
/**
 * AccountsController implements the CRUD actions for Accounts model.
 */
class AccountsController extends Controller
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
   
    /**
     * Lists all Accounts models.
     * @return mixed
     */
    public function actionIndex()
    {

       
         $searchModel = new AccountsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
          $id=$_POST['editableKey'];
          $model = Accounts::findOne($id);
          $out = Json::encode(['output'=>'', 'message'=>'']);
          $post = [];
          $redirectFlag = false;
          $posted = current($_POST['Accounts']);
          $post['Accounts'] = $posted;
          if ($model->load($post)) {
            date_default_timezone_set("Africa/Nairobi");
            //$model->updated_by  = \Yii::$app->user->identity->id;
            $model->updated_at = date('Y:m:d H:i:s');
           $model->save(false);
                $output ='me';
                 $out = Json::encode(['output'=>'', 'message'=>'']);
            // \Yii::$app->getSession()->setFlash('success', 'successfully got on to the payment page');
                  Yii::$app->session->setFlash('success',['type' => 'success','message'=>'Update successfully.']);
             }
          
          echo $out;
        return $this->redirect(['index']);
          
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Accounts model.
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
     * Creates a new Accounts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RegisterUser();
       
        if ($model->load(Yii::$app->request->post()) && $model->registeruser()){
           // $modelsRegister->load(Yii::$app->request->post());
                date_default_timezone_set("Africa/Nairobi");  
 
                    if (Yii::$app->request->isAjax) {
                        // JSON response is expected in case of successful save
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return $this->redirect(['index']); 
                    }

                               
                

            }

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('create', [
                    'model' => $model,
                    //'modelsRegister'=>$modelsRegister,
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    //'modelsRegister'=>$modelsRegister,
                ]);
            }
    }

    /**
     * Updates an existing Accounts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
         $model = $this->findModel($id);
        $model->setAttribute('password_hash', null);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {


                date_default_timezone_set("Africa/Nairobi");  
 
                    if (Yii::$app->request->isAjax) {
                        // JSON response is expected in case of successful save
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return $this->redirect(['index']); 
                    }
               

            }

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('update', [
                    'model' => $model,
                  
                ]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
    }

     public function actionProfile($id)
    {
        $model = $this->findModel($id);
        $model->setAttribute('password_hash', null);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {


                date_default_timezone_set("Africa/Nairobi");  
 
                    if (Yii::$app->request->isAjax) {
                        // JSON response is expected in case of successful save
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        //return $this->redirect(['dash/index']); 
                    }
               

            }

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('updateprofile', [
                    'model' => $model,
                  
                ]);
            } else {
                return $this->render('updateprofile', [
                    'model' => $model,
                ]);
            }
    }




    /**
     * Deletes an existing Accounts model.
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
     * Finds the Accounts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Accounts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Accounts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
