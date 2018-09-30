<?php

namespace modules\spreadsheet\components\generator;

use Yii;
use yii\gii\CodeFile;

/**
 * Class GeneratorCodeFile
 * @package modules\spreadsheet\components\generator
 */
class GeneratorCodeFile extends CodeFile
{
    /**
     * Saves the code into the file specified by [[path]].
     * @return string|bool the error occurred while saving the code file, or true if no error.
     */
    public function save()
    {
        $module = Yii::$app->controller->module;
        if ($this->operation === self::OP_OVERWRITE) {
            $dir = dirname($this->path);
            if (!is_dir($dir)) {
                $mask = @umask(0);
                $result = @mkdir($dir, $module->newDirMode, true);
                @umask($mask);
                if (!$result) {
                    return "Unable to create the directory '$dir'.";
                }
            }
        }
        if (@file_put_contents($this->path, $this->content) === false) {
            return "Unable to write the file '{$this->path}'.";
        }

        return true;
    }
}
