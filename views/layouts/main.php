<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\components\Rbac;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= app\widgets\LoginFormWidget::widget() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $menuItems = [
        [
            'label' => Yii::t('app', 'Home'),
            'url' => ['/site/index']
        ],
        [
            'label' => Yii::t('app', 'About'),
            'url' => ['/site/about']
        ],
        [
            'label' => Yii::t('app', 'Contact'),
            'url' => ['/site/contact']
        ],
        [
            'label' => Yii::t('app', 'Tables'),
            'url' => ['/spreadsheet/default/index'],
            'visible' => Yii::$app->user->can(Rbac::PERMISSION_ACCESS_TABLE)
        ],
    ];

    if (Yii::$app->user->isGuest) {
        /*$menuItems[] = [
            'label' => Yii::t('app', 'Login'),
            'url' => ['/site/login'],
            'visible' => Yii::$app->user->isGuest
        ];*/
        $menuItems[] = ['label' => Yii::t('app', 'Login'),'url' => '#','options' => [
            'data' => [
                'toggle' => 'modal',
                'target' => '#login-modal',
            ],
        ]
        ];
    } else {
        /** @var app\models\User $identity */
        $identity = Yii::$app->user->identity;
        $menuItems[] = [
            'label' => Yii::t('app', 'Menu ({:username})', [':username' => $identity->username]),
            'items' => [
                [
                    'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::t('app', 'Profile'),
                    'url' => ['/profile/index']
                ],
                [
                    'label' => '<i class="glyphicon glyphicon-log-out"></i> ' . Yii::t('app', 'Logout'),
                    'url' => ['/site/logout'],
                    'linkOptions' => [
                        'data-method' => 'POST'
                    ],
                ],
            ],
        ];
    }

    echo Nav::widget([
        'options' => [
            'class' => 'navbar-nav navbar-right'
        ],
        'activateParents' => true,
        'encodeLabels' => false,
        'items' => array_filter($menuItems),
    ]);

    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
