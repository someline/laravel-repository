<?php

namespace Someline\Repository\Transformers;


use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{

    protected $autoToArrayTransformer = true;

    protected $availableTransformers = [
    ];

    protected static function throwBadMethodCallException($method)
    {
        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $method
        ));
    }

    public function __call($name, $arguments)
    {
        $data = $this->autoInclude($name, $arguments);
        if ($data !== false) {
            return $data;
        }
        self::throwBadMethodCallException($name);
        return null;
    }

    protected function autoInclude($name, $arguments)
    {
        if (!Str::startsWith($name, 'include')) {
            return false;
        }

        $name = substr($name, strlen('include'));
        [$model, $paramBag] = $arguments;
        $nameInSnakeCase = Str::snake($name);
        $nameInSingular = Str::singular($nameInSnakeCase);
//        if ($nameInSnakeCase === $nameInSingular) {
//            $data = $model->{$nameInSnakeCase}()->first();
//            $data = $model->{$nameInSnakeCase};
//        } else {
        $data = $model->{$nameInSnakeCase};
//        }
//        $data = $model->{$nameInSnakeCase};
//        dd($data);
//        $bb = $model->products;
//        if (empty($data)) {
//            dump($model);
//            dump($nameInSnakeCase);
//            dd($bb);
//            dd($nameInSnakeCase);
//            dd($data);
//        }
        $transformer = $this->findAvailableTransformer($nameInSnakeCase, $nameInSingular);
        if (!$transformer) {
            return false;
        }

        if ($data instanceof Collection) {
            return $this->collection($data, new $transformer());
        } else if (!empty($data)) {
            return $this->item($data, new $transformer());
        } else {
            return null;
        }
    }

    protected function findAvailableTransformer($nameInSnakeCase, $nameInSingular)
    {
        if (!empty($this->availableTransformers)) {
            if (!empty($this->availableTransformers[$nameInSnakeCase])) {
                return $this->availableTransformers[$nameInSnakeCase];
            } else if (!empty($this->availableTransformers[$nameInSingular])) {
                return $this->availableTransformers[$nameInSingular];
            }
        }
        if ($this->autoToArrayTransformer) {
            return ToArrayTransformer::class;
        }
        return false;
    }

}