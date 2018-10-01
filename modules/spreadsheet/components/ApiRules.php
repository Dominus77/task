<?php

namespace modules\spreadsheet\components;

/**
 * Class ApiConfig
 * @package modules\spreadsheet\components
 */
class ApiRules
{
    /**
     * Конфиг API для Spreadsheet
     *
     * @return mixed
     */
    public static function getSpreadsheetRules()
    {
        $conf = require_once dirname(__DIR__) . '/config/apiRules.php';
        return is_array($conf) ? $conf : '';
    }
}