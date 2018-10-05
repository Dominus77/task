<?php

/**
 * @var $this yii\web\View
 * @var $user app\models\User
 */

$urlManager = Yii::$app->urlManager;
$confirmLink = $urlManager->createAbsoluteUrl(['site/email-confirm', 'token' => $user->email_confirm_token]);

echo Yii::t('app', 'Hello!');

echo Yii::t('app', 'When registering on the site {:Website} you or someone else has indicated the address of your email.', [':Website' => Yii::$app->name]);

echo Yii::t('app', 'If you did not do this, then just ignore this letter.');

echo Yii::t('app', 'To activate your account, please follow the link below or copy it to your browser.');

echo $confirmLink;

echo Yii::t('app', 'The letter was sent by the robot and you do not need to respond to it.');


echo Yii::t('app', 'Sincerely, website administration {:Website}', [':Website' => Yii::$app->name]);
