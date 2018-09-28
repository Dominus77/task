<?php

namespace modules\spreadsheet\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use modules\spreadsheet\traits\ModuleTrait;

/**
 * Class Import
 * @package modules\spreadsheet\components
 */
class Import
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $pattern = '*.xls';

    /**
     * @var array
     */
    public $cells = [];

    /**
     * @var null
     */
    private $_dir = null;

    /**
     * @var null
     */
    private $_parseData = null;

    /**
     * Import constructor.
     */
    public function __construct()
    {
        $this->getDir();
    }

    /**
     * Возвращает путь к директории
     *
     * @return bool|string
     */
    private function getDir()
    {
        if ($this->_dir === null) {
            $this->_dir = $this->getUploadPath();
        }
        return $this->_dir;
    }

    /**
     * Возвращает файлы с указаным расширением из директории
     *
     * @return array
     */
    public function getFiles()
    {
        return FileHelper::findFiles($this->_dir, [
            'only' => [$this->pattern],
        ]);
    }

    /**
     * Возвращает имена файлов
     *
     * @return array
     */
    public function getFilesNames()
    {
        $files = $this->getFiles();
        $names = [];
        foreach ($files as $file) {
            if (file_exists($file)) {
                $info = pathinfo($file);
                $names[$file] = ArrayHelper::getValue($info, 'filename');
            }
        }
        return $names;
    }

    /**
     * Колонки
     *
     * @return array
     */
    public function getColumns()
    {
        $data = $this->_parseData;
        if (isset($data['types'])) {
            if (!isset($data['types']['id'])) {
                $IdElement = ['id' => ArrayHelper::getValue($this->getTypesArray(), 'n')];
                $data['types'] = ArrayHelper::merge($IdElement, $data['types']);
            }
            return $data['types'];
        }
        return [];
    }

    /**
     * Формируем строку колонок для создания таблицы
     *
     * @return string
     */
    public function getDbColumnTable()
    {
        $columns = $this->getColumns();
        $str = '';
        foreach ($columns as $key => $value) {
            if ($key == 'id') {
                $str .= $key . ' ' . $value . ' AUTO_INCREMENT, ';
            } else {
                $str .= $key . ' ' . $value . ', ';
            }
        }
        return $str;
    }

    /**
     * Создаем таблицу если она еще не создана
     *
     * @param string $file
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \yii\db\Exception
     */
    public function createDbTable($file = '')
    {
        if ($file) {
            $this->_parseData = $this->parseFile($file);
            $table_name = $this->_parseData['name'];

            $columns = $this->getDbColumnTable();
            $db = Yii::$app->db;
            if ($db->getTableSchema($table_name, true) === null) {
                if ($columns) {
                    $query = $db->createCommand('CREATE TABLE ' . $table_name . ' (' . $columns . ' PRIMARY KEY (id)) ENGINE InnoDB CHARACTER SET utf8;');
                    $query->query();
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Удаляем таблицу, если она существует
     *
     * @param string $table_name
     * @return bool
     * @throws \yii\db\Exception
     */
    public function removeDbTable($table_name = '')
    {
        if ($table_name) {
            $db = Yii::$app->db;
            if ($db->getTableSchema($table_name, true) !== null) {
                $query = $db->createCommand('DROP TABLE ' . $table_name . ';');
                $query->query();
                return true;
            }
        }
        return false;
    }

    /**
     * Заполняем таблицу данными
     *
     * @param string $file
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \yii\db\Exception
     */
    public function loadDataDbTable($file = '')
    {
        if ($file) {
            $this->_parseData = $this->parseFile($file);
            $table_name = $this->_parseData['name'];

            $arr = $this->_parseData['data'];

            // Поля
            $fields = '';
            foreach ($arr[2] as $key => $cell) {
                if ($key != 'id') {
                    $fields .= '`' . $key . '`' . ',';
                }
            }
            $fields = trim($fields, ',');

            $db = Yii::$app->db;

            // Значения
            $str = '';
            foreach ($arr as $item) {
                $str .= "(";
                foreach ($item as $key => $cell) {
                    if ($key != 'id') {
                        $str .= $db->quoteValue($cell) . ",";
                    }
                }
                $str = trim($str, ",");
                $str .= "),";
            }
            $str = trim($str, ",");

            if ($db->getTableSchema($table_name, true) !== null) {
                $transaction = $db->beginTransaction();
                try {
                    // INSERT INTO `table_name` (``,``,``...) VALUES ('','','',...);
                    $query = $db->createCommand('INSERT INTO `' . $table_name . '` (' . $fields . ') VALUES ' . $str . ';');
                    $query->query();
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollback();
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Очищаем таблицу
     *
     * @param $table_name
     * @return bool
     * @throws \yii\db\Exception
     */
    public function clearTable($table_name)
    {
        if ($table_name) {
            $db = Yii::$app->db;
            if ($db->getTableSchema($table_name, true) !== null) {
                $query = $db->createCommand('TRUNCATE `' . $table_name . '`;');
                $result = $query->query();
                if ($result)
                    return true;
            }
        }
        return false;
    }

    /**
     * Тип поля
     *
     * @param string $type
     * @return mixed
     */
    public function getType($type)
    {
        return ArrayHelper::getValue($this->getTypesArray(), $type);
    }

    /**
     * Парсер
     *
     * $result = [
     *      'name' => 'fileName',
     *      'data' => [
     *          2 => [
     *              'attribute' => 'value',
     *              //...
     *          ],
     *          //...
     *      ],
     *      'types' => [
     *          'attribute' => 'type',
     *          //...
     *      ],
     *      'cells' => [
     *          'A' => 'attribute',
     *          //...
     *      ],
     * ];
     *
     * @param string $file
     * @return array|null
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function parseFile($file = '')
    {
        $inputFileName = $file;

        $result = [];
        if (file_exists($inputFileName)) {

            $info = pathinfo($inputFileName);
            $result['name'] = $info['filename'];

            /**  Identify the type of $inputFileName  **/
            $inputFileType = IOFactory::identify($inputFileName);
            /**  Create a new Reader of the type that has been identified  **/
            $reader = IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($inputFileName);
            $rowIterator = $spreadsheet->getActiveSheet()->getRowIterator();

            foreach ($rowIterator as $row) {
                if ($row->getRowIndex() == 1) {
                    $cellIterator = $row->getCellIterator();
                    foreach ($cellIterator as $cell) {
                        $this->cells[$cell->getColumn()] = $cell->getCalculatedValue();
                    }
                } else {
                    $cellIterator = $row->getCellIterator();
                    foreach ($cellIterator as $cell) {
                        $cellPath = $cell->getColumn();
                        if (isset($this->cells[$cellPath])) {
                            $result['data'][$row->getRowIndex()][$this->cells[$cellPath]] = $cell->getCalculatedValue();
                            if ($row->getRowIndex() == 2) {
                                $result['types'][$this->cells[$cellPath]] = $this->getType($cell->getDataType());
                            }
                        }
                    }
                }
            }
            $result['cells'] = $this->cells;

            $this->_parseData = $result;
        }
        return $this->_parseData;
    }
}
