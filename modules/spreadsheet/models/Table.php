<?php

namespace modules\spreadsheet\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * Class Table
 * @package app\models
 */
class Table extends \yii\db\ActiveRecord
{
    public $dir = null;

    /**
     * @var string
     */
    public $pattern = '*.xls';

    /**
     * {@inheritdoc}
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [];
    }

    public function getAllTables()
    {
        $db = Yii::$app->db;
        $query = $db->createCommand('SHOW TABLES;');
        $result = $query->queryAll();
        return $result;
    }

    /**
     * Возвращает путь к директории
     *
     * @return bool|string
     */
    private function getDir()
    {
        if ($this->dir === null) {
            $this->dir = Yii::getAlias('@uploads/files/xls/');
        }
        return $this->dir;
    }

    /**
     * Возвращает файлы с указаным расширением из директории
     *
     * @return array
     */
    public function getFiles()
    {
        return FileHelper::findFiles($this->getDir(), [
            'only' => [$this->pattern],
        ]);
    }

    /**
     * @return array
     */
    public function getTypesArray()
    {
        return [
            DataType::TYPE_BOOL => 'bool',
            DataType::TYPE_ERROR => 'error',
            DataType::TYPE_FORMULA => 'formula',
            DataType::TYPE_INLINE => 'text',
            DataType::TYPE_NULL => 'Null',
            DataType::TYPE_NUMERIC => 'integer',
            DataType::TYPE_STRING => 'STRING',
            DataType::TYPE_STRING2 => 'string',
        ];
    }

    /**
     * @param string $type
     * @return mixed
     */
    public function getType($type)
    {
        return ArrayHelper::getValue($this->getTypesArray(), $type);
    }

    /**
     * Возвращает массив с данными
     *
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function processParseFile()
    {
        $files = $this->getFiles();
        $inputFileName = $files[2];

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

            $cells = $spreadsheet->getActiveSheet()->getCellCollection();
            $coordinates = $cells->getCoordinates();

            foreach ($coordinates as $key => $value) {
                $index = $spreadsheet->getActiveSheet()->getCell($value)->getParent()->getCurrentRow();
                $result['rows'][$index][$key]['value'] = $spreadsheet->getActiveSheet()->getCell($value)->getValue();
                $result['rows'][$index][$key]['type'] = $spreadsheet->getActiveSheet()->getCell($value)->getDataType();
                $result['rows'][$index][$key]['column'] = $spreadsheet->getActiveSheet()->getCell($value)->getColumn();
            }
            //$result['rows'] = $spreadsheet->getActiveSheet()->toArray();
        }
        return $result;
    }

    public function createColumns()
    {
        $data = $this->processParseFile();
        if (($key = array_search('id', $data['rows'][0])) !== false) {
            unset($data['rows'][0][$key]);
        }
        $row = '';
        foreach ($data['rows'][0] as $item) {
            $row .= $item . ' TEXT, ';
        }
        return $row;
    }

    public function createDbTable()
    {
        $data = $this->processParseFile();
        $table_name = $data['name'];
        $nameColumns = $this->createColumns();
        $db = Yii::$app->db;
        if ($db->getTableSchema($table_name, true) === null) {
            if ($data['rows']) {
                $query = $db->createCommand('CREATE TABLE ' . $table_name . ' (id SERIAL, ' . $nameColumns . ' PRIMARY KEY (id)) ENGINE InnoDB CHARACTER SET utf8;');
                $query->query();
                return true;
            }
        }
        return false;
    }
}
