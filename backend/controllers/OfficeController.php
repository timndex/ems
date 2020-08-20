<?php

namespace backend\controllers;

use Yii;
use app\models\Office;
use app\models\OfficeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Patches\Patch;
use yii\helpers\Json;
use yii\filters\AccessControl;

/**
 * OfficeController implements the CRUD actions for Office model.
 */
class OfficeController extends Controller
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
     * Lists all Office models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OfficeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

      if (Yii::$app->request->post('hasEditable')) {
      $office_id=$_POST['editableKey'];
      $model = Office::findOne($office_id);
      $out = Json::encode(['output'=>'', 'message'=>'']);
      $post = [];
      $redirectFlag = false;
      $posted = current($_POST['Office']);
      $post['Office'] = $posted;
      if ($model->load($post)) {
        date_default_timezone_set("Africa/Nairobi");
        $model->updated_by  = \Yii::$app->user->identity->id;
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
     * Displays a single Office model.
     * @param string $id
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
     * Creates a new Office model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Office();

            if ($model->load(Yii::$app->request->post())) {
                date_default_timezone_set("Africa/Nairobi");
                $model->office_id = Patch::randomNumbers();
                $model->created_at = date('Y:m:d H:i:s');
                $model->created_by = \Yii::$app->user->identity->id;
                $model->status = 9; 
                //$model->save();

                if ($model->save(false)) {
                   
                    if (Yii::$app->request->isAjax) {
                        // JSON response is expected in case of successful save
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return $this->redirect(['index']); 
                    }

                               
                }

            }

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            } else {
               return $this->render('create', [
                    'model' => $model,
                ]);
            }
    }

    /**
     * Updates an existing Office model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
        

           if ($model->save(false)) {
                   
                    if (Yii::$app->request->isAjax) {
                        // JSON response is expected in case of successful save
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return $this->redirect(['index']); 
                    }

                               
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

    /**
     * Deletes an existing Office model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Office model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Office the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Office::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
