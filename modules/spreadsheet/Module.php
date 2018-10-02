<?php

namespace modules\spreadsheet;

use Yii;
use yii\console\Application as ConsoleApplication;

/**
 * Class Module
 * @package modules\spreadsheet
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public static $name = 'spreadsheet';

    /**
     * Search file pattern
     *
     * @var string
     */
    public static $pattern = '*.xls';

    /**
     * Version Api
     *
     * @var string
     */
    public $versionApi = 'v1';

    /**
     * @var int the permission to be set for newly generated code files.
     * This value will be used by PHP chmod function.
     * Defaults to 0666, meaning the file is read-writable by all users.
     */
    public $newFileMode = 0666;

    /**
     * @var int the permission to be set for newly generated directories.
     * This value will be used by PHP chmod function.
     * Defaults to 0777, meaning the directory can be read, written and executed by all users.
     */
    public $newDirMode = 0777;

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'modules\spreadsheet\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (Yii::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'modules\spreadsheet\commands';
        }
    }
}
