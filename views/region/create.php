<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\CreateRegionForm */
/* @var $region karakum\region\models\Region */
/* @var $levels karakum\region\models\RegionLevel[] */

if ($region->parent_id) {
    $this->title = Yii::t('regions/region', 'Create Region in {region}', ['region' => $region->parent->name]);
} else {
    $this->title = Yii::t('regions/region', 'Create Country');
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/region', 'Regions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-create', [
        'model' => $model,
        'levels' => $levels,
    ]) ?>

</div>
