<?php

namespace VDVT\Support\Utils;

class Presenter
{
    use \Illuminate\Database\Eloquent\Concerns\HasAttributes;

    /**
     * @var Array
     */
    protected $fillable = array();

    /**
     * @var Array
     */
    protected $columnMappings = array();

    /**
     * @var array
     */
    protected $withFillables = array();

    /**
     * @param Array $options
     */
    public function __construct(array $attributes = [])
    {
        $this->setFillablesWithMapping();

        $this->fill($attributes);
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Set Fillables with mapping columns
     *
     * @author TrinhLe(trinh.le@bigin.vn)
     */
    public function setFillablesWithMapping()
    {
        $fillables = array_fill_keys(
            $this->fillable,
            null
        );

        foreach ($this->columnMappings as $columnInbounce => $columnOutbounce) {
            # code...
            unset($fillables[$columnOutbounce]);
            $fillables[$columnInbounce] = $columnOutbounce;
        }

        $this->withFillables = $fillables;
    }

    /**
     * Fill Value
     *
     * @param array $data
     */
    public function fill(array $attributes)
    {
        foreach (array_intersect_key($attributes, $this->withFillables) as $key => $value) {
            # code...
            $column = array_get($this->withFillables, $key) ?: $key;
            $this->{$column} = $value;
        }
    }

    /**
     * To Array Email
     *
     * @return Array
     */
    public function toArray(): array
    {
        return array_merge(
            array_fill_keys(
                $this->fillable,
                null
            ),
            $this->attributes
        );
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return !is_null($this->getAttribute($offset));
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }
}
