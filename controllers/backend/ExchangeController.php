<?php

namespace karakum\region\controllers\backend;


use karakum\region\models\ExportForm;
use karakum\region\models\ImportForm;
use karakum\region\models\Region;
use karakum\region\models\RegionLevel;
use karakum\region\RegionManager;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;

class ExchangeController extends Controller
{
    public function behaviors()
    {
        return [
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
     * Export regions
     * @return mixed
     */
    public function actionExport()
    {
        $model = new ExportForm();
        if ($model->load(Yii::$app->request->post())) {
            $region = Region::findOne($model->region_id);

            $data = $this->getRegionManager()->exportRegion($region);
            $name = $region->code ?: $region->name;

            Yii::$app->response->sendContentAsFile(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), "regions_$name.json", [
                'mimeType' => 'application/json',
            ]);
            return;
        }

        $countries = Region::find()->where(['parent_id' => null])->all();

        return $this->render('export', [
            'model' => $model,
            'countries' => $countries,
        ]);
    }

    /**
     * Import regions
     * @return mixed
     */
    public function actionImport()
    {
        $model = new ImportForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            if ($model->validate()) {
                $data = json_decode(file_get_contents($model->importFile->tempName), true);
                $result = $this->getRegionManager()->importRegion($data, RegionLevel::findOne($model->level_id));
                if ($result['errors']) {
                    $message = Yii::t('regions/import', 'Import {fileName} failed', ['fileName' => $model->importFile->name]);
                    foreach ($result['errors'] as $error) {
                        $message .= "<br/>\n" . $error;
                    }
                    Yii::$app->session->addFlash('danger', $message);
                } else {
                    $message = Yii::t('regions/import', 'Successfully imported {fileName}', ['fileName' => $model->importFile->name]);
                    $message .= "<br/>\n" . Yii::t('regions/import', 'Inserted') . ': ' . Yii::t('regions/import', '{cnt,plural,one{# item} other{# items}}', ['cnt' => $result['insert']]);
                    $message .= "<br/>\n" . Yii::t('regions/import', 'Updated') . ': ' . Yii::t('regions/import', '{cnt,plural,one{# item} other{# items}}', ['cnt' => $result['update']]);
                    $message .= "<br/>\n" . Yii::t('regions/import', 'Deleted') . ': ' . Yii::t('regions/import', '{cnt,plural,one{# item} other{# items}}', ['cnt' => $result['delete']]);
                    Yii::$app->session->addFlash('success', $message);
                }
                return $this->redirect(['import']);
            }
        }
        $levels = RegionLevel::find()->where(['parent_id' => null])->all();

        return $this->render('import', [
            'model' => $model,
            'levels' => $levels,
        ]);
    }

}
