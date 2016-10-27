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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('regions/level', 'Create Region Level'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= TreeGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
