<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\components\treeBuilder;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Category */
/* @var $lang array */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="category-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php
        if(isset($model->id)){
            echo Html::hiddenInput('select_lang', '0');
            echo $form->field($translation, 'lang_id')
                ->dropDownList(ArrayHelper::map($lang, 'id', 'name'), 
                array('onchange'=>'console.log(this.form); this.form.elements["select_lang"].value = "1"; this.form.submit()')); 
        }else{
            echo $form->field($translation, 'lang_id')
                ->dropDownList(ArrayHelper::map($lang, 'id', 'name')); 
        }
    ?>
    
    <label class="control-label" for="category-parent_id">Patent ID</label>
    <select id="category-parent_id" class="form-control" name="Category[parent_id]">
        <?php 
            treeBuilder::getRoot();
            treeBuilder::printTree($model->parent_id, $category); 
        ?>
    </select>
    
    <?= $form->field($model, 'img')->fileInput() ?>
    <?= $form->field($translation, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($translation, 'description')->textArea(['rows' => '3']) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
