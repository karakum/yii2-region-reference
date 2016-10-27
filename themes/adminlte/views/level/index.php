<?php

use karakum\region\models\RegionLevel;
use karakum\grid\TreeGridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel karakum\region\models\RegionLevelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('regions/level', 'Region Levels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-level-index">

    <div class="box box-primary">
        <div class="box-header with-border">
            <div>
                <div class="box-tools pull-right">
                    <?= Html::a(Yii::t('regions/level', 'Create Region Level'), ['create'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>

        <div class="box-body">
            <?= TreeGridView::widget([
                'dataProvider' => $dataProvider,
                'pluginOptions' => [
                    'treeColumn' => 2,
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    [
                        'attribute' => 'name',
                        'value' => function (RegionLevel $data) {
                            return $data->name ?: $data->type->code;
                        },
                    ],
                    [
                        'attribute' => 'type_id',
                        'value' => function (RegionLevel $data) {
                            return $data->type->code;
                        },
                    ],
                    'top_id',
                    'level',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>