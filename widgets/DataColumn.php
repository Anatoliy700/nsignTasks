<?php


namespace app\widgets;


use Yii;
use yii\helpers\ArrayHelper;

class DataColumn extends \yii\grid\DataColumn
{
    /**
     * @var string | array
     */
    public $emptyCellMessage;

    /**
     * @var string
     */
    public $emptyCellTemplate = '<span class="not-set">{message}</span>';

    /**
     * @var string
     */
    public $emptyCellFormat = 'html';

    /**
     * @var string
     */
    protected $emptyCell;


    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->setEmptyCell();
    }

    /**
     *
     */
    public function setEmptyCell()
    {
        if (is_array($this->emptyCellMessage)) {
            $category = array_key_first($this->emptyCellMessage);
            $message = Yii::t($category, $this->emptyCellMessage[$category]);
        } elseif (is_string($this->emptyCellMessage)) {
            $message = $this->emptyCellMessage;
        } else {
            $message = '(no data)';
        }

        $this->emptyCell = strtr($this->emptyCellTemplate, ['{message}' => $message]);
    }

    /**
     * @inheritDoc
     */
    public function getDataCellValue($model, $key, $index)
    {
        $output = null;

        if ($this->value !== null) {
            if (is_string($this->value)) {
                $output = ArrayHelper::getValue($model, $this->value);
            } else {
                $output = call_user_func($this->value, $model, $key, $index, $this);
            }
        } elseif ($this->attribute !== null) {
            $output = ArrayHelper::getValue($model, $this->attribute);
        }

        if ($output === null) {
            $this->format = $this->emptyCellFormat;
            return $this->emptyCell;
        }

        return $output;
    }
}
