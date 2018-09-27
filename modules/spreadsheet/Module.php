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
    public static $name = 'spreadsheet';

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
