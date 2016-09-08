<?php

namespace app\modules\admin\models;

use Yii;
use app\modules\front\models\Product;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property integer $img
 *
 * @property CategoryTranslation[] $categoryTranslations
 * @property Product[] $products
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
       //   [['img'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Patent ID'),
            'name' => Yii::t('app', 'Name'),
            'img' => Yii::t('app', 'Img'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryTranslations()
    {
        return $this->hasMany(CategoryTranslation::className(), ['category_id' => 'id'])->inverseOf('category');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id'])->inverseOf('category');
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $this->img->saveAs(\Yii::getAlias('@webroot') . '/uploads/' . 
                $this->img->baseName . '.' . $this->img->extension);
            return true;
        } else {
            return false;
        }
    }
 
    
}
