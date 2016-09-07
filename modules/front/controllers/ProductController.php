<?php

namespace app\modules\front\controllers;

use Yii;
use app\modules\front\models\Product;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\Category;
use app\components\treeBuilder;
use app\modules\admin\models\Language;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $lang = $this->getLang();
       // $lang = (!$lang)? '1': $lang;
  
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()
            ->select('product.id, product.title, category_translation.name, category.name')
            ->joinWith('category')  
            ->leftJoin('category_translation', '`category_translation`.`category_id` = `category`.`id`')
            ->where(['category_translation.lang_id' => $lang])
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'category' => $this->getTreeCategory()  
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'category' => $this->getTreeCategory()  
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
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
        $lang = Yii::$app->request->get('lang');
      //  $lang = Language::find()->where(array('code' => $lang))->one();       
      //  $lang = (!$lang)? '1': $lang->id;
         $lang = (!$lang)? '1': $lang;

        $category = Category::find()->select('category.id, category.parent_id, category_translation.name, category_translation.description')
            ->joinWith('categoryTranslations')
            ->where(['category_translation.lang_id' => $lang])
            ->all();

        treeBuilder::$activeRecord = true;
        return treeBuilder::buildTree($category);
    }

    /**
     * 
     */
    protected function getLang(){
        $parts = explode('/', Yii::$app->request->url);
        return $parts[1];
    }
    
}
