<?php

namespace app\components;
use yii\web\UrlManager;
use Yii;

class ZUrlManager extends UrlManager
{
    public function createUrl($params)
    {
      //  echo '<pre>';
        var_dump($params);
        
        exit;
      //  echo '</pre>';
        
      //  VarDumper::dump($params);
        
     /*   if (!isset($params['language'])) {
            if (Yii::$app->session->has('language'))
                Yii::$app->language = Yii::$app->session->get('language');
            else if(isset(Yii::$app->request->cookies['language']))
                Yii::$app->language = Yii::$app->request->cookies['language']->value;
            $params['language']=Yii::$app->language;
        }*/

        return parent::createUrl($params);
    }
}