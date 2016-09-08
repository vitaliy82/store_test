<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Category;
//use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\CategoryTranslation;
use app\modules\admin\models\Language;
use app\components\treeBuilder;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'data' => $this->getTreeCategory(),
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
        $translation = new CategoryTranslation();
        $translation->load(Yii::$app->request->post());
        $model->name = $translation->name;
        if ($model->load(Yii::$app->request->post()) ) {
           $img = $model->img = UploadedFile::getInstance($model, 'img');
            $model->save();
            $model->img = $img;
            $model->upload() ;
            $translation->category_id = $model->id;
            $translation->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'translation' => $translation,
                'model' => $model,
                'lang' => Language::find()->all(),
                'category' => $this->getTreeCategory()
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(isset(Yii::$app->request->post()['CategoryTranslation']['lang_id'])){
            $lang_id = Yii::$app->request->post()['CategoryTranslation']['lang_id'];
            $param = array('category_id' => $id, 'lang_id' => $lang_id);
        }else{
            $param = array('category_id' => $id);
        }
        $translation = CategoryTranslation::find()->where($param)->one();
        if(!$translation){
            $translation = new CategoryTranslation();
        }
        if(isset($lang_id)){
            $translation->lang_id = $lang_id;
        }
        if(isset(Yii::$app->request->post()['select_lang']) &&
           Yii::$app->request->post()['select_lang'] == '1'){
            return $this->render('update', [
                'model' => $model,
                'translation' => $translation,
                'lang' => Language::find()->all(),
                'category' => $this->getTreeCategory()
            ]);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $translation->load(Yii::$app->request->post());
            $translation->category_id = $id;
            $translation->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'translation' => $translation,
                'lang' => Language::find()->all(),
                'category' => $this->getTreeCategory()
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     *
     * @return string
     */
    protected function getTreeCategory(){
        $category = Category::find()->orderBy('order_id')->all();
        treeBuilder::$activeRecord = true;
        return treeBuilder::buildTree($category);
    }

    public function actionListsave()
    {
        $post = Yii::$app->request->post();
        $list = json_decode($post['list']);
        $this->treeSave($list);
        return 'ok';
    }

    protected function treeSave($tree, $parent = 0){
        foreach($tree as $key=>$val){
            $model = $this->findModel($val->id);
            $model->parent_id = $parent;
            $model->order_id = $key;
            $model->save();
            if(isset($val->children)){
                $this->treeSave($val->children, $val->id);
            }                
        }
    }
    
}
