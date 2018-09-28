<?php

namespace modules\spreadsheet\commands;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use app\components\helpers\Console;
use modules\spreadsheet\components\Import;
use modules\spreadsheet\traits\ModuleTrait;
use yii\helpers\ArrayHelper;

/**
 * Class ImportController
 * @package modules\spreadsheet\commands
 */
class ImportController extends Controller
{
    use ModuleTrait;

    public $color = true;

    /**
     * Commands
     *
     * @inheritdoc
     */
    public function actionIndex()
    {
        echo 'yii spreadsheet/import/show-files-names' . PHP_EOL;
        echo 'yii spreadsheet/import/create-table' . PHP_EOL;
        echo 'yii spreadsheet/import/remove-table' . PHP_EOL;
        echo 'yii spreadsheet/import/load-data' . PHP_EOL;
    }

    /**
     * Show names Files
     */
    public function actionShowFilesNames()
    {
        $this->stderr(Console::convertEncoding(implode(PHP_EOL, $this->getFilesNames())), Console::FG_GREEN, Console::BOLD);
    }

    /**
     * Create Table
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \yii\db\Exception
     */
    public function actionCreateTable()
    {
        $names = array_flip($this->getFilesNames());
        if (($select = Console::convertEncoding($names)) && is_array($select)) {
            $sel = $this->select(Console::convertEncoding(Yii::t('app', 'File Name')) . ':', $select);
            $file = ArrayHelper::getValue($names, $sel);
            $model = new Import();
            $model->createDbTable($file);
        }
        $this->log(true);
    }

    /**
     * Remove Table
     *
     * @throws \yii\db\Exception
     */
    public function actionRemoveTable()
    {
        $names = array_flip($this->getFilesNames());
        if (($select = Console::convertEncoding($names)) && is_array($select)) {
            $name = $this->select(Console::convertEncoding(Yii::t('app', 'Table Name')) . ':', $select);
            $model = new Import();
            $model->removeDbTable($name);
        }
        $this->log(true);
    }

    /**
     * Loading data into the database table
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \yii\db\Exception
     */
    public function actionLoadData()
    {
        $result = false;
        $names = array_flip($this->getFilesNames());

        if (($select = Console::convertEncoding($names)) && is_array($select)) {
            $name = $this->select(Console::convertEncoding(Yii::t('app', 'Table Name')) . ':', $select);
            $file = ArrayHelper::getValue($names, $name);
            $model = new Import();
            if ($model->clearTable($name)) {
                $result = $model->loadDataDbTable($file);
            }
        }
        if ($result) {
            $this->log(true);
        } else {
            $this->log(false);
        }
    }

    /**
     * @param bool|int $success
     */
    private function log($success = false)
    {
        if ($success === true) {
            $this->stdout(Console::convertEncoding(Yii::t('app', 'Done!')), Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stderr(Console::convertEncoding(Yii::t('app', 'Fail!')), Console::FG_RED, Console::BOLD);
        }
        echo PHP_EOL;
    }
}
