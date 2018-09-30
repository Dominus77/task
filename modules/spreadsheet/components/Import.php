<?php

namespace modules\spreadsheet\components;

use Yii;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use modules\spreadsheet\traits\ModuleTrait;

/**
 * Class Import
 * @package modules\spreadsheet\components
 */
class Import
{
    use ModuleTrait;

    /**
     * @var array
     */
    public $cells = [];

    /**
     * @var null
     */
    private $_parseData = null;

    /**
     * Return types
     *
     * @return array
     */
    public function getTypesArray()
    {
        return [
            DataType::TYPE_BOOL => 'BOOLEAN',
            DataType::TYPE_ERROR => 'TEXT',
            DataType::TYPE_FORMULA => 'TEXT',
            DataType::TYPE_INLINE => 'TEXT',
            DataType::TYPE_NULL => 'TEXT',
            DataType::TYPE_NUMERIC => 'INTEGER',
            DataType::TYPE_STRING => 'TEXT',
            DataType::TYPE_STRING2 => 'TEXT',
        ];
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
            $result = ['id' => 'INTEGER'] + $data['types'];
            return $result;
        }
        return [];
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
            if ($column = $this->getColumns()) {
                $db = Yii::$app->db;
                if ($db->getTableSchema($table_name, true) === null) {
                    $db->createCommand()->createTable($table_name, $column)->execute();
                    $db->createCommand()->addPrimaryKey($table_name . '_pk', $table_name, 'id')->execute();
                    $db->createCommand()->alterColumn($table_name, 'id', 'INTEGER NOT NULL AUTO_INCREMENT')->execute();
                }

                // Генерируем модель
                if ($this->createModel($table_name) === true) {
                    // Генерируем GRUD
                    if ($this->createGrud($table_name) === true) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Генератор модели
     *
     * @param string $table_name
     * @return bool
     */
    public function createModel($table_name)
    {
        $model = new ProcessModel();
        return $model->createModel($table_name);
    }

    /**
     * Генератор GRUD
     *
     * @param string $table_name
     * @return bool
     */
    public function createGrud($table_name)
    {
        $model = new ProcessGrud();
        return $model->createGrud($table_name);
    }

    /**
     * Удаляем таблицу, если она существует
     *
     * @param string $table_name
     * @return bool
     * @throws \yii\base\ErrorException
     * @throws \yii\db\Exception
     */
    public function removeDbTable($table_name = '')
    {
        if ($table_name) {
            $db = Yii::$app->db;
            if ($db->getTableSchema($table_name, true) !== null) {
                $db->createCommand()->dropTable($table_name)->execute();
                // Удаляем GRUD
                $this->removeGrud($table_name);
                // Удаляем модель
                $this->removeModel($table_name);
            }
            return true;
        }
        return false;
    }

    /**
     * Удаляем модель
     *
     * @param string $table_name
     * @return bool
     */
    public function removeModel($table_name)
    {
        $model = new ProcessModel();
        return $model->removeModel($table_name);
    }

    /**
     * Удаляем GRUD
     *
     * @param string $table_name
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function removeGrud($table_name)
    {
        $model = new ProcessGrud();
        return $model->removeGrud($table_name);
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
            // Атрибуты
            $fields = [];
            foreach ($arr[2] as $key => $cell) {
                if ($key != 'id') {
                    $fields[] = $key;
                }
            }
            // Значения
            $values = [];
            $i = 0;
            foreach ($arr as $item) {
                foreach ($item as $key => $cell) {
                    if ($key != 'id') {
                        $values[$i][] = $cell;
                    }
                }
                $i++;
            }

            $db = Yii::$app->db;
            if ($db->getTableSchema($table_name, true) !== null) {
                $transaction = $db->beginTransaction();
                try {
                    $db->createCommand()->batchInsert($table_name, $fields, $values)->execute();
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
                $db->createCommand()->truncateTable($table_name)->execute();
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
                $cellIterator = $row->getCellIterator();
                if ($row->getRowIndex() == 1) {
                    foreach ($cellIterator as $cell) {
                        $this->cells[$cell->getColumn()] = $cell->getCalculatedValue();
                    }
                } else {
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
