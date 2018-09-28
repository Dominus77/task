<?php

namespace modules\spreadsheet\traits;

use Yii;
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
}
