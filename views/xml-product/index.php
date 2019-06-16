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

$str1 = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi delectus enim esse iste officiis tempore. Aliquid amet corporis dignissimos eligendi facilis fugiat id modi, qui quia reprehenderit repudiandae ut voluptates?';

$str2 = 'created_at';

$str3 = 'Купи слона';

?>
<?php Pjax::begin() ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => SerialColumn::class],
        'id',
        'category',
        'price:currency',
        'hidden:boolean',
        'emptyData' // для задания по виджетам
    ],
    'filterModel' => $searchModel
]) ?>

<?php Pjax::end() ?>


<div class="alert alert-success">
    <h4>Из 30 слов, выводилось 12</h4>
    <p><?= $str1 ?></p>
    <hr>
    <p><?= \yii\helpers\StringHelper::truncateWords($str1, 12) ?></p>
</div>
<div class="alert alert-success">
    <h4>Преобразовать строку из created_at в CreatedAt</h4>
    <p><?= $str2 ?></p>
    <hr>
    <p><?= \yii\helpers\Inflector::camelize($str2) ?></p>
</div>
<div class="alert alert-success">
    <h4>Строку "Купи слона" преобразовать в "Kupi slona"</h4>
    <p><?= $str3 ?></p>
    <hr>
    <p><?= \yii\helpers\Inflector::transliterate($str3) ?></p>
</div>

