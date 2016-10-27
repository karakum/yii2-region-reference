<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\Region */
/* @var $levels karakum\region\models\RegionLevel[] */

$this->title = Yii::t('regions/region', 'Update Region') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/region', 'Regions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('regions/region', 'Update');
?>
<div class="region-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'levels' => $levels,
    ]) ?>

</div>
