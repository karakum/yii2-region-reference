<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\RegionLevel */

$this->title = $model->name ?: $model->type->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/level', 'Region Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-level-view">

    <div class="box box-primary">
        <div class="box-header with-border">
            <div>
                <div class="box-tools pull-right">
                    <?= Html::a(Yii::t('regions/level', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('regions/level', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('regions/level', 'Are you sure you want to delete this item?'),
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
                    'value' => $model->parent_id ? ($model->parent->name ?: $model->parent->type->code) : null,
                ],
                [
                    'attribute' => 'type_id',
                    'value' => $model->type->code,
                ],
                'name',
                'level',
            ],
        ]) ?>
    </div>

</div>
