<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Stdlib;

use MSBios\Stdlib\Exception\RuntimeException;
use ReflectionClass;

/**
 * Class Enum
 *
 * Base Enum class. Create an enum by implementing this class and adding class constants.
 *
 * @package MSBios\Stdlib
 */
abstract class Enum
{
    /**
     * @var array ReflectionClass
     */
    protected static $reflectorInstances = [];

    /**
     * @var array
     */
    protected static $enumInstances = [];

    /**
     * @var array
     */
    protected static $foundNameValueLink = [];

    /**
     * @var
     */
    protected $name;

    /**
     * @var
     */
    protected $value;

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param $value
     * @return mixed
     */
    final public static function get($value)
    {
        /** @var string $name */
        $name = self::nameOf($value);

        if ($name === false) {
            throw new RuntimeException("Unknown constant!");
        }

        /** @var string $className */
        $className = get_called_class();
        self::init($className, $name);
        return self::$enumInstances[$className][$name];
    }

    /**
     * @return array
     */
    final public static function toArray()
    {
        /** @var array $classConstantsArray */
        $classConstantsArray = self::getReflectorInstance()->getConstants();
        foreach ($classConstantsArray as $k => $v) {
            $classConstantsArray[$k] = (string)$v;
        }
        return $classConstantsArray;
    }

    /**
     * @param $value
     * @return mixed
     */
    final public static function nameOf($value)
    {
        /** @var string $className */
        $className = (string)get_called_class();

        if (! isset(self::$foundNameValueLink[$className][$value])) {
            $constantName = array_search($value, self::toArray(), true);
            self::$foundNameValueLink[$className][$value] = $constantName;
        }

        return self::$foundNameValueLink[$className][$value];
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    final public static function valueOf($name)
    {
        /** @var array $names */
        $names = self::toArray();
        return array_key_exists($name, $names) ? $names[$name] : false;
    }

    /**
     * @param $name
     * @return bool
     */
    final public static function nameExists($name)
    {
        /** @var array $constArray */
        $constArray = self::toArray();
        return isset($constArray[$name]);
    }

    /**
     * @param $value
     * @return bool
     */
    final public static function valueExists($value)
    {
        return self::nameOf($value) === false ? false : true;
    }

    /**
     * @return string
     */
    final public function __toString()
    {
        return (string)$this->value;
    }

    /**
     *
     */
    final private function __clone()
    {
    }

    /**
     * Enum constructor.
     * @param $name
     * @param $value
     */
    final private function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @param $className
     * @param $name
     */
    final private function init($className, $name)
    {
        if (! isset(self::$enumInstances[$className][$name])) {
            $value = constant($className . '::' . $name);
            self::$enumInstances[$className][$name] = new $className($name, $value);
        }
    }

    /**
     * @return ReflectionClass
     */
    final private static function getReflectorInstance()
    {
        /** @var string $className */
        $className = get_called_class();

        if (! isset(self::$reflectorInstances[$className])) {
            self::$reflectorInstances[$className] = new ReflectionClass($className);
        }

        return self::$reflectorInstances[$className];
    }
}
