<?php

/* @var $this yii\web\View */
/* @var $model karakum\region\models\Region */
/* @var $levels karakum\region\models\RegionLevel[] */

$this->title = Yii::t('regions/region', 'Update Region') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/region', 'Regions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('regions/region', 'Update');
?>
<div class="region-update">

    <div class="box box-primary">
        <?= $this->render('_form', [
            'model' => $model,
            'levels' => $levels,
        ]) ?>
    </div>

</div>
