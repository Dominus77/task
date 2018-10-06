<?php

/**
 * @var $this yii\web\View
 * @var $user app\models\User
 */

use yii\helpers\Html;

$urlManager = Yii::$app->urlManager;
$resetLink = $urlManager->createAbsoluteUrl(['v1/users/reset-password', 'token' => $user->password_reset_token]);
$link = $urlManager->hostInfo;
?>

<div class="password-reset">
    <p><?= Yii::t('app', 'Hello {username}', ['username' => $user->username]); ?>!</p>
    <p><?= Yii::t('app', 'You or someone else indicated your email address in the form of a password reset on the {:Website}. If you did not then just ignore this email.', [':Website' => Html::a(Yii::$app->name, $link)]) ?></p>
    <p><?= Yii::t('app', 'To reset your password follow the link below, or copy it to your browser.') ?></p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
    <p><?= Yii::t('app', 'The letter was sent by the robot and you do not need to respond to it.') ?></p>
    <br>
    <p><?= Yii::t('app', 'Sincerely, website administration {:Website}', [':Website' => Html::a(Yii::$app->name, $link)]) ?>
        .</p>
</div>
