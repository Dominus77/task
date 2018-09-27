<?php

namespace modules\spreadsheet\traits;

use Yii;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use modules\spreadsheet\Module;

/**
 * Trait ModuleTrait
 *
 * @property-read Module $module
 * @package modules\spreadsheet\traits
 */
trait ModuleTrait
{
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
