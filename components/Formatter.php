<?php


namespace app\components;


class Formatter extends \yii\i18n\Formatter
{

    /**
     * May be a string or an array,
     * where the key of the array is the number of digits in the number
     * and the value is the mask of the phone number
     *
     * @var string | array
     */
    public $phoneFormat = [11 => '+0(000)000-00-00'];

    /**
     * @var string
     */
    public $phoneMask = '0';


    /**
     * @param $value
     * @param null $format
     * @return string|string[]|null
     */
    public function asPhone($value, $format = null)
    {
        $mask = $this->phoneMask;

        if ($value === null) {
            return $this->nullDisplay;
        }

        if ($format === null) {
            $format = $this->phoneFormat;
        }

        $value = preg_replace('/[^0-9]/', '', $value);

        if (is_array($format)) {
            if (array_key_exists(strlen($value), $format)) {
                $format = $format[strlen($value)];
            } else {
                return $value;
            }
        }

        $pattern = '/' . str_repeat('([0-9])?', substr_count($format, $mask)) . '(.*)/';

        $format = preg_replace_callback(
            str_replace('#', $mask, '/([#])/'),
            function () use (&$counter) {
                return '${' . (++$counter) . '}';
            },
            $format
        );

        return trim(preg_replace($pattern, $format, $value, 1));
    }
}
