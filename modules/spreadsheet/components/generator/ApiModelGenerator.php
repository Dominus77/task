<?php

namespace modules\spreadsheet\components\generator;

use Yii;
use yii\gii\generators\model\Generator;

/**
 * Class ApiModelGenerator
 * @package modules\spreadsheet\components\generator
 */
class ApiModelGenerator extends Generator
{
    public $template = 'api';
    public $templates = [
        'api' => '@modules/spreadsheet/components/generator/api'
    ];

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            // model :
            $modelClassName = $this->generateClassName($tableName);
            $queryClassName = ($this->generateQuery) ? $this->generateQueryClassName($modelClassName) : false;
            $tableSchema = $db->getTableSchema($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $modelClassName,
                'queryClassName' => $queryClassName,
                'tableSchema' => $tableSchema,
                'properties' => $this->generateProperties($tableSchema),
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],
            ];

            $params['modelClassName'] = $modelClassName;
            $codeFile = new GeneratorCodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $modelClassName . '.php',
                $this->render('model.php', $params)
            );
            $codeFile->operation = GeneratorCodeFile::OP_OVERWRITE;
            return $codeFile->save();
        }

        return false;
    }
}
