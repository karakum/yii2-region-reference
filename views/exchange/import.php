<?php

use karakum\grid\GridView;
use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\ImportForm */
/* @var $levels karakum\region\models\RegionLevel[] */

$this->title = Yii::t('regions/import', 'Regions import');
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/region', 'Regions'), 'url' => ['region/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="region-type-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'level_id')->dropDownList(ArrayHelper::map($levels, 'id', 'name')) ?>

    <?= $form->field($model, 'importFile')->fileInput(['accept' => 'application/json']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('regions/import', 'Import'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
