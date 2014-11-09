<?php
namespace EyeOpen;

/**
 * Class to access array element as an object, used to supply CompositeData with array data.
 */
class ArrayObject implements Arrayable
{
    /**
     * @var array
     */
    private $array;

    /**
     * Constructs array class.
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function __call($method, $args)
    {
        if(strpos($method, 'get') !== false) {
            $name = lcfirst(substr($method, strlen('get')));
            return $this->__get($name);
        }
    }

    /**
     * Return value if key exists.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists(lcfirst($name), $this->array)) {
            return $this->array[lcfirst($name)];
        }
        return null;
    }

    /**
     * Check if key exists.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function __isset($name)
    {
        if (array_key_exists(lcfirst($name), $this->array)) {
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return $this->array;
    }
}