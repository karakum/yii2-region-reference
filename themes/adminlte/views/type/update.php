<?php

/* @var $this yii\web\View */
/* @var $model karakum\region\models\RegionType */

$this->title = Yii::t('regions/type', 'Update Region Type') . ': ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/type', 'Region Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('regions/type', 'Update');
?>
<div class="region-type-update">

    <div class="box box-primary">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
