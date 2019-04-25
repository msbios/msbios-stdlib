<?php
/**
 * @access protected
 * @author Judzhin Miles <judzhin[at]gns-it.com>
 */

namespace MSBios\Stdlib;

use MSBios\Stdlib\Exception\RuntimeException;
use Zend\Stdlib\ArrayObject;

/**
 * Interface RegistryInstance
 * @package MSBios\Stdlib
 */
interface RegistryInstance
{
    /**
     * Retrieves the default registry instance.
     * @return null
     */
    public static function getInstance();

    /**
     * Set the default registry instance to a specified instance.
     *
     * @param Registry $registry An object instance of type Registry,
     *                           or a subclass.
     *
     * @return void
     * @throws RuntimeException if registry is already initialized.
     */
    public static function setInstance(RegistryInstance $registry);

    /**
     * Unset the default registry instance.
     * Primarily used in tearDown() in unit tests.
     *
     * @return void
     */
    public static function unsetInstance();

    /**
     * getter method, basically same as offsetGet().
     *
     * This method can be called from an object of type Zendregistry, or it
     * can be called statically.  In the latter case, it uses the default
     * static instance stored in the class.
     *
     * @param string $index - get the value associated with $index
     *
     * @return mixed
     * @throws RuntimeException if no entry is registerd for $index.
     */
    public static function get($index);

    /**
     * setter method, basically same as offsetSet().
     *
     * This method can be called from an object of type Zendregistry, or it
     * can be called statically.  In the latter case, it uses the default
     * static instance stored in the class.
     *
     * @param string $index The location in the ArrayObject in which to store
     *                      the value.
     * @param mixed $value The object to store in the ArrayObject.
     *
     * @return void
     */
    public static function set($index, $value);

    /**
     * Returns true if the $index is a named value in the registry,
     * or false if $index was not found in the registry.
     *
     * @param string $index Index
     *
     * @return boolean
     */
    public static function has($index);
}
