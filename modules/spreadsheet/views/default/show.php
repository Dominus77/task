<?php

/**
 * @var $this yii\web\View
 * @var $model modules\spreadsheet\models\Table
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $name string
 */

use yii\grid\GridView;
use yii\helpers\Html;
use modules\spreadsheet\widgets\MenuWidgets;

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
        <div class="col-md-9">
            <?= GridView::widget([
                'id' => 'grid-tables',
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover',
                ],
            ]); ?>
        </div>
    </div>
</div>
