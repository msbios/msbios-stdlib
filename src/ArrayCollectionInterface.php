<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Stdlib;

/**
 * Interface ArrayCollectionInterface
 * @package MSBios\Stdlib
 */
interface ArrayCollectionInterface extends
    \ArrayAccess,
    \Countable,
    \IteratorAggregate,
    \JsonSerializable
{
    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @param $items
     * @return static
     */
    public static function make($items);

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all();

    /**
     * Collapse the collection items into a single array.
     *
     * @return static
     */
    public function collapse();

    /**
     * Diff the collection with the given items.
     *
     * @param $items
     * @return static
     */
    public function diff($items);

    /**
     * Execute a callback over each item.
     *
     * @param \Closure $callback
     * @return $this
     */
    public function each(\Closure $callback);

    /**
     * Fetch a nested element of the collection.
     *
     * @param $key
     * @return static
     */
    public function fetch($key);

    /**
     * Run a filter over each of the items.
     *
     * @param \Closure $callback
     * @return static
     */
    public function filter(\Closure $callback);

    /**
     * Get the first item from the collection.
     *
     * @param \Closure|null $callback
     * @param null $default
     * @return mixed|null
     */
    public function first(\Closure $callback = null, $default = null);

    /**
     * Get a flattened array of the items in the collection.
     * @return static
     */
    public function flatten();

    /**
     * Remove an item from the collection by key.
     *
     * @param  mixed $key
     * @return void
     */
    public function forget($key);

    /**
     * Get an item from the collection by key.
     *
     * @param  mixed $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Group an associative array by a field or Closure value.
     *
     * @param $groupBy
     * @return static
     */
    public function groupBy($groupBy);

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param  mixed $key
     * @return bool
     */
    public function has($key);

    /**
     * Concatenate values of a given key as a string.
     *
     * @param  string $value
     * @param  string $glue
     * @return string
     */
    public function implode($value, $glue = null);

    /**
     * Intersect the collection with the given items.
     *
     * @param $items
     * @return static
     */
    public function intersect($items);

    /**
     * Determine if the collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Get the last item from the collection.
     *
     * @return mixed|null
     */
    public function last();

    /**
     * Get an array with the values of a given key.
     *
     * @param  string $value
     * @param  string $key
     * @return array
     */
    public function lists($value, $key = null);

    /**
     * Run a map over each of the items.
     *
     * @param \Closure $callback
     * @return static
     */
    public function map(\Closure $callback);

    /**
     * Merge the collection with the given items.
     *
     * @param $items
     * @return static
     */
    public function merge($items);

    /**
     * Get and remove the last item from the collection.
     *
     * @return mixed|null
     */
    public function pop();

    /**
     * Push an item onto the beginning of the collection.
     *
     * @param  mixed $value
     * @return void
     */
    public function prepend($value);

    /**
     * Push an item onto the end of the collection.
     *
     * @param  mixed $value
     * @return void
     */
    public function push($value);

    /**
     * Put an item in the collection by key.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function put($key, $value);

    /**
     * Reduce the collection to a single value.
     *
     * @param  callable $callback
     * @param  mixed $initial
     * @return mixed
     */
    public function reduce($callback, $initial = null);

    /**
     * Get one or more items randomly from the collection.
     *
     * @param  int $amount
     * @return mixed
     */
    public function random($amount = 1);

    /**
     * Reverse items order.
     *
     * @return static
     */
    public function reverse();

    /**
     * Get and remove the first item from the collection.
     *
     * @return mixed|null
     */
    public function shift();

    /**
     * Slice the underlying collection array.
     *
     * @param $offset
     * @param null $length
     * @param bool $preserveKeys
     * @return static
     */
    public function slice($offset, $length = null, $preserveKeys = false);

    /**
     * Chunk the underlying collection array.
     *
     * @param $size
     * @param bool $preserveKeys
     * @return static
     */
    public function chunk($size, $preserveKeys = false);

    /**
     * Sort through each item with a callback.
     *
     * @param \Closure $callback
     * @return $this
     */
    public function sort(\Closure $callback);

    /**
     * Sort the collection using the given Closure.
     *
     * @param $callback
     * @param int $options
     * @param bool $descending
     * @return $this
     */
    public function sortBy($callback, $options = SORT_REGULAR, $descending = false);

    /**
     * Sort the collection in descending order using the given Closure.
     *
     * @param $callback
     * @param int $options
     * @return ArrayCollection
     */
    public function sortByDesc($callback, $options = SORT_REGULAR);

    /**
     * Splice portion of the underlying collection array.
     *
     * @param $offset
     * @param int $length
     * @param array $replacement
     * @return static
     */
    public function splice($offset, $length = 0, $replacement = []);

    /**
     * Get the sum of the given values.
     *
     * @param  \Closure $callback
     * @param  string $callback
     * @return mixed
     */
    public function sum($callback);

    /**
     * Take the first or last {$limit} items.
     *
     * @param null $limit
     * @return ArrayCollection
     */
    public function take($limit = null);

    /**
     * Transform each item in the collection using a callback.
     *
     * @param \Closure $callback
     * @return $this
     */
    public function transform(\Closure $callback);

    /**
     * Return only unique items from the collection array.
     *
     * @return static
     */
    public function unique();

    /**
     * Reset the keys on the underlying array.
     *
     * @return $this
     */
    public function values();

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray();

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize();

    /**
     * Get the collection of items as JSON.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0);

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator();

    /**
     * Get a CachingIterator instance.
     *
     * @return \CachingIterator
     */
    public function getCachingIterator($flags = \CachingIterator::CALL_TOSTRING);

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count();

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed $key
     * @return bool
     */
    public function offsetExists($key);

    /**
     * Get an item at a given offset.
     *
     * @param  mixed $key
     * @return mixed
     */
    public function offsetGet($key);

    /**
     * Set the item at a given offset.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($key, $value);

    /**
     * Unset the item at a given offset.
     *
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key);

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString();
}
