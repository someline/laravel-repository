<?php


namespace Someline\Repository\Response;


use Dingo\Api\Http\Response\Factory;
use ErrorException;

/**
 * @property ResponseFactory $response
 */
trait ResponseHelper
{

    /**
     * Get the response factory instance.
     *
     * @return ResponseFactory
     */
    protected function response()
    {
        return app(ResponseFactory::class);
    }

    /**
     * Magically handle calls to certain properties.
     *
     * @param string $key
     *
     * @return mixed
     * @throws \ErrorException
     *
     */
    public function __get($key)
    {
        $callable = [
            'response',
        ];

        if (in_array($key, $callable) && method_exists($this, $key)) {
            return $this->$key();
        }

        throw new ErrorException('Undefined property ' . get_class($this) . '::' . $key);
    }

}
