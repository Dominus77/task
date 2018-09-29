<?php

/**
 * @var $this yii\web\View
 * @var $model modules\spreadsheet\models\Table
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $name string
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use modules\spreadsheet\widgets\MenuWidgets;

$name = $model->tableName;
$this->title = Yii::t('app', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spreadsheet-default-show">

    <h1><?= Html::encode($this->title) ?>: "<?= Html::encode($name); ?>"</h1>

    <div class="row">
        <div class="col-md-3">
            <?= MenuWidgets::widget() ?>
        </div>
        <?php
        $columns = ArrayHelper::merge([['class' => 'yii\grid\SerialColumn']], $model->getFieldsTable());
        $columns = ArrayHelper::merge($columns, [[
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t('app', 'Actions'),
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url, $model, $key) use ($name) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', [
                        '/spreadsheet/default/view', 'name' => $name, 'id' => $model['id']
                    ], [
                        'title' => Yii::t('app', 'View'),
                        'data' => [
                            'toggle' => 'tooltip',
                            'pjax' => 0,
                        ]
                    ]);
                },
                'update' => function ($url, $model, $key) use ($name) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', [
                        '/spreadsheet/default/update', 'name' => $name, 'id' => $model['id']
                    ], [
                        'title' => Yii::t('app', 'Update'),
                        'data' => [
                            'toggle' => 'tooltip',
                            'pjax' => 0,
                        ]
                    ]);
                },
                'delete' => function ($url, $model, $key) use ($name) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', [
                        '/spreadsheet/default/delete', 'name' => $name, 'id' => $model['id']
                    ], [
                        'title' => Yii::t('app', 'Delete'),
                        'data' => [
                            'method' => 'post',
                            'pjax' => 0,
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        ],
                    ]);
                },
            ],
        ]]);
        ?>
        <div class="col-md-9">
            <?= GridView::widget([
                'id' => 'grid-tables',
                'dataProvider' => $model->getActiveProvider(),
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover',
                ],
                'columns' => $columns,
            ]); ?>
        </div>
    </div>
</div>
