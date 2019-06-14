<?php


namespace app\controllers;


use app\models\xml\SearchProducts;
use yii\web\Controller;

class XmlProductController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new SearchProducts();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

}
