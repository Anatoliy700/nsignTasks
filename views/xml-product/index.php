<?php

/* @var $dataProvider ArrayDataProvider */

/* @var $searchModel SearchProducts */

use app\models\xml\SearchProducts;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\widgets\Pjax;


$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php Pjax::begin() ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => SerialColumn::class],
        'id',
        'category',
        'price:currency',
        'hidden:boolean'
    ],
    'filterModel' => $searchModel
]) ?>

<?php Pjax::end() ?>
