<?php

use karakum\region\models\Region;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Region */
/* @var $path Region[] */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/region', 'Regions'), 'url' => ['index']];

foreach ($path as $item) {
    $this->params['breadcrumbs'][] = ['label' => $item->name, 'url' => ['index', 'id' => $item->id]];
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-view">

    <div class="box box-primary">
        <div class="box-header with-border">
            <div>
                <div class="box-tools pull-right">
                    <?= Html::a(Yii::t('regions/region', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('regions/region', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('regions/region', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'parent_id',
                    'format' => 'raw',
                    'value' => $model->parent ? Html::a($model->parent->name, ['region/view', 'id' => $model->parent_id]) : null,
                ],
                [
                    'attribute' => 'level_id',
                    'value' => $model->level->type->code,
                ],
                'code',
                'name',
                'fullname',
                'statusName',
                'created',
                'updated',
                'deleted',
            ],
        ]) ?>
    </div>

</div>
