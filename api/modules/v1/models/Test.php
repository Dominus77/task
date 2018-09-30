<?php

namespace api\modules\v1\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "test".
 *
 * @property int $id
 * @property string $title
 * @property string $anons
 * @property string $content
 * @property string $author
 * @property string $meta_description
 * @property string $meta_keywords
 */
class Test extends \modules\spreadsheet\models\Test
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), []);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), []);
    }

    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'id',
            'title',
            'anons',
            'content',
            'author',
            'meta_description',
            'meta_keywords',
        ];
    }
}
