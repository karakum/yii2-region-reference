<?php

use karakum\grid\GridView;
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

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'region_id')->dropDownList(ArrayHelper::map($countries, 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('regions/export', 'Export'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
