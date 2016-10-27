<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\RegionLevel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/level', 'Region Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-level-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('regions/level', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('regions/level', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('regions/level', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'parent_id',
                'value' => $model->parent_id ? ($model->parent->name ?: $model->parent->type->code) : null,
            ],
            [
                'attribute' => 'type',
                'value' => $model->type->code,
            ],
            'name',
            'level',
        ],
    ]) ?>

</div>
