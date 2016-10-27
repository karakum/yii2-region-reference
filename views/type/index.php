<?php

use karakum\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel karakum\region\models\RegionTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('regions/type', 'Region Types');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="region-type-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('regions/type', 'Create Region Type'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'code',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
