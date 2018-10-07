<?php
/**
 * This is the template for generating a controller class file.
 */

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator modules\spreadsheet\components\generator\ApiControllerGenerator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);

echo "<?php\n";
?>

namespace <?= $generator->getControllerNamespace() ?>;

use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

/**
 * <?= $controllerClass ?> implements the API actions for <?= $modelClass ?> model.
 */
class <?= StringHelper::basename($generator->controllerClass) ?> extends ApiController<?= "\n" ?>
{
    /**
     * @var string
     */
    public $modelClass = 'api\modules\v1\models\<?= $modelClass ?>';

    /**
     * @var string
     */
    public $searchModel = '\modules\spreadsheet\models\search\<?= $modelClass ?>Search';

    /**
     * Reserved Attributes
     *
     * @var array
     */
    public $reservedParams = ['sort', 'q'];

    /**
     * Override actions
     *
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    /**
     * Filter
     *
     * /v1/spreadsheet/test?author=AUTHOR&title=TITLE
     *
     * @return \yii\data\ActiveDataProvider
     * @throws BadRequestHttpException
     */
    public function prepareDataProvider()
    {
        $params = \Yii::$app->request->queryParams;
        $model = new $this->modelClass;
        $modelAttr = $model->attributes;
        $search = [];
        if (!empty($params)) {
            foreach ($params as $key => $value) {

                if (!is_scalar($key) or !is_scalar($value)) {
                    throw new BadRequestHttpException('Bad Request');
                }

                if (!in_array(strtolower($key), $this->reservedParams)
                && ArrayHelper::keyExists($key, $modelAttr, false)) {
                    $search[$key] = $value;
                }
            }
        }

        $searchByAttr['<?= $modelClass ?>Search'] = $search;
        /** @var \modules\spreadsheet\models\search\<?= $modelClass ?>Search $searchModel */
        $searchModel = new $this->searchModel;
        return $searchModel->search($searchByAttr);
    }
}
