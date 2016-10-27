<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\RegionType */

$this->title = Yii::t('regions/type', 'Create Region Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/type', 'Region Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
