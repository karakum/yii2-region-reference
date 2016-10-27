<?php

use karakum\region\models\Region;
use karakum\grid\GridView;
use nterms\pagesize\PageSize;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel karakum\region\models\RegionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $path Region[] */
/* @var $parent integer|null */

$this->title = Yii::t('regions/region', 'Regions');

if ($path) {
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
    foreach ($path as $item) {
        $this->params['breadcrumbs'][] = ['label' => $item->name, 'url' => ['index', 'id' => $item->id]];
    }
} else {
    $this->params['breadcrumbs'][] = $this->title;
}

$bulkActions = ['' => Yii::t('regions/region', 'With Selected Do')];

$bulkActions['activate'] = Yii::t('regions/region', 'Activate');
$bulkActions['deactivate'] = Yii::t('regions/region', 'Deactivate');
$bulkActions['delete'] = Yii::t('regions/region', 'Delete');

?>
<div class="region-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('regions/region', 'Create Region'), ['create', 'id' => $parent, 'redirect' => Url::current()], ['class' => 'btn btn-success']) ?>
    </p>
    <div>
        <?= Nav::widget([
            'options' => ['class' => 'nav-tabs'],
            'items' => [
                ['label' => Yii::t('regions/region', 'Structure'), 'url' => ['region/index']],
                ['label' => Yii::t('regions/region', 'List'), 'url' => ['region/all']],
            ],
        ]) ?>
        <br/>
        <div class="tab-content">


            <?= Html::beginForm(['bulk'], 'post'); ?>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
                    <?= Html::dropDownList('action', '', $bulkActions, ['class' => 'dropdown form-control',]) ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
                    <?= Html::submitButton(Yii::t('admin', 'Do'), ['class' => 'btn btn-info',]); ?>
                </div>
                <div class="col-lg-7 col-md-6 col-sm-0 col-xs-0">
                </div>
                <div class="col-lg-1 col-md-2 col-sm-4 col-xs-4">
                    <?= PageSize::widget([
                        'template' => '{list}',
                        'sizes' => [20 => 20, 50 => 50, 100 => 100, 200 => 200, '00' => 'All'],
                        'options' => ['class' => 'form-control'],
                    ]); ?>
                </div>
            </div>
            <?php if (Yii::$app->request->get('sort')) { ?>
                <?= Html::hiddenInput('sort', Yii::$app->request->get('sort')) ?>
            <?php } ?>
            <?= Html::hiddenInput('return', Yii::$app->controller->action->id) ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterSelector' => 'select[name="per-page"]',
                'columns' => [
                    ['class' => 'yii\grid\CheckboxColumn', 'options' => ['style' => 'width: 40px;']],
                    ['class' => 'yii\grid\SerialColumn', 'options' => ['style' => 'width: 40px;']],

                    ['attribute' => 'id', 'options' => ['style' => 'width: 100px;']],
                    [
                        'attribute' => 'level_id',
                        'value' => function (Region $data) {
                            return $data->level->type->code;
                        },
                        'filter' => false,
                    ],
                    'code',
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function (Region $data) {
                            return Html::a($data->name, ['index', 'id' => $data->id]);
                        },
                    ],
                    'fullname',
                    [
                        'attribute' => 'status',
                        'value' => 'statusName',
                        'filter' => Region::getStatuses(),
                    ],
                    ['attribute' => 'created', 'filter' => false, 'options' => ['style' => 'width: 140px;']],
                    ['attribute' => 'updated', 'filter' => false, 'options' => ['style' => 'width: 140px;']],
                    ['attribute' => 'deleted', 'filter' => false, 'options' => ['style' => 'width: 140px;'], 'visible' => $searchModel->status == Region::STATUS_DELETED],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete} {create}',
                        'options' => ['style' => 'width: 90px;'],
                        'buttons' => [
                            'create' => function ($url, $model, $key) {
                                return Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-plus']), $url);
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?= Html::endForm(); ?>
        </div>
    </div>

</div>
