<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Stdlib;

use Laminas\Json\Encoder;
use Laminas\Stdlib\ArrayObject;
use Laminas\Stdlib\InitializableInterface;
use MSBios\Stdlib\Exception\InvalidArgumentException;

/**
 * Class AbstractObject
 * Abstract object, all classes are extends from it to automate accessors, generate xml, json or array.
 * @package MSBios\Stdlib
 */
abstract class AbstractObject extends ArrayObject implements ObjectInterface
{
    /**
     * Original data
     *
     * @var array
     */
    protected $origStorage;

    /**
     * Setter/Getter underscore transformation cache
     *
     * @var array
     */
    protected static $underscoreCache = [];

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->offsetSet('id', $id);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->offsetGet('id');
    }

    /**
     * Object constructor.
     * @param array $input
     * @param int $flags
     * @param string $iteratorClass
     */
    public function __construct(array $input = [], $flags = self::STD_PROP_LIST, $iteratorClass = 'ArrayIterator')
    {
        parent::__construct($input, $flags, $iteratorClass);

        if ($this instanceof InitializableInterface) {
            $this->init();
        }

        $this->setOrigData();
    }

    /**
     * Add data to the object.
     * Retains previous data in the object.
     *
     * @param array $arr
     * @return $this
     */
    public function addData(array $arr)
    {
        /**
         * @var int $index
         * @var mixed $value
         */
        foreach ($arr as $index => $value) {
            $this->offsetSet($index, $value);
        }
        return $this;
    }

    /**
     * Overwrite data in the object.
     *
     * $key can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     *
     * If $key is an array, it will overwrite all the data in the object.
     *
     * @param string|array $key Key
     * @param mixed $value Value
     * @return $this
     */
    public function setData($key, $value = null)
    {
        if (is_array($key)) {
            $this->storage = $key;
        } else {
            $this->offsetSet($key, $value);
        }

        return $this;
    }

    /**
     * Unset data from the object.
     *
     * $key can be a string only. Array will be ignored.
     *
     * @param null $key
     * @return $this
     */
    public function unsetData($key = null)
    {
        $this->offsetUnset($key);
        return $this;
    }

    /**
     * Retrieves data from the object
     *
     * If $key is empty will return all the data as an array
     * Otherwise it will return value of the attribute specified by $key
     *
     * If $index is specified it will assume that attribute data is an array
     * and retrieve corresponding member.
     *
     * @param string $key key
     * @param string|int $index Index
     *
     * @return mixed
     */
    public function getData($key = '', $index = null)
    {
        if ('' === $key) {
            return $this->getArrayCopy();
        }

        /** @var null $default */
        $default = null;

        // accept a/b/c as ['a']['b']['c']
        // Not  !== false no need '/a/b always return null
        if (strpos($key, '/')) {

            /** @var array $keyArray */
            $keyArray = explode('/', $key);

            /** @var array $data */
            $data = $this->getArrayCopy();

            /**
             * @var int $i
             * @var mixed $k
             */
            foreach ($keyArray as $i => $k) {
                if ('' === $k) {
                    return $default;
                }

                if (is_array($data)) {
                    if (! isset($data[$k])) {
                        return $default;
                    }
                    $data = $data[$k];
                }
            }

            return $data;
        }

        // legacy functionality for $index
        if ($this->offsetExists($key)) {
            if (is_null($index)) {
                // return $this->data[$key];
                return $this->offsetGet($key);
            }

            // $value = $this->data[$key];
            $value = $this->offsetGet($key);

            if (is_array($value)) {
                if (isset($value[$index])) {
                    return $value[$index];
                }
                return null;
            } elseif (is_string($value)) {

                /** @var array $array */
                $array = explode(PHP_EOL, $value);
                return (isset($array[$index])
                    && (! empty($array[$index])
                        || strlen($array[$index]) > 0)) ? $array[$index] : null;
            } elseif ($value instanceof AbstractObject) {
                return $value->getData($index);
            }

            return $default;
        }

        return $default;
    }

    /**
     * If $key is empty, checks whether there's any data in the object
     * Otherwise checks if the specified attribute is set.
     *
     * @param string $key Key
     *
     * @return boolean
     */
    public function hasData($key = '')
    {
        return $this->offsetExists($key);
    }

    /**
     * Convert object attributes to array
     *
     * @param array $array array of required attributes
     *
     * @return array
     */
    public function __toArray(array $array = [])
    {
        if (empty($array)) {
            return $this->getArrayCopy();
        }

        /** @var array $result */
        $result = [];

        foreach ($array as $attr) {
            if ($this->offsetExists($attr)) {
                $result[$attr] = $this->offsetGet($attr);
            } else {
                $result[$attr] = null;
            }
        }

        return $result;
    }

    /**
     * Public wrapper for __toArray
     *
     * @param array $array Data
     *
     * @return array
     */
    public function toArray(array $array = [])
    {
        return $this->__toArray($array);
    }

    /**
     * Convert object attributes to XML
     *
     * @param array $array Array of required attributes
     * @param string $rootName Name of the root element
     * @param boolean $addOpenTag Insert <?xml>
     * @param boolean $addCdata Insert CDATA[]
     *
     * @return string
     */
    protected function __toXml(array $array = [], $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $xml = '';
        if ($addOpenTag) {
            $xml .= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        }
        if (empty($array)) {
            $array = $this->toArray();
        }
        if (! empty($rootName) and ! is_numeric($rootName)) {
            $xml .= '<' . $rootName;
            if (isset($array['id'])) {
                $xml .= ' id="' . $array['id'] . '"';
                unset($array['id']);
            }
            $xml .= '>' . PHP_EOL;
        }
        foreach ($array as $fieldName => $fieldValue) {
            if (is_array($fieldValue)) {
                if (! empty($fieldValue)) {
                    $xml .= $this->__toXml($fieldValue, $fieldName);
                    continue;
                }
                $fieldValue = '';
            } elseif (is_object($fieldValue) and method_exists($fieldValue, 'toXml')) {
                $xml .= $fieldValue->toXml([], $fieldValue->name);
                continue;
            }
            if ($addCdata === true) {
                $fieldValue = '<![CDATA[' . $fieldValue . ']]>';
                $xml .= '<' . $fieldName . '>' . $fieldValue . '</' . $fieldName . '>' . PHP_EOL;
            } else {
                $fieldValue = htmlentities($fieldValue);
                $xml .= '<' . $fieldName . '>' . $fieldValue . '</' . $fieldName . '>' . PHP_EOL;
            }
        }
        if (! empty($rootName) and ! is_numeric($rootName)) {
            $xml .= '</' . $rootName . '>' . PHP_EOL;
        }
        return $xml;
    }

    /**
     * Public wrapper for __toXml
     *
     * @param array $array Data
     * @param string $rootName Root name
     * @param boolean $addOpenTag Insert <?xml>
     * @param boolean $addCdata Insert CDATA[]
     *
     * @return string
     */
    public function toXml(array $array = [], $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        return $this->__toXml($array, $rootName, $addOpenTag, $addCdata);
    }

    /**
     * Convert object attributes to JSON
     *
     * @param array $array array of required attributes
     *
     * @return string
     */
    protected function __toJson(array $array = [])
    {
        return Encoder::encode($this->toArray($array));
    }

    /**
     * Public wrapper for __toJson
     *
     * @param array $array Data
     *
     * @return string
     */
    public function toJson(array $array = [])
    {
        return $this->__toJson($array);
    }

    /**
     * Public wrapper for __toString
     *
     * Will use $format as an template and substitute {{key}} for attributes
     *
     * @param string $format Format
     *
     * @return string
     */
    public function toString($format = '')
    {
        if (empty($format)) {
            $str = implode(', ', $this->getData());
        } else {
            preg_match_all('/\{\{([a-z0-9_]+)\}\}/is', $format, $matches);
            foreach ($matches[1] as $var) {
                $format = str_replace('{{' . $var . '}}', $this->getData($var), $format);
            }
            $str = $format;
        }
        return $str;
    }

    /**
     * Set/Get attribute wrapper
     *
     * @param $method
     * @param $args
     * @return bool|mixed|Object
     */
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get':
                $key = $this->underscore(substr($method, 3));
                return $this->getData($key, isset($args[0]) ? $args[0] : null);
                break;
            case 'set':
                $key = $this->underscore(substr($method, 3));
                return $this->setData($key, isset($args[0]) ? $args[0] : null);
                break;
            case 'uns':
                $key = $this->underscore(substr($method, 3));
                return $this->unsetData($key);
                break;
            case 'has':
                $key = $this->underscore(substr($method, 3));
                return $this->hasData($key);
                break;
        }

        throw new InvalidArgumentException(
            'Invalid method ' . get_class($this) . '::' . $method . '(' . print_r($args, 1) . ')'
        );
    }

    /**
     * Converts field names for setters and geters
     *
     * $this->setMyField($value) === $this->setData('my_field', $value)
     * Uses cache to eliminate unneccessary preg_replace
     *
     * @param string $name Name
     *
     * @return string
     */
    protected function underscore($name)
    {
        if (isset(self::$underscoreCache[$name])) {
            return self::$underscoreCache[$name];
        }
        $result = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', $name));
        self::$underscoreCache[$name] = $result;
        return $result;
    }

    /**
     * Get Original data
     *
     * @param string $key Key
     *
     * @return mixed
     */
    public function getOrigData($key = null)
    {
        if (is_null($key)) {
            return $this->origStorage;
        }
        return isset($this->origStorage[$key]) ? $this->origStorage[$key] : null;
    }

    /**
     * Set Original data
     *
     * @param null $key
     * @param null $data
     * @return $this
     */
    public function setOrigData($key = null, $data = null)
    {
        if (is_null($key)) {
            $this->origStorage = $this->getArrayCopy();
        } else {
            $this->origStorage[$key] = $data;
        }
        return $this;
    }

    /**
     * Check if data has changed
     *
     * @param string $field Field
     *
     * @return boolean
     */
    public function hasDataChangedFor($field)
    {
        return $this->getData($field) != $this->getOrigData($field);
    }
}
