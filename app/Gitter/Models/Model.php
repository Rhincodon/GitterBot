<?php
namespace App\Gitter\Models;

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 00:00
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Model
 * @package App\Gitter\Models
 */
abstract class Model implements Arrayable
{
    /**
     * @var string
     */
    protected static $primaryKey = 'id';

    /**
     * @var array
     */
    protected static $storage = [];

    /**
     * @var array
     */
    protected static $booted = [];

    /**
     * @param array $data
     * @return Model
     */
    public static function findOrCreate(array $data): Model
    {
        static::boot();

        $id = $data[static::$primaryKey];
        if (!array_key_exists($id, static::$storage[static::class])) {
            static::$storage[static::class][$id] = new static($data);
        }

        return static::$storage[static::class][$id];
    }

    /**
     * @return void
     */
    public static function boot()
    {
        if (array_key_exists(static::class, static::$booted)) {
            return;
        }

        if (!array_key_exists(static::class, static::$storage)) {
            static::$storage[static::class] = [];
        }

        static::$booted[static::class] = true;
    }

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $attributes = $this->format($attributes);

        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }

        static::boot();
        
        if (array_key_exists(static::$primaryKey, $attributes)) {
            $id = $attributes[static::$primaryKey];
            static::$storage[static::class][$id] = $this;
        }
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function format(array $attributes): array
    {
        return $attributes;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $setter = sprintf('set%sAttribute', Str::studly($key));
        if (method_exists($this, $setter)) {
            $value = call_user_func([$this, $setter], $value);
        }

        $this->attributes[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __get($key)
    {
        $value = null;
        if (array_key_exists($key, $this->attributes)) {
            $value = $this->attributes[$key];
        }

        $getter = sprintf('get%sAttribute', Str::studly($key));
        if (method_exists($this, $getter)) {
            return call_user_func([$this, $getter], $value);
        }

        return $value;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->toArray();
    }
}
