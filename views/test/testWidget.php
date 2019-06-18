<?php

/* @var $model TestWidget*/

use app\models\TestWidget;
use anatoliy700\tinymce\TinymceWidget;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Test widget';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'content')->widget(TinymceWidget::class, [
    'editorConfig' => [
        'language' => 'ru'
    ]
]) ?>

<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end() ?>
