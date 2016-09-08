<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\treeBuilder;

$dom_id = 'category_list';
$token = yii::$app->request->csrfToken;
$this->registerJsFile('/js/jquery.nestable.js', ['position' => yii\web\View::POS_END]);
$script = <<< JS
    $('#$dom_id').nestable({});
    $('#$dom_id').on('change', function() {
      $.ajax({
        type: "POST",
        url: "/admin/category/listsave",
        data: 'list=' + JSON.stringify($('#$dom_id').nestable('serialize'))+ '&_csrf=$token',
        success: function(msg){
            console.log(msg);
        }
      });  
    });
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
$this->registerCssFile('/css/jquery.nestable.css');

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo treeBuilder::getTreeListFull($data, $dom_id) ?>
</div>
