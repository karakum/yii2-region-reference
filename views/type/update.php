<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\RegionType */


$this->title = Yii::t('regions/type', 'Update Region Type') . ': ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/type', 'Region Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('regions/type', 'Update');
?>
<div class="region-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
