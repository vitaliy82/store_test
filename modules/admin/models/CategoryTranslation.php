<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "category_translation".
 *
 * @property integer $category_id
 * @property string $name
 * @property string $description
 * @property integer $lang_id
 *
 * @property Category $category
 * @property Language $lang
 */
class CategoryTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'lang_id'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['lang_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => Yii::t('app', 'Category ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'lang_id' => Yii::t('app', 'Lang ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id'])->inverseOf('categoryTranslations');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Language::className(), ['id' => 'lang_id'])->inverseOf('categoryTranslations');
    }

    /**
     * @inheritdoc
     * @return CategoryTranslationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryTranslationQuery(get_called_class());
    }
}
