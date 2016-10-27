<?php

namespace karakum\region\controllers\frontend;

use karakum\region\models\Region;
use karakum\region\models\RegionSearch;
use karakum\region\RegionManager;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class AjaxController extends Controller
{

    /**
     * @return RegionManager
     */
    private function getRegionManager()
    {
        /** @var RegionManager $manager */
        $manager = Yii::$app->regionManager;
        return $manager;
    }

    public function actionIndex()
    {
        $articleSearch = new RegionSearch();
        $params = Yii::$app->request->queryParams;
        $rm = $this->getRegionManager();
        if ($rm->country) {
            /** @var Region $country */
            $country = Region::find()->where([
                'parent_id' => null,
                'code' => $rm->country,
            ])->one();
            if ($country) {
                $params['RegionSearch']['country_id'] = $country->id;
            }
        }
        $dataProvider = $articleSearch->search($params);

        $items = [];
        foreach ($dataProvider->getModels() as $model) {
            /** @var Region $model */
            $items[] = [
                'id' => $model->id,
                'text' => $model->name . ($model->fullname ? '(' . $model->fullname . ')' : ''),
                'data' => $model->toArray([
                    'id',
                    'name',
                    'fullname',
                ]),
            ];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'results' => $items,
        ];
    }

}
