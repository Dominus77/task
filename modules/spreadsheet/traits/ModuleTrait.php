<?php

namespace modules\spreadsheet\traits;

use Yii;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use modules\spreadsheet\Module;

/**
 * Trait ModuleTrait
 *
 * @property-read Module $module
 * @package modules\spreadsheet\traits
 */
trait ModuleTrait
{
    public $pattern = '*.xls';

    /**
     * @return null|\yii\base\Module
     */
    public function getModule()
    {
        return Yii::$app->getModule(Module::$name);
    }

    /**
     * Return url to uploads dir
     * @return string
     */
    public function getUploadUrl()
    {
        return Yii::$app->urlManager->hostInfo . '/uploads/' . Module::$name;
    }

    /**
     * Return absolute path
     * @return bool|string
     */
    public function getUploadPath()
    {
        return Yii::getAlias('@uploads/') . Module::$name;
    }

    /**
     * Возвращает файлы из директории с указаным в патерне расширением
     *
     * @return array
     */
    public function getFiles()
    {
        return FileHelper::findFiles($this->getUploadPath(), [
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
}
