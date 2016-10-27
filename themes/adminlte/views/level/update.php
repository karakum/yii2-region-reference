<?php

/* @var $this yii\web\View */
/* @var $model karakum\region\models\RegionLevel */

$this->title = Yii::t('regions/level', 'Update Region Level') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/level', 'Region Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('regions/level', 'Update');
?>
<div class="region-level-update">

    <div class="box box-primary">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
