<?php

use karakum\region\models\RegionLevel;
use karakum\region\models\RegionType;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\RegionLevel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="region-level-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box-body">

        <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map(RegionLevel::find()->all(), 'id', function ($el) {
            return $el->name ?: $el->type->code;
        }), ['prompt' => Yii::t('regions/level', 'Top level')]) ?>

        <?= $form->field($model, 'type_id')->dropDownList(ArrayHelper::map(RegionType::find()->all(), 'id', 'code')) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'level')->textInput() ?>

    </div>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('regions/level', 'Create') : Yii::t('regions/level', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
