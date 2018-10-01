<?php

namespace modules\spreadsheet\components;

/**
 * Class ApiControllers
 * @package modules\spreadsheet\components
 */
class ApiControllers
{
    /**
     * Контроллеры для api rule
     * 
     * @return mixed
     */
    public static function getControllers()
    {
        $controllers = require_once dirname(__DIR__) . '/config/api.php';
        return $controllers;
    }
}