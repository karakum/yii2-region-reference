<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\ImportForm */
/* @var $levels karakum\region\models\RegionLevel[] */

$this->title = Yii::t('regions/type', 'Regions import');
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/region', 'Regions'), 'url' => ['region/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="region-type-import">

    <div class="box box-primary">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="box-body">
            <?= $form->field($model, 'level_id')->dropDownList(ArrayHelper::map($levels, 'id', 'name')) ?>

            <?= $form->field($model, 'importFile')->fileInput(['accept' => 'application/json']) ?>
        </div>

        <div class="box-footer">
            <?= Html::submitButton(Yii::t('regions/type', 'Import'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
