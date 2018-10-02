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
     * Path to folder model
     *
     * @return string
     */
    public function getModelPath()
    {
        return Yii::getAlias('@modules/') . Module::$name . '/models';
    }

    /**
     * Namespace model
     *
     * @return string
     */
    public function getNsModel()
    {
        return 'modules\\' . Module::$name . '\models';
    }

    /**
     * Path to folder search model
     *
     * @return string
     */
    public function getSearchModelPath()
    {
        return Yii::getAlias('@modules/') . Module::$name . '/models/search';
    }

    /**
     * Path to folder controller
     *
     * @return string
     */
    public function getControllerPath()
    {
        return Yii::getAlias('@modules/') . Module::$name . '/controllers';
    }

    /**
     * Path to folder view
     *
     * @param string $folderName
     * @return bool|string
     */
    public function getViewsPath($folderName)
    {
        if ($folderName) {
            return '@modules/' . Module::$name . '/views/' . $folderName;
        }
        return false;
    }

    /**
     * Version API
     *
     * @return string
     */
    public function getVersionApi()
    {
        /** @var \modules\spreadsheet\Module $module */
        $module = $this->getModule();
        return $module->versionApi;
    }

    /**
     * Path to folder api model
     *
     * @return bool|string
     */
    public function getApiModelPath()
    {
        return Yii::getAlias('@api/modules/v1/models');
    }

    /**
     * Namespace model
     *
     * @return string
     */
    public function getNsApiModel()
    {
        return 'api\modules\v1\models';
    }

    /**
     * Path to folder api controller
     *
     * @return bool|string
     */
    public function getApiControllerPath()
    {
        return Yii::getAlias('@api/modules/v1/controllers');
    }

    /**
     * Namespace controller
     *
     * @return string
     */
    public function getNsApiController()
    {
        return 'api\modules\v1\controllers';
    }

    /**
     * Path to folder api controller
     *
     * @return bool|string
     */
    public function getApiConfigPath()
    {
        return Yii::getAlias('@modules/' . Module::$name . '/config');
    }

    /**
     * Namespace controller
     *
     * @return string
     */
    public function getNsController()
    {
        return 'modules\\' . Module::$name . '\controllers';
    }

    /**
     * Pattern search file
     *
     * @return mixed
     */
    public function getPattern()
    {
        return Module::$pattern;
    }

    /**
     * Возвращает файлы из директории с указаным в патерне расширением
     *
     * @return array
     */
    public function getFiles()
    {
        return FileHelper::findFiles($this->getUploadPath(), [
            'only' => [$this->getPattern()],
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
                $name = ArrayHelper::getValue($info, 'filename');
                // Игнорируем файл с именем равным названию модуля
                if ($name !== Module::$name) {
                    $names[$file] = $name;
                }
            }
        }
        return $names;
    }
}
