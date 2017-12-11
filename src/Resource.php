<?php

/**
 * Author: Alban Afmeti
 * Email: albanafmeti@gmail.com
 */

namespace Noisim\Resource;

use JsonSerializable;
use Closure;

abstract class Resource implements JsonSerializable {

    protected $resource;
    protected $only = [];
    protected $except = [];
    protected $withData = [];

    /**
     * Resource constructor.
     * @param $resource
     * @param array $only
     */
    function __construct($resource, array $only = []) {
        $this->only = array_combine($only, $only);
        $this->processResource($resource);
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        if (is_array($this->resource)) {
            return $this->resource;
        } else {
            return $this->filter($this->toArr());
        }
    }

    /**
     * @param $resource
     */
    public function processResource($resource) {
        if (is_iterable($resource)) {
            $class = get_class($this);
            $this->resource = [];
            foreach ($resource as $item) {
                $this->resource[] = new $class($item, $this->only);
            }
        } else {
            $this->resource = $resource;
        }
    }

    /**
     * @param Closure $closure
     * @return $this
     */
    public function with(Closure $closure) {
        if (is_array($this->resource)) {
            foreach ($this->resource as &$item) {
                $item = $item->with($closure);
            }
        } else {
            $this->withData = $closure->call($this, $this);
        }

        return $this;
    }

    /**
     * @param array $only
     * @return $this
     */
    public function only(array $only) {
        if (is_array($this->resource)) {
            foreach ($this->resource as &$item) {
                $item = $item->only($only);
            }
        } else {
            $this->only = array_combine($only, $only);
        }
        return $this;
    }

    /**
     * @param array $except
     * @return $this
     */
    public function except(array $except) {
        if (is_array($this->resource)) {
            foreach ($this->resource as &$item) {
                $item = $item->except($except);
            }
        } else {
            $this->except = array_combine($except, $except);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function toArr() {
        return array_merge($this->toArray(), $this->withData);
    }

    /**
     * @param array $arrData
     * @return array
     */
    private function filter(array $arrData) {
        if (!empty($this->only)) {
            return array_intersect_key($arrData, $this->only);
        }

        if (!empty($this->except)) {
            return array_diff_key($arrData, $this->except);
        }

        return $arrData;
    }

    /**
     * @return array
     */
    protected abstract function toArray();

    public function __call($method, $parameters) {
        return $this->resource->{$method}(...$parameters);
    }

    public function __get($key) {
        return $this->resource->{$key};
    }
}