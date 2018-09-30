<?php

namespace modules\spreadsheet\components\generator;

use Yii;
use yii\gii\generators\crud\Generator;

/**
 * Class GrudGenerator
 * @package modules\spreadsheet\components\generator
 */
class GrudGenerator extends Generator
{
    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');

        $codeFile = new GeneratorCodeFile($controllerFile, $this->render('controller.php'));
        $codeFile->operation = GeneratorCodeFile::OP_OVERWRITE;
        $codeFile->save();

        if (!empty($this->searchModelClass)) {
            $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
            $codeFile = new GeneratorCodeFile($searchModel, $this->render('search.php'));
            $codeFile->operation = GeneratorCodeFile::OP_OVERWRITE;
            $codeFile->save();
        }

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $codeFile = new GeneratorCodeFile("$viewPath/$file", $this->render("views/$file"));
            }
            $codeFile->operation = GeneratorCodeFile::OP_OVERWRITE;
            $codeFile->save();
        }

        return true;
    }
}
