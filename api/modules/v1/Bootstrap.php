<?php

namespace api\modules\v1;

use Yii;
use modules\spreadsheet\components\ApiRules as SpreadsheetApiRules;

/**
 * Class Bootstrap
 * @package api\modules\v1
 */
class Bootstrap
{
    /**
     * Bootstrap constructor.
     */
    public function __construct()
    {
        $urlManager = Yii::$app->urlManager;
        $urlManager->addRules(
            $this->rulesApi()
        );
    }

    /**
     * Rules API
     *
     * @return array
     */
    protected function rulesApi()
    {
        return [
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => [
                    'v1/user',
                ],
                'except' => ['delete'],
                'pluralize' => true,
                'extraPatterns' => [
                    'POST signup' => 'signup',
                    'GET profile' => 'profile',
                ],
            ],
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => [
                    'v1/message'
                ],
                'pluralize' => false,
            ],
            $this->getSpreadsheetRules(),
        ];
    }

    /**
     * Правила API для модуля spreadsheet
     *
     * @return mixed
     */
    protected function getSpreadsheetRules()
    {
        return SpreadsheetApiRules::getSpreadsheetRules();
    }
}
