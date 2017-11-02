<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Stdlib;

/**
 * Class ArrayCollection
 * @package MSBios\Stdlib
 */
class ArrayCollection implements ArrayCollectionInterface
{
    use ArrayCollectionTrait;

    /**
     * ArrayCollection constructor.
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }
}
