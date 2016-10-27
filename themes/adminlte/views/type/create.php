<?php


/* @var $this yii\web\View */
/* @var $model karakum\region\models\RegionType */

$this->title = Yii::t('regions/type', 'Create Region Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/type', 'Region Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-type-create">

    <div class="box box-primary">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
