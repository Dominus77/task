<?php

namespace api\modules\v1;

use Yii;
use modules\spreadsheet\components\ApiConfig as SpreadsheetApiConfig;

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
            $this->getSpreadsheetConfig(),
        ];
    }

    /**
     * Контроллеры для модуля spreadsheet
     * @return mixed
     */
    protected function getSpreadsheetConfig()
    {
        return SpreadsheetApiConfig::getSpreadsheetConfig();
    }
}
