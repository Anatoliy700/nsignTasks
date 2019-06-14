<?php


namespace app\components;


use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class XmlParser extends BaseObject
{
    /**
     * @var
     */
    public $path;

    /**
     * @var string
     */
    public $extension = 'xml';

    /**
     * @var
     */
    private $data;

    /**
     * @param string $fileName
     * @return XmlParser
     * @throws InvalidConfigException
     */
    public function parse(string $fileName): self
    {
        $array = $this->parseFile($fileName);

        $this->setData($array);

        return $this;
    }

    /**
     * @param array $params
     * @return object|ArrayDataProvider
     * @throws InvalidConfigException
     */
    public function getDataProvider(array $params = [])
    {
        $config = ArrayHelper::merge($params, ['allModels' => $this->getData()]);

        return \Yii::createObject(ArrayDataProvider::class, [$config]);
    }


    /**
     * @param string $fileNAme
     * @param array $onCondition
     * @param array $mergeAttr
     * @return XmlParser
     * @throws InvalidConfigException
     */
    public function join(string $fileNAme, array $onCondition, array $mergeAttr): self
    {
        $secondRef = array_key_first($onCondition);
        $firstRef = $onCondition[$secondRef];

        $secondary = ArrayHelper::index($this->parseFile($fileNAme), $secondRef);

        $data = $this->getData();
        array_walk($data, function (&$item) use ($secondary, $mergeAttr, $firstRef) {
            if ($secondItem = $secondary[$item[$firstRef]]) {
                foreach ($mergeAttr as $currentName => $newName) {
                    if (is_integer($currentName)) {
                        $currentName = $newName;
                    }
                    $item[$newName] = $secondItem[$currentName];
                }
            }
        });

        $this->setData($data);

        return $this;
    }

    /**
     * @param array $compare
     * @return $this
     */
    public function filter(array $compare)
    {
        $data = $this->getData();

        $sort = function ($item) use ($compare) {
            foreach ($compare as $attr => $val) {
                if (!isset($item[$attr]) || !strlen($val)) {
                } elseif (strstr(strtolower($item[$attr]), strtolower($val)) === false) {
                    return false;
                }
            }
            return true;
        };

        $filterData = array_filter($data, $sort) ?: [];

        $this->setData($filterData);

        return $this;
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->getData();
    }

    /**
     * @param $data
     */
    protected function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * @param string $name
     * @return string|null
     * @throws InvalidConfigException
     */
    protected function getFullName(string $name): string
    {
        if ($this->path === null) {
            throw new InvalidConfigException('Base path not specified in "$path" variable');
        }

        return $this->path . DIRECTORY_SEPARATOR . $name . '.' . $this->extension;
    }

    /**
     * @param $fileName
     * @return array
     * @throws InvalidConfigException
     */
    protected function parseFile($fileName): array
    {
        $fullName = $this->getFullName($fileName);

        if (!file_exists($fullName)) {
            throw new \Exception("File '{$fullName}' not exists");
        }

        $data = simplexml_load_file($fullName, 'SimpleXMLElement', LIBXML_NOCDATA);
        $countElements = $data->count();
        $array = $this->convertXmlToArray($data);
        return $this->normaliseArray($array, $countElements);
    }

    /**
     * @param $xml
     * @return array
     */
    protected function convertXmlToArray($xml): array
    {
        if (is_string($xml)) {
            $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        }

        $result = (array)$xml;
        foreach ($result as $key => $value) {
            if (!is_scalar($value)) {
                $result[$key] = $this->convertXmlToArray($value);
            }
        }
        return $result;
    }

    /**
     * @param array $array
     * @param int $count
     * @return array
     */
    protected function normaliseArray(array $array, int $count): array
    {
        if (count($array) === 1 && count(current($array)) === $count) {
            return current($array);
        }

        return $array;

    }
}
