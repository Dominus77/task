<?php

namespace modules\spreadsheet\components;

/**
 * Class ApiConfig
 * @package modules\spreadsheet\components
 */
class ApiConfig
{
    /**
     * Конфиг API для Spreadsheet
     *
     * @return mixed
     */
    public static function getSpreadsheetConfig()
    {
        $conf = require_once dirname(__DIR__) . '/config/api.php';
        return is_array($conf) ? $conf : '';
    }
}