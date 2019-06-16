<?php


namespace app\controllers;


use app\models\TestWidget;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionTestWidget()
    {
        $model = new TestWidget();

        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
        }

        return $this->render('testWidget', ['model' => $model]);
    }

}
