<?php

namespace app\controllers;

use app\models\Supplier;
use app\models\SupplierSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SupplierController implements the CRUD actions for Supplier model.
 */
class SupplierController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Supplier models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SupplierSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Supplier model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Supplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Supplier();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Supplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Supplier model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Supplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Supplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Supplier::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    /**
     * Creates a new Supplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCsv()
    {
        $supplier = new Supplier();
        $ids = \Yii::$app->request->get('ids');
        $supplier->ids = $ids;
        return $this->render('csv',[
            'model' => $supplier
        ]);
    }


    public function actionExport()
    {
        $postData = \Yii::$app->request->post();

        $arrId = empty($postData['Supplier']['ids']) ? []:explode(',', $postData['Supplier']['ids']);

        $title  = 'id';
        if(!empty($postData['name'])){ $title .= ',name';}
        if(!empty($postData['code'])){ $title .= ',code';}
        if(!empty($postData['t_status'])){ $title  .= ',t_status';}
        $title .= "\n";

        $fileName = 'suppliers'.date('Ymd').'.csv';

        $dataArr = (new Supplier())::find()->andFilterWhere(['in', 'id', $arrId])->asArray()->all();

        $writeStr = '';
        if(!empty($dataArr)){
            foreach ($dataArr as $key => $value) {
                $writeStr .= $value['id'];
                if(!empty($postData['name'])){ $writeStr .= ','.$value['name'];}
                if(!empty($postData['code'])){ $writeStr .=','.$value['code'];}
                if(!empty($postData['t_status'])){ $writeStr  .= ','.$value['t_status'];}

                $writeStr .= "\n";
            }
        }

        $this->csvExport( $fileName, $title, $writeStr);
    }

    public function csvExport($file = '', $title = '', $data)
    {
        header("Content-Disposition:attachment;filename=".$file);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        ob_start();
        $wrstr = $title;
        $wrstr .= $data;
        $wrstr = iconv("utf-8", "GBK//ignore", $wrstr);
        ob_end_clean();
        echo $wrstr;
    }
}
