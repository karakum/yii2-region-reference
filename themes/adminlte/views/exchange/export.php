<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\ExportForm */
/* @var $countries karakum\region\models\Region[] */

$this->title = Yii::t('regions/export', 'Regions export');
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/region', 'Regions'), 'url' => ['region/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="region-type-export">

    <div class="box box-primary">
        <?php $form = ActiveForm::begin(); ?>

        <div class="box-body">
            <?= $form->field($model, 'region_id')->dropDownList(ArrayHelper::map($countries, 'id', 'name')) ?>
        </div>

        <div class="box-footer">
            <?= Html::submitButton(Yii::t('regions/export', 'Export'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
