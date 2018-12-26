<?php

namespace Jalameta\Support\Response\Json;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Json Response
 *
 * @author      veelasky <veelasky@gmail.com>
 */
class JsonResponse implements Jsonable,Arrayable
{
    /**
     * Response data
     *
     * @var array
     */
    protected $response;

    /**
     * Header data
     *
     * @var $headers
     */
    protected $headers;

    /**
     * JsonResponse constructor.
     *
     * @param array $response
     * @param array $headers
     */
    public function __construct(array $response = [], array $headers = [])
    {
        $this->response = $response;
        $this->headers = $headers;
    }

    /**
     * Create new json response instance
     *
     * @param array $response
     * @param array $headers
     *
     * @return \Jalameta\Support\Response\Json\JsonResponse
     */
    public static function make(array $response, array $headers = []): JsonResponse
    {
        return new self($response, $headers);
    }

    /**
     * Set response code
     *
     * @param $httpCode
     *
     * @return $this
     */
    public function code($httpCode)
    {
        $this->response['code'] = $httpCode;

        return $this;
    }

    /**
     * Set response type
     *
     * @param $type
     *
     * @return $this
     */
    public function type($type)
    {
        $this->response['type'] = $type;

        return $this;
    }

    /**
     *
     * @param $data
     *
     * @return $this
     */
    public function data($data)
    {
        $this->response['data'] = $data;

        return $this;
    }

    /**
     * Set response message
     *
     * @param $message
     *
     * @return $this
     */
    public function message($message)
    {
        $this->response['message'] = $message;

        return $this;
    }

    /**
     * Set response headers
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function header($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Convert error instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
