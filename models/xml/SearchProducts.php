<?php


namespace app\models\xml;


use yii\base\Model;

class SearchProducts extends Model
{
    public $id;

    public $category;

    public $price;

    public $hidden;

    public function rules()
    {
        return [
            [['id', 'price', 'hidden', 'category'], 'trim'],
            [['id', 'price', 'hidden'], 'integer'],
            [['category'], 'string']
        ];
    }

    public function search($params)
    {
        $dataProviderConfig = [
            'sort' => [
                'attributes' => ['id', 'category', 'price', 'hidden']
            ]
        ];

        $parser = \Yii::$app->xmlParser->parse('products')
            ->join('categories', ['id' => 'categoryId'], ['name' => 'category']);

        $this->load($params);

        if (!$this->validate()) {
            return $parser->getDataProvider($dataProviderConfig);
        }

        $parser->filter([
            'id' => $this->id,
            'category' => $this->category,
            'price' => $this->price,
            'hidden' => $this->hidden
        ]);

        return $parser->getDataProvider($dataProviderConfig);
    }
}
