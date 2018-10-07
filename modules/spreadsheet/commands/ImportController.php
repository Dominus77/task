<?php

namespace modules\spreadsheet\commands;

use Yii;
use yii\console\Controller;
use app\components\helpers\Console;
use modules\spreadsheet\components\Import;
use modules\spreadsheet\traits\ModuleTrait;
use modules\spreadsheet\models\Spreadsheet;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class ImportController
 * @package modules\spreadsheet\commands
 * @property array $tablesNames
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
        echo 'yii spreadsheet/import/clear-data' . PHP_EOL;
        echo 'yii spreadsheet/import/show-tables-names' . PHP_EOL;
        echo 'yii spreadsheet/import/show-table-info' . PHP_EOL;
    }

    /**
     * Show names Files
     */
    public function actionShowFilesNames()
    {
        $this->stderr(Console::convertEncoding(implode(PHP_EOL, $this->getFilesNames())), Console::FG_GREEN, Console::BOLD);
    }

    /**
     * Show names Tables
     */
    public function actionShowTablesNames()
    {
        $this->stderr(Console::convertEncoding(implode(PHP_EOL, $this->getTablesNames())), Console::FG_GREEN, Console::BOLD);
    }

    /**
     * Show table info
     *
     * @throws \yii\db\Exception
     */
    public function actionShowTableInfo()
    {
        $names = array_flip($this->getTablesNames());
        if (($select = Console::convertEncoding($names)) && is_array($select)) {
            $name = $this->select(Console::convertEncoding(Yii::t('app', 'Table Name')) . ':', $select);
            $this->getTableInfo($name);
        } else {
            $this->stdout(Yii::t('app', 'No table specified.') . PHP_EOL, Console::FG_YELLOW, Console::BOLD);
        }
    }

    /**
     * @return array
     */
    public function getTablesNames()
    {
        $model = new Spreadsheet();
        $tables = $model->getTablesNames();
        $files = $this->getFilesNames();
        $tableName = ArrayHelper::getColumn($tables, 'tableName');
        $result = [];
        foreach ($tableName as $value) {
            $key = ArrayHelper::getValue(array_flip($files), $value);
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * Create select table
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \yii\db\Exception
     */
    public function actionCreateTable()
    {
        $result = false;
        $tables = array_flip($this->getTablesNames());
        $files = array_flip($this->getFilesNames());
        $names = [];
        foreach ($files as $key => $value) {
            if (!ArrayHelper::isIn($value, $tables)) {
                $names[$key] = $value;
            }
        }
        if ($names) {
            if (($select = Console::convertEncoding($names)) && is_array($select)) {
                $sel = $this->select(Console::convertEncoding(Yii::t('app', 'File Name')) . ':', $select);
                $file = ArrayHelper::getValue($names, $sel);
                $model = new Import();
                $result = $model->createDbTable($file);
            }
            if ($result) {
                $this->log(true);
            } else {
                $this->log(false);
            }
        } else {
            $this->stdout(Yii::t('app', 'All tables are created.'), Console::FG_GREEN, Console::BOLD);
        }
    }

    /**
     * Remove select table
     *
     * @throws \yii\base\ErrorException
     * @throws \yii\db\Exception
     */
    public function actionRemoveTable()
    {
        $names = array_flip($this->getTablesNames());
        if (($select = Console::convertEncoding($names)) && is_array($select)) {
            $name = $this->select(Console::convertEncoding(Yii::t('app', 'Table Name')) . ':', $select);
            if ($this->confirm(Yii::t('app', 'Do you really want a table "{:Name}"?', [
                ':Name' => $name,
            ]), false)) {
                $model = new Import();
                $model->removeDbTable($name);
                $this->log(true);
            }
        } else {
            $this->log(false);
        }
    }

    /**
     * Loading data into the database select table
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \yii\db\Exception
     */
    public function actionLoadData()
    {
        $result = false;
        $names = array_flip($this->getTablesNames());
        if (($select = Console::convertEncoding($names)) && is_array($select)) {
            $name = $this->select(Console::convertEncoding(Yii::t('app', 'Table Name')) . ':', $select);
            $file = ArrayHelper::getValue($names, $name);
            if ($this->confirm(Yii::t('app', 'All data in the table "{:Name}" will be overwritten, continue?', [
                ':Name' => $name,
            ]), false)) {
                $model = new Import();
                if ($model->clearTable($name)) {
                    $result = $model->loadDataDbTable($file);
                }
            }
        }
        if ($result) {
            $this->log(true);
        } else {
            $this->log(false);
        }
    }

    /**
     * Clear Data select table
     *
     * @throws \yii\db\Exception
     */
    public function actionClearData()
    {
        $result = false;
        $names = array_flip($this->getTablesNames());
        if (($select = Console::convertEncoding($names)) && is_array($select)) {
            $name = $this->select(Console::convertEncoding(Yii::t('app', 'Table Name')) . ':', $select);
            if ($this->confirm(Yii::t('app', 'Are you sure you want to clear the data table "{:Name}"?', [
                ':Name' => $name,
            ]), false)) {
                $model = new Import();
                $result = $model->clearTable($name);
            }
        }

        if ($result) {
            $this->log(true);
        } else {
            $this->log(false);
        }
    }

    /**
     * Render table info
     *
     * @param string $tableName
     * @throws \yii\db\Exception
     */
    protected function getTableInfo($tableName = '')
    {
        if ($tableName) {
            $db = Yii::$app->db;
            $result = $db->getTableSchema($tableName, true);

            $table = new \modules\spreadsheet\components\Table();
            $countRows = $table->getCountItemsTable($tableName);

            $strColumns = implode(' | ', $result->getColumnNames());
            $length = mb_strlen($strColumns);
            $decor = '';
            for ($i = 0; $i <= $length; $i++) {
                $decor .= '-';
            }
            $this->stdout(PHP_EOL . "TABLE: {$tableName}" . PHP_EOL);
            $this->stdout("ROWS: {$countRows}" . PHP_EOL);
            $this->stdout($decor . PHP_EOL);
            $this->stdout($strColumns . PHP_EOL);
            $this->stdout($decor . PHP_EOL);
        } else {
            $this->stdout(Yii::t('app', 'No table specified.') . PHP_EOL, Console::FG_YELLOW, Console::BOLD);
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
