<?php

namespace BeSimple\RosettaBundle\Translation\Webservice;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Request
{
    const METHOD_GET  = 'get';
    const METHOD_POST = 'post';

    const DECODE_JSON = 'json';
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
        $handler  = $this->build();
        $response = $this->send($handler);

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

    /**
     * @return \resource
     */
    private function build()
    {
        $url = $this->method === self::METHOD_GET
            ? $this->buildQuery()
            : $this->url;

        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

        if (count($this->headers)) {
            curl_setopt($handler, CURLOPT_HTTPHEADER, $this->headers);
        }

        if ($this->method === self::METHOD_POST) {
            curl_setopt($handler, CURLOPT_POST, true);
            curl_setopt($handler, CURLOPT_POSTFIELDS, $this->parameters);
        }

        return $handler;
    }

    private function buildQuery()
    {
        $query = strpos($this->url, '?') === false
            ? $this->url.'?'
            : $this->url.'&';

        $parameters = array();
        foreach ($this->parameters as $key => $value) {
            $parameters[] = urlencode($key).'='.urlencode($value);
        }

        return $query.implode('&', $parameters);
    }

    /**
     * @param \resource $handler
     *
     * @return string|false
     */
    private function send($handler)
    {
        $response = curl_exec($handler);

        $this->error = $response === false
            ? curl_error($handler)
            : null;

        curl_close($handler);

        return $response;
    }
}
