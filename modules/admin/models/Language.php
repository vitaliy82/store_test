<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "language".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 *
 * @property CategoryTranslation[] $categoryTranslations
 * @property ProductTranslation[] $productTranslations
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryTranslations()
    {
        return $this->hasMany(CategoryTranslation::className(), ['lang_id' => 'id'])->inverseOf('lang');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTranslations()
    {
        return $this->hasMany(ProductTranslation::className(), ['lang_id' => 'id'])->inverseOf('lang');
    }

    /**
     * @inheritdoc
     * @return LanguageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LanguageQuery(get_called_class());
    }
}
