<?php

/**
 * @var $this yii\web\View
 * @var $user app\models\User
 */

use yii\helpers\Html;

$urlManager = Yii::$app->urlManager;
$confirmLink = $urlManager->createAbsoluteUrl(['site/email-confirm', 'token' => $user->email_confirm_token]);
$link = $urlManager->createAbsoluteUrl(['site/index']);
?>

<div class="email-confirm">
    <p><?= Yii::t('app', 'Hello!'); ?></p>
    <p><?= Yii::t('app', 'When registering on the site {:Website} you or someone else has indicated the address of your email.', [':Website' => Html::a(Yii::$app->name, $link)]) ?></p>
    <p><?= Yii::t('app', 'If you did not do this, then just ignore this letter.'); ?></p>
    <p><?= Yii::t('app', 'To activate your account, please follow the link below or copy it to your browser.'); ?></p>
    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
    <p><?= Yii::t('app', 'The letter was sent by the robot and you do not need to respond to it.') ?></p>
    <br>
    <p><?= Yii::t('app', 'Sincerely, website administration {:Website}', [':Website' => Html::a(Yii::$app->name, $link)]) ?></p>
</div>
