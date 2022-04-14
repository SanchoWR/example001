<?php

namespace App\Http;


class Request
{
    private $request;
    private $query;
    private $server;
    private $cookies;
    private $attributes;
    private $headers;

    public static function createFromGlobals(): Request
    {
        return new static($_POST, $_GET, $_SERVER, $_COOKIE, []);
    }

    public function __construct(array $request, array $query, array $server, array $cookies, array $attributes)
    {
        $this->request = new ArrayObject($request);
        $this->query = new ArrayObject($query);
        $this->server = new ArrayObject($server);
        $this->cookies = new ArrayObject($cookies);
        $this->attributes = new ArrayObject($attributes);
        $this->headers = new ArrayObject(getallheaders());
    }

    public function isMethod(string $method): bool
    {
        return $this->server->get('REQUEST_METHOD') === strtoupper($method);
    }

    public function getRequest(): ArrayObject
    {
        return $this->request;
    }

    public function getQuery(): ArrayObject
    {
        return $this->query;
    }

    public function getServer(): ArrayObject
    {
        return $this->server;
    }

    public function getCookies(): ArrayObject
    {
        return $this->cookies;
    }

    public function getAttributes(): ArrayObject
    {
        return $this->attributes;
    }

    public function getHeaders(): ArrayObject
    {
        return $this->headers;
    }

    public function isXmlHttpRequest()
    {
        return 'XMLHttpRequest' == $this->headers->get('X-Requested-With');
    }
}
