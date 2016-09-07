<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\treeBuilder;

/* @var $this yii\web\View */
/* @var $model app\modules\front\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'owner_id')->textInput() ?>

    <label class="control-label" for="category-parent_id">Patent ID</label>
    <select id="product-category_id" class="form-control" name="Product[category_id]">
        <?php  
            treeBuilder::getRoot();
            treeBuilder::printTree($model->category_id, $category); 
        ?>
    </select>
    
    <?php // $form->field($model, 'category_id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
