<?php

namespace App\Resources;

use Exception;
use App\Config;

class Resource
{
    protected $resource = '';

    protected $primaryKey = 'id';

    /**
     * The resource attributes.
     *
     * @var array
     */
    public $attributes = [];

    /**
     * Create a new resource instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(?array $attributes = null)
    {
        if (is_array($attributes)) {
            $this->attributes = $attributes;

            $this->fill();
        }
    }

    public function all()
    {
        $resources = Config::get($this->resource, []);

        return collect($this->transformCollection($resources, $this));
    }

    public function find(string $key)
    {
        return $this->all()->filter(function ($resource) use ($key) {
            return $resource->{$resource->primaryKey} == $key;
        })
        ->first();
    }

    public function create(array $data)
    {
        $id = count($this->all()) + 1;
        $data[$this->primaryKey] = $id;

        Config::set($this->resource . '.' . $id, $data);

        return $this->find($id);
    }

    public function update(array $data)
    {
        if (! $this->{$this->primaryKey}) {
            throw new Exception("Primary key is missing.");
        }

        if (in_array($this->primaryKey, array_keys($data))) {
            unset($data[$this->primaryKey]);
        }

        $data = array_merge($this->attributes, $data);

        Config::set(
            $this->resource . '.' . $this->{$this->primaryKey},
            $data
        );

        $this->attributes = $data;
        $this->fill();

        return $this->find($this->{$this->primaryKey});
    }

    /**
     * Fill the resource with the array of attributes.
     *
     * @return void
     */
    protected function fill()
    {
        foreach ($this->attributes as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param  array  $collection
     * @param  string  $class
     * @param  array  $extraData
     * @return array
     */
    protected function transformCollection(array $collection, $class, array $extraData = [])
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData);
        }, $collection);
    }
}
