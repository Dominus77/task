<?php

namespace api\modules\v1;

use Yii;

/**
 * Class Bootstrap
 * @package api\modules\v1
 */
class Bootstrap
{
    public function __construct()
    {
        $urlManager = Yii::$app->urlManager;
        $urlManager->addRules(
            $this->rulesApi()
        );
    }

    /**
     * Group rules backend
     * @return array
     */
    protected function rulesApi()
    {
        return [
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => [
                    'v1/user'
                ],
                'except' => ['delete'],
                'pluralize' => true,
            ],
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => [
                    'v1/message'
                ],
                'pluralize' => false,
            ],
            [
                'class' => 'yii\rest\UrlRule',
                'pluralize' => false,
                'prefix' => 'v1',
                'controller' => [
                    'test' => 'v1/test',
                ],
            ],
        ];
    }
}
