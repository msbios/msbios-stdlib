<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Stdlib;

use MSBios\Stdlib\Exception\InvalidArgumentException;
use Zend\Json\Json;

/**
 * Interface ObjectInterface
 * @package MSBios\Stdlib
 */
interface ObjectInterface extends \ArrayAccess
{
    /**
     * @param $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return $this
     */
    public function getId();

    /**
     *
     * @return void
     */
    public function init();

    /**
     * Add data to the object.
     * Retains previous data in the object.
     *
     * @param array $arr
     * @return $this
     */
    public function addData(array $arr);

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
    public function setData($key, $value = null);

    /**
     * Unset data from the object.
     *
     * $key can be a string only. Array will be ignored.
     *
     * @param null $key
     * @return $this
     */
    public function unsetData($key = null);

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
    public function getData($key = '', $index = null);

    /**
     * If $key is empty, checks whether there's any data in the object
     * Otherwise checks if the specified attribute is set.
     *
     * @param string $key Key
     *
     * @return boolean
     */
    public function hasData($key = '');

    /**
     * Convert object attributes to array
     *
     * @param array $array array of required attributes
     *
     * @return array
     */
    public function __toArray(array $array = []);

    /**
     * Public wrapper for __toArray
     *
     * @param array $array Data
     *
     * @return array
     */
    public function toArray(array $array = []);

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
    public function toXml(array $array = [], $rootName = 'item', $addOpenTag = false, $addCdata = true);

    /**
     * Public wrapper for __toJson
     *
     * @param array $array Data
     *
     * @return string
     */
    public function toJson(array $array = []);

    /**
     * Public wrapper for __toString
     *
     * Will use $format as an template and substitute {{key}} for attributes
     *
     * @param string $format Format
     *
     * @return string
     */
    public function toString($format = '');

    /**
     * Implementation of ArrayAccess::offsetSet()
     *
     * @param string $offset Offset
     * @param mixed $value Value
     *
     * @return void
     */
    public function offsetSet($offset, $value);

    /**
     * Implementation of ArrayAccess::offsetExists()
     *
     * @param string $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset);

    /**
     * Implementation of ArrayAccess::offsetUnset()
     *
     * @param string $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset);

    /**
     * Implementation of ArrayAccess::offsetGet()
     *
     * @param string $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset);

    /**
     * Get Original data
     *
     * @param string $key Key
     *
     * @return mixed
     */
    public function getOrigData($key = null);

    /**
     * Set Original data
     *
     * @param null $key
     * @param null $data
     * @return $this
     */
    public function setOrigData($key = null, $data = null);

    /**
     * Check if data has changed
     *
     * @param string $field Field
     *
     * @return boolean
     */
    public function hasDataChangedFor($field);
}
