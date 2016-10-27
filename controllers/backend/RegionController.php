<?php

namespace karakum\region\controllers\backend;

use karakum\region\models\CreateRegionForm;
use karakum\region\RegionManager;
use Yii;
use karakum\region\models\Region;
use karakum\region\models\RegionSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RegionController implements the CRUD actions for Region model.
 */
class RegionController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return RegionManager
     */
    private function getRegionManager()
    {
        /** @var RegionManager $manager */
        $manager = Yii::$app->regionManager;
        return $manager;
    }

    /**
     * Lists all Region models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $searchModel = new RegionSearch();
        $params = Yii::$app->request->queryParams;
        $params['RegionSearch']['only_root'] = is_null($id) ? true : null;
        $params['RegionSearch']['parent_id'] = $id;
        $dataProvider = $searchModel->search($params);
        $dataProvider->pagination->pageSizeLimit = [0, 200];

        $root = null;
        if ($id) {
            $root = Region::findOne($id);
        }
        if ($root) {
            $path = $this->getRegionManager()->getRegionsPath($root);
        } else {
            $path = [];
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'path' => $path,
            'parent' => $id,
        ]);
    }

    public function actionAll()
    {
        $searchModel = new RegionSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        $dataProvider->pagination->pageSizeLimit = [0, 200];

        $path = [];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'path' => $path,
            'parent' => null,
        ]);
    }


    /**
     * Displays a single Region model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'path' => $this->getRegionManager()->getRegionsPath($model, true),
        ]);
    }

    /**
     * Creates a new Region model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer|null $id Parent ID
     * @param string|null $redirect
     * @return mixed
     */
    public function actionCreate($id = null, $redirect = null)
    {
        $region = new Region();
        $region->parent_id = $id;
        $model = new CreateRegionForm();
        $model->parent_id = $id;
        $model->mode = 0;

        if ($model->load(Yii::$app->request->post()) && $model->createRegion()) {
            return $this->redirect($redirect ?: ['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'region' => $region,
                'levels' => $this->getRegionManager()->getNextLevels(new Region(['parent_id' => $id])),
            ]);
        }
    }

    /**
     * Updates an existing Region model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Region::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'levels' => $this->getRegionManager()->getNextLevels($model),
            ]);
        }
    }

    /**
     * Deletes an existing Region model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionBulk()
    {
        $action = Yii::$app->request->post('action');
        $selection = Yii::$app->request->post('selection');
        $return = Yii::$app->request->post('return');
        $searchModel = new RegionSearch();
        $searchModel->load(Yii::$app->request->post());

        switch ($action) {
            case 'activate':
                Region::updateAll(['status' => Region::STATUS_ACTIVE], ['id' => $selection]);
                break;
            case 'deactivate':
                Region::updateAll(['status' => Region::STATUS_INACTIVE], ['id' => $selection]);
                break;
            case 'delete':
                $items = Region::find()->where(['id' => $selection])->all();
                foreach ($items as $item) {
                    $item->delete(); // see MarkDeletedBehavior::beforeDelete()
                }
                break;
        }

        return $this->redirect([$return] + $searchModel->filterParams());
    }

    /**
     * Finds the Region model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Region the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Region::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
