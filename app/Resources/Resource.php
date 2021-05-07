<?php

namespace App\Resources;

use Exception;
use App\Config;
use Illuminate\Support\Collection;

class Resource
{
    /**
     * Resource key.
     *
     * @var string
     */
    protected $resource = '';

    /**
     * Primary key of resource.
     *
     * @var string
     */
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

    /**
     * Get all records from resource.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection
    {
        $resources = Config::get($this->resource, []);

        return collect($this->transformCollection($resources, $this));
    }

    /**
     * Find resource by key.
     *
     * @param string $key
     *
     * @return Resource|null
     */
    public function find(string $key): ?Resource
    {
        return $this->all()->filter(function ($resource) use ($key) {
            return $resource->{$resource->primaryKey} == $key;
        })
        ->first();
    }

    /**
     * Create a new resource.
     *
     * @param array $data
     *
     * @return Resource|null
     */
    public function create(array $data): ?Resource
    {
        $id = count($this->all()) + 1;
        $data[$this->primaryKey] = $id;

        Config::set($this->resource . '.' . $id, $data);

        return $this->find($id);
    }

    /**
     * Update resource.
     *
     * @param array $data
     *
     * @return Resource|null
     */
    public function update(array $data): ?Resource
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
    protected function fill(): void
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
    protected function transformCollection(array $collection, $class, array $extraData = []): array
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData);
        }, $collection);
    }
}
