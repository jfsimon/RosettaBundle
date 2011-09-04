<?php

namespace BeSimple\RosettaBundle\Translation\Webservice;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Request
{
    const METHOD_GET  = 'GET';
    const METHOD_POST = 'POST';

    const DECODE_JSON = 'JSON';
    const DECODE_NONE = null;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $error;

    /**
     * @param string $url
     * @param string $method
     */
    public function __construct($method = self::METHOD_GET, $url = null)
    {
        $this->url        = $url;
        $this->method     = $method;
        $this->parameters = array();
        $this->headers    = array();
    }

    /**
     * @return Request
     */
    static public function get($url = null)
    {
        return new static(self::METHOD_GET, $url);
    }

    /**
     * @return Request
     */
    static public function post($url = null)
    {
        return new static(self::METHOD_POST, $url);
    }

    /**
     * @param string|null $decode
     *
     * @return string|array|null
     */
    public function getResponse($decode = self::DECODE_NONE)
    {
        $query = http_build_query($this->parameters);

        $url = $this->method === self::METHOD_GET
            ? $this->url.'?'.$query
            : $this->url;

        $http = array(
            'method'  => $this->method,
            'header'  => implode("\r\n", $this->headers),
            'content' => $this->method === self::METHOD_POST ? $query : null,
        );

        $context  = stream_context_create(array('http' => $http));
        $response = @file_get_contents($url, 0, $context);

        if ($response === false) {
            return null;
        }

        if ($decode === self::DECODE_JSON) {
            return json_decode($response, true);
        }

        return $response;
    }

    /**
     * @return string
     */
    public function getLastError()
    {
        return $this->error;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $method
     *
     * @return Request
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param array $parameters
     *
     * @return Request
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return Request
     */
    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return Request
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return Request
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;

        return $this;
    }
}
